<?php
include 'layouts/config.php'; // Incluye la configuración de conexión a la base de datos

$tipoVehiculo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

$respuesta = [];
if ($tipoVehiculo != '') {
    // Consulta para obtener las placas de vehículos basándose en el tipo de vehículo
    $sql = "SELECT placa FROM vehiculos WHERE tipo_vehiculo = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $tipoVehiculo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        while ($row = $resultado->fetch_assoc()) {
            $respuesta[] = $row;
        }
        $stmt->close();
    }
}
$conn->close();
echo json_encode($respuesta);
?>