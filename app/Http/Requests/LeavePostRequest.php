<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use \App\Rules\GreaterThanToday;

class LeavePostRequest extends FormRequest
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
            'approval' => 0,
            'start' => $this->input('start-date') . ' ' . $this->input('start-time'),
            'end' => $this->input('end-date') . ' ' . $this->input('end-time'),
            'member_id' => Auth::user()->id,
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
}
