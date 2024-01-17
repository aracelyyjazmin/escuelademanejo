<?php
include_once 'layouts/config.php';

$fecha = $_GET['fecha'] ?? null;

if (!$fecha) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Fecha no proporcionada"]);
    exit;
}

$sql = 
"SELECT 
pc.id_programacion,
e.nombre AS estudiante_nombre,
e.apellido AS estudiante_apellido,
l.categoria_licencia AS categoria_postular,
v.tipo_vehiculo,
i.nombres AS instructor_nombre,
i.apellidos AS instructor_apellido,
pc.fecha_clase,
pc.hora_clase
FROM 
programacion_clases pc
JOIN 
estudiantes e ON pc.id_estudiante = e.id
JOIN 
instructores i ON pc.id_instructor = i.id
JOIN 
vehiculos v ON pc.id_vehiculo = v.id_vehiculo
JOIN 
licencias l ON e.id_licencia_postula = l.id_licencia
WHERE 
pc.fecha_clase = ?
";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $fecha);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $clases = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($clases);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Error en la consulta: " . $conn->error]);
    }

    $stmt->close();
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
}

$conn->close();
?>
