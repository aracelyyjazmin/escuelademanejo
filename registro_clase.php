<?php include_once 'layouts/session.php'; ?>
<?php include_once 'layouts/main.php'; ?>
<?php include_once 'layouts/config.php';
include_once 'fetch_class_schedule.php'; 


function sendJsonResponse($data, $statusCode = 200) {
    header('Content-Type: application/json'); // Define el Content-Type como JSON
    http_response_code($statusCode);         // Envía el código de estado HTTP
    echo json_encode($data);                 // Convierte el array a JSON y lo envía
    exit;
}
// Consulta para obtener las categorías de licencia
$sqlLicencias = "SELECT id_licencia, categoria_licencia FROM licencias";
$resultLicencias = $conn->query($sqlLicencias);
$licencias = [];

if ($resultLicencias->num_rows > 0) {
    while ($row = $resultLicencias->fetch_assoc()) {
        $licencias[] = $row;
    }
}

$sqlEstudiantes = "SELECT estudiantes.*, licencias.categoria_licencia AS categoria_postular 
                   FROM estudiantes 
                   LEFT JOIN licencias ON estudiantes.id_licencia_postula = licencias.id_licencia";
$resultEstudiantes = $conn->query($sqlEstudiantes);
$estudiantes = [];

if ($resultEstudiantes->num_rows > 0) {
    while ($row = $resultEstudiantes->fetch_assoc()) {
        $estudiantes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Registro de Clase</title>
    <?php include 'layouts/title-meta.php'; ?>
    <!-- Datatables css -->
    <link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css">
    <link href="assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/vendor/datatables.net-fixedcolumns-bs5/css/fixedColumns.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/vendor/datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/vendor/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/vendor/datatables.net-select-bs5/css/select.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <?php include 'layouts/head-css.php'; ?>
     <!-- FontAwesome (para los iconos) -->
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">



</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <?php include 'layouts/menu.php'; ?>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            
                            <div class="page-title-box">
                                <div class="page-title-right">
                                   
                                </div>
                                <h4 class="page-title">Agendar Clase</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <h4 class="header-title"></h4>
                                   
                    <!-- Opciones de búsqueda -->
                    <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="searchDate">Buscar por Fecha:</label>
                        <input type="date" class="form-control" id="searchDate">
                    </div>
                   
                    <div class="row mb-4">
                        <div class="col-md-12 d-flex justify-content-end">
                            <button class="btn btn-primary me-2" id="searchBtn">Buscar</button>
                            <button class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#scheduleClassModal">+ Nuevo</button>
                        </div>
                    </div>
                </div>

                    <!-- Tabla de Estudiantes -->
                    <div class="card">
                        <div class="card-body">
                            <table id="datatable-buttons" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>Nombre del estudiante</th>
                                        <th>Categoria postular</th>
                                        <th>Tipo de vehiculo</th>
                                        <th>Instructor</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($resultado as $fila): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($fila['estudiante_nombre']) . ' ' . htmlspecialchars($fila['estudiante_apellido']) ?></td>
                                        <td><?= htmlspecialchars($fila['categoria_postular']) ?></td>
                                        <td><?= htmlspecialchars($fila['tipo_vehiculo']) ?></td>
                                        <td><?= htmlspecialchars($fila['instructor_nombre']) . ' ' . htmlspecialchars($fila['instructor_apellido']) ?></td>
                                        <td><?= htmlspecialchars($fila['fecha_clase']) ?></td>
                                        <td><?= htmlspecialchars($fila['hora_clase']) ?></td>
                                        <!-- ... rest of your code for the table row ... -->
                                        <td>
                                            <!-- Botón para editar -->
                                            <button type="button" class="btn btn-primary edit-class-btn" data-id="<?php echo $fila['id_programacion']; ?>" data-toggle="modal" data-target="#editClassModal">Editar</button>

                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- Botón para eliminar -->
                                            <form method="POST" action="eliminar.php" onsubmit="return confirm('¿Estás seguro de querer eliminar?');" style="display: inline-block;">
                                            <button type="button" class="btn btn-danger delete-class-btn" data-id="<?= htmlspecialchars($fila['id_programacion']); ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            </form>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>

                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->
                    </div> <!-- end row-->

                    
                </div> <!-- container -->

            </div> <!-- content -->
            
            <?php include 'layouts/footer.php'; ?>

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>

    </div>

<div class="modal fade" id="scheduleClassModal" tabindex="-1" aria-labelledby="scheduleClassModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Agendar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                <div id="alertBox" style="display: none;"></div>
                    <form id="nuevoForm" method="post" action="upload2.php" enctype="multipart/form-data">
                        <!-- Fila 1: Nombres y Apellidos -->
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Nombres</label>
                                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingresa nombre">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="apellido">Apellidos</label>
                                    <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Ingresa apellido">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="idEstudiante" value="ID_DEL_ESTUDIANTE">

                        <!-- Fila 2: DNI y Categoría a Postular -->
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="documento">Documento</label>
                                    <input type="text" class="form-control" name="documento" id="documento" placeholder="Documento">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categoriaPostula">Categoría a Postular</label>
                                    <select class="form-control" id="categoriaPostula" name="categoriaPostula">
                                    <option value="">-- Selecciona una categoría --</option>
                                    <?php foreach ($licencias as $licencia): ?>
                                        <!-- Igual aquí, asegúrate de que el valor del option corresponde al id de la licencia -->
                                        <option value="<?= $licencia['id_licencia']; ?>" data-categoria="<?= $licencia['categoria_licencia']; ?>"><?= $licencia['categoria_licencia']; ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Fila 3: Tipo de Vehículo y Número de Horas de Clase -->
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipoVehiculo">Tipo de Vehículo</label>
                                    <select class="form-control" name="tipo_vehiculo" id="tipo_Vehiculo" required>
                                    <option value="">-- Selecciona --</option>
                                    <option value="Auto">Auto</option>
                                    <option value="Combi">Combi/Custer</option>
                                    <option value="Bus">Bus</option>
                                    <option value="Camion pequeño">Camion pequeño</option>
                                    <option value="Camion Grande">Camion Grande</option>
                                    <option value="Mototaxi">Mototaxi</option>
                                </select>                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="horasClase">Número de Horas de Clase</label>
                                    <input type="number" class="form-control" name="horasClase" id="horasClase" placeholder="Número de Horas">
                                </div>
                            </div>
                        </div>

                        <!-- Fila 4: Placa y Nombre del Instructor -->
                        <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="selectPlaca">Placa</label>
                                <select id="selectPlaca" name="placa" class="form-control">
                                    <option value="">-- Seleccione Placa --</option>
                                    <!-- Las opciones se llenarán dinámicamente con JavaScript -->
                                </select>
                            </div>
                        </div>
                
                            <div class="col-md-6">
                            <div class="form-group">
                                <label for="instructor">Nombre del Instructor</label>
                                <select id="instructor" name="instructor" class="form-control">
                                    <option value="">-- Seleccione Instructor --</option>
                                    <!-- Las opciones se llenarán dinámicamente con JavaScript -->
                                </select>
                            </div>

                            </div>
                        </div>

                        <!-- Fila 5: Selección de Fecha y Hora -->
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha">Fecha</label>
                                    <input type="date" class="form-control" name="fecha" id="fecha">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hora">Hora</label>
                                    <input type="time" class="form-control" name="hora" id="hora">
                                </div>
                            </div>
                        </div>

                        <!-- Botones del Formulario -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editClassModal" tabindex="-1" role="dialog" aria-labelledby="editClassModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Editar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                <div id="alertBox" style="display: none;"></div>
                <form id="editClassForm" method="post" action="get_class_details.php" enctype="multipart/form-data">
                    <!-- Fila 1: Nombres y Apellidos -->
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Nombres</label>
                                <input type="text" class="form-control" name="nombre" id="edit_nombre" placeholder="Ingresa nombre" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="apellido">Apellidos</label>
                                <input type="text" class="form-control" name="apellido" id="edit_apellido" placeholder="Ingresa apellido" readonly>
                            </div>
                        </div>
                    </div>

                        <!-- Fila 2: DNI y Categoría a Postular -->
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="documento">Documento</label>
                                    <input type="text" class="form-control" name="documento" id="edit_documento" placeholder="Documento" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categoriaPostula">Categoría a Postular</label>
                                    <select class="form-control"  name="categoriaPostula" id="edit_categoriaPostula">
                                    <option value="">-- Selecciona una categoría --</option>
                                    <?php foreach ($licencias as $licencia): ?>
                                        <!-- Igual aquí, asegúrate de que el valor del option corresponde al id de la licencia -->
                                        <option value="<?= $licencia['id_licencia']; ?>" data-categoria="<?= $licencia['categoria_licencia']; ?>"><?= $licencia['categoria_licencia']; ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Fila 3: Tipo de Vehículo y Número de Horas de Clase -->
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipoVehiculo">Tipo de Vehículo</label>
                                    <select class="form-control" name="tipo_vehiculo" id="edit_tipo_Vehiculo">
                                    <option value="">-- Selecciona --</option>
                                    <option value="Auto">Auto</option>
                                    <option value="Combi">Combi/Custer</option>
                                    <option value="Bus">Bus</option>
                                    <option value="Camion pequeño">Camion pequeño</option>
                                    <option value="Camion Grande">Camion Grande</option>
                                    <option value="Mototaxi">Mototaxi</option>
                                </select>                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="horasClase">Número de Horas de Clase</label>
                                    <input type="number" class="form-control" name="horasClase" id="edit_horasClase" placeholder="Número de Horas">
                                </div>
                            </div>
                        </div>

                        <!-- Fila 4: Placa y Nombre del Instructor -->
                        <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="selectPlaca">Placa</label>
                                <select id="edit_selectPlaca" name="placa" class="form-control">
                                    <option value="">-- Seleccione Placa --</option>
                                    <!-- Las opciones se llenarán dinámicamente con JavaScript -->
                                </select>
                            </div>
                        </div>
                
                            <div class="col-md-6">
                            <div class="form-group">
                                <label for="instructor">Nombre del Instructor</label>
                                <select id="edit_instructor" name="instructor" class="form-control">
                                    <option value="">-- Seleccione Instructor --</option>
                                    <!-- Las opciones se llenarán dinámicamente con JavaScript -->
                                </select>
                            </div>

                            </div>
                        </div>

                        <!-- Fila 5: Selección de Fecha y Hora -->
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha">Fecha</label>
                                    <input type="date" class="form-control" name="fecha" id="edit_fecha">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hora">Hora</label>
                                    <input type="time" class="form-control" name="hora" id="edit_hora">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="editIdProgramacion" name="idProgramacion">

                        <!-- Botones del Formulario -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'layouts/right-sidebar.php'; ?>

<?php include 'layouts/footer-scripts.php'; ?>


<!-- Daterangepicker js -->
<script src="assets/vendor/daterangepicker/moment.min.js"></script>
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

<!-- Bootstrap JS y sus dependencias (asegúrate de tener las versiones correctas) -->
<script src="path/to/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>




<script>
   function showAlert(message, type) {
    var alertBox = document.getElementById('alertBox');
    if (alertBox) { // Verifica si alertBox no es null
        alertBox.innerHTML = message;
        alertBox.className = 'alert ' + (type === 'success' ? 'alert-success' : 'alert-danger');
        alertBox.style.display = 'block';
        setTimeout(function() {
            alertBox.style.display = 'none';
        }, 5000);
    } else {
        console.error('Elemento alertBox no encontrado en el DOM.');
    }
}


    $(document).ready(function() {

    $('#tipo_Vehiculo').change(function() { // Asegúrate de que este ID coincida con el ID en tu HTML
        var tipoVehiculo = $(this).val();
        $.ajax({
            url: 'get_vehicles.php', // URL del script PHP
            method: 'GET',
            data: { tipo: tipoVehiculo },
            dataType: 'json',
            success: function(vehiculos) {
                var selectPlaca = $('#selectPlaca');
                selectPlaca.empty().append('<option value="">-- Seleccione Placa --</option>');
                vehiculos.forEach(function(vehiculo) {
                    selectPlaca.append('<option value="' + vehiculo.placa + '">' + vehiculo.placa + '</option>');
                });
            },
            error: function() {
                alert('Error al obtener datos de los vehículos.');
            }
        });
    });
});
    
    $(document).ready(function() {

    $('#categoriaPostula').change(function() {
        var categoriaLicencia = $(this).val();
        $.ajax({
            url: 'get_instructors_by_license.php',
            method: 'GET',
            data: { categoria: categoriaLicencia },
            dataType: 'json',
            success: function(instructores) {
                var selectInstructor = $('#instructor');
                selectInstructor.empty().append('<option value="">-- Seleccione Instructor --</option>');
                instructores.forEach(function(instructor) {
                    selectInstructor.append('<option value="' + instructor.id + '">' + instructor.nombres + ' ' + instructor.apellidos + '</option>');
                });
            },
            error: function() {
                alert('Error al obtener datos de los instructores.');
            }
        });
    });
});

$(document).ready(function() {
    // Evento que se dispara cuando cambia el valor del campo 'documento'
    $('#documento').on('change', function() {
        var documentoNumero = $(this).val();
        
        // Cargar datos del estudiante para la programación de la clase
        $.ajax({
            url: 'get_student_data2.php', // Cambia esto por la URL real de tu script PHP
            method: 'GET',
            data: { numero: documentoNumero },
            dataType: 'json',
            success: function(data) {
                if (data && !data.error) {
                    $('#nombre').val(data.nombre);
                    $('#apellido').val(data.apellido);
                    $('#categoriaPostula').val(data.id_licencia_postula); // Asegúrate de que el ID coincida con las opciones
                } else {
                    alert('Estudiante no encontrado o no tiene categoría a postular.');
                }
            },
            error: function() {
                alert('Error al obtener datos del estudiante.');
            }
        });
    });
});

function actualizarPlacas(tipoVehiculo, placaSeleccionada) {
    $.ajax({
        url: 'get_vehicles.php',
        method: 'GET',
        data: { tipo: tipoVehiculo },
        dataType: 'json',
        success: function(vehiculos) {
            var selectPlaca = $('#edit_selectPlaca');
            selectPlaca.empty().append('<option value="">-- Seleccione Placa --</option>');
            vehiculos.forEach(function(vehiculo) {
                var selectedAttribute = vehiculo.placa === placaSeleccionada ? ' selected' : '';
                selectPlaca.append('<option value="' + vehiculo.placa + '"' + selectedAttribute + '>' + vehiculo.placa + '</option>');
            });
        },
        error: function() {
            alert('Error al obtener datos de los vehículos.');
        }
    });
}
function actualizarInstructores(categoriaLicencia, instructorSeleccionado) {
    $.ajax({
        url: 'get_instructors_by_license.php',
        method: 'GET',
        data: { categoria: categoriaLicencia },
        dataType: 'json',
        success: function(instructores) {
            var selectInstructor = $('#edit_instructor');
            selectInstructor.empty().append('<option value="">-- Seleccione Instructor --</option>');
            instructores.forEach(function(instructor) {
                var selectedAttribute = instructor.id === instructorSeleccionado ? ' selected' : '';
                selectInstructor.append('<option value="' + instructor.id + '"' + selectedAttribute + '>' + instructor.nombres + ' ' + instructor.apellidos + '</option>');
            });
        },
        error: function() {
            alert('Error al obtener datos de los instructores.');
        }
    });
}
$(document).ready(function() {
    $('#nuevoForm').submit(function(e) {
        console.log('Formulario enviado');
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: 'upload2.php', 
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false, 
            success: function(response) {
                try {
                    if (response.startsWith('{') && response.endsWith('}')) { 
                        var data = JSON.parse(response);
                        showAlert(data.message, data.success ? 'success' : 'danger');

                        // Si la operación fue exitosa, recargar la página después de 2 segundos
                        if (data.success) {
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        }
                    } else {
                        showAlert(' ' + response, 'danger');
                    }
                } catch (error) {
                    showAlert('Error al analizar la respuesta del servidor: ' + error, 'danger');
                }
            },
        });
    });
});




$(document).ready(function() {
    $('#datatable-buttons').DataTable();
});
</script>



<script>
    /// EDITAR

    $(document).ready(function() {
    // Evento que se dispara al hacer clic en el botón de editar
    $('.edit-class-btn').on('click', function() {
        var id_programacion = $(this).data('id');

        $('#editClassModal').modal('show');


        $.ajax({
            url: 'get_class_details.php',
            type: 'GET',
            data: { id_programacion: id_programacion },
            dataType: 'json',
            success: function(data) {
                if (!data.error) {
                    // Rellenar los campos del modal con los datos recibidos
                    $('#editIdProgramacion').val(id_programacion);
                    $('#edit_nombre').val(data.nombre);
                    $('#edit_apellido').val(data.apellido); 
                    $('#edit_documento').val(data.documento);
                    $('#edit_categoriaPostula').val(data.categoria_postular);
                    $('#edit_tipo_Vehiculo').val(data.tipo_vehiculo);
                    $('#edit_selectPlaca').val(data.placa);
                    $('#edit_instructor').val(data.selectInstructor);
                    $('#edit_horasClase').val(data.duracionClase);
                    $('#edit_fecha').val(data.fecha_clase);
                    $('#edit_hora').val(data.hora_clase);
                    
                      $('#editClassModal').on('shown.bs.modal', function() {
                    actualizarPlacas(data.tipo_vehiculo, data.placa);
                });
            } else {
                alert("Error al obtener los detalles: " + data.error);
                
            }
        },
        error: function(xhr, status, error) {
            console.error("Error AJAX: ", error);

        }
    });
});


  

    // Manejar el envío del formulario de edición
    $('#editClassForm').on('submit', function(e) {
        e.preventDefault();
        var id_programacion = $('#editIdProgramacion').val();
        console.log('ID de programación:', id_programacion);

        var formData = $(this).serialize();


        // Llamada AJAX para enviar los datos actualizados al servidor
        $.ajax({
            url: 'editar_clase.php', // URL del script PHP que procesa la actualización
            type: 'POST',
            data: formData,
            success: function(response) {
                // Analizar la respuesta para ver si fue exitosa
                var data = JSON.parse(response);
                if (data.success) {
                    // Cerrar el modal y recargar la tabla
                    $('#editClassModal').modal('hide');
                    $('#tablaEstudiantes').DataTable().ajax.reload(); // Asumiendo que estás utilizando DataTables
                    alert('Datos de la clase actualizados correctamente.');
                } else {
                    // Mostrar mensaje de error si la actualización no fue exitosa
                    alert('Error al actualizar los datos de la clase: ' + data.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error al actualizar la clase.');
                console.error('AJAX Error:', status, error);
            }
        });
    });
});




/// BUSCAR CLASES POR FECHA

$(document).ready(function() {
    $('#searchBtn').on('click', function() {
        var selectedDate = $('#searchDate').val();
        if (selectedDate) {
            $.ajax({
                url: 'fetch_classes_by_date.php',
                type: 'GET',
                data: { fecha: selectedDate },
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        // Manejar el error mostrando un mensaje al usuario
                        console.error(data.error);
                        alert(data.error);
                    } else {
                        // Actualizar la tabla con los datos recibidos
                        updateTable(data);
                    }
                },
                error: function(xhr, status, error) {
                    var errorMsg = xhr.responseText ? xhr.responseText : "Error desconocido";
                    alert('Error al cargar los datos de la clase: ' + errorMsg);
                    console.error('AJAX Error:', status, error, xhr);
                }

            });
        }
    });
});

/// VACIAR CONTENIDO A LA TABLA PRINCIPAL
function updateTable(data) {
    var tableBody = $('#datatable-buttons tbody');
    tableBody.empty(); // Vaciar el contenido actual de la tabla

    if (data.length === 0) {
        tableBody.append('<tr><td colspan="7">No hay clases para esta fecha.</td></tr>');
    } else {
        data.forEach(function(clase) {
            var row = '<tr>' +
            '<td>' + clase.estudiante_nombre + ' ' + clase.estudiante_apellido + '</td>' +
                '<td>' + clase.categoria_postular + '</td>' +
                '<td>' + clase.tipo_vehiculo + '</td>' +
                '<td>' + clase.instructor_nombre + ' ' + clase.instructor_apellido + '</td>' +
                '<td>' + clase.fecha_clase + '</td>' +
                '<td>' + clase.hora_clase + '</td>' +
                '<td>' +
                    '<button type="button" class="btn btn-primary edit-class-btn" data-id="' + clase.id_programacion + '"><i class="fas fa-edit"></i></button>' +
                    '<button type="button" class="btn btn-danger delete-class-btn" data-id="' + clase.id_programacion + '"><i class="fas fa-trash"></i></button>' +
                '</td>' +
                '</tr>';
            tableBody.append(row);
        });
    }
}


/// ELIMINAR

$(document).ready(function() {
    $('.delete-class-btn').on('click', function() {
        var idProgramacion = $(this).data('id');
        if (confirm('¿Estás seguro de querer eliminar esta clase?')) {
            $.ajax({
                url: 'eliminar_registro.php', // URL del script PHP que procesa la eliminación
                type: 'POST',
                data: { id: idProgramacion },
                success: function(response) {
                    // Aquí puedes manejar la respuesta, como mostrar un mensaje
                    alert('Clase eliminada correctamente.');

                    // Recargar la página después de 2 segundos (2000 milisegundos)
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                },
                error: function() {
                    alert('Error al eliminar la clase.');
                }
            });
        }
    });
});



</script>

</body>

</html>