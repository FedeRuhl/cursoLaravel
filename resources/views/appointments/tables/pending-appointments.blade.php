<div class="table-responsive">
    <!-- Specialties -->
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
            <tr>
            <th scope="col">Descripcion</th>
            <th scope="col">Especialidad</th>
            @if($role == 'patient' or $role == 'admin')
            <th scope="col">Médico</th>
            @endif
            @if ($role == 'doctor' or $role == 'admin')
            <th scope="col">Paciente</th>
            @endif
            <th scope="col">Fecha</th>
            <th scope="col">Horario</th>
            <th scope="col">Tipo</th>
            <th scope="col">Estado</th>
            <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($pendingAppointments as $appointment)
            <tr>
                <th scope="row">
                {{ $appointment->description }}
                </th>
                <td>
                {{ $appointment->specialty->name }}
                </td>
                @if($role == 'patient' or $role == 'admin')
                <td>
                {{ $appointment->doctor->name }}
                </td>
                @endif
                @if ($role == 'doctor' or $role == 'admin')
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
                {{ $appointment->status }}
                </td>
                <td>
                    @if($role == 'doctor' or $role == 'admin')
                    <form action="{{ route('appointment.confirm', $appointment->id) }}" method="POST" class="d-inline-block">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success" title="Confirmar turno" data-toggle="tooltip">
                            <i class="ni ni-check-bold"></i>
                        </button>
                    </form>
                    {{-- el medico y el admin SI deben justificar por qué cancelan un turno en espera --}}
                    <a href="{{ route('appointment.showCancelForm', $appointment) }}" class="btn btn-sm btn-danger" title="Cancelar turno" data-toggle="tooltip">
                        <i class="ni ni-fat-remove"></i>
                    </a>
                    @else
                    {{-- el paciente puede cancelar un turno sin justificar siempre y cuando el medico no lo haya confirmado --}}
                    <form action="{{ route('appointment.cancel', $appointment) }}" method="POST" class="d-inline-block">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger" title="Cancelar turno" data-toggle="tooltip">
                            <i class="ni ni-fat-remove"></i>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="pagination justify-content-center">
    {{ $pendingAppointments->links() }} 
</div>