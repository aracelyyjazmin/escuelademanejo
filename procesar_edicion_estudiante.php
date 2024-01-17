<?php
// Incluir archivos de configuración y conexión a la base de datos si es necesario
include 'layouts/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['estudiante_id'])) {
    // Obtener los datos del formulario POST
    $estudianteId = $_POST['estudiante_id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $documento = $_POST['documento'];
    $numero = $_POST['numero'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $categoriaActual = $_POST['categoriaActual'];
    $categoriaPostula = $_POST['categoriaPostula'];

    // Validar los datos (puedes agregar tu propia lógica de validación aquí)

    // Actualizar los datos del estudiante en la base de datos
    $sql = "UPDATE estudiantes SET nombre = ?, apellido = ?, documento = ?, numero = ?, telefono = ?, email = ?, categoria_actual = ?, categoria_postula = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssiii", $nombre, $apellido, $documento, $numero, $telefono, $email, $categoriaActual, $categoriaPostula, $estudianteId);

    if ($stmt->execute()) {
        // La actualización fue exitosa
        echo "Estudiante actualizado exitosamente.";
    } else {
        // La actualización falló
        echo "Error al actualizar el estudiante: " . $conn->error;
    }

    // Cerrar la conexión a la base de datos si es necesario
    $stmt->close();
    $conn->close();
} else {
    // Manejar el caso en que no se haya enviado un formulario POST válido
    echo "Acceso no autorizado.";
}
?>
