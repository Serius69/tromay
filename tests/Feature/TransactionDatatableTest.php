<?php

namespace Tests\Feature;

use App\Models\Cash;
use App\Models\Client;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransactionDatatableTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function datatableParams(array $overrides = []): array
    {
        return array_merge([
            'draw'             => 1,
            'start'            => 0,
            'length'           => 25,
            'search'           => ['value' => ''],
            'order'            => [['column' => 1, 'dir' => 'desc']],
        ], $overrides);
    }

    private function seedTransactions(int $count): void
    {
        $cash1  = Cash::factory()->create(['status' => 1]);
        $cash2  = Cash::factory()->create(['status' => 1]);
        $client = Client::factory()->create();

        $rows = [];
        for ($i = 0; $i < $count; $i++) {
            $rows[] = [
                'user_id'    => $this->user->id,
                'client_id'  => $client->id,
                'cash1_id'   => $cash1->id,
                'cash2_id'   => $cash2->id,
                'type'       => ($i % 2) + 1,
                'amount1'    => 100.0 + $i,
                'amount2'    => 700.0 + $i,
                'date'       => now()->subDays($i % 365)->format('Y-m-d H:i:s'),
                'status'     => 1,
                'created_at' => now()->subDays($i % 365)->format('Y-m-d H:i:s'),
                'updated_at' => now()->subDays($i % 365)->format('Y-m-d H:i:s'),
            ];
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('transactions')->insert($chunk);
        }
    }

    // ── Tests del contrato de respuesta ──────────────────────────────────────

    /** @test */
    public function response_has_required_datatable_keys(): void
    {
        $this->seedTransactions(10);
        $this->actingAs($this->user, 'sanctum');

        $resp = $this->getJson('/api/transactions/datatable?' . http_build_query($this->datatableParams()));
        $resp->assertStatus(200)
             ->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    }

    /** @test */
    public function draw_parameter_is_echoed_back(): void
    {
        $this->seedTransactions(5);
        $this->actingAs($this->user, 'sanctum');

        $resp = $this->getJson('/api/transactions/datatable?' . http_build_query($this->datatableParams(['draw' => 7])));
        $resp->assertStatus(200)->assertJson(['draw' => 7]);
    }

    /** @test */
    public function pagination_returns_only_requested_rows(): void
    {
        $this->seedTransactions(50);
        $this->actingAs($this->user, 'sanctum');

        $resp = $this->getJson('/api/transactions/datatable?' . http_build_query($this->datatableParams(['length' => 10])));
        $resp->assertStatus(200);

        $body = $resp->json();
        $this->assertCount(10, $body['data']);
        $this->assertEquals(50, $body['recordsTotal']);
    }

    /** @test */
    public function search_filters_recordsFiltered_correctly(): void
    {
        // Crear un cliente con CI único
        $cash   = Cash::factory()->create(['status' => 1]);
        $client = Client::factory()->create(['ci' => 'TESTCI12345']);
        DB::table('transactions')->insert([
            'user_id' => $this->user->id, 'client_id' => $client->id,
            'cash1_id' => $cash->id, 'cash2_id' => $cash->id,
            'type' => 1, 'amount1' => 100, 'amount2' => 700,
            'date' => now(), 'status' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->seedTransactions(20);  // registros sin ese CI
        $this->actingAs($this->user, 'sanctum');

        $resp = $this->getJson('/api/transactions/datatable?' . http_build_query(
            $this->datatableParams(['search' => ['value' => 'TESTCI12345']])
        ));

        $body = $resp->json();
        $this->assertEquals(1, $body['recordsFiltered']);
        $this->assertCount(1, $body['data']);
        $this->assertEquals('TESTCI12345', $body['data'][0]['client_ci']);
    }

    /** @test */
    public function records_total_does_not_change_with_search(): void
    {
        $this->seedTransactions(30);
        $this->actingAs($this->user, 'sanctum');

        $all      = $this->getJson('/api/transactions/datatable?' . http_build_query($this->datatableParams()))->json();
        $filtered = $this->getJson('/api/transactions/datatable?' . http_build_query(
            $this->datatableParams(['search' => ['value' => 'xxxxnotfound']])
        ))->json();

        $this->assertEquals($all['recordsTotal'], $filtered['recordsTotal']);
        $this->assertEquals(0, $filtered['recordsFiltered']);
    }

    /** @test */
    public function requires_authentication(): void
    {
        $resp = $this->getJson('/api/transactions/datatable?' . http_build_query($this->datatableParams()));
        $resp->assertStatus(401);
    }

    /** @test */
    public function query_under_100ms_with_1000_records(): void
    {
        $this->seedTransactions(1_000);
        $this->actingAs($this->user, 'sanctum');

        $start = microtime(true);
        $resp  = $this->getJson('/api/transactions/datatable?' . http_build_query($this->datatableParams(['length' => 25])));
        $ms    = (microtime(true) - $start) * 1000;

        $resp->assertStatus(200);
        $this->assertLessThan(
            100,
            $ms,
            "Query tomó {$ms}ms (esperado < 100ms). Verifica que los índices estén aplicados."
        );
    }

    /** @test */
    public function data_rows_have_expected_fields(): void
    {
        $this->seedTransactions(3);
        $this->actingAs($this->user, 'sanctum');

        $resp = $this->getJson('/api/transactions/datatable?' . http_build_query($this->datatableParams(['length' => 3])));
        $data = $resp->json('data');

        $this->assertNotEmpty($data);
        foreach ($data as $row) {
            $this->assertArrayHasKey('id', $row);
            $this->assertArrayHasKey('date', $row);
            $this->assertArrayHasKey('client_ci', $row);
            $this->assertArrayHasKey('type_label', $row);
            $this->assertArrayHasKey('amount1', $row);
            $this->assertArrayHasKey('amount2', $row);
        }
    }
}
