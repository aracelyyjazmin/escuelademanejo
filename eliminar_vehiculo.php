<?php
// Incluye el archivo de configuración de la base de datos
include 'layouts/config.php';

// Verifica si se recibió el ID del vehículo a eliminar
if (isset($_POST['id'])) {
    $vehiculoId = $_POST['id'];

    // Sentencia SQL para eliminar el vehículo por su ID
    $sql = "DELETE FROM vehiculos WHERE id_vehiculo = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $response = array(
            'success' => false,
            'message' => "Error en la preparación de la consulta: " . $conn->error
        );
    } else {
        // Vincular el parámetro (ID del vehículo) a la sentencia
        $stmt->bind_param("i", $vehiculoId);

        // Ejecutar la sentencia
        if ($stmt->execute()) {
            $response = array(
                'success' => true,
                'message' => "El vehículo se eliminó correctamente."
            );
        } else {
            $response = array(
                'success' => false,
                'message' => "Error al eliminar el vehículo: " . $stmt->error
            );
        }

        // Cerrar la sentencia y la conexión
        $stmt->close();
        $conn->close();
    }
} else {
    $response = array(
        'success' => false,
        'message' => "No se recibió el ID del vehículo a eliminar."
    );
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
