<?php

use Illuminate\Database\Seeder;
use App\User;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //id 1
        $faker = Faker::create(); //esto lo instancio para generar los datos falsos sobre mí persona
        User::create([
            'name' => 'Federico',
            'email' => 'federicoruhl@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('federico'), // password
            'remember_token' => Str::random(10),
            'dni' => $faker->randomNumber(8, true),
            'address' => $faker->address,
            'phone' => $faker->e164PhoneNumber,
            'role' => 'admin'
        ]);

        //id 2
        User::create([
            'name' => 'Medico',
            'email' => 'medico@medico.com',
            'email_verified_at' => now(),
            'password' => bcrypt('medico'), // password
            'remember_token' => Str::random(10),
            'dni' => $faker->randomNumber(8, true),
            'address' => $faker->address,
            'phone' => $faker->e164PhoneNumber,
            'role' => 'doctor'
        ]);

        //id 3
        User::create([
            'name' => 'Paciente',
            'email' => 'paciente@paciente.com',
            'email_verified_at' => now(),
            'password' => bcrypt('paciente'), // password
            'remember_token' => Str::random(10),
            'dni' => $faker->randomNumber(8, true),
            'address' => $faker->address,
            'phone' => $faker->e164PhoneNumber,
            'role' => 'patient'
        ]);
        //esto se define en el modelo UserFactory en database/factories
        factory(User::class, 50)->states('patient')->create();
        //factory(User::class, 10)->states('doctor')->create();
    }
}
