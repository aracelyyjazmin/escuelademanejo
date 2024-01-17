<?php
include 'layouts/config.php';

$response = ['error' => 'Ocurrió un error inesperado.']; // Mensaje de error predeterminado

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idInstructor = isset($_POST['idInstructor']) ? intval($_POST['idInstructor']) : null;

    if ($idInstructor) {
        // Realizar la eliminación en la base de datos
        $sql = "DELETE FROM instructores WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("i", $idInstructor);
            if ($stmt->execute()) {
                $response = ['success' => true];
            } else {
                $response = ['error' => 'Error al eliminar el instructor.'];
            }
            $stmt->close();
        } else {
            $response = ['error' => 'Error al preparar la consulta.'];
        }
    } else {
        $response = ['error' => 'ID del instructor no proporcionado.'];
    }
} else {
    $response = ['error' => 'Método de solicitud no válido.'];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
