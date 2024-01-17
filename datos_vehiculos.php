<?php
// Incluye la configuración de la base de datos
include 'layouts/config.php';

// Preparar la consulta SQL para obtener todos los vehículos con la información relacionada
$sql = "SELECT v.*, l.categoria_licencia, c.nombre_categoria 
        FROM vehiculos v 
        LEFT JOIN licencias l ON v.id_licencia_vehiculo = l.id_licencia
        LEFT JOIN categorias c ON v.id_categoria = c.id_categoria";

$result = $conn->query($sql);

$vehiculos = array();

// Verifica si la consulta devolvió filas
if ($result->num_rows > 0) {
    // Recorre los resultados y los añade al array de vehículos
    while ($row = $result->fetch_assoc()) {
        $vehiculos[] = $row;
    }
    // Devuelve los vehículos en formato JSON
    echo json_encode($vehiculos);
} else {
    // Devuelve un array vacío si no hay vehículos
    echo json_encode($vehiculos);
}

// Cierra la conexión a la base de datos
$conn->close();
?>
