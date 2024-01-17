<?php
include 'layouts/session.php';
include 'layouts/main.php';
include 'layouts/config.php';

// Verificar si se ha enviado un ID a través de la URL
$id_categoria = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Variables para almacenar la información de la categoría
$nombre_categoria = '';
$categoria_licencia = '';

// Mensajes de error o éxito
$error = '';
$success = '';

// Si se ha enviado un ID válido, obtener la información de la categoría
if ($id_categoria > 0) {
    $stmt = $conn->prepare("SELECT c.nombre_categoria, l.categoria_licencia FROM categorias c LEFT JOIN licencias l ON c.id_categoria = l.id_categoria WHERE c.id_categoria = ?");
    $stmt->bind_param("i", $id_categoria);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $categoria = $result->fetch_assoc();
        $nombre_categoria = $categoria['nombre_categoria'];
        $categoria_licencia = $categoria['categoria_licencia'];
    } else {
        $error = "No se encontró la categoría con el ID especificado.";
    }
}

// Procesar la información del formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_categoria = $_POST['nombre_categoria'];
    $categoria_licencia = $_POST['categoria_licencia'];

    // Validar los campos aquí...

    // Si no hay errores, intentar actualizar la categoría
    if (empty($error)) {
        $stmt = $conn->prepare("UPDATE categorias SET nombre_categoria = ? WHERE id_categoria = ?");
        $stmt->bind_param("si", $nombre_categoria, $id_categoria);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            $success = "Categoría actualizada correctamente.";
        } else {
            $error = "No se pudo actualizar la categoría.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Incluir los archivos CSS necesarios -->
</head>
<body>
    <!-- Incluir el menú de navegación -->

    <div class="container">
        <h2>Editar Categoría</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="editar_categoria.php?id=<?php echo $id_categoria; ?>" method="post">
            <div class="form-group">
                <label for="nombreCategoria">Nombre de la Categoría</label>
                <input type="text" class="form-control" name="nombre_categoria" id="nombreCategoria" value="<?php echo htmlspecialchars($nombre_categoria); ?>" required>
            </div>
            <div class="form-group">
                <label for="categoriaLicencia">Categoría de Licencia</label>
                <input type="text" class="form-control" name="categoria_licencia" id="categoriaLicencia" value="<?php echo htmlspecialchars($categoria_licencia); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="registro_categoria.php" class="btn btn-default">Cancelar</a>
        </form>
    </div>

    <!-- Incluir el pie de página y los scripts JS -->
</body>
</html>
