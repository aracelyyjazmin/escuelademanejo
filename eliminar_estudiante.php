<?php
include 'layouts/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['studentId'];

    // Ejecuta la consulta para eliminar al estudiante
    $sql = "DELETE FROM estudiantes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $studentId);

    if ($stmt->execute()) {
        $response = ['success' => true];
    } else {
        $response = ['success' => false];
    }

    echo json_encode($response);
} else {
    header('Location: eliminar_estudiante.php'); // Redirigir si no es una solicitud POST válida
}
?>
