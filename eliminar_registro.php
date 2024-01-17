<?php
// Incluir el archivo de configuración de la base de datos
include_once 'layouts/config.php';

// Verificar si el ID se ha enviado
if (isset($_POST['id'])) {
    $idProgramacion = $_POST['id'];

    // Preparar la consulta SQL para eliminar la programación
    $sql = "DELETE FROM programacion_clases WHERE id_programacion = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Vincular variables a la declaración preparada como parámetros
        $stmt->bind_param("i", $param_id);

        // Establecer parámetros
        $param_id = $idProgramacion;

        // Intentar ejecutar la declaración preparada
        if ($stmt->execute()) {
            echo "Clase eliminada correctamente.";
        } else {
            echo "Error: No se pudo ejecutar $sql. " . $conn->error;
        }

        // Cerrar declaración
        $stmt->close();
    } else {
        echo "Error: No se pudo preparar la consulta $sql. " . $conn->error;
    }
} else {
    echo "Error: ID no proporcionado.";
}

// Cerrar conexión
$conn->close();
?>
