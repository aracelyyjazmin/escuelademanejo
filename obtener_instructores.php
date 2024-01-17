<?php
// Incluir el archivo de configuración de la base de datos
include 'layouts/config.php';

// Realizar una consulta SQL para obtener los instructores
$sql = "SELECT id, nombres, apellidos, categoria_licencia FROM instructores";
$resultado = $conn->query($sql);

// Comprobar si se obtuvieron resultados
if ($resultado->num_rows > 0) {
    echo '<table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Categoría de Licencia</th>
                </tr>
            </thead>
            <tbody>';

    // Iterar a través de los resultados y crear filas de tabla
    while ($fila = $resultado->fetch_assoc()) {
        echo '<tr>
                <td>' . htmlspecialchars($fila['nombres']) . '</td>
                <td>' . htmlspecialchars($fila['apellidos']) . '</td>
                <td>' . htmlspecialchars($fila['categoria_licencia']) . '</td>
              </tr>';
    }

    echo '</tbody></table>';
} else {
    echo 'No se encontraron instructores.';
}


// Cerrar la conexión a la base de datos
$conn->close();
?>
