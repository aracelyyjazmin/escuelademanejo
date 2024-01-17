<?php
// Asegúrate de que los archivos de configuración y conexión a la base de datos están incluidos
include 'layouts/session.php';
include 'layouts/config.php';

// Obtener la categoría de licencia de la solicitud
$categoriaLicencia = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// Preparar la consulta SQL para obtener los instructores de la categoría específica
$sql = "SELECT instructores.*, licencias.categoria_licencia FROM instructores 
        LEFT JOIN licencias ON instructores.id_categoria_licencia = licencias.id_licencia
        WHERE licencias.categoria_licencia = ?";

// Preparar la sentencia
$stmt = $conn->prepare($sql);

// Si hubo un error al preparar la sentencia, enviar una respuesta de error
if (!$stmt) {
    echo "Error: " . $conn->error;
    exit;
}

// Vincular parámetros y ejecutar la sentencia
$stmt->bind_param("s", $categoriaLicencia);
$stmt->execute();
$result = $stmt->get_result();

// Recorrer los resultados y construir el HTML de la respuesta
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['nombres']) . "</td>";
    echo "<td>" . htmlspecialchars($row['apellidos']) . "</td>";
    echo "<td>" . htmlspecialchars($row['categoria_licencia']) . "</td>";
    echo "<td>";
    // Botones de Acciones con Iconos
    echo '<button type="button" class="btn btn-primary edit-instructor-btn" data-id="' . $row['id'] . '">';
    echo '<i class="fas fa-edit fa-lg"></i></button>'; // Icono de editar
    echo '<button type="button" class="btn btn-danger delete-instructor-btn" data-id="' . $row['id'] . '">';
    echo '<i class="fas fa-trash fa-lg"></i></button>'; // Icono de eliminar
    echo "</td>";
    echo "</tr>";
}

// Cerrar la sentencia y la conexión a la base de datos
$stmt->close();
$conn->close();
?>
