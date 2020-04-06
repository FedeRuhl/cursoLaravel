<h6 class="navbar-heading text-muted">Gestión de datos</h6>
<ul class="navbar-nav">
    <li class="nav-item">
    <a class="nav-link" href="./index.html">
        <i class="ni ni-tv-2 text-primary"></i> Dashboard
    </a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href="./examples/icons.html">
        <i class="ni ni-bullet-list-67 text-info"></i> Especialidades
    </a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href="./examples/maps.html">
        <i class="ni ni-briefcase-24 text-orange"></i> Medicos
    </a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href="{{ action('HomeController@index') }}"> <!-- acá estaba probando el helper action -->
        <i class="ni ni-satisfied text-success"></i> Pacientes
    </a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href="" onclick="event.preventDefault(); document.getElementById('formLogout').submit();">
        <i class="ni ni-button-power text-danger"></i> Cerrar sesión
    </a>
    <form action="{{ route('logout') }}" method="POST" style="display:none;" id="formLogout">
        @csrf
    </form>
    </li>
</ul>
     <!-- Divider -->
    <hr class="my-3">
    <!-- Heading -->
    <h6 class="navbar-heading text-muted">Reportes</h6>
    <!-- Navigation -->
<ul class="navbar-nav mb-md-3">
    <li class="nav-item">
    <a class="nav-link" href="https://demos.creative-tim.com/argon-dashboard/docs/getting-started/overview.html">
        <i class="ni ni-chart-bar-32 text-default"></i> Frecuencia de citas
    </a>
    </li>
    <li class="nav-item">
    <a class="nav-link" href="https://demos.creative-tim.com/argon-dashboard/docs/foundation/colors.html">
        <i class="ni ni-diamond text-warning"></i> Médicos más activos
    </a>
    </li>
</ul>


