<?php include 'layouts/session.php'; ?>
<?php include 'layouts/main.php'; ?>
<?php include_once 'layouts/config.php';?>

<head>
    <title>Panel Principal</title>
    <?php include 'layouts/title-meta.php'; ?>
    

    <!-- Plugin css -->
    <link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css">
    <!-- Incluir CSS de Bootstrap -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<style>
    /* Estilos para la tabla */

.table-container {
            max-width: 800px; /* Tamaño máximo del contenedor */
            margin: auto; /* Centrar el contenedor */
            padding: 15px; /* Espaciado alrededor de la tabla */
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); /* Sombra para resaltar la tabla */
            background: #fff; /* Fondo blanco para el contenedor */
        }

.table {
    width: 100%;
    margin-top: 1rem;
    margin-bottom: 1rem;
    border-collapse: collapse;
}

.table th, .table td {
            padding: 0.75rem;
            border-bottom: 1px solid #dee2e6;
}
 /* Estilo para el encabezado de la tabla */
 .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        /* Media queries para la responsividad */
        @media (max-width: 768px) {
            .table thead {
                display: none; /* Oculta los encabezados en pantallas pequeñas */
            }

            .table, .table tbody, .table tr, .table td {
                display: block;
                width: 100%;
            }

            .table tr {
                margin-bottom: 0.625rem;
            }

            .table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            .table td::before {
                /* Agrega los títulos de las columnas antes de cada celda */
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-right: 0.75rem;
                white-space: nowrap;
                text-align: left;
                font-weight: bold;
            }

            /* Estilos para la responsividad del botón de exportación */
            .export-btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>
    
    <?php include 'layouts/head-css.php'; ?>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

    <?php include 'layouts/menu.php';?>

    <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    
                                </div>
                                <h4 class="page-title">Dashboard</h4>
                            </div>
                        </div>
                    </div>
                  <!-- ... Tu código HTML anterior ... -->

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Número de Estudiantes</h5>
                <p class="card-text" id="totalStudents">Cargando...</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Número de Vehículos</h5>
                <p class="card-text" id="totalVehicles">Cargando...</p>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Número de Instructores</h5>
                <p class="card-text" id="totalInstructors">Cargando...</p>
            </div>
        </div>
    </div>




    <div class="container-fluid">
    <div class="row">
        <!-- Gráfico de Clases Programadas por Categoría -->
        <div class="col-lg-6">
        <div class="card" style="max-width: 750px; margin: auto;"> <!-- Contenedor con tamaño máximo fijo -->
                <div class="card-body">
                    <h5 class="card-title">Gráfico de Clases Programadas por Categoría</h5>
                    <canvas id="clasesPorCategoriaChart"></canvas>
                </div>
            </div>
        </div>
<?php 
// Consulta para obtener la información de los estudiantes registrados en clases
$query = "
    SELECT 
        e.nombre,
        e.apellido,
        e.documento,
        e.numero,
        e.telefono,
        e.email,
        l.categoria_licencia AS 'Licencia Actual',
        lp.categoria_licencia AS 'Licencia que Postula',
        pc.fecha_clase,
        pc.hora_clase
    FROM estudiantes e
    JOIN licencias l ON e.id_licencia_actual = l.id_licencia
    JOIN licencias lp ON e.id_licencia_postula = lp.id_licencia
    JOIN programacion_clases pc ON e.id = pc.id_estudiante;
";

$resultRegisteredStudents = mysqli_query($conn, $query); // Corrección aquí

// Inicializar el array $registeredStudents
$registeredStudents = [];

if ($resultRegisteredStudents) {
    while ($row = mysqli_fetch_assoc($resultRegisteredStudents)) {
        array_push($registeredStudents, $row);
    }
    mysqli_free_result($resultRegisteredStudents);
} else {
    $error = mysqli_error($conn);
    // Manejar el error como creas conveniente
}

mysqli_close($conn);
?>

        <!-- Tabla de Estudiantes Registrados en Clases -->
        <div class="col-lg-6">
        <div class="table-container">

            <div class="card" style="max-width: 700px; margin: auto;"> <!-- Tamaño máximo fijo para la tabla -->
            <h3>Estudiantes Registrados en Clases</h3>
            <div class="table-responsive"> <!-- Agrega esta clase para hacer la tabla responsiva -->
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th class="d-none d-md-table-cell">Documento</th> <!-- Ocultar en pantallas pequeñas -->
                            <th class="d-none d-md-table-cell">Número</th> <!-- Ocultar en pantallas pequeñas -->
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th class="d-none d-lg-table-cell">Licencia Actual</th> <!-- Ocultar en pantallas más pequeñas -->
                            <th class="d-none d-lg-table-cell">Licencia que Postula</th> <!-- Ocultar en pantallas más pequeñas -->
                            <th>Fecha Clase</th>
                            <th>Hora Clase</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($registeredStudents as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($student['apellido']); ?></td>
                            <td class="d-none d-md-table-cell"><?php echo htmlspecialchars($student['documento']); ?></td>
                            <td class="d-none d-md-table-cell"><?php echo htmlspecialchars($student['numero']); ?></td>
                            <td><?php echo htmlspecialchars($student['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td class="d-none d-lg-table-cell"><?php echo htmlspecialchars($student['Licencia Actual']); ?></td>
                            <td class="d-none d-lg-table-cell"><?php echo htmlspecialchars($student['Licencia que Postula']); ?></td>
                            <td><?php echo htmlspecialchars($student['fecha_clase']); ?></td>
                            <td><?php echo htmlspecialchars($student['hora_clase']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            </div>
            </div>
        </div>
    </div>
</div>

<script>

function loadCharts() {
        // Realiza una solicitud AJAX para obtener las estadísticas
        fetch('obtener_estadisticas.php') // Asegúrate de que esta sea la ruta correcta a tu script PHP
            .then(response => response.json())
            .then(data => {
                // Actualizar el número de estudiantes y vehículos en las tarjetas
                document.getElementById('totalStudents').textContent = data.total_students;
                document.getElementById('totalVehicles').textContent = data.total_vehicles;
                document.getElementById('totalInstructors').textContent = data.total_instructors; // Agrega esta línea


                // Crear un array de etiquetas y un array de datos para el gráfico
                var labels = [];
                var dataValues = [];

                // Recorrer los datos de clases por categoría
                for (var categoria in data.classes_by_category) {
                    if (data.classes_by_category.hasOwnProperty(categoria)) {
                        labels.push(categoria);
                        dataValues.push(data.classes_by_category[categoria]);
                    }
                }

                // Crear el gráfico de clases programadas por categoría
                var clasesPorCategoriaChart = new Chart(document.getElementById('clasesPorCategoriaChart'), {
                    type: 'bar', // Puedes cambiar a 'doughnut' para un gráfico circular
                    data: {
                        labels: labels, // Etiquetas de categoría
                        datasets: [{
                            label: 'Cantidad de Clases',
                            data: dataValues, // Datos de cantidad de clases
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                // Puedes agregar más colores aquí
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(75, 192, 192, 1)',
                                // Puedes agregar más colores aquí
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error al cargar gráficos:', error));
    }

    // Llama a la función para cargar y mostrar los gráficos cuando la página se cargue
    window.onload = loadCharts;
</script>

<script>

fetch('obtener_estadisticas.php')
  .then(response => response.json())
  .then(estudiantes_datos => {
    // Crear el gráfico con Chart.js
    const ctx = document.getElementById('tuCanvas').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar', // o el tipo de gráfico que prefieras
        data: {
            labels: estudiantes_datos.map(est => est.fecha_clase + ' ' + est.hora_clase), // Eje X
            datasets: [{
                label: 'Días restantes para la clase',
                data: estudiantes_datos.map(est => est.dias_para_clase), // Eje Y
                // Otros ajustes de estilo si necesitas
            }]
        },
        options: {
            // Opciones del gráfico
        }
    });
  });
</script>



   
                </div>
                <!-- container -->

            </div>
            <!-- content -->

            <?php include 'layouts/footer.php'; ?>

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <?php include 'layouts/right-sidebar.php'; ?>

    <?php include 'layouts/footer-scripts.php'; ?>

    <!-- Daterangepicker js -->
    <script src="assets/vendor/daterangepicker/moment.min.js"></>
    <script src="assets/vendor/daterangepicker/daterangepicker.js"></script>

    <!-- Charts js -->
    <script src="assets/vendor/chart.js/chart.min.js"></script>
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>

    <!-- Vector Map js -->
    <script src="assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="assets/vendor/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>

    <!-- Analytics Dashboard App js -->
    <script src="assets/js/pages/demo.dashboard-analytics.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</body>

</html>
