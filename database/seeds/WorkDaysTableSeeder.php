<?php

use Illuminate\Database\Seeder;
use App\WorkDay;

class WorkDaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i<5; $i=$i+2){
            WorkDay::create([
                'day' => $i,
                'active' => 1,
                'morningStart' => '07:00:00',
                'morningEnd' => '11:30:00',
                'afternoonStart' => '17:00:00',
                'afternoonEnd' => '20:30:00',
                'doctor_id' => 2 //medico test (user table seeder)
            ]);
        }
    }
}
