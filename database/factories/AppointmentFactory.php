<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Appointment;
use App\User;

$factory->define(Appointment::class, function (Faker $faker) {
    $doctorIds = User::doctors()->pluck('id');
    $patientIds = User::patients()->pluck('id');

    $datetime = $faker->dateTimeBetween('-1 years', 'now');
    $scheduledDate = $datetime->format('Y-m-d');
    $scheduledTime = $datetime->format('H:i:s');

    $type = ['Consulta', 'Examen', 'Operacion'];
    $status = ['Reservado', 'Confirmado', 'Atendido', 'Cancelado'];

    return [
        'description' => $faker->sentence(5),
        'specialty_id' => $faker->numberBetween(1, 2), //hay 3 especialidades
        'doctor_id' => $faker->randomElement($doctorIds),
        'patient_id' => $faker->randomElement($patientIds),
        'scheduled_date' => $scheduledDate,
        'scheduled_time' => $scheduledTime,
        'type' => $faker->randomElement($type),
        'status' => $faker->randomElement($status)
    ];
});
