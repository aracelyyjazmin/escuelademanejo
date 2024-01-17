<?php include 'layouts/session.php'; ?>
<?php include 'layouts/main.php'; ?>
<?php include 'layouts/config.php';

$sqlLicencias = "SELECT id_licencia, categoria_licencia FROM licencias";
$resultLicencias = $conn->query($sqlLicencias);
$licencias = [];

if ($resultLicencias->num_rows > 0) {
    while ($row = $resultLicencias->fetch_assoc()) {
        $licencias[] = $row;
    }
}
$sqlInstructores = "SELECT instructores.*, licencias.categoria_licencia FROM instructores 
                    LEFT JOIN licencias ON instructores.id_categoria_licencia = licencias.id_licencia";
$resultInstructores = $conn->query($sqlInstructores);

$instructores = [];

if ($resultInstructores->num_rows > 0) {
    while ($row = $resultInstructores->fetch_assoc()) {
        $instructores[] = $row;
    }
}
?>

<head>
    <title>Registro de Instructor</title>
    <?php include 'layouts/title-meta.php'; ?>
    <!-- Datatables css -->
    <link href="assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/vendor/datatables.net-fixedcolumns-bs5/css/fixedColumns.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/vendor/datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/vendor/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/vendor/datatables.net-select-bs5/css/select.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <?php include 'layouts/head-css.php'; ?>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />


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
                                <h4 class="page-title">Lista de Instructores</h4>
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
                        <div class="col-md-6">
                        <select class="form-select" id="searchCategory">
                        <option selected>-- Categoría --</option>
                        <?php foreach ($licencias as $licencia): ?>
                            <option value="<?= $licencia['categoria_licencia']; ?>"><?= $licencia['categoria_licencia']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                        <div class="row mb-4">
                            <div class="col-md-12 d-flex justify-content-end">
                                <button class="btn btn-primary me-2" id="searchBtn">Buscar</button>
                                <button class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#addInstructorModal" id="addInstructorModal"> Agregar Instructor</button>
                            </div>
                        </div>

                    <!-- Tabla de Estudiantes -->
                    <div class="card">
                        <div class="card-body">
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Categoria</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($instructores as $instructor): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($instructor['nombres']) ?></td>
                                            <td><?= htmlspecialchars($instructor['apellidos']) ?></td>
                                            <td><?= htmlspecialchars($instructor['categoria_licencia']) ?></td>
                                            <td>
                                                <!-- Botones de Acciones con Iconos -->
                                                <button type="button"  class="btn btn-primary edit-instructor-btn" data-id="<?= $instructor['id'] ?>">
                                                    <i class="fas fa-edit fa-lg"></i> <!-- Icono de editar -->
                                                </button>
                                                <button type="button" class="btn btn-danger delete-instructor-btn"  data-id="<?= $instructor['id'] ?>">
                                                    <i class="fas fa-trash fa-lg"></i> <!-- Icono de eliminar -->
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
    <!-- END wrapper -->
    <div class="modal fade" id="instructorModal" tabindex="-1" aria-labelledby="instructorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="instructorModalLabel">Nuevo Instructor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="instructorForm">
                    <!-- Modo de edición (campo oculto) -->
                    <input type="hidden" id="modoEdicion" name="modoEdicion" value="false">

                    <!-- Sección de nombres y apellidos -->
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="nombre">Nombres</label>
                            <input type="text" class="form-control" id="nombres" name="nombres" placeholder="Ingresa nombre" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="apellido">Apellidos</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Ingresa apellido" required>
                        </div>
                    </div>

                    <!-- Sección de documento y número -->
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="documento">Tipo de Documento</label>
                            <select class="form-control" id="documento" name="documento" required>
                                <option value="">-- Selecciona --</option>
                                <option value="DNI">DNI</option>
                                <option value="CarnetExtranjeria">Carnet de Extranjería</option>
                                <option value="PermisoTemporal">Permiso Temporal</option>
                                <option value="Pasaporte">Pasaporte</option>
                                <option value="CarnetSolicitante">Carnet de Solicitante</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="numero">Número de Documento</label>
                            <input type="text" class="form-control" id="numero" name="numero" placeholder="Número">
                        </div>
                    </div>

                    <!-- Sección de teléfono y categoría de licencia -->
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="categoriaLicencia">Categoría de Licencia</label>
                            <select class="form-control" id="categoriaLicencia" name="categoriaLicencia">
                                <option value="">-- Selecciona --</option>
                                <?php foreach ($licencias as $licencia): ?>
                                    <option value="<?= $licencia['id_licencia']; ?>"><?= $licencia['categoria_licencia']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Sección de email -->
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="email">Correo</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" required>
                        </div>
                    </div>

                    <!-- Campo oculto para almacenar el ID del instructor (solo para edición) -->
                    <input type="hidden" id="idInstructor" name="idInstructor" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" form="instructorForm" class="btn btn-primary">Guardar</button>
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
<script src="assets/js/app.min.js"></script><script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Cargar categorías de licencia al inicio
    loadLicenseCategories();

    // Eventos para abrir el modal y manejar el formulario
    $('#addInstructorModal').on('click', openInstructorModal);
    $('#instructorForm').on('submit', handleFormSubmit);

    // Eventos para los botones de editar y eliminar en la tabla
    $('#datatable-buttons').on('click', '.edit-instructor-btn', function() {
        var instructorId = $(this).data('id');
        openInstructorModal(instructorId);
    });

    $('#datatable-buttons').on('click', '.delete-instructor-btn', function() {
        var instructorId = $(this).data('id');
        deleteInstructor(instructorId, $(this).closest('tr'));
    });
});

function loadLicenseCategories() {
    $.getJSON('obtener_categorias_licencia.php', function(data) {
        var select = $('#categoriaLicencia');
        $.each(data, function(index, categoria) {
            select.append(new Option(categoria.categoria_licencia, categoria.id_licencia));
        });
    });
}

function openInstructorModal(instructorId = null) {
    clearForm();
    if (instructorId) {
        fetchInstructorData(instructorId);
    }
    $('#instructorModal').modal('show');
}

function handleFormSubmit(e) {
    e.preventDefault();
    var formData = new FormData(this);
    submitInstructorForm(formData);
}

function submitInstructorForm(formData) {
    $.ajax({
        type: 'POST',
        url: 'guardar_instructor.php',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: processFormResponse,
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error al guardar los datos del instructor: ' + errorThrown);
        }
    });
}

function deleteInstructor(instructorId, row) {
    if (confirm('¿Seguro que desea eliminar este instructor?')) {
        $.ajax({
            url: 'eliminar_instructor.php',
            method: 'POST',
            data: { idInstructor: instructorId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Instructor eliminado exitosamente.');
                    row.remove();
                } else {
                    alert('Error al eliminar el instructor: ' + response.error);
                }
            }
        });
    }
}

function fetchInstructorData(instructorId) {
    $.ajax({
        url: 'obtener_instructor_por_id.php',
        type: 'GET',
        data: { id: instructorId },
        dataType: 'json',
        success: fillFormWithInstructorData
    });
}

function processFormResponse(response) {
    if (response.error) {
        alert('Error al guardar los datos: ' + response.error);
    } else {
        alert('Datos del instructor guardados con éxito.');
        $('#instructorModal').modal('hide');
        actualizarTablaInstructores();
        location.reload();
    }
}

function fillFormWithInstructorData(instructor) {
    $('#nombres').val(instructor.nombres);
    $('#apellidos').val(instructor.apellidos);
    $('#documento').val(instructor.documento);
    $('#numero').val(instructor.numero);
    $('#telefono').val(instructor.telefono);
    $('#email').val(instructor.email);
    $('#categoriaLicencia').val(instructor.id_categoria_licencia);
    $('#idInstructor').val(instructor.id);
}

function clearForm() {
    $('#instructorForm')[0].reset();
    $('#idInstructor').val('');
}

function actualizarTablaInstructores() {
    $.ajax({
        type: 'GET',
        url: 'obtener_instructores.php',
        dataType: 'html',
        success: function(response) {
            $('#tablaInstructores').html(response);
        }
    });
}
function loadLicenseCategories() {
    $.getJSON('obtener_categorias_licencia.php', function(data) {
        var select = $('#searchCategory');
        select.empty(); // Limpia las opciones actuales
        select.append($('<option>', {
            value: '',
            text: '-- Categoría --',
            selected: true,
            disabled: true
        }));
        $.each(data, function(index, categoria) {
            select.append($('<option>', {
                value: categoria.id_licencia,
                text: categoria.categoria_licencia
            }));
        });
    });
}
$('#searchBtn').on('click', function() {
    var selectedCategory = $('#searchCategory').val(); // Obtener la categoría seleccionada

    // Verificar si se seleccionó una categoría
    if (selectedCategory === '') {
        // Si no se seleccionó ninguna categoría, mostrar todos los instructores
        mostrarTodosLosInstructores();
    } else {
        // Si se seleccionó una categoría, realizar la búsqueda de instructores por categoría
        buscarInstructoresPorCategoria(selectedCategory);
    }
});

function buscarInstructoresPorCategoria(categoria) {
    $.ajax({
        type: 'GET',
        url: 'obtener_instructores_por_categoria.php',
        data: { categoria: categoria },
        dataType: 'html',
        success: function(response) {
            $('#datatable-buttons tbody').html(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error al buscar instructores: ' + errorThrown);
        }
    });
}
function mostrarTodosLosInstructores() {
    $.ajax({
        type: 'GET',
        url: 'obtener_todos_los_instructores.php', // Reemplaza con la URL correcta
        dataType: 'html',
        success: function(response) {
            $('#datatable-buttons tbody').html(response); // Actualizar la tabla de instructores
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error al obtener todos los instructores: ' + errorThrown);
        }
    });
}
</script>

</body>

</html>