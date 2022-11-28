<?php

namespace App\Services;

use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use \App\Repositories\HolidayRepository;
use \App\Repositories\LeaveRepository;
use \App\Enums\LeaveType;

class CalendarService
{
    private $leaveRepository;

    public function __construct(LeaveRepository $leaveRepository, HolidayRepository $holidayRepository, )
    {
        $this->LeaveRepository = $leaveRepository;
        $this->HolidayRepository = $holidayRepository;
    }

    public function getSchedules(array $parms): array
    {
        $year = isset($parms['y']) ? $parms['y'] : Carbon::now()->format('Y');
        $month = isset($parms['m']) ? $parms['m'] : Carbon::now()->format('m');
        // get calendar dates of current month
        $target = Carbon::parse("{$year}-{$month}-1");
        $startOfMonth = $target->copy()->firstOfMonth()->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
        $endOfMonth = $target->copy()->lastOfMonth()->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
        // get holidays for front client calculate leave hours
        $start = Carbon::parse($year)->firstOfYear()->format('Y-m-d');
        $end = Carbon::parse($year)->add(1, 'year')->lastOfYear()->format('Y-m-d');

        $holidays = $this->HolidayRepository->getDayoffByPeriod($start, $end)->keyBy('date');
        $hasTargetSchedule = $this->checkSchedule($year, $holidays);
        $holidays = $hasTargetSchedule ? $holidays : collect();

        $period = Carbon::parse($startOfMonth)->daysUntil($endOfMonth);
        $period = $this->attachLeaves($period, $holidays);

        $calendar = [
            'query' => $target,
            'dates' => $period,
            'holidays' => $holidays,
        ];

        return $calendar;
    }

    public function countHours(string $stratDatetime, string $endDateTime): int
    {
        $today = Carbon::now()->format('Y-m-d');
        $startTime = Carbon::parse($stratDatetime)->format('H:i:s');
        $endTime = Carbon::parse($endDateTime)->format('H:i:s');

        $holidays = $this->HolidayRepository->getDayoffByPeriod($stratDatetime, $endDateTime)->keyBy('date');

        $range = [];
        $period = Carbon::parse($stratDatetime)->daysUntil($endDateTime);

        foreach ($period as $date) {
            $date = $date->format('Y-m-d');
            if (!$holidays->has($date)) {
                array_push($range, $date);
            }
        }
        $afternoon = Carbon::parse($today . ' ' . '13:00:00');
        $startTime = Carbon::parse($today . ' ' . $startTime);
        $endTime = Carbon::parse($today . ' ' . $endTime);
        $days = 0;
        $lastIndex = (count($range)) - 1;

        foreach ($range as $index => $date) {

            if ($index == $lastIndex) {
                if ($startTime->copy()->format('H:i:s') == $endTime->copy()->format('H:i:s') && $endTime->copy()->format('H:i:s') == '09:00:00') {
                    $days += 0;
                    continue;
                }
                $isEndAfternoon = ($afternoon->copy()->diffInHours($endTime->copy(), false)) > 0;
                $days += $isEndAfternoon ? 1 : 0.5;
                continue;
            }

            if ($index == 0) {
                $isStartFromMorning = ($afternoon->copy()->diffInHours($startTime->copy(), false)) < 0;

                $days += $isStartFromMorning ? 1 : 0.5;
                continue;
            }

            $days += 1;
        }

        return $days * 24;
    }

    private function attachLeaves(CarbonPeriod $period, Collection $holidays): array
    {

        $rearrange = [];

        $startOfMonth = $period->startDate->format('Y-m-d');
        $endOfMonth = $period->endDate->format('Y-m-d');
        $leaves = $this->LeaveRepository->getByPeriod($startOfMonth, $endOfMonth);
        $leaves = $this->leavesBydates($leaves);

        foreach ($period as $index => $date) {

            $dateString = $date->format('Y-m-d');
            $dayoff = false;
            $annotation = '';

            if ($holidays->has($dateString)) {
                $dayoff = ($holidays->get($dateString)->dayoff == 1) ? true : false;
                $annotation = $holidays->get($dateString)->annotation;
            }

            $events = [];

            if ($leaves->has($dateString) && !$dayoff) {
                $events = $leaves->get($dateString);
            }

            $rearrange[] = [
                'date' => $date,
                'dayoff' => $dayoff,
                'annotation' => $annotation,
                'events' => $events,
            ];
        }

        return $rearrange;
    }

    private function checkSchedule(string $targetYear, Collection $holidays): bool
    {
        $hasTargetSchedule = false;

        foreach ($holidays as $holiday) {
            $year = Carbon::parse($holiday->date)->format('Y');
            if ($year == $targetYear) {
                $hasTargetSchedule = true;
                break;
            }
        }

        return $hasTargetSchedule;
    }

    private function leavesBydates(Collection $leaves): Collection
    {
        $leavesReform = collect();

        foreach ($leaves as $leave) {
            $leave->type = LeaveType::fromKey($leave->type);
            $start = $leave->start;
            $end = $leave->end;

            // to create time range of this leave
            // ex: start = '2022-01-01' end = '2022-01-03' get  $periods = collect(['2022-01-01','2022-01-02','2022-01-03'])

            $range = Carbon::parse($start)->daysUntil($end);

            foreach ($range as $date) {
                $dateString = $date->format('Y-m-d');

                if (!$leavesReform->has($dateString)) {
                    $leavesReform->put($dateString, collect()->push($leave));
                } else {
                    $leavesReform->get($dateString)->push($leave);
                }
            }
        }

        return $leavesReform;
    }

}
