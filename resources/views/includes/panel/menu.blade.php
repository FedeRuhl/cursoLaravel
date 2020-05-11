<h6 class="navbar-heading text-muted">
    @if (auth()->user()->role == 'admin')
    Gestión de datos
    @else
    Menú
    @endif
</h6>
<ul class="navbar-nav">

    @include('includes.panel.menu.' . auth()->user()->role) {{-- llama al archivo dependiendo el usuario logueado --}}

    <li class="nav-item">
        <a class="nav-link" href="" onclick="event.preventDefault(); document.getElementById('formLogout').submit();">
            <i class="ni ni-button-power text-danger"></i> Cerrar sesión
        </a>
        <form action="{{ route('logout') }}" method="POST" style="display:none;" id="formLogout">
            @csrf
        </form>
    </li>
</ul>

@if (auth()->user()->role == 'admin')
<!-- Divider -->
<hr class="my-3">
<!-- Heading -->
<h6 class="navbar-heading text-muted">Reportes</h6>
<!-- Navigation -->
<ul class="navbar-nav mb-md-3">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('charts.appointments.line') }}">
            <i class="ni ni-chart-bar-32 text-default"></i> Frecuencia de turnos
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('charts.doctors.column') }}">
            <i class="ni ni-diamond text-warning"></i> Médicos más activos
        </a>
    </li>
</ul>
@endif