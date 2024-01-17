<?php
include 'layouts/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['studentId'])) {
    $studentId = $_GET['studentId'];

    // Consulta SQL para obtener los datos del estudiante por su ID
    $sql = "SELECT * FROM estudiantes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $studentId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $estudiante = $result->fetch_assoc();
        echo json_encode($estudiante); // Devuelve los datos del estudiante como JSON
    } else {
        echo json_encode(['error' => 'Error al obtener datos del estudiante.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Solicitud no válida.']);
}
?>
