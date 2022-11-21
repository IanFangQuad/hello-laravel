<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendPostRequest extends FormRequest
{
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
        $this->merge([
            'member_id' => Auth::user()->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'start_time' => $this->input('time'),
            'end_time' => $this->input('time'),
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
            'date' => '',
            'time' => 'required',
            'member_id' => '',
            'start_time' => '',
            'end_time' => '',
        ];
    }

    public function messages()
    {
        return [
            'time.required' => 'time is required',
        ];
    }
}
