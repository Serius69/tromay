<?php

namespace App\Observers;

use App\Models\Cash;
use App\Models\CashRate;
use App\Services\RateService;

class CashObserver
{
    public function __construct(private RateService $rates) {}

    public function saved(Cash $cash): void
    {
        // Record a rate snapshot whenever buy/sell/oficial values change
        if ($cash->wasRecentlyCreated || $cash->wasChanged(['buy', 'sell', 'oficial'])) {
            CashRate::create([
                'cash_id' => $cash->id,
                'buy'     => $cash->buy,
                'sell'    => $cash->sell,
                'oficial' => $cash->oficial,
            ]);
        }

        $this->rates->invalidate();
    }

    public function deleted(Cash $cash): void
    {
        $this->rates->invalidate();
    }
}
