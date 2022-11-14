<?php

namespace App\Repositories;

use App\Models\Leave;

class LeaveRepository
{

    public function create(array $params)
    {
        $result = Leave::create($params);

        return $result;
    }

    public function getByPeriod($start, $end)
    {
        return Leave::with('member')->where('start', '>=', $start)->where('start', '<=', $end)->get();
    }

    public function delete($id)
    {
        return Leave::find($id)->delete();
    }

    public function update($id, array $params)
    {
        return Leave::find($id)->update($params);
    }
}
