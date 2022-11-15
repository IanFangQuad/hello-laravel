<?php

namespace App\Http\Services;

use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use \App\Repositories\LeaveRepository;

class CalendarService
{
    private $leaveRepository;

    public function __construct(LeaveRepository $leaveRepository, )
    {
        $this->LeaveRepository = $leaveRepository;
    }

    public function getSchedules($parms): array
    {
        $year = isset($parms['y']) ? $parms['y'] : Carbon::now()->format('Y');
        $month = isset($parms['m']) ? $parms['m'] : Carbon::now()->format('m');

        $target = Carbon::parse("{$year}-{$month}-1");
        $startOfMonth = $target->copy()->firstOfMonth()->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
        $endOfMonth = $target->copy()->lastOfMonth()->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');

        $period = Carbon::parse($startOfMonth)->daysUntil($endOfMonth);
        $period = $this->attachCommonRule($period);
        $period = $this->attachFestivals($period);
        $period = $this->attachLeaves($period);

        $calendar['query'] = $target;
        $calendar['dates'] = $period;

        return $calendar;
    }

    private function readCSV($csvFile, array $parms): array
    {
        $fileHandle = fopen(public_path($csvFile), 'r');
        while (!feof($fileHandle)) {
            $lines[] = fgetcsv($fileHandle, 0, $parms['delimiter']);
        }
        fclose($fileHandle);
        array_shift($lines); // delete first line (column title)
        $lines = array_filter($lines, function ($item) {
            return $item !== false;
        });

        return $lines;
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

    private function attachCommonRule(CarbonPeriod $period): array
    {
        $rearrange = [];

        foreach ($period as $index => $date) {

            $dayoff = ($date->copy()->dayOfWeek == Carbon::SUNDAY || $date->copy()->dayOfWeek == Carbon::SATURDAY) ? true : false;

            $rearrange[] = [
                'date' => $date,
                'dayoff' => $dayoff,
                'annotation' => '',
            ];
        }

        return $rearrange;
    }

    private function attachFestivals(array $period): array
    {
        //抓今年跟明年 //前端驗證日期不能小於現在
        $year = $period[0]['date']->format('Y');
        $file = "calendar/{$year}.csv";
        $lines = $this->readCSV($file, array('delimiter' => ','));
        $lines = array_map(function ($item) {
            $dayoff = ($item[2] == 0 ? false : true);
            return [
                'date' => Carbon::parse($item[0])->format('Y-m-d'),
                'dayoff' => $dayoff,
                'annotation' => $item[3],
            ];
        }, $lines);
        $festivals = $this->replaceIndexByDate($lines);

        foreach ($period as $index => $date) {

            $dateString = $date['date']->format('Y-m-d');

            if (array_key_exists($dateString, $festivals)) {
                $period[$index]['dayoff'] = $festivals[$dateString]['dayoff'];
                $period[$index]['annotation'] = $festivals[$dateString]['annotation'];
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
