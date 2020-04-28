<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class); //esto invocará a el archivo UsersTableSeeder, el cual se crea escribiendo en consola php artisan make:seeder UsersTableSeeder
        //para ejecutar los seeders ejecutamos en consola php artisan db:seed
        //Podemos utilizar php artisan migrate:refresh --seed para crear nuevamente las tablas y además ejecutar los seeders
        $this->call([
            SpecialtiesTableSeeder::class,
            WorkDaysTableSeeder::class
        ]); //tambien podriamos poner el primer call dentro de este arreglo, pero queda a modo de ejemplo
    }
}
