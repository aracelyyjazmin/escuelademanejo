<?php
include 'layouts/config.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_categoria = $_POST['id_categoria'] ?? null;
    $nombre_categoria = $_POST['nombreCategoria'] ?? '';
    $categoria_licencia = $_POST['categoriaLicencia'] ?? '';

    if ($id_categoria) {
        // Preparar la consulta de actualización para la tabla 'categorias'
        $updateCategoria = "UPDATE categorias SET nombre_categoria = ? WHERE id_categoria = ?";
        $updateLicencia = "UPDATE licencias SET categoria_licencia = ? WHERE id_categoria = ?";

        // Iniciar transacción
        $conn->begin_transaction();

        // Actualizar 'categorias'
        if ($stmt = $conn->prepare($updateCategoria)) {
            $stmt->bind_param("si", $nombre_categoria, $id_categoria);
            $stmt->execute() or die("Error al actualizar la categoría: " . htmlspecialchars($stmt->error));
            $stmt->close();
        } else {
            // Manejo de errores durante la preparación
            die("Error al preparar la consulta en categorías: " . htmlspecialchars($conn->error));
        }

        // Actualizar 'licencias'
        if ($stmt = $conn->prepare($updateLicencia)) {
            $stmt->bind_param("si", $categoria_licencia, $id_categoria);
            $stmt->execute() or die("Error al actualizar la licencia: " . htmlspecialchars($stmt->error));
            $stmt->close();
        } else {
            // Manejo de errores durante la preparación
            die("Error al preparar la consulta en licencias: " . htmlspecialchars($conn->error));
        }

        // Si todo va bien, confirmar la transacción
        $conn->commit();

        echo "La categoría y la licencia se han actualizado correctamente.";

    } else {
        echo "No se proporcionó el ID de la categoría.";
    }
    $conn->close();
} else {
    echo "Método de solicitud no permitido.";
}
?>
