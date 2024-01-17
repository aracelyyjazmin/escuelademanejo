<?php
include 'layouts/session.php';
include 'layouts/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recopilar los datos del formulario y escapar para prevenir inyecciones SQL
    $tipo_vehiculo = mysqli_real_escape_string($conn, $_POST["tipo_vehiculo"]);
    $placa = mysqli_real_escape_string($conn, $_POST["placa"]);
    $categoriaLicencia = mysqli_real_escape_string($conn, $_POST["categoriaLicencia"]);
    $categoriaVehiculo = mysqli_real_escape_string($conn, $_POST["categoriaVehiculo"]);
    $kilometraje_inicial = mysqli_real_escape_string($conn, $_POST["kilometraje_inicial"]);
    $tiempo_maximo = mysqli_real_escape_string($conn, $_POST["tiempoMaximo"]);
    $estado_vehiculo = mysqli_real_escape_string($conn, $_POST["estadoVehiculo"]);
    $modelo_vehiculo = mysqli_real_escape_string($conn, $_POST["modeloVehiculo"]);
    $anio_vehiculo = mysqli_real_escape_string($conn, $_POST["anioVehiculo"]);
    $transmision_vehiculo = mysqli_real_escape_string($conn, $_POST["transmisionVehiculo"]);
    $combustible_vehiculo = mysqli_real_escape_string($conn, $_POST["combustibleVehiculo"]);

    // Iniciar la transacción
    $conn->begin_transaction();

    // Verificar si la categoría de licencia seleccionada existe en la tabla licencias
    $stmtLicencia = $conn->prepare("SELECT id_licencia FROM licencias WHERE categoria_licencia = ?");
    $stmtLicencia->bind_param("s", $categoriaLicencia);
    $stmtLicencia->execute();
    $resultCheckLicencia = $stmtLicencia->get_result();
    $stmtLicencia->close();

    if ($resultCheckLicencia->num_rows > 0) {
        $row = $resultCheckLicencia->fetch_assoc();
        $id_licencia_vehiculo = $row["id_licencia"];

        // Preparar la consulta SQL para insertar un nuevo vehículo
        $stmtVehiculo = $conn->prepare("INSERT INTO vehiculos (tipo_vehiculo, placa, id_licencia_vehiculo, categoriaVehiculo, kilometraje_inicial, tiempo_maximo, estado_vehiculo, modelo_vehiculo, anio_vehiculo, transmision_vehiculo, combustible_vehiculo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtVehiculo->bind_param("ssisdiisiss", $tipo_vehiculo, $placa, $id_licencia_vehiculo, $categoriaVehiculo, $kilometraje_inicial, $tiempo_maximo, $estado_vehiculo, $modelo_vehiculo, $anio_vehiculo, $transmision_vehiculo, $combustible_vehiculo);

        if ($stmtVehiculo->execute()) {
            // Si la inserción es exitosa, confirmar la transacción
            $conn->commit();
            $stmtVehiculo->close();
            // Redirigir a la página de vehículos después de guardar
            echo "Vehículo registrado con éxito.";
        } else {
            // Si la inserción falla, mostrar un mensaje de error y revertir la transacción
            echo "Error al guardar el vehículo: " . $stmtVehiculo->error;
            $conn->rollback();
            $stmtVehiculo->close();
        }
        }
    } else {
        echo "La categoría de licencia seleccionada no existe en la base de datos.";
        $conn->rollback();
    }

    // Siempre cierra la conexión, independientemente del resultado
    $conn->close();
?>
