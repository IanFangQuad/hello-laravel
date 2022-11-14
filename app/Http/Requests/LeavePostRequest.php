<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => 'required',
            'member_id' => 'required',
            'start-date'=> 'required',
            'start-time'=> 'required',
            'end-date'=> 'required',
            'end-time'=> 'required',
            'description'=> '',
            'hours'=> '',
        ];

    }

    public function messages()
    {
        return [
            'type.required' => 'please select type',
        ];
    }
}
