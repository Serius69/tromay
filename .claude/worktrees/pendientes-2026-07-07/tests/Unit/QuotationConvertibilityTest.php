<?php

namespace Tests\Unit;

use App\Models\Quotation;
use Tests\TestCase;

class QuotationConvertibilityTest extends TestCase
{
    private function quotation(array $attributes): Quotation
    {
        return (new Quotation())->forceFill(array_merge([
            'client_id'   => 10,
            'status'      => Quotation::STATUS_VIGENTE,
            'valid_until' => null,
        ], $attributes));
    }

    public function test_a_valid_quotation_with_client_is_convertible(): void
    {
        $this->assertTrue($this->quotation([])->isConvertible());
        $this->assertTrue($this->quotation(['valid_until' => now()->addDay()->toDateString()])->isConvertible());
        $this->assertTrue($this->quotation(['valid_until' => now()->toDateString()])->isConvertible(), 'vence HOY: aún convertible');
    }

    public function test_an_annulled_or_converted_quotation_is_not_convertible(): void
    {
        $this->assertFalse($this->quotation(['status' => Quotation::STATUS_ANULADA])->isConvertible());
        $this->assertFalse($this->quotation(['status' => Quotation::STATUS_CONVERTIDA])->isConvertible());
    }

    public function test_an_expired_quotation_is_not_convertible(): void
    {
        $this->assertFalse($this->quotation(['valid_until' => now()->subDay()->toDateString()])->isConvertible());
    }

    public function test_a_quotation_without_client_is_not_convertible(): void
    {
        $this->assertFalse($this->quotation(['client_id' => null])->isConvertible());
    }
}
