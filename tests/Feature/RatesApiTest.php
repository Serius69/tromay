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
        Cash::factory()->count(3)->create(['status' => 1]);

        $this->getJson('/api/rates')
            ->assertOk()
            ->assertJsonStructure([
                '*' => ['id', 'name', 'buy', 'sell'],
            ]);
    }

    /** @test */
    public function rates_index_only_includes_active_currencies(): void
    {
        Cash::factory()->create(['status' => 1]);
        Cash::factory()->create(['status' => 0]);

        $response = $this->getJson('/api/rates')->assertOk();

        $this->assertCount(1, $response->json());
    }

    /** @test */
    public function rates_show_returns_single_currency(): void
    {
        $cash = Cash::factory()->create(['status' => 1]);

        $this->getJson("/api/rates/{$cash->id}")
            ->assertOk()
            ->assertJsonFragment(['id' => $cash->id]);
    }

    /** @test */
    public function rates_show_returns_404_for_inactive_currency(): void
    {
        $cash = Cash::factory()->create(['status' => 0]);

        $this->getJson("/api/rates/{$cash->id}")->assertNotFound();
    }

    /** @test */
    public function rates_endpoint_returns_json_error_shape_on_not_found(): void
    {
        $this->getJson('/api/rates/99999')
            ->assertNotFound()
            ->assertJsonStructure(['error', 'status', 'message']);
    }

    /** @test */
    public function api_response_has_no_server_fingerprint_headers(): void
    {
        $response = $this->getJson('/api/rates')->assertOk();

        $response->assertHeaderMissing('X-Powered-By');
    }
}
