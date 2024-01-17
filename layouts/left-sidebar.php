<!-- ========== Left Sidebar Start ========== -->

<div class="leftside-menu">
  <!-- Brand Logo Light -->
  <a href="dashboard-analytics.php" class="logo logo-light">
    <span class="logo-lg">
    <img src="assets/images/logo3.png" alt="dark logo" style="width: 45px; height: auto;" />
    </span>
    <span class="logo-sm">
    <img src="assets/images/logo3.png" alt="small logo" style="width: 38px; height: auto;" />
    </span>
  </a>

  <!-- Brand Logo Dark -->
  <a href="dashboard-analytics.php" class="logo logo-dark">
    <span class="logo-lg">
    <img src="assets/images/logo3.png" alt="dark logo" style="width: 45px; height: auto;" />
    </span>
    <span class="logo-sm">
    <img src="assets/images/logo3.png" alt="small logo" style="width: 38px; height: auto;" />
    </span>
  </a>

  <!-- Sidebar Hover Menu Toggle Button -->
  <div
    class="button-sm-hover"
    data-bs-toggle="tooltip"
    data-bs-placement="right"
    title="Show Full Sidebar"
  >
    <i class="ri-checkbox-blank-circle-line align-middle"></i>
  </div>

  <!-- Full Sidebar Menu Close Button -->
  <div class="button-close-fullsidebar">
    <i class="ri-close-fill align-middle"></i>
  </div>

  <!-- Sidebar -left -->
  <div class="h-100" id="leftside-menu-container" data-simplebar>
    <!-- Leftbar User -->
    <div class="leftbar-user">
      <a href="pages-profile.php">
        <img
          src="assets/images/users/avatar-1.jpg"
          alt="user-image"
          height="42"
          class="rounded-circle shadow-sm"
        />
      </a>
    </div>

    <!--- Sidemenu -->
    <ul class="side-nav">
    <li class="side-nav-title">Principal</li>

<!-- Dashboard como una opción directa en lugar de un menú desplegable -->
<li class="side-nav-item">
    <a href="dashboard-analytics.php" class="side-nav-link">
        <i class="ri-home-4-line"></i>
        <span>Dashboard</span>
    </a>
</li>
<!-- Dashboard como una opción directa en lugar de un menú desplegable -->
<li class="side-nav-item">
    <a href="calendario.php" class="side-nav-link">
        <i class="ri-calendar-event-line"></i>
        <span>Calendario</span>
    </a>
</li>
<li class="side-nav-item">
    <a href="registro_clase.php" class="side-nav-link">
        <i class="ri-list-check-3"></i>
        <span>Agendar</span>
    </a>
</li>
<li class="side-nav-item">
    <a href="registro_estudiante.php" class="side-nav-link">
        <i class="ri-task-line"></i>
        <span>Registrar</span>
    </a>
</li>
<li class="side-nav-item">
        <a
          data-bs-toggle="collapse"
          href="#sidebarPagesAuth"
          aria-expanded="false"
          aria-controls="sidebarPagesAuth"
          class="side-nav-link">
          <i class="ri-layout-line"></i>
          <span> Fichas </span>
          <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="sidebarPagesAuth">
          <ul class="side-nav-second-level">
            <li>
              <a href="generar_fichas.php">Informacion</a>
            </li>
            <li>
              <a href="generar_ficha_alumno.php">Manejo del Alumno</a>
            </li>
            <li>
              <a href="generar_ficha_manejo.php">Examen de Manejo</a>
            </li>
          </ul>
        </div>
      </li>

    <li class="side-nav-title">Configuracion</li>

    <li class="side-nav-item">
    <a href="registro_instructor.php" class="side-nav-link">
        <i class="ri-shield-user-line"></i>
        <span>Instructores</span>
    </a>
</li>
<!-- Dashboard como una opción directa en lugar de un menú desplegable -->
<li class="side-nav-item">
    <a href="vehiculo.php" class="side-nav-link">
        <i class=" ri-roadster-fill"></i>
        <span>Vehiculos</span>
    </a>
</li>
<li class="side-nav-item">
    <a href="registro_categoria.php" class="side-nav-link">
        <i class="ri-passport-line"></i>
        <span>Categoria</span>
    </a>
</li>
<li class="side-nav-item">
    <a href="registro_cursos.php" class="side-nav-link">
        <i class="ri-book-fill"></i>
        <span>Cursos</span>
    </a>
</li>
     

    <div class="clearfix"></div>
  </div>
</div>
<!-- ========== Left Sidebar End ========== -->
