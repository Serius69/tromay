<?php

namespace Tests\Feature;

use App\Models\Cash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatesApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function rates_index_returns_json_with_correct_structure(): void
    {
        Cash::factory()->count(3)->create(['status' => 1, 'name' => 'usd']);

        $this->getJson('/api/rates')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'buy', 'sell', 'oficial'],
                ],
                'disclaimer',
                'updated_at',
            ]);
    }

    /** @test */
    public function rates_index_only_includes_active_currencies(): void
    {
        Cash::factory()->create(['status' => 1, 'name' => 'usd']);
        Cash::factory()->create(['status' => 0, 'name' => 'eur']);

        $response = $this->getJson('/api/rates')->assertOk();

        $this->assertCount(1, $response->json('data'));
    }

    /** @test */
    public function rates_index_only_includes_allowed_currencies(): void
    {
        Cash::factory()->create(['status' => 1, 'name' => 'usd']);
        Cash::factory()->create(['status' => 1, 'name' => 'xyz']); // not in ALLOWED list

        $response = $this->getJson('/api/rates')->assertOk();

        $this->assertCount(1, $response->json('data'));
    }

    /** @test */
    public function rates_show_returns_single_currency(): void
    {
        $cash = Cash::factory()->create(['status' => 1, 'name' => 'usd']);

        $this->getJson("/api/rates/{$cash->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $cash->id)
            ->assertJsonStructure(['data' => ['id', 'name', 'buy', 'sell', 'oficial'], 'disclaimer']);
    }

    /** @test */
    public function rates_show_returns_404_for_inactive_currency(): void
    {
        $cash = Cash::factory()->create(['status' => 0, 'name' => 'usd']);

        $this->getJson("/api/rates/{$cash->id}")->assertNotFound();
    }

    /** @test */
    public function rates_show_returns_404_for_nonexistent_id(): void
    {
        $this->getJson('/api/rates/99999')->assertNotFound();
    }

    /** @test */
    public function api_response_has_no_server_fingerprint_headers(): void
    {
        $response = $this->getJson('/api/rates')->assertOk();

        $response->assertHeaderMissing('X-Powered-By');
    }
}
