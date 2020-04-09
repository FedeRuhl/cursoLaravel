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
        //esto se define en el modelo UserFactory
        factory(User::class, 50)->create();
    }
}
