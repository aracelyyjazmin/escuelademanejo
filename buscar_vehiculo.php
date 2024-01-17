<?php
// buscar_vehiculo.php
include 'layouts/config.php';

// Verificar si se recibió el tipo de vehículo a buscar
if (isset($_POST['tipo_vehiculo'])) {
    $tipoVehiculo = $_POST['tipo_vehiculo'];

    // Preparar la consulta SQL para buscar vehículos por tipo
    $sql = "SELECT * FROM vehiculos WHERE LOWER(tipo_vehiculo) LIKE ?";
    $stmt = $conn->prepare($sql);
    $tipoVehiculo = '%' . $tipoVehiculo . '%';
    $stmt->bind_param("s", $tipoVehiculo);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $vehiculos = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($vehiculos);
    } else {
        echo "Error en la consulta: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No se proporcionó el tipo de vehículo para la búsqueda.";
}
?>
