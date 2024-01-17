<?php
// Incluye la configuración de la base de datos
include 'layouts/config.php';

// Asegúrate de que se haya proporcionado un ID de vehículo y es un entero válido.
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $idVehiculo = $_POST['id'];

    // Preparar la consulta SQL para evitar inyecciones SQL.
    $stmt = $conn->prepare("SELECT * FROM vehiculos WHERE id_vehiculo = ?");
    $stmt->bind_param("i", $idVehiculo);

    // Ejecutar la consulta
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Comprobar si se obtuvo un resultado
    if ($fila = $resultado->fetch_assoc()) {
        // Devuelve los datos en formato JSON.
        echo json_encode($fila);
    } else {
        echo json_encode(['error' => 'No se encontró el vehículo con el ID proporcionado.']);
    }

    // Cerrar la declaración preparada y la conexión a la base de datos
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'ID de vehículo no proporcionado o no es válido.']);
}
?>
