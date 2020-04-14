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
            @foreach ($days as $key => $day)
            <tr>
            <th>{{ $day }}</th>
            <td>
                <label class="custom-toggle">
                <input type="checkbox" name="active[]" value=" {{ $key }}"> {{-- para viajar al controller en formato arreglo --}}
                {{-- el value es para poder distingurlos --}}
                    <span class="custom-toggle-slider rounded-circle"></span>
                </label>
            </td>
            <td>
                <div class="row">
                    <div class="col">
                        <select name="morningStart[]" class="form-control" value="1">
                            <option selected="true" >Hora inicio</option> {{-- lo ideal seria ponerle disabled --}}
                            @for ($i=5; $i<=12; $i++)
                            <option value="{{ $i }}:00"> {{ $i }}:00 </option>
                            <option value="{{ $i }}:30"> {{ $i }}:30 </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col">
                        <select name="morningEnd[]" class="form-control">
                            <option selected="true" >Hora fin</option>
                            @for ($i=5; $i<=12; $i++)
                            <option value="{{ $i }}:00"> {{ $i }}:00 </option>
                            <option value="{{ $i }}:30"> {{ $i }}:30 </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="col">
                        <select name="afternoonStart[]" class="form-control">
                            <option selected="true" >Hora inicio</option>
                            @for ($i=13; $i<=23; $i++)
                            <option value="{{ $i }}:00"> {{ $i }}:00 </option>
                            <option value="{{ $i }}:30"> {{ $i }}:30 </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col">
                        <select name="afternoonEnd[]" class="form-control">
                            <option selected="true" >Hora fin</option>
                            @for ($i=13; $i<=23; $i++)
                            <option value="{{ $i }}:00"> {{ $i }}:00 </option>
                            <option value="{{ $i }}:30"> {{ $i }}:30 </option>
                            @endfor
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