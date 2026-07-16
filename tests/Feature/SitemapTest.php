<?php

namespace Tests\Feature;

use App\Models\Cash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function sitemap_responds_200_with_valid_xml_urlset(): void
    {
        Cash::factory()->create(['status' => 1, 'name' => 'usd']);

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));

        $body = $response->getContent();
        $this->assertStringContainsString('<urlset', $body);

        // XML bien formado con raíz <urlset>.
        $previous = libxml_use_internal_errors(true);
        $xml = simplexml_load_string($body);
        libxml_use_internal_errors($previous);

        $this->assertNotFalse($xml, 'El sitemap no es XML bien formado.');
        $this->assertSame('urlset', $xml->getName());
        $this->assertGreaterThan(0, $xml->count(), 'El sitemap no contiene <url> entries.');
    }
}
