<?php
// Incluir el archivo de configuración de la base de datos y cualquier otro archivo necesario
include 'layouts/config.php';

// Obtener el valor de la categoría seleccionada
$category = $_POST['category'];

// Consulta SQL para mostrar toda la tabla de estudiantes con acciones
$sql = "SELECT estudiantes.*, licencias.categoria_licencia AS categoria_postular 
        FROM estudiantes 
        LEFT JOIN licencias ON estudiantes.id_licencia_postula = licencias.id_licencia";

// Si se selecciona una categoría específica, realizar la búsqueda por esa categoría
if (!empty($category) && $category !== "-- Seleccione una Categoría --") {
    $sql .= " WHERE licencias.categoria_licencia = '$category'";
}

$result = $conn->query($sql);

// Generar el HTML de la tabla con los resultados de la búsqueda
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['nombre']) . '</td>';
        echo '<td>' . htmlspecialchars($row['apellido']) . '</td>';
        echo '<td>' . htmlspecialchars($row['categoria_postular']) . '</td>';
        echo '<td>' . htmlspecialchars($row['proceso']) . '</td>';
        echo '<td>';
        // Agregar aquí los botones de acciones
        echo '<button type="button" class="btn btn-primary edit-student-btn" data-id="' . $row['id'] . '">
                <i class="fas fa-edit fa-lg"></i> <!-- Icono de edición con tamaño grande (fa-lg) -->
            </button>';

        echo '<button type="button" class="delete-student btn btn-danger btn" data-id="' . $row['id'] . '">
                <i class="fas fa-trash fa-lg"></i> <!-- Icono de eliminación con tamaño grande (fa-lg) -->
            </button>';

        echo '<button type="button" class="btn btn-info view-student-btn" data-id="' . $row['id'] . '">
                <i class="fas fa-eye fa-lg"></i> <!-- Icono de visualización con tamaño grande (fa-lg) -->
            </button>';
        echo '</td>';
        echo '</tr>';
    }
} else {
    // Si no se encontraron resultados
    echo '<tr><td colspan="5">No se encontraron estudiantes que coincidan con los criterios de búsqueda.</td></tr>';
}

// Cerrar la conexión a la base de datos
$conn->close();

?>
