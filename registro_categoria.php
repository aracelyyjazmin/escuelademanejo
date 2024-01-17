<?php include 'layouts/session.php'; ?>
<?php include 'layouts/main.php'; ?>
<?php include 'layouts/config.php';

// Variable para almacenar los datos

// Procesar la edición de la categoría si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_categoria'])) {
    $id_categoria = $_POST['id_categoria'];
    $nombre_categoria = $_POST['nombreCategoria'];
    $categoria_licencia = $_POST['categoriaLicencia'];

    // Aquí actualizas la base de datos con los nuevos valores
    $updateQuery = "UPDATE categorias SET nombre_categoria = ?, categoria_licencia = ? WHERE id_categoria = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssi", $nombre_categoria, $categoria_licencia, $id_categoria);
    $stmt->execute();

    // Redirige a la misma página para ver los cambios actualizados
    header("Location: registro_categoria.php");
    exit;
}
// Inicializar las variables para almacenar los datos
$datosCategorias = [];
$categorias = [];

// Consultar los datos de las categorías y sus licencias
$sql = "SELECT c.id_categoria, c.nombre_categoria, l.categoria_licencia
        FROM categorias c
        JOIN licencias l ON c.id_categoria = l.id_categoria";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $datosCategorias[] = $row;
    }
}

// Consultar las categorías únicas para el menú desplegable
$sqlCategorias = "SELECT DISTINCT nombre_categoria FROM categorias";
$resultCategorias = $conn->query($sqlCategorias);

if ($resultCategorias && $resultCategorias->num_rows > 0) {
    while ($row = $resultCategorias->fetch_assoc()) {
        $categorias[] = $row['nombre_categoria'];
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Registro de Categoria</title>
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
                                <h4 class="page-title">Lista de Categorias</h4>
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
                        <select class="form-select" id="searchCategory">
                                <option selected value="">-- Seleccione una Categoría --</option>
                                <?php foreach($categorias as $categoria): ?>
                                <option value="<?php echo htmlspecialchars($categoria); ?>"><?php echo htmlspecialchars($categoria); ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row mb-6">
                            <div class="col-md-12 d-flex justify-content-end">
                                <button class="btn btn-primary me-2" id="searchBtn">Buscar</button>
                                <button class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#newStudentModal">+ Nuevo</button>
                            </div>
                        </div>

                    <!-- Tabla de Estudiantes -->
                    <div class="card">
                        <div class="card-body">
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>Tipo Categoria</th>
                                        <th>Categoria de la Licencia</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($datosCategorias as $categoria): ?>
                                        <tr id="category-row-<?php echo $categoria['id_categoria']; ?>">
                                            <td><?= htmlspecialchars($categoria['nombre_categoria']) ?></td>
                                            <td><?= htmlspecialchars($categoria['categoria_licencia']) ?></td>
                                            <td>
                                                <!-- Botones de acción -->
                                                <a href="#" class="edit-category btn btn-info btn-sm" data-id="<?= $categoria['id_categoria'] ?>"><i class="fas fa-edit"></i></a>
                                                <button type="button" class="delete-category btn btn-danger btn-sm" data-id="<?= $categoria['id_categoria'] ?>"><i class="fas fa-trash"></i></button>
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
    <!-- Modal para Nuevo Estudiante -->
    <div class="modal fade" id="newStudentModal" tabindex="-1" aria-labelledby="newStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Nueva Categoria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                <form id="nuevoForm" method="post" action="procesar_categoria.php">
                    <div class="form-group">
                        <label for="nombreCategoria">Nombre de la Categoría</label>
                        <input type="text" class="form-control" name="nombreCategoria" id="nombreCategoria">
                    </div>

                    <div class="form-group">
                        <label for="categoriaLicencia">Categoría de Licencia</label>
                        <input type="text" class="form-control" name="categoriaLicencia" id="categoriaLicencia">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>

        <!-- Modal para Editar Categoría -->
        <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Editar Categoría</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- El formulario de edición se cargará aquí via AJAX -->
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
<script>
    // ... [otros scripts] ...

 // Código para manejar la apertura del modal y la carga del formulario de edición
$(document).on('click', '.edit-category', function() {
    var categoryId = $(this).data('id');
    $.ajax({
        url: "cargar_formulario_edicion.php",
        type: "GET",
        data: {id: categoryId},
        success: function(response) {
            $('#editCategoryModal .modal-body').html(response);
            $('#editCategoryModal').modal('show');
        }
    });
});
$(document).on('click', '.close', function() {
    $('#newStudentModal').modal('hide');
    $('#editCategoryModal').modal('hide');
});


    // Evento para guardar los cambios del formulario de edición
    $(document).on('submit', '#editCategoryForm', function(e) {
    e.preventDefault();
    var formData = $(this).serialize();
    $.ajax({
        url: "procesar_edicion_categoria.php",
        type: "POST",
        data: formData,
        success: function(response) {
            alert(response);
            $('#editCategoryModal').modal('hide');
            location.reload(); // Esta línea recargará la página para mostrar los cambios actualizados.
        }
    });
});
</script>
<script>
$(document).ready(function() {
    $('#searchBtn').on('click', function() {
        var selectedCategory = $('#searchCategory').val().toLowerCase();

        // Iterar sobre todas las filas de la tabla
        $('#datatable-buttons tbody tr').each(function() {
            var row = $(this);
            var category = row.find('td:first').text().toLowerCase();

            // Comparar la categoría de la fila con la seleccionada
            if (selectedCategory === '' || category === selectedCategory) {
                row.show();
            } else {
                row.hide();
            }
        });
    });
});
</script>

<script>


$(document).ready(function() {

    // Evento para abrir el modal de nuevo estudiante
    $('#newStudentBtn').on('click', function() {
        $('#newStudentModal').modal('show');
    });

    // Evento para manejar el envío del formulario de nuevo estudiante
    $('#newStudentForm').on('submit', function(e) {
        e.preventDefault();
        // Lógica para procesar los datos del formulario
        console.log("Registrar nuevo estudiante");

        // Cerrar el modal después de registrar
        $('#newStudentModal').modal('hide');
    });
});

$(document).ready(function() {
    // Evento para manejar la eliminación de una categoría
    $('.delete-category').on('click', function() {
        var categoryId = $(this).data('id');
        if (confirm('¿Estás seguro de querer eliminar esta categoría?')) {
            $.ajax({
                url: "eliminar_categoria.php",
                type: "POST",
                data: { id: categoryId },
                success: function(response) {
                    alert(response.message);
                    if (response.success) {
                        // Elimina la fila de la tabla
                        $('#category-row-' + categoryId).remove();
                    }
                },
                error: function() {
                    alert('Error al eliminar la categoría.');
                }
            });
        }
    });
});

</script>
<script>

    // Actualización dinámica de los campos basada en el tipo de categoría seleccionado
    document.getElementById('tipoCategoria').addEventListener('change', function() {
        var categoria = this.value;
        var selectTipoVehiculoMoto = document.getElementById('tipoVehiculoMoto');
        selectTipoVehiculoMoto.innerHTML = '';

        
        // Reiniciar la selección de la categoría de licencia
        document.getElementById('categoriaLicencia').innerHTML = '';
    });

    </script>


</body>

</html>