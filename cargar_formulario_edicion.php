<?php
include 'layouts/config.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos

$id_categoria = $_GET['id'] ?? 0; // Obtiene el ID de la categoría de la solicitud AJAX

try {
    // Preparar y ejecutar la consulta para obtener los datos de la categoría y su licencia
    $query = "SELECT c.nombre_categoria, l.categoria_licencia 
              FROM categorias c 
              LEFT JOIN licencias l ON c.id_categoria = l.id_categoria 
              WHERE c.id_categoria = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        throw new Exception('Error en prepare: ' . $conn->error);
    }

    $stmt->bind_param("i", $id_categoria);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($categoria = $result->fetch_assoc()) {
        // Crear el formulario con los datos de la categoría
        echo '<form id="editCategoryForm">';
        echo '<div class="form-group">';
        echo '<label for="nombreCategoria">Nombre de la Categoría</label>';
        echo '<input type="text" class="form-control" name="nombreCategoria" id="nombreCategoria" value="' . htmlspecialchars($categoria['nombre_categoria']) . '">';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label for="categoriaLicencia">Categoría de Licencia</label>';
        echo '<input type="text" class="form-control" name="categoriaLicencia" id="categoriaLicencia" value="' . htmlspecialchars($categoria['categoria_licencia'] ?? '') . '">';
        echo '</div>';
        echo '<input type="hidden" name="id_categoria" id="id_categoria" value="' . $id_categoria . '">';
        echo '<button type="submit" class="btn btn-primary">Guardar cambios</button>';
        echo '</form>';
    } else {
        echo 'No se encontraron datos para la categoría seleccionada.';
    }
} catch (Exception $e) {
    // Manejo de excepciones
    echo 'Error: ' . $e->getMessage();
}
$conn->close();
?>
