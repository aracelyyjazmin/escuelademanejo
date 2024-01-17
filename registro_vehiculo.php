<?php
// Incluye los archivos necesarios
include 'layouts/session.php';
include 'layouts/main.php';
include 'layouts/config.php';

// Inicializa variables para mensajes
$mensajeError = "";
$mensajeExito = "";

$sqlVehiculos = "SELECT v.*, c.nombre_categoria AS categoria_licencia, l.categoria_licencia AS categoria_vehiculo
                 FROM vehiculos v
                 LEFT JOIN categorias c ON v.id_categoria = c.id_categoria
                 LEFT JOIN licencias l ON v.id_categoria = l.id_categoria";


// Ejecutar la consulta SQL para obtener vehículos
$resultVehiculos = $conn->query($sqlVehiculos);

// Verificar si la consulta se ejecutó con éxito
if ($resultVehiculos === false) {
    echo "Error en la consulta SQL: " . $conn->error;
} else {
    // Inicializa un array para almacenar los datos de vehículos
    $vehiculos = [];

    // Verifica si la consulta devolvió filas
    if ($resultVehiculos->num_rows > 0) {
        while ($row = $resultVehiculos->fetch_assoc()) {
            $vehiculos[] = $row;
        }
    } else {
        echo "No se encontraron vehículos.";
    }
}

// Consulta para obtener las categorías de licencia
$sqlLicencias = "SELECT l.id_licencia, l.categoria_licencia, c.id_categoria FROM licencias l JOIN categorias c ON l.id_categoria = c.id_categoria";
$resultLicencias = $conn->query($sqlLicencias);
$licencias = [];

// Almacena las categorías de licencia en un array
if ($resultLicencias->num_rows > 0) {
    while ($row = $resultLicencias->fetch_assoc()) {
        $licencias[] = $row;
    }

}

?>

<head>
    <title>Registro de Vehiculo</title>
    <?php include 'layouts/title-meta.php'; ?>
    <!-- Datatables css -->
    <link href="assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/vendor/datatables.net-fixedcolumns-bs5/css/fixedColumns.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/vendor/datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/vendor/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/vendor/datatables.net-select-bs5/css/select.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <?php include 'layouts/head-css.php'; ?>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                                <h4 class="page-title">Lista de Vehiculos</h4>
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
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Buscar por Placa" id="searchLastName">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="searchCategory">
                                <option selected>-- Categoría --</option>
                                <option value="Categoria1">Categoria 1</option>
                                <option value="Categoria2">Categoria 2</option>
                            </select>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12 d-flex justify-content-end">
                                <button class="btn btn-primary me-2" id="searchBtn">Buscar</button>
                                <button type="button" class="btn btn-primary" id="btnOpenModal">Registrar Vehículo</button>
                            </div>
                        </div>
                        </div>
                        <?php if ($mensajeExito != "") echo "<div class='alert alert-success'>$mensajeExito</div>"; ?>
                        <?php if ($mensajeError != "") echo "<div class='alert alert-danger'>$mensajeError</div>"; ?>

                    <!-- Tabla de Estudiantes -->
                    <div class="card">
                        <div class="card-body">
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>Tipo Vehiculo</th>
                                        <th>Placa</th>
                                        <th>Kilometraje</th>
                                        <th>Estado Vehiculo</th>
                                        <th>Acciones</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($vehiculos as $vehiculo): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($vehiculo['tipo_vehiculo']) ?></td>
                                            <td><?= htmlspecialchars($vehiculo['placa']) ?></td>
                                            <td><?= htmlspecialchars($vehiculo['kilometraje_inicial']) ?></td>
                                            <td><?= htmlspecialchars($vehiculo['estado_vehiculo']) ?></td>
                                            <td>
                                                <!-- Botones de Acciones con Iconos -->
                                                <button type="button" class="btn btn-primary edit-vehicle-btn" data-id="<?= $vehiculo['id_vehiculo'] ?>">
                                                    <i class="fas fa-edit fa"></i> <!-- Icono de editar -->
                                                </button>
                                                <button type="button" class="btn btn-danger delete-vehicle-btn" data-id="<?= $vehiculo['id_vehiculo'] ?>">
                                                    <i class="fas fa-trash fa"></i> <!-- Icono de eliminar -->
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
    <div class="modal fade" id="vehicleModal" tabindex="-1" aria-labelledby="vehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Registro de Vehículo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="registroVehiculoForm">
                    <div class="form-row">
                        <div class="col-md-6">
                            <!-- Tipo de Vehículo -->
                            <div class="form-group">
                                
                                <label for="tipo_Vehiculo">Tipo de Vehículo</label>
                                <select class="form-control" name="tipo_vehiculo" id="tipo_Vehiculo" required>
                                    <option value="">-- Selecciona --</option>
                                    <option value="Auto">Auto</option>
                                    <option value="Combi">Combi</option>
                                    <option value="Custer">Custer</option>
                                    <option value="Bus">Bus</option>
                                    <option value="Camion pequeño">Camion pequeño</option>
                                    <option value="Camion Grande">Camion Grande</option>
                                    <option value="Mototaxi">Mototaxi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Placa del Vehículo -->
                            <div class="form-group">
                                <label for="placa">Placa</label>
                                <input type="text" class="form-control"  name="placa" id="placa" placeholder="Placa del Vehículo" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Categoría de Licencia -->
                            <div class="form-group">
                                <label for="categoriaLicencia">Categoría de Licencia</label>
                                <select class="form-control" name="categoriaLicencia" id="categoriaLicencia" required>
                                    <option value="">-- Selecciona --</option>
                                    <?php foreach ($licencias as $licencia): ?>
                                        <!-- Asegúrate de enviar el id_categoria como valor -->
                                        <option value="<?= $licencia['id_categoria']; ?>"><?= $licencia['categoria_licencia']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <!-- Categoría del Vehículo -->
                            <div class="form-group">
                                <label for="categoriaVehiculo">Categoría del Vehículo</label>
                                <select class="form-control" name="categoriaVehiculo" id="categoriaVehiculo" required>
                                    <option value="">-- Selecciona --</option>
                                    <option value="M1">M1</option>
                                    <option value="M2">M2</option>
                                    <option value="C3">C3</option>
                                    <option value="M3">M3</option>
                                    <option value="N2">N2</option>
                                    <option value="N3">N3</option>
                                    <option value="L5">L5</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Kilometraje Inicial -->
                            <div class="form-group">
                                <label for="kilometraje">Kilometraje Inicial</label>
                                <input type="number" class="form-control" name="kilometraje" id="kilometraje" placeholder="Kilometraje Inicial" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Tiempo Máximo de Uso -->
                            <div class="form-group">
                                <label for="tiempoUso">Tiempo Máximo de Uso al Día</label>
                                <input type="hidden" name="tiempoUso"  id="tiempoUso" value="9">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Estado del Vehículo -->
                            <div class="form-group">
                                <label for="estadoVehiculo">Estado</label>
                                <select class="form-control" id="estadoVehiculo" name="estadoVehiculo" required>
                                <option value="disponible" selected>Disponible</option>
                                <option value="no disponible">No Disponible</option>
                            </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Modelo del Vehículo -->
                            <div class="form-group">
                                <label for="modeloVehiculo">Modelo del Vehículo</label>
                                <input type="text" class="form-control" name="modeloVehiculo" id="modeloVehiculo" placeholder="Modelo del Vehículo" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Año del Vehículo -->
                            <div class="form-group">
                                <label for="anioVehiculo">Año</label>
                                <input type="number" class="form-control" name="anioVehiculo" id="anioVehiculo" placeholder="Año del Vehículo" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Tipo de Transmisión -->
                            <div class="form-group">
                                <label for="transmisionVehiculo">Tipo de Transmisión</label>
                                <select class="form-control" name="transmisionVehiculo" id="transmisionVehiculo" required>
                                    <option value="automatico">Automático</option>
                                    <option value="manual">Manual</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Tipo de Combustible -->
                    <div class="form-group">
                        <label for="combustibleVehiculo">Tipo de Combustible</label>
                        <select class="form-control"  name="combustibleVehiculo" id="combustibleVehiculo" required>
                            <option value="gasolina">Gasolina</option>
                            <option value="diesel">Diésel</option>
                        </select>
                    </div>
                    <input type="hidden" id="vehicleId" name="vehicleId" value="">


                    <!-- Botones del formulario -->
                    <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
$(document).ready(function() {
    // Cargar categorías de licencia al inicio
    loadLicenseCategories();

    // Evento para abrir el modal de registro de vehículo
    $('#btnOpenModal').on('click', function() {
        openVehicleModal();
    });

    // Evento para guardar o editar un vehículo
    $('#btnGuardar').on('click', function() {
        saveOrUpdateVehicle();
    });

    // Evento para buscar vehículos
    $('#searchBtn').on('click', function() {
        searchVehicles();
    });

    // Evento para editar un vehículo
    $('#datatable-buttons').on('click', '.edit-vehicle-btn', function() {
        var vehicleId = $(this).data('id');
        openVehicleModal(vehicleId);
    });

    // Evento para eliminar un vehículo
    $('#datatable-buttons').on('click', '.delete-vehicle-btn', function() {
        var vehicleId = $(this).data('id');
        deleteVehicle(vehicleId, $(this).closest('tr'));
    });
});

function openVehicleModal(vehicleId = null) {
    clearVehicleForm();
    if (vehicleId) {
        fetchVehicleData(vehicleId);
    }
    $('#vehicleModal').modal('show');
}

function saveOrUpdateVehicle() {
    var formData = new FormData($('#registroVehiculoForm')[0]);
    $.ajax({
        type: 'POST',
        url: 'registrar_vehiculo.php',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            if (response.exito) { 
                alert('Datos del vehículo guardados con éxito.');
                $('#vehicleModal').modal('hide');
                // Actualizar la tabla de vehículos o realizar cualquier otra acción necesaria
                location.reload();
            } else {
                alert('Error al guardar los datos: ' + response.mensaje); // Cambiar response.error a response.mensaje
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR); // Registra la respuesta completa en la consola
            alert('Error al guardar los datos del vehículo: ' + errorThrown);
        }
    });
}

function deleteVehicle(vehicleId, row) {
    if (confirm('¿Seguro que desea eliminar este vehículo?')) {
        $.ajax({
            url: 'eliminar_vehiculo.php',
            method: 'POST',
            data: { vehiculo_id: vehicleId }, // Cambia idVehiculo a vehiculo_id
            dataType: 'json', // Esperamos una respuesta JSON
            success: function(response) {
                if (response.success) {
                    alert('Vehículo eliminado exitosamente.');
                    row.remove();
                } else {
                    alert('Error al eliminar el vehículo: ' + response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error al eliminar el vehículo: ' + errorThrown);
            }
        });
    }
}


function fetchVehicleData(vehicleId) {
    $.ajax({
        url: 'obtener_vehiculo.php',
        type: 'GET',
        data: { id: vehicleId },
        dataType: 'json',
        success: function(vehicle) {
            $('#tipo_Vehiculo').val(vehicle.tipo_vehiculo);
            $('#placa').val(vehicle.placa);
            $('#categoriaLicencia').val(vehicle.id_categoria_licencia);
            $('#categoriaVehiculo').val(vehicle.categoria_vehiculo);
            $('#kilometraje').val(vehicle.kilometraje_inicial);
            $('#estadoVehiculo').val(vehicle.estado_vehiculo);
            $('#modeloVehiculo').val(vehicle.modelo_vehiculo);
            $('#anioVehiculo').val(vehicle.anio_vehiculo);
            $('#transmisionVehiculo').val(vehicle.transmision_vehiculo);
            $('#combustibleVehiculo').val(vehicle.combustible_vehiculo);
            $('#vehicleId').val(vehicle.id_vehiculo);
        }
    });
}

function clearVehicleForm() {
    $('#registroVehiculoForm')[0].reset();
    $('#vehicleId').val('');
}

function loadLicenseCategories() {
    $.getJSON('obtener_categorias_licencia.php', function(data) {
        var select = $('#categoriaLicencia');
        select.empty();
        select.append($('<option>', {
            value: '',
            text: '-- Selecciona --',
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

function searchVehicles() {
    var placa = $('#searchLastName').val();
    var categoria = $('#searchCategory').val();

    $.ajax({
        type: 'POST',
        url: 'buscar_vehiculos.php',
        data: { placa: placa, categoria: categoria },
        dataType: 'html',
        success: function(response) {
            $('#datatable-buttons tbody').html(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error al buscar vehículos: ' + errorThrown);
        }
    });
}
</script>
<script>
    // Actualización dinámica de la categoría de licencia basada en el tipo de vehículo
    const licenciasPorTipo = {
        auto: ["AI", "AIIA", "AIIB"],
        camion: ["AIIb", "AIIc"]
    };

    document.getElementById('tipoVehiculo').addEventListener('change', function() {
        var tipo = this.value;
        var selectLicencia = document.getElementById('categoriaLicencia');
        selectLicencia.innerHTML = '';

        var optionDefault = document.createElement('option');
        optionDefault.textContent = '-- Selecciona --';
        optionDefault.value = '';
        selectLicencia.appendChild(optionDefault);

        if(licenciasPorTipo[tipo]) {
            licenciasPorTipo[tipo].forEach(function(licencia) {
                var option = document.createElement('option');
                option.textContent = licencia;
                option.value = licencia;
                selectLicencia.appendChild(option);
            });
        }
        var idCategoriaSeleccionada = licenciasPorTipo[tipo][0]; // Supongamos que tomas la primera categoría por defecto
    if (idCategoriaSeleccionada) {
        selectLicencia.value = idCategoriaSeleccionada;
    }
    });
    </>
</script>

<!-- Dentro de tu archivo HTML -->
<script>
const opcionesPorTipo = {
    "Auto": {
        "CategoriaLicencia": ["AIIA", "AIIB"],
        "CategoriaVehiculo": ["M1"]
    },
    "Combi": {
        "CategoriaLicencia": ["AIIB"],
        "CategoriaVehiculo": ["M2", "C3"]
    },
    "Custer": {
        "CategoriaLicencia": ["AIIB"],
        "CategoriaVehiculo": ["M2", "C3"]
        
    },
    "Bus": {
        "CategoriaLicencia": ["AIIIA", "AIIC"],
        "CategoriaVehiculo": ["M3"]
    },
    "Camion pequeño": {
        "CategoriaLicencia": ["AIIB"],
        "CategoriaVehiculo": ["N2"]
    },
    "Camion Grande": {
        "CategoriaLicencia": ["AIIIB", "AIIIC"],
        "CategoriaVehiculo": ["N3"]
    },
    "Mototaxi": {
        "CategoriaLicencia": ["BIIC"],
        "CategoriaVehiculo": ["L5"]
    }
};

// Función para actualizar las opciones de categoría de licencia y categoría de vehículo
function actualizarOpciones() {
    const tipoVehiculoSeleccionado = document.getElementById('tipo_Vehiculo').value;
    const categoriaLicenciaSelect = document.getElementById('categoriaLicencia');
    const categoriaVehiculoSelect = document.getElementById('categoriaVehiculo');

    // Borra las opciones anteriores
    categoriaLicenciaSelect.innerHTML = '';
    categoriaVehiculoSelect.innerHTML = '';

    // Obtiene las opciones disponibles para el tipo de vehículo seleccionado
    const opciones = opcionesPorTipo[tipoVehiculoSeleccionado] || {};

    // Llena el campo de categoría de licencia
    if (opciones && opciones.CategoriaLicencia) {
        opciones.CategoriaLicencia.forEach(opcion => {
            const optionElement = document.createElement('option');
            optionElement.textContent = opcion;
            optionElement.value = opcion;
            categoriaLicenciaSelect.appendChild(optionElement);
        });
    }

    // Llena el campo de categoría de vehículo
    if (opciones && opciones.CategoriaVehiculo) {
        opciones.CategoriaVehiculo.forEach(opcion => {
            const optionElement = document.createElement('option');
            optionElement.textContent = opcion;
            optionElement.value = opcion;
            categoriaVehiculoSelect.appendChild(optionElement);
        });
    }
}
document.getElementById('tipo_Vehiculo').addEventListener('change', actualizarOpciones);

// Llamar a la función de actualización de opciones al cargar la página
actualizarOpciones();
</script>


</body>

</html>