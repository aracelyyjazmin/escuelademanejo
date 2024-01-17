<?php
// Conectarse a la base de datos
include 'layouts/config.php';

// Verificar conexión
if ($conexion->connect_error) {
    die("La conexión falló: " . $conexion->connect_error);
}

// Consultar la base de datos
$query = "SELECT l.id_licencia, l.categoria_licencia, c.nombre_categoria FROM licencias l JOIN categorias c ON l.id_categoria = c.id_categoria";
$result = $conexion->query($query);

// Crear un array para almacenar los resultados
$categorias = array();
while($row = $result->fetch_assoc()) {
    $categorias[] = array(
        "id_licencia" => $row["id_licencia"],
        "categoria_licencia" => $row["categoria_licencia"],
        "nombre_categoria" => $row["nombre_categoria"]
    );
}

// Devolver el array como JSON
echo json_encode($categorias);

// Cerrar conexión
$conexion->close();
?>
