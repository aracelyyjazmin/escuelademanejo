<?php
include 'layouts/config.php'; // Este archivo debe contener la configuración de tu base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['studentId']) && !empty($_POST['studentId'])) {
    $studentId = $_POST['studentId'];

    $sql = "SELECT e.*, la.categoria_licencia as categoria_actual, 
            lp.categoria_licencia as categoria_postular
            FROM estudiantes e
            LEFT JOIN licencias la ON e.id_licencia_actual = la.id_licencia
            LEFT JOIN licencias lp ON e.id_licencia_postula = lp.id_licencia
            WHERE e.id = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(['error' => 'Error al preparar la consulta: ' . htmlspecialchars($conn->error)]);
        exit;
    }

    $stmt->bind_param("i", $studentId);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            // Eliminado el manejo de la foto del estudiante
            echo json_encode($row);
        } else {
            echo json_encode(['error' => 'Estudiante no encontrado']);
        }
    } else {
        echo json_encode(['error' => 'Error al ejecutar la consulta']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'ID de estudiante no válido']);
}
$conn->close();
?>
