<?php
// Incluye tu archivo de configuración de base de datos
include 'layouts/config.php';

// Verifica si se ha enviado el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera los datos del formulario
    $studentId = $_POST['studentId'];
    $nombre = $_POST['nombreEdit'];
    $apellido = $_POST['apellidoEdit'];
    $tipoDocumento = $_POST['documentoEdit'];
    $numeroDocumento = $_POST['numeroDocumentoEdit'];
    $telefono = $_POST['telefonoEdit'];
    $email = $_POST['emailEdit'];
    $categoriaActual = $_POST['categoriaActualEdit'];
    $categoriaPostula = $_POST['categoriaPostulaEdit'];
    $gradoInstruccion = $_POST['gradoInstruccionEdit'];
    $fechaNacimiento = $_POST['fechaNacimientoEdit'];
    $domicilio = $_POST['domicilioEdit'];
    $proceso = $_POST['procesoEdit'];

    // Consulta SQL para actualizar los datos del estudiante
    $sql = "UPDATE estudiantes SET
                nombre = ?,
                apellido = ?,
                tipo_documento = ?,
                numero_documento = ?,
                telefono = ?,
                email = ?,
                categoria_actual = ?,
                categoria_postula = ?,
                grado_instruccion = ?,
                fecha_nacimiento = ?,
                domicilio = ?,
                proceso = ?
            WHERE id_estudiante = ?";

    // Prepara la consulta
    $stmt = $conn->prepare($sql);

    // Vincula los parámetros
    $stmt->bind_param("ssssssssssssi", $nombre, $apellido, $tipoDocumento, $numeroDocumento, $telefono, $email, $categoriaActual, $categoriaPostula, $gradoInstruccion, $fechaNacimiento, $domicilio, $proceso, $studentId);

    // Ejecuta la consulta
    if ($stmt->execute()) {
        // La actualización se realizó con éxito
        // Puedes redirigir al usuario a la página de lista de estudiantes o mostrar un mensaje de éxito
        header('Location: lista_estudiantes.php'); // Cambia esto a la URL deseada
        exit();
    } else {
        // La actualización falló
        echo 'Error al actualizar los datos del estudiante.';
    }

    // Cierra la conexión a la base de datos
    $stmt->close();
    $conn->close();
} else {
    // El formulario no se envió correctamente
    echo 'Error: El formulario no se envió correctamente.';
}
?>
