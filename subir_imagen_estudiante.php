<?php
include 'layouts/config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

function esImagenValida($archivo) {
    $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
    $tamañoMaximo = 5000000; // 5MB

    if (!getimagesize($archivo['tmp_name']) || !in_array($archivo['type'], $tiposPermitidos) || $archivo['size'] > $tamañoMaximo) {
        return false;
    }

    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen_estudiante']) && isset($_POST['estudiante_id'])) {
    $estudianteId = $_POST['estudiante_id'];
    $archivo = $_FILES['imagen_estudiante'];

    $rutaCarpetaSubidas = 'uploads/';
    if (!is_dir($rutaCarpetaSubidas)) {
        if (!mkdir($rutaCarpetaSubidas, 0755, true)) {
            echo json_encode(['error' => 'No se pudo crear el directorio de subidas.']);
            exit;
        }
    }

    $nombreArchivo = $estudianteId . '_' . time() . '_' . basename($archivo['name']);
    $rutaSubida = $rutaCarpetaSubidas . $nombreArchivo;

    if (esImagenValida($archivo)) {
        if (move_uploaded_file($archivo['tmp_name'], $rutaSubida)) {
            $consulta = "UPDATE estudiantes SET foto = ? WHERE id = ?";
            if ($stmt = $conn->prepare($consulta)) {
                $stmt->bind_param("si", $nombreArchivo, $estudianteId);
                if ($stmt->execute()) {
                    echo json_encode(['success' => 'La imagen se ha subido y registrado correctamente.']);
                } else {
                    echo json_encode(['error' => 'Error en la actualización de la base de datos: ' . $stmt->error]);
                }
                $stmt->close();
            } else {
                echo json_encode(['error' => 'Error al preparar la declaración SQL.']);
            }
        } else {
            echo json_encode(['error' => 'Hubo un error al mover el archivo subido.']);
        }
    } else {
        echo json_encode(['error' => 'El archivo no es una imagen válida o excede el tamaño máximo permitido.']);
    }
} else {
    echo json_encode(['error' => 'No se recibió ningún archivo o identificador de estudiante.']);
}
?>
