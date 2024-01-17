<?php
// Incluir archivos de configuración de base de datos
include 'layouts/config.php';

// Obtener categoría de licencia desde la solicitud POST
$categoriaLicencia = isset($_POST['categoriaLicencia']) ? $_POST['categoriaLicencia'] : '';

// Validar y limpiar la categoría de licencia

// Preparar y ejecutar consulta para vehículos
$sqlVehiculos = "SELECT id_vehiculo, tipo_vehiculo, placa FROM vehiculos WHERE categoriaVehiculo = ?";
$stmtVehiculos = $conn->prepare($sqlVehiculos);
$stmtVehiculos->bind_param("s", $categoriaLicencia);
$stmtVehiculos->execute();
$resultVehiculos = $stmtVehiculos->get_result();
$vehiculos = $resultVehiculos->fetch_all(MYSQLI_ASSOC);

// Preparar y ejecutar consulta para instructores
$sqlInstructores = "SELECT id, nombres, apellidos FROM instructores WHERE id_categoria_licencia IN (SELECT id_licencia FROM licencias WHERE categoria_licencia = ?)";
$stmtInstructores = $conn->prepare($sqlInstructores);
$stmtInstructores->bind_param("s", $categoriaLicencia);
$stmtInstructores->execute();
$resultInstructores = $stmtInstructores->get_result();
$instructores = $resultInstructores->fetch_all(MYSQLI_ASSOC);

// Devolver resultados como JSON
echo json_encode(array("vehiculos" => $vehiculos, "instructores" => $instructores));

// Cerrar la conexión
$stmtVehiculos->close();
$stmtInstructores->close();
$conn->close();
?>
