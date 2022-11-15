<?php
namespace App\Helper;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;

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

    public static function replaceIndexByDate(array $array, $isRange = false)
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
}
