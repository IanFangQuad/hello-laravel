<?php

namespace App\Services;

use Illuminate\Support\Carbon;
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
        $record = $this->AttendanceRepository->getByMemberDate($member_id, $date)->first();

        if ($record) {
            $start = Carbon::parse($record->date . ' ' . $record->start_time);
            $deadline = Carbon::parse($record->date . ' 09:30:00');
            $late = ($start->diffInMinutes($deadline, false) < 0) ? 'late' : '';

            $excused = '';
            if ($record->end_time) {
                $end = Carbon::parse($record->date . ' ' . $record->end_time);
                $workoff = $late ? Carbon::parse($record->date . ' 18:30:00') : $start->copy()->add(9, 'hour');
                $excused = ($end->diffInMinutes($workoff, false) > 0) ? 'excused' : '';
            }

            $status = [
                'start_time' => $late,
                'end_time' => $excused,
            ];

            $record->status = $status;
        }

        return $record;
    }

    public function attachStatus(Collection $record)
    {
    }

}
