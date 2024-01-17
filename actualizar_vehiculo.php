<?php
include 'layouts/session.php';
include 'layouts/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recopilar los datos del formulario
    $idVehiculo = $_POST["vehicleId"];
    $tipo_vehiculo = $_POST["tipo_vehiculo"];
    $placa = $_POST["placa"];
    $id_licencia_vehiculo = $_POST["categoriaLicencia"];
    $categoriaVehiculo = $_POST["categoriaVehiculo"];
    $kilometraje_inicial = $_POST["kilometraje_inicial"];
    $tiempo_maximo = $_POST["tiempoMaximo"];
    $estado_vehiculo = $_POST["estadoVehiculo"];
    $modelo_vehiculo = $_POST["modeloVehiculo"];
    $anio_vehiculo = $_POST["anioVehiculo"];
    $transmision_vehiculo = $_POST["transmisionVehiculo"];
    $combustible_vehiculo = $_POST["combustibleVehiculo"];

    // Preparar la consulta SQL para actualizar el vehículo
    $sql = "UPDATE vehiculos SET 
            tipo_vehiculo = ?, 
            placa = ?, 
            id_licencia_vehiculo = ?, 
            categoriaVehiculo = ?, 
            kilometraje_inicial = ?, 
            tiempo_maximo = ?, 
            estado_vehiculo = ?, 
            modelo_vehiculo = ?, 
            anio_vehiculo = ?, 
            transmision_vehiculo = ?, 
            combustible_vehiculo = ? 
            WHERE id_vehiculo = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisssssissi", 
        $tipo_vehiculo, 
        $placa, 
        $id_licencia_vehiculo, 
        $categoriaVehiculo, 
        $kilometraje_inicial, 
        $tiempo_maximo, 
        $estado_vehiculo, 
        $modelo_vehiculo, 
        $anio_vehiculo, 
        $transmision_vehiculo, 
        $combustible_vehiculo,
        $idVehiculo);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $message = 'Vehículo actualizado exitosamente.';
    } else {
        $message = "Error al actualizar el vehículo: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();

    echo $message;
}
?>
