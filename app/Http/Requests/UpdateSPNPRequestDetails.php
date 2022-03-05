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
            'person_id'         => 'required|integer|gte:1',
            'groups'            => 'required|array|min:1',
            'groups.*.group_id'     => 'required|integer|distinct|gte:1',
            'groups.*.hourly_rate'  => 'required|numeric|gte:1',
            'groups.*.work_weeks'   => 'required|numeric|gte:1',
            'groups.*.weekly_hours' => 'required|numeric',
            'position_activities'                  => 'required|array|min:1',
            'position_activities.*.position_id'    => 'required|integer|gte:1',
            'position_activities.*.activities'     => 'required|array|min:2',
            'position_activities.*.activities.*'   => 'required|string|distinct',
        ];
    }
}
