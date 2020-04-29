@extends('layouts.panel')

@section('content')

<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Registrar nuevo turno</h3>
        </div>
        <div class="col text-right">
            <a href="{{ route('home') }}" class="btn btn-sm btn-default">Cancelar y volver</a>
        </div>
        </div>
    </div>
    <div class="card-body">
    @if($errors->any())
        <ul>
            <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $error)
            <li>
                {{ $error }}
            </li>
            @endforeach
            </div>
        </ul>
    @endif
        <form action="{{ route('appointment.store') }}" method="post">
        @csrf

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="specialty"> Especialidad </label>
                <select name="specialty_id" id="specialty" class="form-control" required>
                    <option selected="true" disabled="disabled">Selecciona una especialidad</option>
                    @foreach($specialties as $specialty)
                    <option value="{{ $specialty->id }}"
                        @if(old('specialty_id') == $specialty->id) selected @endif > {{$specialty->name}} 
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-6">
                <label for="doctor"> Médico </label> {{-- Solo se usa el option si ya se había cargado el formulario--}}
                <select name="doctor_id" id="doctor" class="form-control" required>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" @if(old('doctor_id') == $doctor->id) selected @endif>
                            {{ $doctor->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="scheduled_date"> Fecha </label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                </div>
                <input class="form-control datepicker" placeholder="Eliga una fecha" type="text" id="date" name="scheduled_date"
                value ="{{ old('scheduled_date', date('Y/m/d')) }}" data-date-format="yyyy/mm/dd" data-date-start-date="{{ date('Y/m/d') }}"
                data-date-end-date="+30d">
            </div>
        </div>

        <div class="form-group">
            <label for="address"> Hora de atención </label>
            <div id=hours>
                <div class="alert alert-info" role="alert">
                    Selecciona un médico y una fecha para determinar sus turnos disponibles.
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="type"> Tipo de consulta </label>
            <div class="custom-control custom-radio mb-3">
                <input name="type" class="custom-control-input" id="type1" type="radio"
                @if(old('type', 'consultation') == 'consultation') checked @endif value="consultation">
                <label for="type1" class="custom-control-label">Consulta</label>
            </div>
            <div class="custom-control custom-radio mb-3">
                <input type="radio" name="type" id="type2" class="custom-control-input"
                @if(old('type') == 'examination') checked @endif value="examination">
                <label for="type2" class="custom-control-label">Examen</label>
            </div>
            <div class="custom-control custom-radio mb-3">
                <input type="radio" name="type" id="type3" class="custom-control-input"
                @if(old('type') == 'operation') checked @endif value="operation">
                <label for="type3" class="custom-control-label">Operacion</label>
            </div>
        </div>

        <div class="form-group">
            <label for="">Descripcion</label>
            <input type="text" name="description" id="description" class="form-control"
        placeholder="Describa brevemente su consulta" value="{{ old('description') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">
            Guardar
        </button>

        </form>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/appointments/create.js') }}"></script>
@endsection