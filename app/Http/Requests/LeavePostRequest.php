<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use \App\Repositories\HolidayRepository;
use \App\Rules\GreaterThanToday;
use \App\Services\CalendarService;

class LeavePostRequest extends FormRequest
{
    private $holidayRepository;
    private $calendarService;

    public function __construct(HolidayRepository $holidayRepository, CalendarService $calendarService, )
    {
        $this->HolidayRepository = $holidayRepository;
        $this->CalendarService = $calendarService;
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

        $hours = $this->CalendarService->countHours($start, $end);

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
            'hours' => '',
        ];

    }

    public function messages()
    {
        return [
            'type.required' => 'please select type',
        ];
    }

}
