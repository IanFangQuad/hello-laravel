<?php

namespace App\Http\Services;

use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
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
        $year = Carbon::parse($year)->firstOfYear()->firstOfMonth()->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
        $next = Carbon::parse($year)->add(1, 'year')->lastOfYear()->lastOfMonth()->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
        $holidays = $this->HolidayRepository->getByPeriod($year, $next)->toArray();
        $holidays = $this->replaceIndexByDate($holidays);

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

    private function replaceIndexByDate(array $array, $isRange = false)
    {
        $new = [];

        if ($isRange) {
            foreach ($array as $item) {
                $start = explode(' ', $item['start'])[0];
                $end = explode(' ', $item['end'])[0];

                $periods = [];
                $range = Carbon::parse($start)->daysUntil($end);
                foreach ($range as $date) {
                    $periods[] = $date->format('Y-m-d');
                }

                foreach ($periods as $date) {
                    if (!isset($new[$date])) {
                        $new[$date] = [$item];
                    } else {
                        array_push($new[$date], $item);
                    }
                }
            }

            return $new;
        }

        foreach ($array as $index => $item) {
            $new[$item['date']] = $array[$index];
        }

        return $new;
    }

    private function attachProps(CarbonPeriod $period): array
    {
        $rearrange = [];

        foreach ($period as $index => $date) {

            $rearrange[] = [
                'date' => $date,
                'dayoff' => false,
                'annotation' => '',
            ];
        }

        return $rearrange;
    }

    private function attachHolidays(array $period, $holidays): array
    {
        foreach ($period as $index => $date) {

            $dateString = $date['date']->format('Y-m-d');

            if (array_key_exists($dateString, $holidays)) {
                $dayoff = ($holidays[$dateString]['dayoff'] == 1) ? true : false;
                $period[$index]['dayoff'] = $dayoff;
                $period[$index]['annotation'] = $holidays[$dateString]['annotation'];
            }

        }
        return $period;
    }

    private function attachLeaves(array $period): array
    {
        $startOfMonth = $period[0]['date']->format('Y-m-d');
        $lastIndex = count($period) - 1;
        $endOfMonth = $period[$lastIndex]['date']->format('Y-m-d');

        $leaves = $this->LeaveRepository->getByPeriod($startOfMonth, $endOfMonth)->toArray();
        $leaves = $this->replaceIndexByDate($leaves, true);

        foreach ($period as $index => $date) {

            $event = [];

            $dateString = $date['date']->format('Y-m-d');

            if (array_key_exists($dateString, $leaves)) {
                $event = $leaves[$dateString];
            }

            $period[$index]['events'] = $event;

        }

        return $period;
    }
}
