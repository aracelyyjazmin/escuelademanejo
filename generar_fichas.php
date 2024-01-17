<?php include 'layouts/session.php'; ?>
<?php include 'layouts/main.php'; ?>
<?php include 'layouts/config.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generar Fichas</title>
    <?php include 'layouts/title-meta.php'; ?>
    <link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <link rel="stylesheet" href="assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="assets/css/estilo_formulario.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">

    <style>
/* Estilos base para la tabla y el formulario de subida */
.table-responsive {
    margin: 0 15px; /* Añade un espacio en los lados de la tabla */
}

.upload-form {
    display: flex;
    flex-direction: column; /* Los elementos se apilarán verticalmente */
    align-items: start; /* Alinea los elementos al inicio del contenedor */
}

table.dataTable tbody td {
    text-align: center; /* Centra el contenido de todas las celdas */
}

table.dataTable tbody td.actions-column {
    width: 100px; /* Establece un ancho fijo para la columna de acciones si es necesario */
}

.upload-form input[type="file"],
.upload-form button,
.form-file-input,
.form-submit-button {
    width: 100%; /* El botón ocupa todo el ancho disponible */
    margin-bottom: 5px; /* Espacio entre el input de archivo y el botón */
}

#datatable-buttons {
    text-align: right; /* Alinea los botones a la derecha */
    margin-bottom: 10px; /* Espacio debajo de los botones */
}

/* Estilos para controlar el ancho y el espaciado de la tabla */
table.dataTable {
    width: 100% !important; /* Fuerza a la tabla a usar todo el ancho disponible */
}

table.dataTable thead th,
table.dataTable tbody td {
    padding: 8px; /* Ajusta el relleno dentro de las celdas para controlar el espacio */
    white-space: nowrap; /* Asegura que los encabezados de las columnas no se rompan en líneas */
}

/* Estilos responsivos para pantallas pequeñas */
@media (max-width: 800px) {
    #tablaEstudiantes thead {
        display: none;
    }

    .search-container {
        display: flex;
        flex-direction: column; /* Apila los elementos verticalmente en pantallas pequeñas */
        justify-content: space-between; /* Separa los elementos */
        align-items: center; /* Centra los elementos verticalmente */
        margin-bottom: 20px; /* Espacio debajo del contenedor */
    }
}

@media (max-width: 556px) {
    /* Si necesitas más control, puedes ocultar partes de la tabla en puntos de interrupción específicos */
    .table-responsive-sm .long-info {
        display: none;
    }
}

</style>
</head>


    <?php include 'layouts/head-css.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <?php include 'layouts/menu.php'; ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right"></div>
                                <h4 class="page-title">Ficha</h4>
                            </div>
                        </div>
                    </div>
                    

                    <div class="row">
                        <div class="col-12">
                        <div class="search-container">
                            <div class="card">
                            <div class="card-body">

                        
                                    <div id="datatable-buttons"></div>

                                 </div>
                                <div class="table-responsive">
                                        <table id="tablaEstudiantes" class="table table-striped dt-responsive nowrap">
                                            <thead>
                                                <tr>
                                                <th>Nombre Completo</th>
                                                    <th>DNI</th>
                                                    <th>Fecha de Nacimiento</th>
                                                    <th>Email</th>
                                                    <th>Postula</th> 
                                                    <th>Proceso</th> 
                                                    <th>Horas T</th> 
                                                    <th>Horas P</th> 
                                                    <th class="actions-column">Acciones</th>
                                                    <th>Reporte</th> 

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Los datos de los estudiantes se cargarán aquí mediante AJAX -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div> <!-- end container-fluid -->
            </div> <!-- end content -->
        </div> <!-- end content-page -->
        <?php include 'layouts/footer.php'; ?>

    </div> <!-- end wrapper -->

    <?php include 'layouts/right-sidebar.php'; ?>
    <?php include 'layouts/footer-scripts.php'; ?>
    <!-- Apex Charts js -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>

    <!-- Vector Map js -->
    <script src="assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="assets/vendor/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>

    <!-- Dashboard App js -->
    <script src="assets/js/pages/demo.dashboard.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

    <script>$(document).ready(function() {
    cargarEstudiantes();
});

function cargarEstudiantes() {
    $.ajax({
        url: 'fetch_estudiantes.php',
        type: 'GET',
        dataType: 'json',
        success: function(estudiantes) {
            var tbody = $('#tablaEstudiantes tbody');
            tbody.empty();

            estudiantes.forEach(function(estudiante) {
                var fila = '<tr>' +
                    '<td>' + estudiante.nombre + ' ' + estudiante.apellido + '</td>' +
                    '<td>' + estudiante.numero + '</td>' + // Asumiendo que documento es el DNI
                    '<td>' + estudiante.fecha_nacimiento + '</td>' +
                    '<td>' + estudiante.email + '</td>' +
                    '<td>' + estudiante.licencia_postula + '</td>' +
                    '<td>' + (estudiante.nombre_modulo || 'Sin Asignar') + '</td>' + // Se maneja el valor nulo con 'Sin Asignar'
                    '<td>' + estudiante.horas_teoricas_requeridas + '</td>' +
                    '<td>' + estudiante.horas_practicas_requeridas + '</td>' +
                    '<td>' + // Aquí podrías agregar botones o acciones relacionadas al estudiante
                    '<form onsubmit="return subirImagen(event, ' + estudiante.id + ')">' +
                    '<input type="file" name="imagen_estudiante" accept="image/*" required />' +
                    '<button type="submit">Subir</button>' +
                    '</form>' +
                    '</td>' +
                    '<td>' +
                    '<button onclick="descargarReportePersonalizado(' + estudiante.id + ')">' +
                            '<i class="ri-file-search-fill"></i>' +
                        '</button>' +
                    '</td>' +
                    '</tr>';
                tbody.append(fila);
            });

            if ($.fn.dataTable.isDataTable('#tablaEstudiantes')) {
                $('#tablaEstudiantes').DataTable().clear().destroy();
            }

            var table = $('#tablaEstudiantes').DataTable({
                responsive: true,
                autoWidth: false, // Deshabilita el ancho automático de DataTables



                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'pdf', 'print'
                    
                ]
            });

            table.buttons().container().appendTo('#datatable-buttons');
        },
        error: function() {
            alert('Error al cargar los datos');
        }
    });
}
$(document).ready(function() {
    $('#scroll-horizontal-datatable').DataTable({
        "scrollX": true
    });
});


function subirImagen(event, estudianteId) {
    event.preventDefault();

    var form = event.target;
    var formData = new FormData(form);

    formData.append('estudiante_id', estudianteId);

    $.ajax({
        url: 'subir_imagen_estudiante.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            alert('Imagen subida con éxito');
            cargarEstudiantes();
            location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error al subir la imagen: ' + textStatus);
        }
    });
}

</script>
<script>

function descargarReportePersonalizado(estudianteId) {
    // Redirige al usuario al script del servidor que genera el PDF
    var url = 'generar_reporte.php?id=' + estudianteId;
    window.open(url, '_blank');
}


</script>
</body>
</html>
