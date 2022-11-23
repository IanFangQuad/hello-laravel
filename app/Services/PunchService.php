<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use \App\Exceptions\PostException;
use \App\Helper\Helper;
use \App\Repositories\AttendanceRepository;
use \App\Repositories\HolidayRepository;
use \App\Repositories\LeaveRepository;

class PunchService
{

    private $attendanceRepository;
    private $leaveRepository;
    private $holidayRepository;

    public function __construct(AttendanceRepository $attendanceRepository, LeaveRepository $leaveRepository, HolidayRepository $holidayRepository, )
    {
        $this->AttendanceRepository = $attendanceRepository;
        $this->LeaveRepository = $leaveRepository;
        $this->HolidayRepository = $holidayRepository;
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
            $diff = $start->diffInMinutes($deadline, false);
            $isLate = ($diff < 0);
            $lateMsg = $isLate ? 'late ' . abs($diff) . ' minutes' : '';

            $excusedMsg = '';
            if ($record->end_time) {
                $end = Carbon::parse($record->date . ' ' . $record->end_time);
                $workoff = $isLate ? Carbon::parse($record->date . ' 18:30:00') : $start->copy()->add(9, 'hour');
                $diff = $end->diffInMinutes($workoff, false);
                $isExcused = ($diff > 0);
                $excusedMsg = $isExcused ? 'excused ' . abs($diff) . ' minutes' : '';
            }

            $status = [
                'start_time' => $lateMsg,
                'end_time' => $excusedMsg,
            ];

            $record->status = $status;
        }

        return $record;
    }

    public function getList($member_id, $parms)
    {
        $year = isset($parms['y']) ? $parms['y'] : Carbon::now()->format('Y');
        $month = isset($parms['m']) ? $parms['m'] : Carbon::now()->format('m');

        $start = Carbon::parse("{$year}-{$month}-1");
        $end = $start->copy()->lastOfMonth();

        $leaves = $this->LeaveRepository->getByPeriod($start, $end);
        $leaves = Helper::leavesBydates($leaves);
        foreach ($leaves as $date => $events) {
            $events = $events->filter(function ($leave) use ($member_id) {
                return $leave->member_id == $member_id;
            })->values();

            if ($events->isEmpty()) {
                unset($leaves[$date]);
                continue;
            }

            $leaves[$date] = $events;
        }

        $range = [];
        $period = $start->copy()->daysUntil($end->copy()->format('Y-m-d'));

        $holidays = $this->HolidayRepository->getByPeriod($start, $end)->keyBy('date');
        $holidays = $holidays->filter(function ($value) {
            return $value->dayoff;
        });

        foreach ($period as $day) {
            $isPast = (Carbon::now()->StartOfDay()->diffInDays($day, false) <= 0);
            $date = $day->copy()->format('Y-m-d');
            $dayoff = ($holidays->has($date));
            if ($isPast && !$dayoff) {
                array_push($range, $date);
            }
        }

        $records = $this->AttendanceRepository->getByMemberPeriod($member_id, $start->format('Y-m-d'), $end->format('Y-m-d'))->keyBy('date');

        foreach ($range as $date) {

            $isToday = ($date == Carbon::now()->format('Y-m-d'));
            $status = '';

            if (!$records->has($date)) { // the day doesn't have record, its status default to absent

                $status = $isToday ? 'remember to punch in' : 'absent';
                $stuff = collect();

                if ($leaves->has($date)) { // but that day have leave
                    $type = $leaves[$date][0]->type->key;
                    $isApproved = $leaves[$date][0]->approval;
                    $status = $isApproved ? $type . ' leave' : 'leave reviewing';

                    $start = Carbon::parse($leaves[$date][0]->start);
                    $end = Carbon::parse($leaves[$date][0]->end);
                    // if the date is ends of leave, it could be a half day leave that not allow to have no record
                    $isEndsDateOfLeave = ($end->copy()->format('Y-m-d') == $date || $start->copy()->format('Y-m-d') == $date);

                    $usage = $isEndsDateOfLeave ? $start->copy()->setMonth(1)->setDay(1)->diffInHours($end->copy()->setMonth(1)->setDay(1)) : 9;
                    $isAllDayLeave = ($usage == 9);

                    if ($isEndsDateOfLeave && !$isAllDayLeave) {
                        $status = $isApproved ? 'half day ' . $type . ' leave, half day absent' : 'leave reviewing, but still have half day absent';
                    }
                }

                $stuff->status = $status;
                $stuff->start_time = '';
                $stuff->end_time = '';
                $records[$date] = $stuff;
                continue;
            }

            $start_time = $records[$date]->start_time;
            $end_time = $records[$date]->end_time;

            if (!$start_time || !$end_time) { // the day only have one record must be forget to punch
                $status = $isToday ? 'remember to punch out' : 'forget to punch';
                $records[$date]->status = $status;
                continue;
            }

            // the day have both records will count late/excused
            $start = Carbon::parse($date . ' ' . $start_time);
            $deadline = Carbon::parse($date . ' 09:30:00');
            $isLate = ($start->diffInMinutes($deadline, false) < 0);
            $status .= $isLate ? 'late,' : '';

            $end = Carbon::parse($date . ' ' . $end_time);
            $workoff = $isLate ? Carbon::parse($date . ' 18:30:00') : $start->copy()->add(9, 'hour');
            $isExcused = ($end->diffInMinutes($workoff, false) > 0);
            $status .= $isExcused ? ' excused' : '';
            $status = trim($status, ' ,');

            $records[$date]->status = $status;
        }

        $records = $records->sortBy(function ($record, $date) {
            return Carbon::parse($date)->timestamp;
        });

        return $records;
    }

}
