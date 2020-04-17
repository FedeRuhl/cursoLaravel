@extends('layouts.panel')

@section('styles')
<!-- Multiselect-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.13/dist/css/bootstrap-select.min.css">
@endsection

@section('content')

<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">Editar médico</h3>
        </div>
        <div class="col text-right">
            <a href="{{ route('doctors') }}" class="btn btn-sm btn-default">Cancelar y volver</a>
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
        <form action="{{ route('doctors.update', $doctor->id) }}" method="post">
        @method('PUT')
        @csrf
            <div class="form-group">
                <label for="name"> Nombre </label>
                <input type="text" name="name" class="form-control"  value="{{ old('name', $doctor->name) }}">
            </div>

            <div class="form-group">
                <label for="email"> E-mail </label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $doctor->email)}}">
            </div>

            <div class="form-group">
                <label for="dni"> D.N.I. </label>
                <input type="text" name="dni" class="form-control"  value="{{ old('dni', $doctor->dni) }}">
            </div>

            <div class="form-group">
                <label for="specialties"> Especialidad </label>
                <select name="specialties[]" id="specialties" class="specialties form-control selectpicker" data-style="" multiple title="Seleccione una o más especialidades">
                    @foreach($specialties as $specialty)
                    <option value="{{ $specialty->id }}">
                        {{$specialty->name}} 
                    </option>
                    @endforeach
                  </select>                  
            </div>

            <div class="form-group">
                <label for="address"> Dirección </label>
                <input type="text" name="address" class="form-control"  value="{{ old('address', $doctor->address) }}">
            </div>

            <div class="form-group">
                <label for="phone"> Teléfono </label>
                <input type="text" name="phone" class="form-control"  value="{{ old('phone', $doctor->phone) }}">
            </div>

            <div class="form-group">
                <label for="password"> Contraseña </label>
                <input type="text" name="password" class="form-control"  value="">
                <p>Ingrese un valor solo si desea modificar la contraseña</p>
            </div>

            <button type="submit" class="btn btn-primary">
                Guardar
            </button>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.13/dist/js/bootstrap-select.min.js"></script>

<script>
    $(document).ready(() =>{
        $('#specialties').selectpicker('val', @json($specialty_ids)); //json convierte el arreglo php en un arreglo json de js
    });
</script>

@endsection