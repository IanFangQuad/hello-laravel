<?php

namespace Database\Seeders;

use App\Models\Holiday;
use App\Helper\Helper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $table = 'holidays';
        $path = '/calendar';

        $files = Helper::getfFileNames($path);

        foreach ($files as $file) {

            $lines = Helper::readCSV($file);

            foreach ($lines as $line) {

                $date = Carbon::parse($line[0])->format('Y-m-d');
                $dayoff = ($line[2] == 0 ? false : true);
                $annotation = $line[3];

                if ($dayoff || !empty($annotation)) {
                    Holiday::create([
                        'date' => $date,
                        'weekName' => $line[1],
                        'dayoff' => $dayoff,
                        'annotation' => $annotation,
                    ]);
                }

            }
        }
    }

}
