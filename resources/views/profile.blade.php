@extends('layouts.panel')

@section('content')

<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Modificar datos de usuario</h3>
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
    @if(session('notification'))
        <ul>
            <div class="alert alert-success" role="alert">
            {{ session('notification') }}
            </div>
        </ul>
    @endif
        <form action="{{ route('profile.update') }}" method="post">
        @csrf

        <div class="form-group">
            <label for="">Nombre completo</label>
            <input type="text" name="name" id="name" class="form-control"
        value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label for="">Número de teléfono</label>
            <input type="text" name="phone" id="phone" class="form-control"
        value="{{ old('phone', $user->phone) }}">
        </div>

        <div class="form-group">
            <label for="">Dirección</label>
            <input type="text" name="address" id="address" class="form-control"
        value="{{ old('address', $user->address) }}">
        </div>

        <button type="submit" class="btn btn-primary">
            Guardar cambios
        </button>

        </form>
    </div>
</div>

@endsection