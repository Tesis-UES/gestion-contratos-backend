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

  public function generateRequestCode(string $schoolName) {
    $currentYear = date('Y');
    $schoolCode;
    switch ($schoolName) {
      case 'Arquitectura':
        $schoolCode = 'ARQ';
        break;
      
      case 'Ingeniería Civil':
        $schoolCode = 'CIV';
        break;
            
      case 'Ingeniería Industrial':
        $schoolCode = 'IND';
        break;
            
      case 'Ingeniería Mecánica':
        $schoolCode = 'MEC';
        break;
            
      case 'Ingeniería Eléctrica':
        $schoolCode = 'ELE';
        break;
            
      case 'Ingeniería Química':
        $schoolCode = 'QMC';
        break;
               
      case 'Ingeniería de Alimentos':
        $schoolCode = 'ALI';
        break;
               
      case 'Ingeniería en Sistemas Informáticos':
        $schoolCode = 'SIF';
        break;
               
      case 'Unidad de Ciencias Basicas':
        $schoolCode = 'UCB';
        break;
                          
      default:
        $schoolCode = 'PGD';
        break;
    }
    $requestCode = RequestCode::where([
      'school_code' => $schoolCode, 
      'year'  => $currentYear,
    ])->first();
    if(!$requestCode) {
      $requestCode = RequestCode::create([
        'school_code' => $schoolCode,
        'year' => $currentYear,
        'next_code' => 1,
      ]);
    }
    
    $formatedCode = $schoolCode.substr(str_repeat(0, 3).$requestCode->next_code, - 3);
    $requestCode->next_code = $requestCode->next_code + 1;
    $requestCode->save();

    return $formatedCode;
    
  }
}