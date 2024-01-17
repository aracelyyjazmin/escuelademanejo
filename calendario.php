<?php include 'layouts/session.php'; ?>
<?php include 'layouts/main.php'; ?>
<?php
include 'layouts/config.php';

// Consulta para obtener las clases programadas
$sql = "SELECT pc.id_programacion, pc.id_estudiante, pc.id_instructor, pc.id_vehiculo, pc.fecha_clase, pc.hora_clase, v.tipo_vehiculo 
        FROM programacion_clases pc
        JOIN vehiculos v ON pc.id_vehiculo = v.id_vehiculo";
$result = $conn->query($sql);

$events = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Aquí es donde se utiliza el switch para asignar un color basado en el tipo de vehículo
        switch ($row['tipo_vehiculo']) {
            case 'Auto':
                $color = 'bg-primary'; // Ejemplo: Azul para Auto
                break;
            case 'Combi':
                $color = 'bg-success'; // Ejemplo: Verde para Combi
                break;
            case 'Custer':
                $color = 'bg-danger'; // Ejemplo: Rojo para Custer
                break;
            case 'Bus':
                $color = 'bg-warning'; // Ejemplo: Amarillo para Bus
                break;
            case 'Camion pequeño':
                $color = 'bg-info'; // Ejemplo: Cian para Camión pequeño
                break;
            case 'Camion Grande':
                $color = 'bg-dark'; // Ejemplo: Oscuro para Camión Grande
                break;
            case 'Mototaxi':
                $color = 'bg-secondary'; // Ejemplo: Gris para Mototaxi
                break;
            default:
                $color = 'bg-light'; // Color por defecto
                break;
        }

        $event = [
            'id' => $row['id_programacion'],
            'title' => 'Clase programada - ' . $row['tipo_vehiculo'],
            'start' => $row['fecha_clase'] . 'T' . $row['hora_clase'],
            'end' => $row['fecha_clase'] . 'T' . $row['hora_clase'],
            'className' => $color,
        ];
        array_push($events, $event);
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Calendario</title>
    <?php include 'layouts/title-meta.php'; ?>

    <!-- Fullcalendar css -->
    <link href="assets/vendor/fullcalendar/main.min.css" rel="stylesheet" type="text/css" />
    <style>
        .bg-primary { background-color: #007bff; }
        .bg-success { background-color: #28a745; }
        .bg-danger { background-color: #dc3545; }
        .bg-warning { background-color: #ffc107; }
        .bg-info { background-color: #17a2b8; }
        .bg-dark { background-color: #343a40; }
        .bg-secondary { background-color: #6c757d; }
        .bg-light { background-color: #f8f9fa; }
    </style>

    <?php include 'layouts/head-css.php'; ?>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <?php include 'layouts/menu.php'; ?>

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">

                                </div>
                                <h4 class="page-title">Calendario</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">

                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="mt-4 mt-lg-0">
                                                <!-- Botones de navegación para cambiar de vista -->
                                                <div class="btn-group" style="margin-bottom: 10px;">
                                                    <button class="btn btn-primary" id="btn-mes">Mes</button>
                                                    <button class="btn btn-primary" id="btn-semana">Semana</button>
                                                    <button class="btn btn-primary" id="btn-dia">Día</button>
                                                    <button class="btn btn-primary" id="btn-lista">Lista</button>
                                                </div>
                                                <div id="calendar"></div>
                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->
                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div>
                        <!-- end col-12 -->
                    </div> <!-- end row -->

                </div> <!-- container -->

            </div> <!-- content -->

            <?php include 'layouts/footer.php'; ?>

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <?php include 'layouts/right-sidebar.php'; ?>

    <?php include 'layouts/footer-scripts.php'; ?>
    

    <!-- Fullcalendar js -->
    <script src="assets/vendor/fullcalendar/main.min.js"></script>
    <script src="assets/vendor/fullcalendar/locales/es.js"></script>



    <script>
        // Inicializar el calendario
        document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',  // Establece la localización a español
        initialView: 'dayGridMonth',
        events: <?php echo json_encode($events); ?>,
            });
            calendar.render();

           // Manejar los botones de navegación
        
                    document.getElementById('btn-mes').addEventListener('click', function () {
                calendar.changeView('dayGridMonth');
            });

            document.getElementById('btn-semana').addEventListener('click', function () {
                calendar.changeView('timeGridWeek');
            });

            document.getElementById('btn-dia').addEventListener('click', function () {
                calendar.changeView('timeGridDay');
            });

            document.getElementById('btn-lista').addEventListener('click', function () {
                calendar.changeView('listWeek');
            });
        });
    </script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>

</html>
