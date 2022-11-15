<?php

namespace App\Repositories;

use App\Models\Holiday;

class HolidayRepository
{
    public function getByPeriod($start, $end)
    {
        return Holiday::where('date', '>=', $start)->where('date', '<=', $end)->get();
    }
}
