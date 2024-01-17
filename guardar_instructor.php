<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'layouts/config.php';

// Definir los campos requeridos
$requiredFields = ['nombres', 'apellidos', 'documento', 'numero', 'telefono', 'email', 'categoriaLicencia'];

// Variable para almacenar posibles errores
$errores = [];

// Función para limpiar los datos del formulario
function limpiar($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Solo procesar en caso de método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recorrer los campos requeridos y validar
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $errores[] = "El campo $field es obligatorio.";
        } else {
            $_POST[$field] = limpiar($_POST[$field]);
        }
    }

    if (count($errores) == 0) {
        $nombres = $_POST['nombres'];
        $apellidos = $_POST['apellidos'];
        $documento = $_POST['documento'];
        $numero = $_POST['numero'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $idCategoriaLicencia = $_POST['categoriaLicencia'];
        $idInstructor = $_POST['idInstructor'] ?? '';

        if (empty($idInstructor)) {
            $sql = "INSERT INTO instructores (nombres, apellidos, documento, numero, telefono, email, id_categoria_licencia) VALUES (?, ?, ?, ?, ?, ?, ?)";
        } else {
            $sql = "UPDATE instructores SET nombres = ?, apellidos = ?, documento = ?, numero = ?, telefono = ?, email = ?, id_categoria_licencia = ? WHERE id = ?";
        }

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo json_encode(['error' => "Error al preparar la consulta: " . $conn->error]);
            exit;
        }

        if (empty($idInstructor)) {
            $stmt->bind_param("ssssssi", $nombres, $apellidos, $documento, $numero, $telefono, $email, $idCategoriaLicencia);
        } else {
            $stmt->bind_param("ssssssii", $nombres, $apellidos, $documento, $numero, $telefono, $email, $idCategoriaLicencia, $idInstructor);
        }

        if ($stmt->execute()) {
            $response = [
                'id' => isset($idInstructor) ? $idInstructor : $stmt->insert_id,
                'nombres' => $nombres,
                'apellidos' => $apellidos
            ];
            echo json_encode($response);
        } else {
            echo json_encode(['error' => "Error al guardar los datos: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => "Error: Faltan campos obligatorios - " . implode(', ', $errores)]);
    }
    $conn->close();
} else {
    echo json_encode(['error' => "Método no permitido"]);
}
?>
