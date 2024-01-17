<?php
include 'layouts/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreCategoria = $_POST['nombreCategoria']; // Asumiendo que este es el nombre del campo en el formulario
    $categoriaLicencia = $_POST['categoriaLicencia'];

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Insertar o buscar el ID de categoría
        $sql = "INSERT INTO categorias (nombre_categoria) VALUES (?) ON DUPLICATE KEY UPDATE id_categoria=LAST_INSERT_ID(id_categoria)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nombreCategoria);
        $stmt->execute();
        $categoriaId = $conn->insert_id;
        $stmt->close();

        // Insertar en licencias
        $sql = "INSERT INTO licencias (id_categoria, categoria_licencia) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $categoriaId, $categoriaLicencia);
        $stmt->execute();
        $stmt->close();

        // Comprometer la transacción
        $conn->commit();

        // Mensaje de éxito
        $_SESSION['mensaje'] = "Registro completado con éxito.";
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();

        // Mensaje de error
        $_SESSION['error'] = "Error al registrar: " . $e->getMessage();
    }

    // Cerrar la conexión
    $conn->close();

    // Redireccionar de vuelta a la página principal
    header("Location: registro_categoria.php");
    exit();
}
?>
