<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

use App\Appointment;

class SendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcm:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar mensajes vía FCM';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Buscando turnos médicos con estado confirmado.');

        $now = Carbon::now();

        $headers = [
            'id',
            'scheduled_date',
            'scheduled_time',
            'patient_id'
        ];

        $appointmentsTomorrow = $this->getAppointments24Hours($now);

        foreach($appointmentsTomorrow as $appointment){
            $appointment->patient->sendFCM('No olvides tu turno mañana a esta hora.');
            $this->info('Mensaje FCM enviado 24 horas antes al paciente con ID: ' . $appointment->patient_id);
        }

        $this->table($headers, $appointmentsTomorrow);

        $appointmentsNextHour = $this->getAppointmentsNextHour($now);

        foreach($appointmentsNextHour as $appointment){
            $appointment->patient->sendFCM('Tienen un turno en una hora. Te esperamos.');
            $this->info('Mensaje FCM enviado 1 hora antes al paciente con ID: ' . $appointment->patient_id);
        }

        $this->table($headers, $appointmentsNextHour);

    }

    private function getAppointments24Hours($now){
        return Appointment::where('status', 'Confirmado')
            ->where('scheduled_date', $now->addDay()->toDateString())
            ->where('scheduled_time', '>=' ,$now->copy()->subMinutes(3)->toTimeString()) //usamos copy para no alterar el objeto, sino una copia del mismo
            ->where('scheduled_time', '<' ,$now->copy()->addMinutes(2)->toTimeString()) //sumamos y restamos minutos para que sea aproximadamente a la misma hora
            ->get();
    }

    private function getAppointmentsNextHour($now){
        return Appointment::where('status', 'Confirmado')
            ->where('scheduled_date', $now->addHour()->toDateString())
            ->where('scheduled_time', '>=' ,$now->copy()->subMinutes(3)->toTimeString()) //usamos copy para no alterar el objeto, sino una copia del mismo
            ->where('scheduled_time', '<' ,$now->copy()->addMinutes(2)->toTimeString()) //sumamos y restamos minutos para que sea aproximadamente a la misma hora
            ->get();
    }
}
