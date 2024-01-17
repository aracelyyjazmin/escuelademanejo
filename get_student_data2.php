<?php
include 'layouts/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['numero'])) {
    $numero = $_GET['numero'];

    // Consulta SQL para obtener los datos del estudiante por su número de documento
    $sql = "SELECT estudiantes.*, licencias.categoria_licencia AS categoria_postular
            FROM estudiantes
            LEFT JOIN licencias ON estudiantes.id_licencia_postula = licencias.id_licencia
            WHERE estudiantes.numero = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $numero);

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
