<?php 
include 'layouts/session.php'; 
include 'layouts/main.php';
include 'layouts/config.php';

$sqlVehiculos = "SELECT v.*, l.categoria_licencia
                 FROM vehiculos v
                 LEFT JOIN licencias l ON v.id_licencia_vehiculo = l.id_licencia";

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
        <?php include 'layouts/menu.php';?>

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
                                    <div class="row mb-3">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" placeholder="Buscar por Tipo de Vehículo" id="searchByVehicleType">
                                    </div>
                                    <div class="row mb-4">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <button class="btn btn-primary me-2" id="searchBtn">Buscar</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#crearVehiculoModal" id="registrarVehiculoBtn">Registrar Vehículo</button>
                                    </div>
                                </div>
                                </div>
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
                                                <button class="btn btn-primary btnEdit" data-vehicle-id="<?= $vehiculo['id_vehiculo'] ?>">Editar</button>
                                                 <button class="btn btn-danger btnDelete" data-vehicle-id="<?= $vehiculo['id_vehiculo'] ?>">Eliminar</button>
                                            </td>
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
                           
                </div>

            </div> 

            <?php include 'layouts/footer.php'; ?>

    </div>
    <!-- END wrapper -->
    <div class="modal fade" id="crearVehiculoModal" tabindex="-1" role="dialog" aria-labelledby="crearVehiculoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearVehiculoModalLabel">Crear Vehículo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="crearVehiculoForm">
                    <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">

                            <label for="tipo_Vehiculo">Tipo de Vehículo:</label>
                            <select class="form-control" name="tipo_vehiculo" id="tipo_Vehiculo" required>
                                    <option value="">-- Selecciona --</option>
                                    <option value="Auto">Auto</option>
                                    <option value="Combi/Custer">Combi/Custer</option>
                                    <option value="Bus">Bus</option>
                                    <option value="Camion pequeño">Camion pequeño</option>
                                    <option value="Camion Grande">Camion Grande</option>
                                    <option value="Mototaxi">Mototaxi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="placa">Placa:</label>
                            <input type="text" class="form-control" name="placa" id="placa" required>
                        </div>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                        <label for="categoriaLicencia">Categoría de Licencia:</label>
                        <select class="form-control" name="categoriaLicencia" id="categoriaLicencia" required>
                            <option value="">-- Selecciona --</option>
                            <?php foreach ($licencias as $licencia): ?>
                                <!-- Envía el id_licencia como valor -->
                                <option value="<?= $licencia['id_licencia']; ?>"><?= $licencia['categoria_licencia']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="categoriaVehiculo">Categoría de Vehículo:</label>
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
                        <div class="form-group">
                            <label for="kilometraje_inicial">Kilometraje Inicial:</label>
                            <input type="number" class="form-control" name="kilometraje_inicial" id="kilometraje_inicial" required>
                        </div>
                        </div>

                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="tiempoMaximo">Tiempo de Uso:</label>
                            <input type="number" class="form-control" name="tiempoMaximo" id="tiempoMaximo" required>
                        </div>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="estadoVehiculo">Estado del Vehículo:</label>
                            <select class="form-control" name="estadoVehiculo" id="estadoVehiculo" required>
                                <option value="disponible" selected>Disponible</option>
                                <option value="no disponible">No Disponible</option>
                            </select>
                        </div>
                        </div>

                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="modeloVehiculo">Modelo del Vehículo:</label>
                            <input type="text" class="form-control" name="modeloVehiculo" id="modeloVehiculo" required>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="anioVehiculo">Año del Vehículo:</label>
                            <input type="number" class="form-control" name="anioVehiculo" id="anioVehiculo" required>
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="transmisionVehiculo">Transmisión del Vehículo:</label>
                            <select class="form-control" name="transmisionVehiculo" id="transmisionVehiculo" required>
                                <option value="automatico">Automático</option>
                                <option value="manual">Manual</option>
                            </select>
                        </div>
                    </div>
                    </div>
                        <div class="form-group">
                            <label for="combustibleVehiculo">Tipo de Combustible:</label>
                            <select class="form-control" name="combustibleVehiculo" id="combustibleVehiculo" required>
                                <option value="gasolina">Gasolina</option>
                                <option value="diesel">Diésel</option>
                            </select>
                        </div>

                    <!-- Agrega aquí los demás campos si es necesario -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Vehículo -->
<div class="modal fade" id="editarVehiculoModal" tabindex="-1" role="dialog" aria-labelledby="editarVehiculoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarVehiculoModalLabel">Editar Vehículo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editarVehiculoForm">
                    <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">

                            <label for="tipo_vehiculo">Tipo de Vehículo:</label>
                            <select class="form-control" name="tipo_vehiculo" id="tipo_vehiculo" required>
                                <option value="">-- Selecciona --</option>
                                <option value="Auto">Auto</option>
                                <option value="Combi/Custer">Combi/Custer</option>
                                <option value="Bus">Bus</option>
                                <option value="Camion pequeño">Camion pequeño</option>
                                <option value="Camion Grande">Camion Grande</option>
                                <option value="Mototaxi">Mototaxi</option>
                            </select>
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="placa">Placa:</label>
                            <input type="text" class="form-control" name="placa" id="placa" required>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">

                        <label for="categoriaLicencia">Categoría de Licencia:</label>
                        <select class="form-control" name="categoriaLicencia" id="categoriaLicencia" required>
                            <option value="">-- Selecciona --</option>
                            <?php foreach ($licencias as $licencia): ?>
                                <!-- Envía el id_licencia como valor -->
                                <option value="<?= $licencia['id_licencia']; ?>"><?= $licencia['categoria_licencia']; ?></option>
                            <?php endforeach; ?>
                        </select>


                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">

                            <label for="categoriaVehiculo">Categoría de Vehículo:</label>
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
                        <div class="form-group">

                            <label for="kilometraje_inicial">Kilometraje Inicial:</label>
                            <input type="text" class="form-control" name="kilometraje_inicial" id="kilometraje_inicial" required>
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">

                            <label for="tiempoMaximo">Tiempo de Uso:</label>
                            <input type="text" class="form-control" name="tiempoMaximo" id="tiempoMaximo" required>
                        </div>
                        <input type="hidden" name="vehicleId" id="vehicleId" value="">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="estadoVehiculo">Estado del Vehículo:</label>
                            <select class="form-control" name="estadoVehiculo" id="estadoVehiculo" required>
                                <option value="disponible" selected>Disponible</option>
                                <option value="no disponible">No Disponible</option>
                            </select>
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="modeloVehiculo">Modelo del Vehículo:</label>
                            <input type="text" class="form-control" name="modeloVehiculo" id="modeloVehiculo" required>
                        </div>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">

                            <label for="anioVehiculo">Año del Vehículo:</label>
                            <input type="text" class="form-control" name="anioVehiculo" id="anioVehiculo" required>
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="transmisionVehiculo">Tipo de Transmisión:</label>
                            <select class="form-control" name="transmisionVehiculo" id="transmisionVehiculo" required>
                                <option value="automatico">Automático</option>
                                <option value="manual">Manual</option>
                            </select>
                        </div>
                        </div>
                    </div>
  
                        <div class="form-group">

                            <label for="combustibleVehiculo">Tipo de Combustible:</label>
                            <select class="form-control" name="combustibleVehiculo" id="combustibleVehiculo" required>
                                <option value="gasolina">Gasolina</option>
                                <option value="diesel">Diésel</option>
                            </select>
                    </div>
                    <!-- Agrega aquí los demás campos si es necesario -->
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
 
// Abrir el modal para editar un vehículo
$("body").on("click", ".btnEdit", function() {
    var vehicleId = $(this).data("vehicle-id");

    // Aquí debes cargar los datos del vehículo con vehicleId y llenar el formulario de edición.
    
    // Luego, muestra el modal de edición
    $("#editarVehiculoModal").modal("show");
});

// Cerrar el modal de edición de vehículo
$("#editarVehiculoModal").on("hidden.bs.modal", function() {
    // Limpia el formulario de edición
    $("#editarVehiculoForm")[0].reset();
});

function abrirModalRegistroVehiculo() {
    $('#crearVehiculoModal').modal('show'); // Abre el modal
}
$(document).ready(function() {
    $('#registrarVehiculoBtn').click(function() {
        abrirModalRegistroVehiculo();
    });
});
// Enviar solicitud AJAX para crear un vehículo
$(document).ready(function() {
    
    $("#crearVehiculoForm").submit(function(event) {
        event.preventDefault(); // Evita el envío del formulario por defecto

        // Realiza una solicitud AJAX para guardar los datos
        $.ajax({
            url: "server.php", // Ruta al archivo PHP que maneja la solicitud
            method: "POST",
            data: $(this).serialize(), // Serializa el formulario para enviar los datos
            success: function(response) {
                // Procesa la respuesta del servidor
                alert(response); // Puedes mostrar una alerta o realizar otras acciones
                if(response.includes("exitosamente")) { // Comprobar si la respuesta contiene la palabra "exitosamente"
                    $("#crearVehiculoForm")[0].reset(); // Limpia el formulario
                }
                window.location.reload();

                $("#crearVehiculoModal").modal("hide");
                actualizarTablaVehiculos();
            },
            error: function(error) {
                // Maneja los errores si la solicitud AJAX falla
                console.error("Error en la solicitud AJAX: " + error);
            }
        
        });
    });
    function actualizarTablaVehiculos() {
    // Realiza una solicitud AJAX para obtener los datos actualizados de los vehículos
    $.ajax({
        url: "datos_vehiculos.php",
        method: "GET",
        success: function(response) {
            // Aquí asumimos que `response` es un array de objetos de vehículos
            var vehiculos = JSON.parse(response);
            var html = '';
            vehiculos.forEach(function(vehiculo) {
                html += '<tr>' +
                        '<td>' + vehiculo.tipo_vehiculo + '</td>' +
                        '<td>' + vehiculo.placa + '</td>' +
                        '<td>' + vehiculo.kilometraje_inicial + '</td>' +
                        '<td>' + vehiculo.estado_vehiculo + '</td>' +
                        '<td> Botones de Acciones </td>' +
                        '</tr>';
            });
            $('#datatable-buttons tbody').html(html);
        }
    });
}
});

// Enviar solicitud AJAX para editar un vehículo
$("#editarVehiculoForm").submit(function(event) {
    event.preventDefault();

    $.ajax({
        url: "server.php", // URL del archivo PHP que maneja la edición
        method: "POST",
        data: $(this).serialize(),
        success: function(response) {
            // Procesar la respuesta del servidor y actualizar la tabla de vehículos
            // Cerrar el modal de edición
            $("#editarVehiculoModal").modal("hide");
        }
    });
});
$(document).ready(function() {
    // Abrir el modal para editar un vehículo y cargar los datos
    $("body").on("click", ".btnEdit", function() {
        var vehicleId = $(this).data("vehicle-id");

        // Realiza una solicitud AJAX para obtener los datos del vehículo
        $.ajax({
            url: "obtener_datos_vehiculo.php", // URL del script PHP que devuelve los datos del vehículo
            method: "POST",
            dataType: "json",
            data: { id: vehicleId },
            success: function(vehiculo) {
                // Asumiendo que la respuesta es un objeto JSON con los datos del vehículo
                $('#editarVehiculoForm #tipoVehiculo').val(vehiculo.tipo_vehiculo);
                $('#editarVehiculoForm #placa').val(vehiculo.placa);
                $('#editarVehiculoForm #categoriaLicencia').val(vehiculo.id_categoria);
                $('#editarVehiculoForm #categoriaVehiculo').val(vehiculo.categoriaVehiculo);
                $('#editarVehiculoForm #kilometraje_inicial').val(vehiculo.kilometraje_inicial);
                $('#editarVehiculoForm #tiempoMaximo').val(vehiculo.tiempo_maximo);
                $('#editarVehiculoForm #estadoVehiculo').val(vehiculo.estado_vehiculo);
                $('#editarVehiculoForm #modeloVehiculo').val(vehiculo.modelo_vehiculo);
                $('#editarVehiculoForm #anioVehiculo').val(vehiculo.anio_vehiculo);
                $('#editarVehiculoForm #transmisionVehiculo').val(vehiculo.transmision_vehiculo);
                $('#editarVehiculoForm #combustibleVehiculo').val(vehiculo.combustible_vehiculo);
                $('#editarVehiculoForm #vehicleId').val(vehiculo.id_vehiculo); // Asegúrate de tener un input hidden para el ID

                $("#editarVehiculoModal").modal("show");
            },
            error: function(error) {
                console.error("Error al obtener los datos del vehículo: " + error.statusText);
}

        });
    });

    // Enviar solicitud AJAX para editar un vehículo
    $("#editarVehiculoForm").submit(function(event) {
        event.preventDefault();

        // Realiza una solicitud AJAX para actualizar los datos del formulario
        $.ajax({
            url: "actualizar_vehiculo.php", // URL del script PHP que maneja la actualización
            method: "POST",
            data: $(this).serialize(), // Serializa los datos del formulario
            success: function(response) {
                alert(response); // Muestra una alerta con la respuesta del servidor
                $("#editarVehiculoModal").modal("hide"); // Cierra el modal
                window.location.reload(); // Recarga la página para mostrar los cambios actualizados
            },
            error: function(error) {
                console.error("Error al actualizar el vehículo: " + error.statusText);
            }
        });
    });
});

// Eliminar vehículo
$("body").on("click", ".btnDelete", function() {
    var vehicleId = $(this).data("vehicle-id");

    // Envía una solicitud AJAX al servidor para eliminar el vehículo con vehicleId
    $.ajax({
    url: "eliminar_vehiculo.php", // URL del script PHP
    method: "POST",
    data: { id: vehicleId }, // Datos enviados al servidor
    dataType: "json", // Asegúrate de recibir una respuesta JSON
    success: function(response) {
        // Verificar si la operación fue exitosa
        if(response.success) {
            // Mostrar mensaje de éxito
            alert(response.message); // O puedes usar tu propio elemento HTML para mostrar el mensaje
            // Recargar la página después de un breve retraso para ver el mensaje
            setTimeout(function() {
                window.location.reload();
            }, 2000); // Recarga después de 2 segundos
        } else {
            // Mostrar mensaje de error
            alert(response.message); // O puedes usar tu propio elemento HTML para mostrar el mensaje
        }
    },
    error: function(error) {
        // Maneja los errores si la solicitud AJAX falla
        console.error("Error en la solicitud AJAX: " + error);
    }
});
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const opcionesPorTipo = {
        "Auto": {
            "CategoriaLicencia": ["AIIA", "AIIB"],
            "CategoriaVehiculo": ["M1"]
        },
        "Combi/Custer": {
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
    // Escucha el evento de cambio en el tipo de vehículo
    document.getElementById('tipo_Vehiculo').addEventListener('change', actualizarOpciones);

    // Llamar a la función de actualización de opciones al cargar la página
    actualizarOpciones();

});

</script>
<script>

$(document).ready(function() {
    // Evento de clic para el botón de búsqueda
    $("#searchBtn").click(function() {
        var searchValue = $("#searchByVehicleType").val();

        // Realiza una solicitud AJAX para buscar los vehículos por tipo
        $.ajax({
            url: "buscar_vehiculo.php", // La URL del script PHP que realizará la búsqueda en la base de datos
            method: "POST",
            data: { tipo_vehiculo: searchValue },
            success: function(response) {
                // Asumimos que 'response' es un array de objetos de vehículos devueltos por la búsqueda
                var vehiculos = JSON.parse(response);
                var html = '';
                vehiculos.forEach(function(vehiculo) {
                    html += '<tr>' +
                            '<td>' + vehiculo.tipo_vehiculo + '</td>' +
                            '<td>' + vehiculo.placa + '</td>' +
                            '<td>' + vehiculo.kilometraje_inicial + '</td>' +
                            '<td>' + vehiculo.estado_vehiculo + '</td>' +
                            '<td>' +
                                '<button class="btn btn-primary btnEdit" data-vehicle-id="' + vehiculo.id_vehiculo + '">Editar</button>' +
                                '<button class="btn btn-danger btnDelete" data-vehicle-id="' + vehiculo.id_vehiculo + '">Eliminar</button>' +
                            '</td>' +
                            '</tr>';
                });
                $('#datatable-buttons tbody').html(html);
            },
            error: function(error) {
                console.error("Error en la solicitud AJAX: " + error.statusText);
            }
        });
    });
});

    </script>
</body>
</html>