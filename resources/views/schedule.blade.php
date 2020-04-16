@extends('layouts.panel')

@section('content')
<form action="{{ route('schedule.store') }}" method="post">
    @csrf
    <div class="card shadow">
        <div class="card-header border-0">
            <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Gestionar horarios</h3>
            </div>
            <div class="col text-right">
                <button type="submit"class="btn btn-sm btn-success">Guardar cambios</button>
            </div>
            </div>
        </div>
        @if (session('notification'))
        <div class="card-body">
            
            <div class="alert alert-success" role="alert">
                <strong> {{session('notification')}} </strong>
            </div>
        </div>
        @endif

        @if (session('errors'))
        <div class="alert alert-danger" role=alert>
            Los cambios se han guardado pero debe tener en cuenta que:
            <ul>
                @foreach(session('errors') as $error)
                <li> {{$error}} </li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="table-responsive">
            <!-- Specialties -->
        <table class="table align-items-center table-flush">
        <thead class="thead-light">
            <tr>
            <th scope="col">Dia</th>
            <th scope="col">Activo</th>
            <th scope="col">Turno mañana</th>
            <th scope="col">Turno tarde</th>
            </tr>
        </thead>
        <tbody>

            @php
            $diasTrabajo = array();
            for($i= 0; $i<sizeOf($workDays); $i++){
                $diasTrabajo[$workDays[$i]->day] = $workDays[$i];
            }
            @endphp  

            @foreach ($days as $key => $day)
            <tr>
            <th>{{ $day }}</th>
            <td>
                <label class="custom-toggle">
                <input type="checkbox" name="active[]" value=" {{ $key }}" {{-- para viajar al controller en formato arreglo --}}
                @if(!empty($diasTrabajo[$key]) && $diasTrabajo[$key]->day == $key && $diasTrabajo[$key]->active) checked @endif>
                {{-- el value es para poder distingurlos --}}
                    <span class="custom-toggle-slider rounded-circle"></span>
                </label>
            </td>
            <td>
                <div class="row">
                    <div class="col">
                        <select name="morningStart[]" class="form-control" value="1">
                            <option selected="true" >Hora inicio</option> {{-- lo ideal seria ponerle disabled --}}
                            @foreach ($morningHours as $morningHour)
                            <option value="{{ $morningHour }}"
                            @if(!empty($diasTrabajo[$key]) && $morningHour == $diasTrabajo[$key]->morningStart) selected @endif>
                                {{$morningHour}} 
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <select name="morningEnd[]" class="form-control">
                            <option selected="true" >Hora fin</option>
                            @foreach($morningHours as $morningHour)
                            <option value="{{ $morningHour }}"
                            @if(!empty($diasTrabajo[$key]) && $morningHour == $diasTrabajo[$key]->morningEnd) selected @endif>
                                {{$morningHour}} 
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="col">
                        <select name="afternoonStart[]" class="form-control">
                            <option selected="true" >Hora inicio</option>
                            @foreach($afternoonHours as $afternoonHour)
                            <option value="{{ $afternoonHour }}"
                            @if(!empty($diasTrabajo[$key]) && $afternoonHour == $diasTrabajo[$key]->afternoonStart) selected @endif>
                                {{$afternoonHour}} 
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <select name="afternoonEnd[]" class="form-control">
                            <option selected="true" >Hora fin</option>
                            @foreach($afternoonHours as $afternoonHour)
                            <option value="{{ $afternoonHour }}"
                            @if(!empty($diasTrabajo[$key]) && $afternoonHour == $diasTrabajo[$key]->afternoonEnd) selected @endif>
                                {{$afternoonHour}} 
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </td>
            </tr>
            @endforeach
        </tbody>
        </table>
        </div>
    </div>
</form>


@endsection