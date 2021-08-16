<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonValidationController extends Controller
{
    public function show($personId)
    {
        $personValidation = Person::where('id', $personId)->firstOrFail()->personValidations;
    
        return response($personValidation, 200);
    }

    public function showMe()
    {
        $user = Auth::user();
        $personValidation = Person::where('user_id', $user->id)->firstOrFail()->personValidations;
    
        return response($personValidation, 200);
    }

    public function update(Request $request, $personId)
    {
        $request->validate([
            'dui_readable'                  => 'required|boolean',
            'name_correct'                  => 'required|boolean',
            'address_correct'               => 'required|boolean',
            'dui_current'                   => 'required|boolean',
            'nit_readable'                  => 'required|boolean',
            'curriculum_readable'           => 'required|boolean',
            'profesional_title_readable'    => 'required|boolean',
            'profesional_title_validated'   => 'required|boolean',
            'bank_account_readable'         => 'required|boolean',
            'work_permission_readable'      => 'boolean',
        ]);

        $personValidation = Person::where('id', $personId)->firstOrFail()->personValidations;
        $personValidation->update($request->all());
        return response($personValidation, 200);
    }
}
