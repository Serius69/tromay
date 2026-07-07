<?php

namespace Tests\Feature;

use App\Models\Cash;
use App\Models\Client;
use App\Models\Transaction;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class DashboardServiceTest extends TestCase
{
    use RefreshDatabase;

    private DashboardService $service;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        $this->service = app(DashboardService::class);
    }

    /** @test */
    public function summary_returns_all_required_keys(): void
    {
        $summary = $this->service->summary();

        $this->assertArrayHasKey('total_tx',         $summary);
        $this->assertArrayHasKey('today_tx',         $summary);
        $this->assertArrayHasKey('month_tx',         $summary);
        $this->assertArrayHasKey('month_volume_bob', $summary);
        $this->assertArrayHasKey('new_clients',      $summary);
        $this->assertArrayHasKey('top_currency',     $summary);
    }

    /** @test */
    public function api_data_returns_all_required_sections(): void
    {
        $data = $this->service->apiData();

        $this->assertArrayHasKey('kpis',              $data);
        $this->assertArrayHasKey('chart_by_currency', $data);
        $this->assertArrayHasKey('chart_volume',      $data);
        $this->assertArrayHasKey('top_clients',       $data);

        $this->assertArrayHasKey('today_tx',         $data['kpis']);
        $this->assertArrayHasKey('month_volume_bob', $data['kpis']);
        $this->assertArrayHasKey('new_clients',      $data['kpis']);
        $this->assertArrayHasKey('top_currency',     $data['kpis']);
    }

    /** @test */
    public function today_tx_count_reflects_todays_transactions(): void
    {
        $user   = User::factory()->create();
        $client = Client::factory()->create();
        $cash1  = Cash::factory()->create(['name' => 'usd', 'status' => 1]);
        $cash2  = Cash::factory()->create(['name' => 'eur', 'status' => 1]);

        Transaction::factory()->count(3)->create([
            'user_id'   => $user->id,
            'client_id' => $client->id,
            'cash1_id'  => $cash1->id,
            'cash2_id'  => $cash2->id,
            'created_at'=> now(),
        ]);

        $summary = $this->service->summary();

        $this->assertEquals(3, $summary['today_tx']);
        $this->assertEquals(3, $summary['total_tx']);
    }

    /** @test */
    public function analytics_api_endpoint_returns_json(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson(route('admin.analytics.api'))
            ->assertOk()
            ->assertJsonStructure([
                'kpis' => ['today_tx', 'month_tx', 'month_volume_bob', 'new_clients', 'top_currency'],
                'chart_by_currency' => ['labels', 'data'],
                'chart_volume'      => ['labels', 'data'],
                'top_clients',
            ]);
    }

    /** @test */
    public function invalidate_clears_analytics_cache(): void
    {
        Cache::put('kap_analytics_api', ['test' => true], 300);

        $this->assertTrue(Cache::has('kap_analytics_api'));

        $this->service->invalidate();

        $this->assertFalse(Cache::has('kap_analytics_api'));
    }
}
