<?php

namespace App\Repositories;

use App\Models\Attendance;

class AttendanceRepository
{

    public function create(array $params)
    {
        $result = Attendance::create($params);

        return $result;
    }

    public function getByMemberDate($member_id, $date)
    {
        return Attendance::with('member')->where('member_id', '=', $member_id)->where('date', '=', $date)->get();
    }

    public function getByMemberPeriod($member_id, $start, $end)
    {
        return Attendance::with('member')->where('member_id', '=', $member_id)->where('date', '>=', $start)->where('date', '<=', $end)->get();
    }

    public function update($id, array $params)
    {
        return Attendance::find($id)->update($params);
    }
}
