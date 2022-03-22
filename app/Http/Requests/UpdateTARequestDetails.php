<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTARequestDetails extends FormRequest
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
      'start_date'     => 'required|date_format:Y-m-d',
      'finish_date'    => 'required|date_format:Y-m-d|after:start_date',
      'weekly_hours'   => 'required|numeric|gt:1.0|max:10',
      'work_weeks'     => 'required|numeric|gt:1',
      'hourly_rate'    => 'required|numeric|gt:0.0',
      'person_id'      => 'required|integer|gte:1',
      'group_ids'      => 'required|array|min:1',
      'group_ids.*'    => 'required|integer|distinct|gte:1',
      'stay_schedule_id' => 'required|integer|gte:1',
      'position_activities'                  => 'required|array|min:1',
      'position_activities.*.position_id'    => 'required|integer|gte:1',
      'position_activities.*.activities'     => 'required|array|min:2',
      'position_activities.*.activities.*'   => 'required|string',
    ];
  }
}
