<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSPNPRequestDetails extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'start_date'        => 'required|date_format:Y-m-d',
            'finish_date'       => 'required|date_format:Y-m-d|after:start_date',
            'position'          => 'required|string',
            'person_id'         => 'required|integer|gte:1',
            'activities'        => 'required|array|min:2',
            'activities.*'      => 'required|string|distinct',
            'groups'            => 'required|array|min:1',
            'groups.*.group_id'     => 'required|integer|distinct|gte:1',
            'groups.*.hourly_rate'  => 'required|numeric|gte:1',
            'groups.*.work_weeks'   => 'required|numeric|gte:1',
            'groups.*.weekly_hours' => 'required|numeric|gte:1',
        ];
    }
}
