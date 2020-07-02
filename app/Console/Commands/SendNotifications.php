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

        $appointmentsTomorrow = $this->getAppointments24Hours($now->copy());
        $this->table($headers, $appointmentsTomorrow->toArray());

        foreach($appointmentsTomorrow as $appointment){
            $appointment->patient->sendFCM('No olvides tu turno mañana a esta misma hora.');
            $this->info('Mensaje FCM enviado 24 horas antes al paciente con ID: ' . $appointment->patient_id);
        }
        

        $appointmentsNextHour = $this->getAppointmentsNextHour($now->copy());
        $this->table($headers, $appointmentsNextHour->toArray());

        foreach($appointmentsNextHour as $appointment){
            $appointment->patient->sendFCM('Tienes un turno dentro de una hora. Te esperamos.');
            $this->info('Mensaje FCM enviado 1 hora antes al paciente con ID: ' . $appointment->patient_id);
        }

    }

    private function getAppointments24Hours($now){
        return Appointment::where('status', 'Confirmado')
            ->where('scheduled_date', $now->addDay()->toDateString())
            ->where('scheduled_time', $now->toTimeString())
            ->get();
    }

    private function getAppointmentsNextHour($now){
        return Appointment::where('status', 'Confirmado')
            ->where('scheduled_date', $now->addHour()->toDateString())
            ->where('scheduled_time', $now->toTimeString())
            ->get();
    }
}
