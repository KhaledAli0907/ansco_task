<?php

namespace App\Actions;

use Carbon\Carbon;


class CalculateSubscribtionEndDateAction
{
    public function handle(Carbon $startDate, int $duration, string $durationType): Carbon
    {
        return match ($durationType) {
            'days' => $startDate->copy()->addDays($duration),
            'months' => $startDate->copy()->addMonths($duration),
            'years' => $startDate->copy()->addYears($duration),
            default => $startDate->copy()->addDays($duration),
        };
    }
}
