<?php
// Incluir archivos de configuración y conexión a la base de datos si es necesario
include 'layouts/config.php';

if (isset($_GET['id'])) {
    // Obtener el ID del estudiante desde la solicitud GET
    $studentId = $_GET['id'];

    // Realizar una consulta a la base de datos para obtener los datos del estudiante con el ID proporcionado
    $sql = "SELECT * FROM estudiantes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        // En este punto, tienes los datos del estudiante que deseas editar en el array $student
        // A continuación, puedes construir el formulario de edición utilizando estos datos
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Editar Estudiante</title>
        <!-- Incluir aquí tus etiquetas meta, enlaces a CSS, y otros elementos del encabezado que necesites -->
    </head>
    <body>
        <!-- Aquí comienza el formulario de edición -->
        <form id="editStudentForm" method="post" action="procesar_edicion_estudiante.php" enctype="multipart/form-data">
            <input type="hidden" name="estudiante_id" value="<?= $student['id'] ?>">

            <!-- Campos del formulario -->
            <div>
                <label for="edit_nombre">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="edit_nombre" value="<?= htmlspecialchars($student['nombre']) ?>">
            </div>

            <div>
                <label for="edit_apellido">Apellido</label>
                <input type="text" class="form-control" name="apellido" id="edit_apellido" value="<?= htmlspecialchars($student['apellido']) ?>">
            </div>

            <div>
                <label for="edit_documento">Tipo de Documento</label>
                <select class="form-control" name="documento" id="edit_documento">
                    <option value="DNI" <?= ($student['documento'] === 'DNI') ? 'selected' : '' ?>>DNI</option>
                    <option value="CarnetExtranjeria" <?= ($student['documento'] === 'CarnetExtranjeria') ? 'selected' : '' ?>>Carnet de Extranjería</option>
                    <option value="PermisoTemporal" <?= ($student['documento'] === 'PermisoTemporal') ? 'selected' : '' ?>>Permiso Temporal</option>
                    <option value="Pasaporte" <?= ($student['documento'] === 'Pasaporte') ? 'selected' : '' ?>>Pasaporte</option>
                    <option value="CarnetSolicitante" <?= ($student['documento'] === 'CarnetSolicitante') ? 'selected' : '' ?>>Carnet de Solicitante</option>
                </select>
            </div>

            <div>
                <label for="edit_numeroDocumento">Número de Documento</label>
                <input type="text" class="form-control" name="numero" id="edit_numeroDocumento" value="<?= htmlspecialchars($student['numero']) ?>">
            </div>

            <div>
                <label for="edit_telefono">Teléfono</label>
                <input type="text" class="form-control" name="telefono" id="edit_telefono" value="<?= htmlspecialchars($student['telefono']) ?>">
            </div>

            <div>
                <label for="edit_email">E-mail</label>
                <input type="email" class="form-control" name="email" id="edit_email" value="<?= htmlspecialchars($student['email']) ?>">
            </div>

            <div>
                <label for="edit_categoriaActual">Categoría Actual</label>
                <select class="form-control" name="categoriaActual" id="edit_categoriaActual">
                    <!-- Opciones de categoría actual -->
                    <?php
                    $sqlLicencias = "SELECT id_licencia, categoria_licencia FROM licencias";
                    $resultLicencias = $conn->query($sqlLicencias);

                    if ($resultLicencias->num_rows > 0) {
                        while ($row = $resultLicencias->fetch_assoc()) {
                            $selected = ($student['categoriaActual'] == $row['id_licencia']) ? 'selected' : '';
                            echo "<option value='{$row['id_licencia']}' {$selected}>{$row['categoria_licencia']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="edit_categoriaPostula">Categoría a Postular</label>
                <select class="form-control" name="categoriaPostula" id="edit_categoriaPostula">
                    <!-- Opciones de categoría a postular -->
                    <?php
                    $sqlLicencias = "SELECT id_licencia, categoria_licencia FROM licencias";
                    $resultLicencias = $conn->query($sqlLicencias);

                    if ($resultLicencias->num_rows > 0) {
                        while ($row = $resultLicencias->fetch_assoc()) {
                            $selected = ($student['categoriaPostula'] == $row['id_licencia']) ? 'selected' : '';
                            echo "<option value='{$row['id_licencia']}' {$selected}>{$row['categoria_licencia']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <!-- Repite este proceso para los demás campos del formulario si es necesario -->

            <!-- Botones para guardar y cerrar el formulario -->
            <div>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </form>
    </body>
    </html>
<?php
    } else {
        // Manejar el caso en que no se encuentre el estudiante con el ID proporcionado
        echo 'Estudiante no encontrado.';
    }
} else {
    // Manejar el caso en que no se proporcionó un ID de estudiante válido en la solicitud GET
    echo 'ID de estudiante no válido.';
}
?>
