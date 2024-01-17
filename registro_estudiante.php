<?php include 'layouts/session.php'; ?>
<?php include 'layouts/main.php'; ?>
<?php include 'layouts/config.php';


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
    <title>Registro de Estudiante</title>
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
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />

     <style>
        /* Estilo para opciones deshabilitadas en el select */
        select[disabled] {
            color: #808080; /* Cambia el color a gris */
            background-color: #f8f8f8; /* Cambia el color de fondo a un gris más claro si lo deseas */
        }
    </style>
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
                                <h4 class="page-title">Lista de Estudiantes</h4>
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
                    <div class="col-md-5">
                        <select class="form-control" id="searchCategory">
                            <option value="">-- Seleccione una Categoría --</option>
                            <?php foreach ($licencias as $licencia): ?>
                                <option value="<?= $licencia['categoria_licencia']; ?>"><?= $licencia['categoria_licencia']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row mb-7">
                        <div class="col-md-13 d-flex justify-content-end">
                            <button class="btn btn-primary me-2" id="searchBtn">Buscar</button>
                            <button type="button" class="btn btn-success" id="addStudentBtn"> Agregar Estudiante</button>                        </div>
                    </div>
                </div>

                    <!-- Tabla de Estudiantes -->
                    <div class="card">
                        <div class="card-body">
                            <table id="datatable-buttons" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Categoria Postular</th>
                                        <th>Proceso</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($estudiantes as $estudiante): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($estudiante['nombre']) ?></td>
                                            <td><?= htmlspecialchars($estudiante['apellido']) ?></td>
                                            <td><?= htmlspecialchars($estudiante['categoria_postular']) ?></td>
                                            <td><?= htmlspecialchars($estudiante['proceso']) ?></td>
                                            <td>
                                            <!-- Botón de editar con icono -->
                                            <button type="button" class="btn btn-primary edit-student-btn" data-id="<?= $estudiante['id']; ?>">
                                                <i class="fas fa-edit fa-xs"></i> <!-- Icono de edición con tamaño grande (fa-lg) -->
                                            </button>

                                            <!-- Botón de eliminar con icono -->
                                            <button type="button" class="delete-student btn btn-danger btn" data-id="<?= $estudiante['id']; ?>">
                                                <i class="fas fa-trash fa-xs"></i> <!-- Icono de eliminación con tamaño grande (fa-lg) -->
                                            </button>

                                            <!-- Botón de visualizar con icono -->
                                            <button type="button" class="btn btn-info view-student-btn" data-id="<?= $estudiante['id']; ?>">
                                                <i class="fas fa-eye fa-xs"></i> <!-- Icono de visualización con tamaño grande (fa-lg) -->
                                            </button>
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
<!-- Modal paraEstudiante -->
<div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Agregar/Editar Estudiante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <form id="studentForm" method="post" action="guardar_estudiante.php" enctype="multipart/form-data">
                    <!-- Campos del formulario -->
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                
                                <label for="nombre">Nombres</label>
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingresa nombre">
                            </div>
                            <div class="form-group mb-3">
                                <label for="apellido">Apellidos</label>
                                <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Ingresa apellido">
                            </div>
                            <div class="form-group mb-3">
                                <label for="documento">Tipo de Documento</label>
                                <select class="form-control" name="documento" id="documento">
                                    <option value="DNI">DNI</option>
                                    <option value="CarnetExtranjeria">Carnet de Extranjería</option>
                                    <option value="PermisoTemporal">Permiso Temporal</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                    <option value="CarnetSolicitante">Carnet de Solicitante</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="numero">Número de Documento</label>
                                <input type="text" class="form-control" name="numero" id="numero" placeholder="Número de Documento">
                            </div>
                            <div class="form-group mb-3">
                                <label for="telefono">Teléfono</label>
                                <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Teléfono">
                            </div>
                            <div class="form-group mb-3">
                                <label for="email">E-mail</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="E-mail">
                            </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group mb-3">
                        <label for="id_licencia_actual">Categoría Actual</label>
                        <select class="form-control" name="id_licencia_actual" id="id_licencia_actual">
                            <option value="">-- Selecciona una categoría --</option>
                            <?php foreach ($licencias as $licencia): ?>
                                <!-- Asegúrate de que el valor del option corresponde al id de la licencia -->
                                <option value="<?= $licencia['id_licencia']; ?>" data-categoria="<?= $licencia['categoria_licencia']; ?>"><?= $licencia['categoria_licencia']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="id_licencia_postula">Categoría a Postular</label>
                        <select class="form-control" name="id_licencia_postula" id="id_licencia_postula">
                            <option value="">-- Selecciona una categoría --</option>
                            <?php foreach ($licencias as $licencia): ?>
                                <!-- Igual aquí, asegúrate de que el valor del option corresponde al id de la licencia -->
                                <option value="<?= $licencia['id_licencia']; ?>" data-categoria="<?= $licencia['categoria_licencia']; ?>"><?= $licencia['categoria_licencia']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                            <div class="form-group mb-3">
                                <label for="gradoInstruccion">Grado de Instrucción</label>
                                <input type="text" class="form-control" name="grado_instruccion" id="gradoInstruccion">
                            </div>
                            <div class="form-group mb-3">
                                <label for="fechaNacimiento">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" name="fecha_nacimiento" id="fechaNacimiento">
                            </div>
                            <div class="form-group mb-3">
                                <label for="domicilio">Domicilio</label>
                                <input type="text" class="form-control" name="domicilio" id="domicilio">
                            </div>
                            <div class="form-group mb-3">
                            <label for="proceso">Proceso</label>
                            <select class="form-control" name="proceso" id="proceso">
                                <option value="Nuevo">Nuevo</option>
                                <option value="Revalidacion">Revalidación</option>
                                <option value="Recategorizacion">Recategorización</option>
                            </select>
                        </div>
                            </div>
                        
                    </div>
                    <!-- Campo oculto para almacenar el ID del estudiante (solo para edición) -->
                    <input type="hidden" id="studentId" name="studentId" value="">
                </form>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" id="saveStudentBtn" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal para mostrar detalles del estudiante -->
<div class="modal fade" id="studentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="studentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentDetailsModalLabel">Detalles del Estudiante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Columna 1: Información Personal -->
                    <div class="col-md-6">
                        <p><strong>Nombre:</strong> <span id="studentName"><?php echo $student['nombre']; ?></span></p>
                        <p><strong>Apellido:</strong> <span id="studentLastName"><?php echo $student['apellido']; ?></span></p>
                        <p><strong>Tipo de Documento:</strong> <span id="studentDocumentType"><?php echo $student['tipo_documento']; ?></span></p>
                        <p><strong>Número de Documento:</strong> <span id="studentDocumentNumber"><?php echo $student['numero_documento']; ?></span></p>
                        <p><strong>Teléfono:</strong> <span id="studentPhone"><?php echo $student['telefono']; ?></span></p>
                        <p><strong>E-mail:</strong> <span id="studentEmail"><?php echo $student['email']; ?></span></p>
                    </div>
                    <!-- Columna 2: Otra Información -->
                    <div class="col-md-6">
                        <p><strong>Categoría Actual:</strong> <span id="studentCurrentCategory"><?php echo $student['id_licencia_actual']; ?></span></p>
                        <p><strong>Categoría a Postular:</strong> <span id="studentPostCategory"><?php echo $licencia['id_licencia_postula']; ?></span></p>
                        <p><strong>Grado de Instrucción:</strong> <span id="studentEducation"><?php echo $student['grado_instruccion']; ?></span></p>
                        <p><strong>Fecha de Nacimiento:</strong> <span id="studentBirthDate"><?php echo $student['fecha_nacimiento']; ?></span></p>
                        <p><strong>Domicilio:</strong> <span id="studentAddress"><?php echo $student['domicilio']; ?></span></p>
                        <p><strong>Proceso:</strong> <span id="studentProcess"><?php echo $student['proceso']; ?></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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

<!-- JavaScript para abrir el modal de agregar o editar estudiante -->
<script>
    $(document).ready(function() {
        // Función para abrir el modal de agregar o editar estudiante
        function openStudentModal(studentId = null) {
            $('#studentForm')[0].reset();
            $('#studentId').val(studentId);
            $('#modalLabel').text(studentId ? 'Editar Estudiante' : 'Nuevo Estudiante');

            if (studentId) {
                // Cargar datos del estudiante para editar
                $.ajax({
                    url: 'obtener_estudiante.php',
                    method: 'GET',
                    data: { studentId: studentId },
                    dataType: 'json',
                    success: function(data) {
                        $('#nombre').val(data.nombre);
                        $('#apellido').val(data.apellido);
                        $('#documento').val(data.documento);
                        $('#numero').val(data.numero);
                        $('#telefono').val(data.telefono);
                        $('#email').val(data.email);
                        $('#id_licencia_actual').val(data.id_licencia_actual);
                        $('#id_licencia_postula').val(data.id_licencia_postula);
                        $('#gradoInstruccion').val(data.grado_instruccion);
                        $('#fechaNacimiento').val(data.fecha_nacimiento);
                        $('#domicilio').val(data.domicilio);
                        $('#proceso').val(data.proceso);
                        // Puedes cargar otros campos si es necesario
                    },
                    error: function() {
                        alert('Error al obtener datos del estudiante.');
                    }
                });
            }

            $('#studentModal').modal('show');
        }

        // Evento de clic para abrir modal de agregar nuevo estudiante
        $('#addStudentBtn').on('click', function() {
            openStudentModal();
        });

        // Delegación de eventos para botones de edición
        $('#datatable-buttons').on('click', '.edit-student-btn', function() {
            var studentId = $(this).data('id');
            openStudentModal(studentId);
        });
    });
</script>

<!-- JavaScript para guardar estudiante -->
<script>
    $(document).ready(function() {
        $('#saveStudentBtn').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData($('#studentForm')[0]);
            $.ajax({
                url: 'guardar_estudiante.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        alert('Error al guardar los datos: ' + response.error);
                    } else {
                        alert('Datos guardados correctamente.');

                        // Cerrar automáticamente el modal
                        $('#studentModal').modal('hide');

                        // Recargar la página automáticamente después de guardar
                        location.reload();
                    }
                },
                error: function() {
                    alert('Error al guardar los datos.');
                }
            });
        });
    });
</script>

<!-- JavaScript para eliminar estudiante -->
<script>
    $(document).ready(function() {
        $('#datatable-buttons').on('click', '.delete-student', function() {
            var studentId = $(this).data('id');
            var row = $(this).closest('tr');

            if (confirm('¿Seguro que desea eliminar este estudiante?')) {
                $.ajax({
                    url: 'eliminar_estudiante.php',
                    method: 'POST',
                    data: { studentId: studentId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Estudiante eliminado exitosamente.');
                            // Recargar la página actual
                            location.reload();
                        } else {
                            alert('Error al eliminar el estudiante: ' + response.error);
                        }
                    },
                    error: function() {
                        alert('Error al eliminar el estudiante.');
                    }
                });
            }
        });
    });
</script>

<!-- JavaScript para buscar estudiantes -->
<script>
$(document).ready(function() {
    $('#searchBtn').on('click', function() {
        var category = $('#searchCategory').val();

        // Comprobar si la opción predeterminada "-- Seleccione una Categoría --" está seleccionada
        if (category === "-- Seleccione una Categoría --") {
            // Si se selecciona la opción predeterminada, muestra toda la tabla de estudiantes
            $('#datatable-buttons').DataTable().search('').draw(); // Reiniciar la búsqueda
        } else {
            // Si se selecciona una categoría específica, realiza la búsqueda por esa categoría
            $.ajax({
                url: 'buscar_estudiantes.php',
                method: 'POST',
                data: {
                    category: category
                },
                dataType: 'html',
                success: function(response) {
                    // Actualizar la tabla de estudiantes con los resultados de la búsqueda
                    $('#datatable-buttons tbody').html(response);
                },
                error: function() {
                    alert('Error al realizar la búsqueda.');
                }
            });
        }
    });
});

</script>

<!-- JavaScript para ver detalles de estudiantes -->
<script>
    $(document).ready(function() {
        $('#datatable-buttons').on('click', '.view-student-btn', function() {
            var studentId = $(this).data('id');

            // Función para actualizar el modal con los detalles del estudiante
            function updateStudentDetailsModal(response) {
                $('#studentName').text(response.nombre);
                $('#studentLastName').text(response.apellido);
                $('#studentDocumentType').text(response.documento);
                $('#studentDocumentNumber').text(response.numero);
                $('#studentPhone').text(response.telefono);
                $('#studentEmail').text(response.email);
                $('#studentCurrentCategory').text(response.categoria_actual);
                $('#studentPostCategory').text(response.categoria_postular);
                $('#studentEducation').text(response.grado_instruccion);
                $('#studentBirthDate').text(response.fecha_nacimiento);
                $('#studentAddress').text(response.domicilio);
                $('#studentProcess').text(response.proceso);

                // Mostrar el modal de detalles del estudiante
                $('#studentDetailsModal').modal('show');
            }

            // Realizar una solicitud AJAX para obtener los detalles del estudiante por su ID
            $.ajax({
                url: 'obtener_estudiante2.php',
                method: 'POST',
                data: { studentId: studentId },
                dataType: 'json',
                success: function(response) {
                    updateStudentDetailsModal(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error al obtener detalles del estudiante: ' + errorThrown);
                    // Puedes mostrar un mensaje de error más amigable aquí si lo deseas
                }
            });
        });
    });
</script>
