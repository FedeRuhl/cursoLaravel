<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Interfaces\ScheduleServiceInterface;
use Carbon\Carbon;

class StoreAppointment extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    private $scheduleService;

    public function __construct(ScheduleServiceInterface $scheduleService){
        $this->scheduleService = $scheduleService;
    }

    public function rules()
    {
        return [
            'description' => 'required',
            'specialty_id' => 'exists:specialties,id',
            'doctor_id' => 'required|exists:users,id',
            'scheduled_time' => 'required',
            'scheduled_date' => 'required'
        ];
    }

    public function messages(){
        return [
            'scheduled_time.required' => 'Por favor, seleccione una hora válida para reservar su turno.',
            'scheduled_date.required' => 'Por favor, seleccione una fecha disponible.'
        ];
    }

    public function withValidator($validator){
        //ya que con validate no es suficiente
        $validator->after(function ($validator){
            $date = $this->input('scheduled_date');
            $doctorId = $this->input('doctor_id');
            $scheduledTime = $this->input('scheduled_time');
            $start = new Carbon($scheduledTime);

            if($this->scheduleService->isAvailableInterval($date, $doctorId, $start) == false){
                $validator->errors()
                          ->add('available_time', 'La hora seleccionada se acaba de reservar por otro paciente.');
            }
        });
    }
}
