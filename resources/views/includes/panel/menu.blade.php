<h6 class="navbar-heading text-muted">
    @if (auth()->user()->role == 'admin')
    Gestión de datos
    @else
    Menú
    @endif
</h6>
<ul class="navbar-nav">
    @if (auth()->user()->role == 'admin')
    <li class="nav-item">
    <a class="nav-link" href="{{ route('home') }}"">
        <i class="ni ni-tv-2 text-primary"></i> Dashboard
    </a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href="{{ route('specialties') }}">
        <i class="ni ni-bullet-list-67 text-info"></i> Especialidades
    </a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href="{{ route('doctors') }}">
        <i class="ni ni-briefcase-24 text-orange"></i> Medicos
    </a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href="{{ route('patients') }}">
        <i class="ni ni-satisfied text-success"></i> Pacientes
    </a>
    </li>
    @elseif (auth()->user()->role == 'doctor')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('home') }}"">
            <i class="ni ni-calendar-grid-58 text-primary"></i> Gestionar horario
        </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('patients') }}">
                <i class="ni ni-satisfied text-success"></i> Mis pacientes
        </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('specialties') }}">
                <i class="ni ni-time-alarm text-info"></i> Mis citas
        </a>
        </li>
    @else {{--patient--}}
    <li class="nav-item">
        <a class="nav-link" href="{{ route('home') }}"">
            <i class="ni ni-calendar-grid-58 text-primary"></i> Reservar turno
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('specialties') }}">
            <i class="ni ni-time-alarm text-info"></i> Mis citas
    </a>
    </li>

    @endif
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
    <a class="nav-link" href="#">
        <i class="ni ni-chart-bar-32 text-default"></i> Frecuencia de citas
    </a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href="#">
        <i class="ni ni-diamond text-warning"></i> Médicos más activos
    </a>
    </li>
</ul>
@endif


