<?php

namespace App\Repositories;

use App\Models\Holiday;

class HolidayRepository
{
    public function getByPeriod(string $start, string $end)
    {
        return Holiday::where('date', '>=', $start)->where('date', '<=', $end)->get();
    }

    public function getDayoffByPeriod(string $start, string $end)
    {
        return Holiday::where('date', '>=', $start)->where('date', '<=', $end)->where('dayoff', '=', 1)->get();
    }
}
