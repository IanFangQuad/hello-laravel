<?php

namespace App\Services;

use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use \App\Repositories\HolidayRepository;
use \App\Repositories\LeaveRepository;

class CalendarService
{
    private $leaveRepository;

    public function __construct(LeaveRepository $leaveRepository, HolidayRepository $holidayRepository, )
    {
        $this->LeaveRepository = $leaveRepository;
        $this->HolidayRepository = $holidayRepository;
    }

    public function getSchedules($parms): array
    {
        $year = isset($parms['y']) ? $parms['y'] : Carbon::now()->format('Y');
        $month = isset($parms['m']) ? $parms['m'] : Carbon::now()->format('m');
        // get calendar dates of current month
        $target = Carbon::parse("{$year}-{$month}-1");
        $startOfMonth = $target->copy()->firstOfMonth()->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
        $endOfMonth = $target->copy()->lastOfMonth()->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
        // get holidays for front client calculate leave hours
        $start = Carbon::parse("{$year}-01-01")->firstOfYear()->format('Y-m-d');
        $end = Carbon::parse($year)->add(1, 'year')->lastOfYear()->format('Y-m-d');

        // $holidays = $this->HolidayRepository->getByPeriod($start, $end)->toArray();
        $holidays = $this->HolidayRepository->getByPeriod($start, $end)->keyBy('date');
        $hasTargetSchedule = $this->checkSchedule($year, $holidays);
        $holidays = $hasTargetSchedule ? $holidays : collect();

        $period = Carbon::parse($startOfMonth)->daysUntil($endOfMonth);
        $period = $this->attachProps($period);
        $period = $this->attachHolidays($period, $holidays);
        $period = $this->attachLeaves($period);

        $calendar = [
            'query' => $target,
            'dates' => $period,
            'holidays' => $holidays,
        ];

        return $calendar;
    }

    private function attachProps(CarbonPeriod $period): Collection
    {
        $rearrange = collect();

        foreach ($period as $index => $date) {

            $rearrange->push((object) [
                'date' => $date,
                'dayoff' => false,
                'annotation' => '',
            ]);
        }

        return $rearrange;
    }

    private function attachHolidays(Collection $period, Collection $holidays): Collection
    {
        foreach ($period as $index => $date) {

            $dateString = $date->date->format('Y-m-d');

            if ($holidays->has($dateString)) {
                $dayoff = ($holidays->get($dateString)->dayoff == 1) ? true : false;
                $annotation = $holidays->get($dateString)->annotation;
                $period->get($index)->dayoff = $dayoff;
                $period->get($index)->annotation = $annotation;
            }

        }
        return $period;
    }

    private function attachLeaves(Collection $period): Collection
    {
        $startOfMonth = $period->first()->date->format('Y-m-d');
        $endOfMonth = $period->last()->date->format('Y-m-d');

        $leaves = $this->LeaveRepository->getByPeriod($startOfMonth, $endOfMonth);
        $leaves = $this->leavesBydates($leaves);

        foreach ($period as $index => $date) {

            $dateString = $date->date->format('Y-m-d');

            if (!$leaves->has($dateString) || $date->dayoff) {
                $period->get($index)->events = collect();
                continue;
            }

            $period->get($index)->events = $leaves->get($dateString);

        }

        return $period;
    }

    private function checkSchedule($targetYear, Collection $holidays): bool
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
            $start = $leave->start;
            $end = $leave->end;

            // to create time range of this leave
            // ex: start = '2022-01-01' end = '2022-01-03' get  $periods = collect(['2022-01-01','2022-01-02','2022-01-03'])
            $periods = collect();
            $range = Carbon::parse($start)->daysUntil($end);

            foreach ($range as $date) {
                $periods->push($date->format('Y-m-d'));
            }

            foreach ($periods as $date) {
                if (!$leavesReform->has($date)) {
                    $leavesReform->put($date, collect()->push($leave));
                } else {
                    $leavesReform->get($date)->push($leave);
                }
            }
        }

        return $leavesReform;
    }

}
