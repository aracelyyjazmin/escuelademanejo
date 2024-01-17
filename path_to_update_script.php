<?php
// Incluir el archivo de configuración de conexión a la base de datos
include 'layouts/config.php';

// Iniciar sesión para manejar mensajes y errores con sesiones
session_start();

// Comprobar si el método de solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar y validar los datos del formulario
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $moduleName = isset($_POST['moduleName']) ? trim($_POST['moduleName']) : '';
    $circulationClasses = isset($_POST['circulationClasses']) ? trim($_POST['circulationClasses']) : '';
    $circuitRoadways = isset($_POST['circuitRoadways']) ? trim($_POST['circuitRoadways']) : '';

    // Aquí puedes agregar validaciones adicionales para los datos recibidos

    // Preparar la consulta SQL para actualizar los datos
    $sql = "UPDATE cursos SET nombre_modulo = ?, clases_circulacion_vias = ?, circuito_vias = ? WHERE id_curso = ?"; // Asegúrate de que 'id_curso' es el nombre correcto de la columna de identificación

    // Preparar la declaración preparada
    if ($stmt = $conn->prepare($sql)) {
        // Vincular los parámetros y ejecutar
        $stmt->bind_param("sssi", $moduleName, $circulationClasses, $circuitRoadways, $id);
        
        if ($stmt->execute()) {
            // Verificar si la actualización fue exitosa
            if ($stmt->affected_rows > 0) {
                $_SESSION['message'] = "Curso actualizado con éxito.";
            } else {
                // Si no hay filas afectadas, puede ser que los datos sean los mismos o que la fila no exista
                $_SESSION['error'] = "No se encontró el curso o no se realizaron cambios.";
            }
        } else {
            // Error al ejecutar la consulta
            $_SESSION['error'] = "Error al actualizar el curso: " . $stmt->error;
        }

        // Cerrar la declaración preparada
        $stmt->close();
    } else {
        // Error al preparar la consulta
        $_SESSION['error'] = "Error al preparar la actualización del curso.";
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
    
    // Redirigir de nuevo a la página de registro de cursos
    header("Location: registro_cursos.php");
    exit;
} else {
    // Método de solicitud incorrecto, no es POST
    $_SESSION['error'] = "Acceso inválido.";
    header("Location: registro_cursos.php");
    exit;
}
?>
