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
                    <form action="{{ route('appointment.patient', $appointment->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Cancelar turno"> Cancelar </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="pagination justify-content-center">
    {{ $confirmedAppointments->links() }} 
</div>