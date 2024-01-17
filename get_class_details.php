<?php
include_once 'layouts/config.php';
header('Content-Type: application/json');

// Asegúrate de que se proporciona un ID de programación y es un número
$id_programacion = isset($_GET['id_programacion']) ? (int)$_GET['id_programacion'] : null;
if (!$id_programacion) {
    echo json_encode(['error' => 'ID de programación no proporcionado o no es un número válido']);
    exit;
}

// Prepara la consulta SQL para obtener todos los detalles necesarios de la programación de clases
$query = "SELECT pc.*, e.nombre, e.apellido, e.numero AS documento, l.categoria_licencia AS categoriaPostula, 
v.tipo_vehiculo, v.placa, i.id AS id_instructor, i.nombres AS nombreInstructor, i.apellidos AS apellidoInstructor
FROM programacion_clases pc
JOIN estudiantes e ON pc.id_estudiante = e.id
JOIN licencias l ON e.id_licencia_postula = l.id_licencia
JOIN vehiculos v ON pc.id_vehiculo = v.id_vehiculo
JOIN instructores i ON pc.id_instructor = i.id
WHERE pc.id_programacion = ?";

$response = [];
try {
    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_programacion);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si se encuentra la fila, devolver los datos como JSON
    if ($row = $result->fetch_assoc()) {
        // Añadir el id_instructor al objeto de respuesta
        $row['id_instructor'] = (int)$row['id_instructor'];
        $response = $row;
    } else {
        $response['error'] = 'No se encontró la clase con el ID proporcionado.';
    }
} catch (Exception $e) {
    $response['error'] = 'Error en la consulta: ' . $e->getMessage();
} finally {
    $stmt->close();
}

echo json_encode($response);
?>
