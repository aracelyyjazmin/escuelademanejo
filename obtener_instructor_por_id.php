<?php
include 'layouts/config.php';

// Asegúrate de que se haya proporcionado un ID.
if (isset($_GET['id'])) {
    $idInstructor = $_GET['id'];

    // Prepara la consulta SQL para evitar inyecciones SQL.
    $stmt = $conn->prepare("SELECT id, nombres, apellidos, documento, numero, telefono, email, id_categoria_licencia FROM instructores WHERE id = ?");
    $stmt->bind_param("i", $idInstructor);

    // Ejecutar la consulta
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Comprobar si se obtuvo un resultado
    if ($fila = $resultado->fetch_assoc()) {
        // Devuelve los datos en formato JSON.
        echo json_encode($fila);
    } else {
        echo json_encode(['error' => 'No se encontró el instructor.']);
    }

    // Cerrar la declaración preparada y la conexión a la base de datos
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'ID no proporcionado.']);
}
?>
