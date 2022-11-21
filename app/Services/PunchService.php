<?php

namespace App\Services;

use Illuminate\Support\Collection;
use \App\Exceptions\PostException;
use \App\Repositories\AttendanceRepository;

class PunchService
{

    private $attendanceRepository;

    public function __construct(AttendanceRepository $attendanceRepository)
    {
        $this->AttendanceRepository = $attendanceRepository;
    }

    public function punchin($parms)
    {
        $status = $this->AttendanceRepository->create($parms);
        throw_if(!$status, new PostException);

        return redirect()->back()->with('msg', 'punch in success');
    }

    public function punchout($parms, $id)
    {
        $status = $this->AttendanceRepository->update($id, $parms);
        throw_if(!$status, new PostException);

        return redirect()->back()->with('msg', 'punch out success');
    }

    public function getRecord($parms)
    {
        $member_id = $parms['member_id'];
        $date = $parms['date'];
        $record = $this->AttendanceRepository->getByMemberDate($member_id, $date);
        return $record;
    }

    public function attachStatus(Collection $record)
    {
    }

}
