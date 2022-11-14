<?php

namespace Database\Seeders;

use App\Models\Festival;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class FestivalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $table = 'festivals';
        $path = '/calendar';

        $files = $this->getCSVFiles($path);

        foreach ($files as $file) {

            $lines = $this->readCSV($file);

            foreach ($lines as $line) {

                $date = Carbon::parse($line[0])->format('Y-m-d');
                $dayoff = ($line[2] == 0 ? false : true);

                Festival::create([
                    'date' => $date,
                    'weekName' => $line[1],
                    'dayoff' => $dayoff,
                    'annotation' => $line[3],
                ]);
            }
        }
    }

    private function readCSV($csvFile, array $parms = ['delimiter' => ',']): array
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

    private function getCSVFiles($path)
    {
        $fileNames = [];
        $path = public_path($path);
        $files = File::allFiles($path);

        foreach ($files as $file) {
            array_push($fileNames, pathinfo($file)['dirname'] . '/' . pathinfo($file)['basename']);
        }

        return $fileNames;
    }
}
