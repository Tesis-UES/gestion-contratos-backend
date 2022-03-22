<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTIRequestDetails extends FormRequest
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
      'stay_schedule_id'  => 'required|integer|gte:1',
      'goal'              => 'required|string',
      'justification'     => 'required|string',
      'work_months'       => 'required|numeric|gt:0.0',
      'monthly_salary'    => 'required|numeric|gt:0.0',
      'salary_percentage' => 'required|numeric|min:0|max:0.25',
      'person_id'         => 'required|integer|gte:1',
      'group_ids'         => 'required|array|min:1',
      'group_ids.*'       => 'required|integer|distinct|gte:1',
      'position_activities'                  => 'required|array|min:1',
      'position_activities.*.position_id'    => 'required|integer|gte:1',
      'position_activities.*.activities'     => 'required|array|min:2',
      'position_activities.*.activities.*'   => 'required|string',
    ];
  }
}
