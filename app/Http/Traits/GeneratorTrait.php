<?php

namespace App\Http\Traits;

use RandomLib\Factory;
use App\Models\RequestCode;


trait GeneratorTrait {
  public function generatePassword() {
      $factory = new Factory();
      $generator = $factory->getMediumStrengthGenerator();
      return $generator->generateString(32);
    }

    public function getSchoolCode(int $schoolId){
      $schoolCode = [
        '1' => 'ARQ',
        '2' => 'CIV',
        '3' => 'IND',
        '4' => 'MEC',
        '5' => 'ELE',
        '6' => 'QMC',
        '7' => 'ALI',
        '8' => 'SIF',
        '9' => 'UCB',
        'DEFAULT' => 'PGD'
      ];
      if ($schoolId>sizeof($schoolCode)-1) return $schoolCode['DEFAULT'];
      else return $schoolCode[$schoolId];
    }

  public function generateRequestCode(int $schoolId) {
    $currentYear = date('Y');
    $schoolCode = $this->getSchoolCode($schoolId);
    $requestCode = RequestCode::where([
      'school_id' => $schoolId, 
      'year'  => $currentYear,
    ])->first();
    if(!$requestCode) {
      $requestCode = RequestCode::create([
        'school_id' => $schoolId,
        'year' => $currentYear,
        'next_code' => 1,
      ]);
    }
    
    $yearCode = substr($currentYear, -2);
    $serial = substr(str_repeat(0, 3).$requestCode->next_code, - 3);
    $formatedCode = $schoolCode.$yearCode.$serial;

    $requestCode->next_code = $requestCode->next_code + 1;
    $requestCode->save();

    return $formatedCode;
    
  }
}