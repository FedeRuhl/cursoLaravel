<div class="table-responsive">
    <!-- Specialties -->
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
            <tr>
            <th scope="col">Descripcion</th>
            <th scope="col">Especialidad</th>
            <th scope="col">Médico</th>
            <th scope="col">Fecha</th>
            <th scope="col">Horario</th>
            <th scope="col">Tipo</th>
            <th scope="col">Estado</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($oldAppointments as $appointment)
            <tr>
                <th scope="row">
                {{ $appointment->description }}
                </th>
                <td>
                {{ $appointment->specialty->name }}
                </td>
                <td>
                {{ $appointment->doctor->name }}
                </td>
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
                    @if($appointment->cancellation)
                        <button type="button" class="btn btn-sm btn-info" data-toggle="popover" title="Info Cancelación" 
                        data-content=" Cancelado por {{ $appointment->cancellation->cancelled_by->name }} el
                        {{ $appointment->cancellation->created_at }}
                        con la siguiente justificación: {{ $appointment->cancellation->justification }}">
                            ?
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="pagination justify-content-center">
    {{ $oldAppointments->links() }} 
</div>