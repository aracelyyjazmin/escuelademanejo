<?php
include 'layouts/config.php'; // Incluye la configuración de la conexión a la base de datos

function limpiar($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validarArchivo($archivo) {
    return $archivo['error'] == 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = limpiar($_POST['nombre']);
    $apellido = limpiar($_POST['apellido']);
    $documento = limpiar($_POST['documento']); // Ajustado para coincidir con la columna 'documento'
    $numero = limpiar($_POST['numero']); // Ajustado para coincidir con la columna 'numero'
    $telefono = limpiar($_POST['telefono']);
    $email = limpiar($_POST['email']);
    $gradoInstruccion = limpiar($_POST['gradoInstruccion']);
    $fechaNacimiento = limpiar($_POST['fechaNacimiento']);
    $domicilio = limpiar($_POST['domicilio']);
    $proceso = limpiar($_POST['proceso']);
    $id_licencia_actual = limpiar($_POST['categoriaActual']);
    $id_licencia_postula = limpiar($_POST['categoriaPostula']);

    $rutaFoto = '';
    if (isset($_FILES['foto']) && validarArchivo($_FILES['foto'])) {
        $rutaDirectorio = __DIR__ . "/uploads/";
        if (!is_dir($rutaDirectorio)) {
            mkdir($rutaDirectorio, 0777, true);
        }
        $nombreArchivo = basename($_FILES['foto']['name']);
        $rutaFoto = $rutaDirectorio . $nombreArchivo;
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $rutaFoto)) {
            echo "Error al subir el archivo.";
            exit;
        }
    }

    $sql = "INSERT INTO estudiantes (nombre, apellido, documento, numero, telefono, email, grado_instruccion, fecha_nacimiento, domicilio, proceso, foto, id_licencia_actual, id_licencia_postula) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssssssssi", $nombre, $apellido, $documento, $numero, $telefono, $email, $gradoInstruccion, $fechaNacimiento, $domicilio, $proceso, $rutaFoto, $id_licencia_actual, $id_licencia_postula);
        if (!$stmt->execute()) {
            echo "Error al insertar en la base de datos: " . $stmt->error;
            exit;
        }
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $conn->error;
        exit;
    }

    $conn->close();
    header("Location: registro_estudiante.php");
    exit();
}
?>
