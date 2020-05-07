<div class="table-responsive">
    <!-- Specialties -->
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
            <tr>
            <th scope="col">Descripcion</th>
            <th scope="col">Especialidad</th>
            @if($role == 'patient')
            <th scope="col">Médico</th>
            @elseif ($role == 'doctor')
            <th scope="col">Paciente</th>
            @endif
            <th scope="col">Fecha</th>
            <th scope="col">Horario</th>
            <th scope="col">Tipo</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($confirmedAppointments as $appointment)
            <tr>
                <th scope="row">
                {{ $appointment->description }}
                </th>
                <td>
                {{ $appointment->specialty->name }}
                </td>
                @if($role == 'patient')
                <td>
                {{ $appointment->doctor->name }}
                </td>
                @elseif ($role == 'doctor')
                <td>
                    {{ $appointment->patient->name }}
                </td>
                @endif
                <td>
                {{ $appointment->scheduled_date }}
                </td>
                <td>
                {{ $appointment->scheduled_time_24 }}
                </td>
                <td>
                {{ $appointment->type }}
                </td>
                <td>
                <a href="{{ route('appointment.showCancelForm', $appointment) }}" class="btn btn-sm btn-danger" title="Cancelar turno">Cancelar</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="pagination justify-content-center">
    {{ $confirmedAppointments->links() }} 
</div>