<?php
include 'layouts/config.php';
session_start(); // Iniciar la sesión al principio del archivo

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera los datos del formulario
    $moduleName = $_POST['moduleName'];
    $circulationClasses = $_POST['circulationClasses'];
    $circuitRoadways = $_POST['circuitRoadways'];

    // Prepara la consulta SQL
    if ($stmt = $conn->prepare("INSERT INTO cursos (nombre_modulo, clases_circulacion_vias, circuito_vias) VALUES (?, ?, ?)")) {
        // Vincula los parámetros y ejecuta
        $stmt->bind_param("sss", $moduleName, $circulationClasses, $circuitRoadways);
        $stmt->execute();

        // Verifica si la inserción fue exitosa
        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "Curso agregado con éxito.";
        } else {
            $_SESSION['error'] = "Error al agregar curso: " . $conn->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Error al preparar la consulta: " . $conn->error;
    }

    $conn->close();

    // Redirigir a una página específica
    header("Location: registro_cursos.php"); // Ajusta esto según la estructura de tu sitio
    exit();
}
