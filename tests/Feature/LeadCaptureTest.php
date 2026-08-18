<?php

namespace Tests\Feature;

use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Flujo de conversión de la vitrina: la lista de espera de alertas.
 *
 * El punto delicado NO es que guarde el email, es que el copy y la respuesta
 * digan la verdad: Tromay no envía correos hoy, así que el endpoint no puede
 * prometer un aviso ni una baja de suscripción, y no debe filtrar el email
 * hacia otro host por la URL de continuación.
 */
class LeadCaptureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_stores_a_lead_and_points_to_a_tracker_that_is_actually_live(): void
    {
        $response = $this->postJson('/leads', [
            'email'    => '  Persona@Ejemplo.COM ',
            'currency' => 'usd',
            'source'   => 'home',
        ]);

        $response->assertOk()->assertJson(['ok' => true]);

        // Normalizado: sin espacios y en minúscula, para que el UNIQUE sirva.
        $this->assertDatabaseHas('leads', ['email' => 'persona@ejemplo.com', 'currency' => 'usd']);

        $continue = $response->json('continue_url');
        $this->assertStringStartsWith('https://paralelo.kapitalya.com.bo', $continue);

        // El email NO puede viajar en la URL hacia otro host: quedaría en el
        // Referer y en los logs de acceso de ese servicio.
        $this->assertStringNotContainsString('persona@ejemplo.com', $continue);
        $this->assertStringNotContainsString('email=', $continue);
    }

    /** @test */
    public function it_does_not_promise_an_alert_it_cannot_send(): void
    {
        $message = $this->postJson('/leads', ['email' => 'a@b.com'])->json('message');

        foreach (['te avisaremos', 'te avisamos', 'darte de baja'] as $promise) {
            $this->assertStringNotContainsStringIgnoringCase($promise, $message);
        }
    }

    /** @test */
    public function resubmitting_the_same_email_does_not_duplicate_the_lead(): void
    {
        foreach (range(1, 3) as $_) {
            $this->postJson('/leads', ['email' => 'repetido@ejemplo.com'])->assertOk();
        }

        $this->assertSame(1, Lead::where('email', 'repetido@ejemplo.com')->count());
    }

    /** @test */
    public function the_honeypot_silently_rejects_bots_without_storing_anything(): void
    {
        $this->postJson('/leads', [
            'email'   => 'bot@spam.com',
            'website' => 'http://spam.example',
        ])->assertOk()->assertJson(['ok' => true]);

        $this->assertDatabaseCount('leads', 0);
    }

    /**
     * CVE-2026-48019 (inyección CRLF en la regla `email`) no tiene parche para
     * Laravel 9, que está fuera de soporte: la defensa vive en la validación.
     *
     * @test
     */
    public function it_rejects_control_characters_smuggled_into_the_email(): void
    {
        foreach ([
            "victima@ejemplo.com\r\nBcc: atacante@evil.com",
            "victima@ejemplo.com\nSubject: spam",
            "victima@ejemplo.com\x00",
        ] as $payload) {
            $this->postJson('/leads', ['email' => $payload])->assertStatus(422);
        }

        $this->assertDatabaseCount('leads', 0);
    }

    /** @test */
    public function it_rejects_an_invalid_email_and_an_unsupported_currency(): void
    {
        $this->postJson('/leads', ['email' => 'no-es-un-email'])->assertStatus(422);
        $this->postJson('/leads', ['email' => 'a@b.com', 'currency' => 'xyz'])->assertStatus(422);

        $this->assertDatabaseCount('leads', 0);
    }
}
