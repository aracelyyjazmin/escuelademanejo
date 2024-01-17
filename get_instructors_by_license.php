<?php
include 'layouts/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['categoria'])) {
    $categoriaLicencia = $_GET['categoria'];

    // Consulta SQL para obtener los instructores relacionados con una categoría de licencia
    $sql = "SELECT id, nombres, apellidos FROM instructores WHERE id_categoria_licencia = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $categoriaLicencia);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $instructores = [];
        while ($row = $result->fetch_assoc()) {
            $instructores[] = $row;
        }
        echo json_encode($instructores); // Devuelve los instructores como JSON
    } else {
        echo json_encode(['error' => 'Error al obtener datos de los instructores.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Solicitud no válida.']);
}
?>
