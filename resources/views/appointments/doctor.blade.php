@extends('layouts.panel')

@section('content')
<div class="card shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="mb-0">Mis turnos</h3>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if (session('notification'))
        <div class="alert alert-success" role="alert">
            <strong> {{session('notification')}} </strong>
        </div>
        @endif
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#confirmed-appointments" role="tab" aria-selected="true">
                    Mis próximos turnos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#pending-appointments" role="tab" aria-selected="false">
                    Turnos pendientes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#old-appointments" role="tab" aria-selected="false">
                    Historial de turnos
                </a>
            </li>
        </ul>
    </div>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="confirmed-appointments" role="tabpanel">
            @include('appointments.confirmed-appointments')
        </div>
        <div class="tab-pane fade" id="pending-appointments" role="tabpanel">
            @include('appointments.pending-appointments')
        </div>
        <div class="tab-pane fade" id="old-appointments" role="tabpanel">
            @include('appointments.old-appointments')
        </div>
    </div>
</div>
@endsection