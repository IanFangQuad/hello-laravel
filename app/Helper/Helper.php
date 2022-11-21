<?php
namespace App\Helper;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use \App\Enums\LeaveType;

class Helper
{
    public static function readCSV($csvFile, array $parms = ['delimiter' => ',']): array
    {

        $fileHandle = fopen($csvFile, 'r');
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

    public static function getfFileNames($path)
    {
        $fileNames = [];
        $path = public_path($path);
        $files = File::allFiles($path);

        foreach ($files as $file) {
            array_push($fileNames, pathinfo($file)['dirname'] . '/' . pathinfo($file)['basename']);
        }

        return $fileNames;
    }

    public static function leavesBydates(Collection $leaves): Collection
    {
        $leavesReform = collect();

        foreach ($leaves as $leave) {
            $type = LeaveType::fromKey($leave->type);
            $leave->type = $type;
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
