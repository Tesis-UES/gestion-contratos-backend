<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
   
    protected $model = Person::class;

    public function definition()
    {
        $pais = $this->faker->randomElement(['El Salvador','Rusia','España', 'Estados Unidos']);
        $es_nacionalizado = $this->faker->randomElement([true, false]);

        if($pais == 'El Salvador'){

            if($es_nacionalizado){
                return [
                    'status'                        => 'Validado',
                    'first_name'                    => $this->faker->firstName,
                    'middle_name'                   => $this->faker->firstName,
                    'last_name'                     => $this->faker->lastName,
                    'birth_date'                    => $this->faker->date,
                    'gender'                        => $this->faker->randomElement(['Masculino', 'Femenino']),
                    'civil_status'                  =>'Soltero',
                    'telephone'                     => $this->faker->phoneNumber,
                    'alternate_telephone'           => $this->faker->phoneNumber,
                    'alternate_mail'                => $this->faker->safeEmail,
                    'address'                       => $this->faker->address,   
                    'department'                    => $this->faker->randomElement(['San Salvador','La Libertad','Santa Ana', 'Son Sonate', 'San Miguel', 'Ahuachapan', 'Chalatenango', 'Cuscatlan', 'La Paz', 'La Union', 'Morazan', 'San Vicente', 'Usulutan']),
                    'city'                          => $this->faker->randomElement(['Mejicanos', 'Ayutuxtepeque','Cidad Delgado','Soyapango']),
                    'nationality'                   => 'El Salvador',
                    'professional_title'            => $this->faker->randomElement(['Ingeniero en Sistemas Informaticos','Ingeniero en Alimentos','Ingeniero Civil','Ingeniero Industrial']),
                    'nup'                           => '123456987',
                    'isss_number'                   => '852159753465',
                    'nit_number'                    => '0804-890597-207-8',
                    'nit_text'                      => 'CERO OCHOCIENTOS CUATRO - OCHOCIENTOS NOVENTA MIL QUINIENTOS NOVENTA Y SIETE - DOSCIENTOS SIETE - OCHO ',
                    'bank_id'                       => $this->faker->numberBetween(1, 5),
                    'bank_account_type'         => $this->faker->randomElement(['Cuenta Corriente','Cuenta de Ahorro']),
                    'bank_account_number'           => '0125415656',
                    'is_nationalized'               => $es_nacionalizado,
                    'resident_card_number'          => '6044785',
                    'resident_card_text'            => 'NUMERO X',
                    'resident_expiration_date'      => '2022-01-29',
                    'other_title'                   => true,
                    'other_title_name'              => $this->faker->randomElement(['PHD. Ing. en Sistemas Informaticos','PHD. Ing. en Alimentos','PHD. Ing. Civil','PHD. Ing. Industrial']),
                    'curriculum'                    => 'Curriculum.pdf',
                    'nit'                           => 'NIT.pdf',
                    'other_title_doc'               => 'OTRO-T.pdf',
                    'bank_account'                  => 'BANCO.pdf',
                    'professional_title_scan'       => 'TITULO.pdf',
                    'resident_card'                 => 'CARNET.pdf',
                ];
            }else{
                return [
                    'status'                        => 'Validado',
                    'first_name'                    => $this->faker->firstName,
                    'middle_name'                   => $this->faker->firstName,
                    'last_name'                     => $this->faker->lastName,
                    'birth_date'                    => $this->faker->date,
                    'gender'                        => $this->faker->randomElement(['Masculino', 'Femenino']),
                    'civil_status'                  =>'Soltero',
                    'telephone'                     => $this->faker->phoneNumber,
                    'alternate_telephone'           => $this->faker->phoneNumber,
                    'alternate_mail'                => $this->faker->safeEmail,
                    'address'                       => $this->faker->address,   
                    'department'                    => $this->faker->randomElement(['San Salvador','La Libertad','Santa Ana', 'Son Sonate', 'San Miguel', 'Ahuachapan', 'Chalatenango', 'Cuscatlan', 'La Paz', 'La Union', 'Morazan', 'San Vicente', 'Usulutan']),
                    'city'                          => $this->faker->randomElement(['Mejicanos', 'Ayutuxtepeque','Cidad Delgado','Soyapango']),
                    'nationality'                   => 'El Salvador',
                    'is_nationalized'               => $es_nacionalizado,
                    'professional_title'            => $this->faker->randomElement(['Ingeniero en Sistemas Informaticos','Ingeniero en Alimentos','Ingeniero Civil','Ingeniero Industrial']),
                    'nup'                           => '123456987',
                    'isss_number'                   => '852159753465',
                    'dui_number'                    => '05295281-3',
                    'dui_text'                      => 'CERO CIENTO NOVENTA Y DOS MILLONES QUINIENTOS CINCUENTA Y SEIS MIL SESENTA Y SIETE GUION OCHO ',
                    'dui_expiration_date'           => '2022-01-29',
                    'nit_number'                    => '0804-890597-207-8',
                    'nit_text'                      => 'CERO OCHOCIENTOS CUATRO - OCHOCIENTOS NOVENTA MIL QUINIENTOS NOVENTA Y SIETE - DOSCIENTOS SIETE - OCHO ',
                    'bank_id'                       => $this->faker->numberBetween(1, 5),
                    'bank_account_number'           => '0125415656',
                    'other_title'                   => true,
                    'bank_account_type'         => $this->faker->randomElement(['Cuenta Corriente','Cuenta de Ahorro']),
                    'other_title_name'              => $this->faker->randomElement(['PHD. Ing. en Sistemas Informaticos','PHD. Ing. en Alimentos','PHD. Ing. Civil','PHD. Ing. Industrial']),
                    'curriculum'                    => 'Curriculum.pdf',
                    'nit'                           => 'NIT.pdf',
                    'dui'                           => 'DUI.pdf',	
                    'other_title_doc'               => 'OTRO-T.pdf',
                    'bank_account'                  => 'BANCO.pdf',
                    'professional_title_scan'       => 'TITULO.pdf',
                    
                ];
            }
        }else{

            return [
                'status'                    => 'Validado',
                'first_name'                => $this->faker->firstName,
                'middle_name'               => $this->faker->firstName,
                'last_name'                 => $this->faker->lastName,
                'know_as'                   => $this->faker->firstName,
                'birth_date'                => $this->faker->date,
                'gender'                    => $this->faker->randomElement(['Masculino', 'Femenino']),
                'civil_status'              => 'Soltero',
                'passport_number'           => '24573848445',
                'telephone'                 => $this->faker->phoneNumber,
                'alternate_telephone'       => $this->faker->phoneNumber,
                'alternate_mail'            => $this->faker->safeEmail,
                'address'                   => $this->faker->address,
                'nationality'               => $pais,
                'is_nationalized'           => false,
                'professional_title'        => $this->faker->randomElement(['Ingeniero en Sistemas Informaticos','Ingeniero en Alimentos','Ingeniero Civil','Ingeniero Industrial']),
                'bank_id'                   => $this->faker->numberBetween(1, 5),
                'bank_account_type'         => $this->faker->randomElement(['Cuenta Corriente','Cuenta de Ahorro']),
                'bank_account_number'       => '0125415656',
                'curriculum'                => 'Curriculum.pdf',
                'passport'                  => 'PASAPORTE.pdf',
                'bank_account'              => 'BANCO.pdf',
                'professional_title_scan'   => 'TITULO.pdf',
            ];
        }

        
    }
}
