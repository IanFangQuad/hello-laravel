<?php

namespace App\Services;

use \App\Exceptions\PostException;
use \App\Repositories\AttendanceRepository;

class PunchService
{

    private $attendanceRepository;

    public function __construct(AttendanceRepository $attendanceRepository)
    {
        $this->AttendanceRepository = $attendanceRepository;
    }

    public function punch($parms)
    {
        $member_id = $parms['member_id'];
        $date = $parms['date'];
        $record = $this->AttendanceRepository->getByMemberDate($member_id, $date);

        if ($record->count() == 0) {
            $parms['start_time'] = $parms['time'];
            unset($parms['time']);
            $status = $this->AttendanceRepository->create($parms);
            throw_if(!$status, new PostException);

            return redirect()->back()->with('msg', 'punch success');
        }

        $id = $record->first()->id;
        $parms['end_time'] = $parms['time'];
        unset($parms['time']);
        $status = $this->AttendanceRepository->update($id, $parms);
        throw_if(!$status, new PostException);

        return redirect()->back()->with('msg', 'punch success');
    }

}
