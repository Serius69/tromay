<?php

namespace Tests\Feature;

use App\Models\Cash;
use App\Models\Latest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function home_page_returns_ok(): void
    {
        $this->get('/')->assertOk();
    }

    /** @test */
    public function about_page_returns_ok(): void
    {
        $this->get('/about')->assertOk();
    }

    /** @test */
    public function contact_page_returns_ok(): void
    {
        $this->get('/contact')->assertOk();
    }

    /** @test */
    public function quote_page_returns_ok(): void
    {
        $this->get('/quote')->assertOk();
    }

    /** @test */
    public function privacy_page_returns_ok(): void
    {
        $this->get('/privacy')->assertOk();
    }

    /** @test */
    public function terms_page_returns_ok(): void
    {
        $this->get('/terms')->assertOk();
    }

    /** @test */
    public function cash_detail_returns_ok_for_active_currency(): void
    {
        $cash = Cash::factory()->create(['status' => 1]);

        $this->get("/dinero/{$cash->id}")->assertOk();
    }

    /** @test */
    public function cash_detail_returns_404_for_inactive_currency(): void
    {
        $cash = Cash::factory()->create(['status' => 0]);

        $this->get("/dinero/{$cash->id}")->assertNotFound();
    }

    /** @test */
    public function unknown_routes_return_404(): void
    {
        $this->get('/ruta-que-no-existe')->assertNotFound();
    }
}
