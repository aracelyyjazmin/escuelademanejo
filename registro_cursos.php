<?php include 'layouts/session.php'; ?>
<?php include 'layouts/main.php'; ?>
<?php
include 'layouts/config.php';
$sql = "SELECT * FROM cursos";
$result = $conn->query($sql);


?>

<head>

    <title>Registro Cursos</title>
    <?php include 'layouts/title-meta.php'; ?>

    <!-- Daterangepicker css -->
    <link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css">

    <!-- Vector Map css -->
    <link rel="stylesheet" href="assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css">

    <?php include 'layouts/head-css.php'; ?>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <?php include 'layouts/menu.php';?>

        <?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="toast" style="position: absolute; top: 20px; right: 20px;">
            <div class="toast-header">
                <strong class="mr-auto">Notificación</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
            </div>
            <div class="toast-body">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="toast" style="position: absolute; top: 20px; right: 20px; background-color: red;">
            <div class="toast-header">
                <strong class="mr-auto">Error</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
            </div>
            <div class="toast-body">
                <?php echo $_SESSION['error']; ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        </div>
    <?php endif; ?>

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
                        <h4 class="page-title">Cursos</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title"></h4>

                            <div class="row mb-6">
                            <div class="col-md-11 d-flex justify-content-end">
                                
                            <button class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#addCourseModal">Nuevo</button>
                            </div>
                        </div>
                            <div class="card">
                        <div class="card-body">
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>Modulo</th>
                                        <th>Clase de Circulacion</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($result as $row): // Asegúrate de que $result es el array de filas ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['nombre_modulo']); ?></td>
                                        <td><?php echo htmlspecialchars($row['clases_circulacion_vias']); ?></td>
                                        <td>
                                        <button type="button" class="btn btn-primary btn-edit" data-id="<?php echo $row['id_curso']; ?>" data-toggle="modal" data-target="#editModal">Editar</button>
                                            <button type="button" class="btn btn-danger btn-delete" data-id="<?php echo $row['id_curso']; ?>" data-toggle="modal" data-target="#deleteModal">Eliminar</button>
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

<!-- Add Course Modal -->
<div id="addCourseModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addCourseModalLabel">Agregar Curso</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="save_course.php" method="post">
                    <div class="form-group mb-3">
                        <label for="moduleName">Nombre del Modulo</label>
                        <input type="text" class="form-control" id="moduleName" name="moduleName" placeholder="Ingrese el nombre del modulo" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="circulationClasses">Clases de Circulación en Vías</label>
                        <input type="text" class="form-control" id="circulationClasses" name="circulationClasses" placeholder="Ingrese las clases de circulación en vías realizadas" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="circuitRoadways">Circuito/Vías</label>
                        <input type="text" class="form-control" id="circuitRoadways" name="circuitRoadways" placeholder="Detalles del circuito o vías" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Curso</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal para editar -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Curso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" action="path_to_update_script.php" method="post">
                <div class="modal-body">
                    <input type="hidden" id="editId" name="id" value="">
                    <div class="form-group">
                        <label for="editModuleName">Nombre del Módulo</label>
                        <input type="text" class="form-control" id="editModuleName" name="moduleName" required>
                    </div>
                    <div class="form-group">
                        <label for="editCirculationClasses">Clases de Circulación en Vías</label>
                        <input type="text" class="form-control" id="editCirculationClasses" name="circulationClasses" required>
                    </div>
                    <div class="form-group">
                        <label for="editCircuitRoadways">Circuito/Vías</label>
                        <input type="text" class="form-control" id="editCircuitRoadways" name="circuitRoadways" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>



    <!-- END wrapper -->

    <?php include 'layouts/right-sidebar.php'; ?>

    <?php include 'layouts/footer-scripts.php'; ?>

    <!-- Daterangepicker js -->
    <script src="assets/vendor/daterangepicker/moment.min.js"></script>
    <script src="assets/vendor/daterangepicker/daterangepicker.js"></script>

    <!-- Apex Charts js -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>

    <!-- Vector Map js -->
    <script src="assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="assets/vendor/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>

    <!-- Dashboard App js -->
    <script src="assets/js/pages/demo.dashboard.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

    <script>
    $(document).ready(function() {
        $('.toast').toast('show');
    });

    
</script>
<script>
$(document).ready(function() {
    // Manejar clic en el botón de editar
    $('.btn-edit').on('click', function() {
        var id_curso = $(this).data('id');
        // Llamada AJAX para obtener los datos del curso
        $.ajax({
            url: 'get_course_data.php',
            type: 'GET',
            data: {id_curso: id_curso},
            success: function(data) {
                var curso = JSON.parse(data);
                $('#editId').val(curso.id_curso);
                $('#editModuleName').val(curso.nombre_modulo);
                $('#editCirculationClasses').val(curso.clases_circulacion_vias);
                $('#editCircuitRoadways').val(curso.circuito_vias);
                $('#editModal').modal('show');
            }
        });
    });

    // Manejar clic en el botón de eliminar
    $('.btn-delete').on('click', function() {
        var id_curso = $(this).data('id');
        // Llamada AJAX para eliminar el curso
        if (confirm('¿Está seguro de que desea eliminar este curso?')) {
            $.ajax({
                url: 'delete_course.php',
                type: 'POST',
                data: {id_curso: id_curso},
                success: function(response) {
                    alert(response);
                    location.reload(); // Recargar la página para actualizar la tabla
                }
            });
        }
    });
});
</script>



</body>

</html>