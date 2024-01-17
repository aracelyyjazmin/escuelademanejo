<?php
// Incluir el archivo de configuración con la conexión a la base de datos
include_once 'config.php'; // Verifica que este path sea correcto

// Recuperar datos del formulario y validarlos
$idProgramacion = filter_input(INPUT_POST, 'idProgramacion', FILTER_VALIDATE_INT);
$fecha = filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING);
$hora = filter_input(INPUT_POST, 'hora', FILTER_SANITIZE_STRING);

// Verifica si los datos están presentes y son válidos
if ($idProgramacion === false) {
    echo json_encode(["success" => false, "message" => "ID de programación no válido"]);
    exit;
}

if (!$fecha || !$hora) {
    echo json_encode(["success" => false, "message" => "Fecha u hora no proporcionados o en formato incorrecto"]);
    exit;
}

// Preparar la sentencia SQL para actualizar la fecha y hora
$sql = "UPDATE programacion_clases SET fecha_clase = ?, hora_clase = ? WHERE id_programacion = ?";

// Preparar la sentencia
if ($stmt = $conn->prepare($sql)) {
    // Vincular parámetros
    $stmt->bind_param("ssi", $fecha, $hora, $idProgramacion);

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Clase actualizada correctamente"]);
    } else {
        // Si algo sale mal, envía un mensaje con el error de la base de datos
        echo json_encode(["success" => false, "message" => "Error al actualizar la clase: " . $stmt->error]);
    }

    // Cerrar sentencia
    $stmt->close();
} else {
    // Si algo sale mal al preparar, envía un mensaje con el error de la base de datos
    echo json_encode(["success" => false, "message" => "Error al preparar la consulta: " . $conn->error]);
}

// Cerrar conexión
$conn->close();
?>
