<?php
include 'layouts/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $instructorId = $_POST['idInstructor'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $documento = $_POST['documento'];
    $numero = $_POST['numero'];
    $telefono = $_POST['telefono'];
    $categoriaLicencia = $_POST['categoriaLicencia'];
    $email = $_POST['email'];

    // Realizar la actualización en la base de datos
    $sql = "UPDATE instructores SET nombres = ?, apellidos = ?, documento = ?, numero = ?, telefono = ?, id_categoria_licencia = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $nombres, $apellidos, $documento, $numero, $telefono, $categoriaLicencia, $email, $idInstructor);

    if ($stmt->execute()) {
        $response = ['success' => true];
    } else {
        $response = ['error' => 'Error al actualizar el instructor.'];
    }

    $stmt->close();
} else {
    $response = ['error' => 'Método de solicitud no válido.'];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
