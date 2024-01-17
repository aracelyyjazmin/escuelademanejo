<?php
include 'layouts/config.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos

$response = new stdClass();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id_categoria = $_POST['id'];

    // Inicia una transacción
    $conn->begin_transaction();

    try {
        // Primero elimina las licencias asociadas
        $deleteLicenciasQuery = "DELETE FROM licencias WHERE id_categoria = ?";
        $stmtLicencias = $conn->prepare($deleteLicenciasQuery);
        $stmtLicencias->bind_param("i", $id_categoria);
        $stmtLicencias->execute();
        $stmtLicencias->close();

        // Luego elimina la categoría
        $deleteCategoriaQuery = "DELETE FROM categorias WHERE id_categoria = ?";
        $stmtCategoria = $conn->prepare($deleteCategoriaQuery);
        $stmtCategoria->bind_param("i", $id_categoria);
        $stmtCategoria->execute();
        $stmtCategoria->close();

        // Si todo va bien, haz commit a la transacción
        $conn->commit();

        $response->success = true;
        $response->message = "Categoría y licencias asociadas eliminadas con éxito.";
    } catch (Exception $e) {
        // Si hay un error, haz rollback a la transacción
        $conn->rollback();

        $response->success = false;
        $response->message = "Error al eliminar la categoría: " . $e->getMessage();
    }
} else {
    $response->success = false;
    $response->message = "Petición no válida.";
}

$conn->close();

// Devuelve la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
