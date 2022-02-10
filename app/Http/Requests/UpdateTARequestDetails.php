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
      'position'       => 'required|string',
      'weekly_hours'   => 'required|numeric|gt:1.0|max:10',
      'stay_schedule_id' =>'required|integer|gte:1',
      'work_weeks'     => 'required|numeric|gt:1',
      'hourly_rate'    => 'required|numeric|gt:0.0',
      'person_id'      => 'required|integer|gte:1',
      'activities'     => 'required|array|min:2',
      'activities.*'   => 'required|string|distinct',
      'group_ids'      => 'required|array|min:1',
      'group_ids.*'    => 'required|integer|distinct|gte:1',
    ];
  }
}
