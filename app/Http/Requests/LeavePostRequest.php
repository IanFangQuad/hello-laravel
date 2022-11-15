<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use \App\Helper\Helper;
use \App\Repositories\HolidayRepository;
use \App\Rules\GreaterThanToday;

class LeavePostRequest extends FormRequest
{
    private $holidayRepository;

    public function __construct(HolidayRepository $holidayRepository, )
    {
        $this->HolidayRepository = $holidayRepository;
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {

        $startTime = $this->input('start-time');
        $endTime = $this->input('end-time');
        $start = $this->input('start-date') . ' ' . $startTime;
        $end = $this->input('end-date') . ' ' . $endTime;

        $hours = $this->countHours($start, $end);

        $this->merge([
            'approval' => 0,
            'start' => $start,
            'end' => $end,
            'member_id' => Auth::user()->id,
            'hours' => $hours,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => 'required',
            'start-date' => ['required', new GreaterThanToday],
            'start-time' => 'required',
            'end-date' => 'required',
            'end-time' => 'required',
            'description' => '',
            'approval' => '',
            'start' => '',
            'end' => '',
            'member_id' => '',
        ];

    }

    public function messages()
    {
        return [
            'type.required' => 'please select type',
        ];
    }

    private function countHours($stratDatetime, $endDateTime): int
    {
        $today = Carbon::now()->format('Y-m-d');
        $startTime = Carbon::parse($stratDatetime)->format('H:i:s');
        $endTime = Carbon::parse($endDateTime)->format('H:i:s');

        $holidays = $this->HolidayRepository->getByPeriod($stratDatetime, $endDateTime)->toArray();
        $holidays = Helper::replaceIndexByDate($holidays);

        $range = [];
        $period = Carbon::parse($stratDatetime)->daysUntil($endDateTime);

        foreach ($period as $date) {
            $date = $date->format('Y-m-d');
            if (!array_key_exists($date, $holidays)) {
                array_push($range, $date);
            }
        }
        $afternoon = Carbon::parse($today . ' ' . '12:00:00');
        $startTime = Carbon::parse($today . ' ' . $startTime);
        $endTime = Carbon::parse($today . ' ' . $endTime);
        $days = 0;
        $lastIndex = (count($range)) - 1;

        foreach ($range as $index => $date) {

            if ($index == 0) {
                $isStartFromMorning = ($afternoon->copy()->diffInHours($startTime->copy(), false)) < 0;
                $days += $isStartFromMorning ? 1 : 0.5;
                continue;
            }

            if ($index == $lastIndex) {
                if ($startTime->copy()->format('H:i:s') == $endTime->copy()->format('H:i:s') && $endTime->copy()->format('H:i:s') == '09:00:00') {
                    $days += 0;
                    continue;
                }
                $isEndAfternoon = ($afternoon->copy()->diffInHours($endTime->copy(), false)) > 0;
                $days += $isEndAfternoon ? 1 : 0.5;
                continue;
            }

            $days += 1;
        }

        return $days * 24;
    }
}
