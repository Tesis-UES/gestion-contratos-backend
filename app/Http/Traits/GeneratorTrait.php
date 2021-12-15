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

  public function generateRequestCode(int $schoolId) {
    $currentYear = date('Y');
    $schoolCode;
    switch ($schoolId) {
      case 1:
        $schoolCode = 'ARQ';
        break;
      
      case 2:
        $schoolCode = 'CIV';
        break;
            
      case 3:
        $schoolCode = 'IND';
        break;
            
      case 4:
        $schoolCode = 'MEC';
        break;
            
      case 5:
        $schoolCode = 'ELE';
        break;
            
      case 6:
        $schoolCode = 'QMC';
        break;
               
      case 7:
        $schoolCode = 'ALI';
        break;
               
      case 8:
        $schoolCode = 'SIF';
        break;
               
      case 9:
        $schoolCode = 'UCB';
        break;
                          
      default:
        $schoolCode = 'PGD';
        break;
    }
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