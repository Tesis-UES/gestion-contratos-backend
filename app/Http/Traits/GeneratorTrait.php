<?php

namespace App\Http\Traits;

use RandomLib\Factory;


trait GeneratorTrait {
  public function generatePassword() {
      $factory = new Factory();
      $generator = $factory->getMediumStrengthGenerator();
      return $generator->generateString(32);
    }
}