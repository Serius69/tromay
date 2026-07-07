<?php

namespace Tests\Feature;

use App\Models\Cash;
use App\Models\Client;
use App\Models\Transaction;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyCloseReportTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Cash $usd;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->usd  = Cash::factory()->create(['name' => 'usd', 'buy' => 6.92, 'sell' => 7.10, 'status' => 1]);
    }

    private function makeTransaction(int $type, float $amount1, float $amount2, string $date = null): Transaction
    {
        return Transaction::factory()->create([
            'user_id'   => $this->user->id,
            'client_id' => Client::factory()->create()->id,
            'cash1_id'  => $this->usd->id,
            'cash2_id'  => $this->usd->id,
            'amount1'   => $amount1,
            'amount2'   => $amount2,
            'date'      => $date ?? now()->toDateTimeString(),
            'type'      => $type,
        ]);
    }

    /** @test */
    public function guests_cannot_access_the_daily_close(): void
    {
        $this->get(route('admin.reports.daily-close'))->assertRedirect(route('login'));
    }

    /** @test */
    public function the_daily_close_shows_counts_and_bob_volumes_by_type(): void
    {
        // Casa compra 100 USD → paga 692 BOB (TYPE_SELL, BOB en amount2)
        $this->makeTransaction(TransactionService::TYPE_SELL, 100, 692.0);
        // Casa vende 100 USD → cobra 710 BOB (TYPE_BUY, BOB en amount1)
        $this->makeTransaction(TransactionService::TYPE_BUY, 710.0, 100);

        $response = $this->actingAs($this->user)
            ->get(route('admin.reports.daily-close', ['date' => today()->toDateString()]))
            ->assertOk();

        $response->assertViewHas('total_count', 2)
            ->assertViewHas('buy_count', 1)
            ->assertViewHas('sell_count', 1)
            ->assertViewHas('buy_volume', 692.0)
            ->assertViewHas('sell_volume', 710.0);
    }

    /** @test */
    public function transactions_from_other_days_are_excluded(): void
    {
        $this->makeTransaction(TransactionService::TYPE_SELL, 100, 692.0, now()->subDays(3)->toDateTimeString());

        $this->actingAs($this->user)
            ->get(route('admin.reports.daily-close', ['date' => today()->toDateString()]))
            ->assertOk()
            ->assertViewHas('total_count', 0);
    }

    /** @test */
    public function the_daily_close_can_be_exported_as_csv(): void
    {
        $this->makeTransaction(TransactionService::TYPE_SELL, 100, 692.0);

        $response = $this->actingAs($this->user)
            ->get(route('admin.reports.daily-close', ['date' => today()->toDateString(), 'export' => 'csv']));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
        $this->assertStringContainsString(
            'cierre-diario-' . today()->toDateString() . '.csv',
            $response->headers->get('content-disposition'),
        );

        $csv = $response->streamedContent();
        $this->assertStringContainsString('Cierre Diario', $csv);
        $this->assertStringContainsString('USD', $csv);
        $this->assertStringContainsString('692.0000', $csv);
        $this->assertStringContainsString('"Volumen compras (BOB)";692.00', $csv);
    }

    /** @test */
    public function csv_export_neutralizes_formula_injection_in_client_names(): void
    {
        Transaction::factory()->create([
            'user_id'   => $this->user->id,
            'client_id' => Client::factory()->create(['name' => '=HYPERLINK("http://evil";"x")', 'lastname' => 'Test'])->id,
            'cash1_id'  => $this->usd->id,
            'cash2_id'  => $this->usd->id,
            'amount1'   => 100,
            'amount2'   => 692.0,
            'date'      => now()->toDateTimeString(),
            'type'      => TransactionService::TYPE_SELL,
        ]);

        $csv = $this->actingAs($this->user)
            ->get(route('admin.reports.daily-close', ['date' => today()->toDateString(), 'export' => 'csv']))
            ->streamedContent();

        // El mutador de Client guarda el nombre en minúsculas; lo relevante es
        // que el valor arranque con apóstrofo y nunca con "=" desnudo tras el separador.
        $this->assertStringContainsStringIgnoringCase("'=hyperlink", $csv, 'El valor peligroso debe ir precedido de apóstrofo');
        $this->assertStringNotContainsStringIgnoringCase(';=hyperlink', str_replace('"', '', $csv));
    }

    /** @test */
    public function csv_export_requires_authentication(): void
    {
        $this->get(route('admin.reports.daily-close', ['export' => 'csv']))
            ->assertRedirect(route('login'));
    }
}
