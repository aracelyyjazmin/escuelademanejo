<?php
// Incluir el archivo de configuración de la base de datos
include_once 'layouts/config.php';

// Consulta para obtener el número de estudiantes registrados
$queryStudentsCount = "SELECT COUNT(*) AS total_students FROM estudiantes";
$resultStudentsCount = mysqli_query($conn, $queryStudentsCount);

if (!$resultStudentsCount) {
    // Manejar el error de la consulta, si lo hay
    $error = mysqli_error($conn);
    echo json_encode(["error" => $error]);
    exit;
}

$rowStudentsCount = mysqli_fetch_assoc($resultStudentsCount);

// Consulta para obtener el número de vehículos registrados
$queryVehiclesCount = "SELECT COUNT(*) AS total_vehicles FROM vehiculos";
$resultVehiclesCount = mysqli_query($conn, $queryVehiclesCount);

if (!$resultVehiclesCount) {
    // Manejar el error de la consulta, si lo hay
    $error = mysqli_error($conn);
    echo json_encode(["error" => $error]);
    exit;
}

$rowVehiclesCount = mysqli_fetch_assoc($resultVehiclesCount);

// Consulta para obtener las cantidades de clases programadas por categoría
$queryClassesByCategory = "SELECT categoria_licencia, COUNT(*) AS cantidad_clases FROM programacion_clases pc
    JOIN estudiantes e ON pc.id_estudiante = e.id
    JOIN licencias l ON e.id_licencia_actual = l.id_licencia
    GROUP BY categoria_licencia";

$resultClassesByCategory = mysqli_query($conn, $queryClassesByCategory);

if (!$resultClassesByCategory) {
    // Manejar el error de la consulta, si lo hay
    $error = mysqli_error($conn);
    echo json_encode(["error" => $error]);
    exit;
}

// Crear un array para almacenar las cantidades de clases por categoría
$classesByCategory = array();

while ($rowClass = mysqli_fetch_assoc($resultClassesByCategory)) {
    $categoria = $rowClass['categoria_licencia'];
    $cantidadClases = $rowClass['cantidad_clases'];
    $classesByCategory[$categoria] = $cantidadClases;
}

// Crear un array con todas las estadísticas
$statistics = array(
    "total_students" => $rowStudentsCount['total_students'],
    "total_vehicles" => $rowVehiclesCount['total_vehicles'],
    "classes_by_category" => $classesByCategory
);
// Consulta para obtener el número total de instructores registrados
$queryInstructorsCount = "SELECT COUNT(*) AS total_instructors FROM instructores";
$resultInstructorsCount = mysqli_query($conn, $queryInstructorsCount);

if (!$resultInstructorsCount) {
    // Manejar el error de la consulta, si lo hay
    $error = mysqli_error($conn);
    echo json_encode(["error" => $error]);
    exit;
}

$rowInstructorsCount = mysqli_fetch_assoc($resultInstructorsCount);

// Agregar el número total de instructores al array de estadísticas
$statistics["total_instructors"] = $rowInstructorsCount['total_instructors'];

// Resto de tu código para las otras estadísticas


// Devolver las estadísticas como JSON
header('Content-Type: application/json');
echo json_encode($statistics);



