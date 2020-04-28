<?php

use Illuminate\Database\Seeder;
use App\Specialty;
use App\User;

class SpecialtiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $specialties = [
                'Oftalmología',
                'Pediatría',
                'Neurología'
        ];

        foreach($specialties as $specialtyName){
           $specialty = Specialty::create([
                'name' => $specialtyName
            ]);
            //make genera datos pero no los inserta en la bd
            $specialty->users()->saveMany( //save many no espera un solo modelo, sino una coleccion
                factory(User::class, 2)->states('doctor')->make()
            );
        }

        User::find(2)->specialties()->save($specialty); //medico test

    }
}
