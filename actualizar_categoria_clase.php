<?php
include 'layouts/config.php'; // Asegúrate de que este es el camino correcto hacia tu archivo de configuración

$documento = isset($_GET['documento']) ? $_GET['documento'] : '';

$response = [];

if ($documento != '') {
    // Preparar la consulta SQL para obtener la categoría a la que postula el estudiante
    $query = "SELECT e.id_licencia_postula, l.categoria_licencia 
              FROM estudiantes e 
              JOIN licencias l ON e.id_licencia_postula = l.id_licencia 
              WHERE e.numero = ?"; // Asegúrate de que 'numero' es el campo correcto en tu base de datos

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $documento);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $response['success'] = true;
            $response['categoriaPostulaId'] = $row['id_licencia_postula'];
            $response['categoriaPostulaNombre'] = $row['categoria_licencia'];
        } else {
            $response['success'] = false;
            $response['error'] = "No se encontró la categoría a postular para el documento proporcionado.";
        }

        $stmt->close();
    } else {
        $response['success'] = false;
        $response['error'] = "Error al preparar la consulta SQL.";
    }
} else {
    $response['success'] = false;
    $response['error'] = "No se proporcionó un documento válido.";
}

$conn->close();

echo json_encode($response);
?>
