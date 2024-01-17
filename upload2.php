<?php
include 'layouts/config.php';

function actualizarHorasRequeridas(&$estudiante, $duracionClase) {
     $categoriaActual = $estudiante['categoria_actual'];
    $categoriaPostula = $estudiante['categoria_postula'];

    if ($categoriaActual === $categoriaPostula) {
        $estudiante['horas_teoricas_requeridas'] = 15;
        $estudiante['horas_practicas_requeridas'] = 9;
    } elseif ($categoriaActual === 'AIIIB' && $categoriaPostula === 'AIIIC') {
        $estudiante['horas_teoricas_requeridas'] = 50;
        $estudiante['horas_practicas_requeridas'] = 51;
    } elseif (($categoriaActual === 'AI' && $categoriaPostula === 'AIIA') ||
            ($categoriaActual === 'AIIA' && $categoriaPostula === 'AIIB') ||
            ($categoriaActual === 'AIIB' && $categoriaPostula === 'AIIIA') ||
            ($categoriaActual === 'AIIIA' && $categoriaPostula === 'AIIIB') ||
            ($categoriaActual === 'AIIIB' && $categoriaPostula === 'AIIIC')) {
        $estudiante['horas_teoricas_requeridas'] = 30;
        $estudiante['horas_practicas_requeridas'] = 26;
    } else {
    }
    // Después de llamar a actualizarHorasRequeridas($estudiante)
    $horasTeoricasRequeridas = $estudiante['horas_teoricas_requeridas'];
    $horasPracticasRequeridas = $estudiante['horas_practicas_requeridas'];

    // Calcular las horas prácticas completadas
    $estudiante['horas_practicas_completadas'] = (int)$estudiante['horas_practicas_completadas'];
    $duracionClase = (int)$duracionClase;
    
    $estudiante['horas_practicas_completadas'] += (int)$duracionClase;
    if ($estudiante['horas_practicas_completadas'] > $estudiante['horas_practicas_requeridas']) {
        $estudiante['horas_practicas_completadas'] = $estudiante['horas_practicas_requeridas'];
        $estudiante['estado_estudiante'] = 'TERMINADO'; // Estado cuando el estudiante ha completado todas las horas requeridas
    } else {
        $estudiante['estado_estudiante'] = 'EN CURSO'; // Estado cuando aún no se completan las horas requeridas
    
    }
    // Las horas teóricas se mantienen como están
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       $numeroDocumento = $_POST['documento']; // Asegúrate de que este campo exista en tu formulario
    $idInstructor = $_POST['instructor'];
    $placaVehiculo = $_POST['placa'];
    $fechaClase = $_POST['fecha'];
    $horaClase = $_POST['hora'];
    $duracionClase = $_POST['horasClase'];


 // Obtener información del estudiante
 $sqlEstudiante = "SELECT e.id, e.*, la.categoria_licencia AS categoria_actual, lp.categoria_licencia AS categoria_postula
 FROM estudiantes e
 LEFT JOIN licencias la ON e.id_licencia_actual = la.id_licencia
 LEFT JOIN licencias lp ON e.id_licencia_postula = lp.id_licencia
 WHERE e.numero = ?";
$stmtEstudiante = $conn->prepare($sqlEstudiante);
$stmtEstudiante->bind_param('s', $numeroDocumento);
$stmtEstudiante->execute();
$resultadoEstudiante = $stmtEstudiante->get_result();

if ($estudiante = $resultadoEstudiante->fetch_assoc()) {
 actualizarHorasRequeridas($estudiante, $duracionClase);

 if ($estudiante['horas_practicas_completadas'] >= $estudiante['horas_practicas_requeridas']) {
     echo json_encode(["success" => false, "message" => "El estudiante ya ha completado todas las horas prácticas requeridas."]);
     exit;
 }

 // Verificar si ya hay clases programadas para el vehículo en la misma fecha y hora
 $sqlVerificarVehiculo = "SELECT COUNT(*) AS num_clases FROM programacion_clases WHERE id_vehiculo = ? AND fecha_clase = ? AND hora_clase = ?";
 $stmtVerificarVehiculo = $conn->prepare($sqlVerificarVehiculo);
 $stmtVerificarVehiculo->bind_param('iss', $vehiculo['id_vehiculo'], $fechaClase, $horaClase);
 $stmtVerificarVehiculo->execute();
 $resultadoVerificarVehiculo = $stmtVerificarVehiculo->get_result();
 $rowVerificarVehiculo = $resultadoVerificarVehiculo->fetch_assoc();

 if ($rowVerificarVehiculo['num_clases'] > 0) {
     echo json_encode(["success" => false, "message" => "El vehículo ya está programado en la misma fecha y hora."]);
     exit;
 }

 // Obtener el kilometraje actual del vehículo
$sqlObtenerKilometraje = "SELECT kilometraje_actual FROM vehiculos WHERE placa = ?";
$stmtObtenerKilometraje = $conn->prepare($sqlObtenerKilometraje);
$stmtObtenerKilometraje->bind_param('s', $placaVehiculo);
$stmtObtenerKilometraje->execute();
$resultadoKilometraje = $stmtObtenerKilometraje->get_result();

if ($rowKilometraje = $resultadoKilometraje->fetch_assoc()) {
    $kilometrajeActual = (int)$rowKilometraje['kilometraje_actual'];
    
    // Actualizar el kilometraje actual con los kilómetros recorridos por el estudiante
    $kilometrosRecorridos = (int)$duracionClase;
    $nuevoKilometrajeActual = $kilometrajeActual + $kilometrosRecorridos;
    
    // Actualizar el valor en la tabla de vehículos
    $sqlActualizarKilometraje = "UPDATE vehiculos SET kilometraje_actual = ? WHERE placa = ?";
    $stmtActualizarKilometraje = $conn->prepare($sqlActualizarKilometraje);
    $stmtActualizarKilometraje->bind_param('is', $nuevoKilometrajeActual, $placaVehiculo);
    $stmtActualizarKilometraje->execute();
    $stmtActualizarKilometraje->close();
} else {
    echo json_encode(["success" => false, "message" => "Error al obtener el kilometraje actual del vehículo."]);
    exit;
}

  // Verificar si ya hay clases programadas para el instructor en la misma fecha y hora
  $sqlVerificarInstructor = "SELECT COUNT(*) AS num_clases FROM programacion_clases WHERE id_instructor = ? AND fecha_clase = ? AND hora_clase = ?";
  $stmtVerificarInstructor = $conn->prepare($sqlVerificarInstructor);
  $stmtVerificarInstructor->bind_param('iss', $idInstructor, $fechaClase, $horaClase);
  $stmtVerificarInstructor->execute();
  $resultadoVerificarInstructor = $stmtVerificarInstructor->get_result();
  $rowVerificarInstructor = $resultadoVerificarInstructor->fetch_assoc();

  if ($rowVerificarInstructor['num_clases'] > 0) {
      echo json_encode(["success" => false, "message" => "El instructor ya está programado en la misma fecha y hora."]);
      exit;
  }

  $sqlActualizarEstudiante = "UPDATE estudiantes SET 
                                  horas_practicas_completadas = ?,
                                  estado_estudiante = ?
                                  WHERE numero = ?";
  $stmtActualizarEstudiante = $conn->prepare($sqlActualizarEstudiante);
  $stmtActualizarEstudiante->bind_param("iss", 
      $estudiante['horas_practicas_completadas'], 
      $estudiante['estado_estudiante'],
      $numeroDocumento);
  $stmtActualizarEstudiante->execute();
  $stmtActualizarEstudiante->close();
  
  $sqlObtenerIdVehiculo = "SELECT id_vehiculo FROM vehiculos WHERE placa = ?";
  $stmtObtenerIdVehiculo = $conn->prepare($sqlObtenerIdVehiculo);
  $stmtObtenerIdVehiculo->bind_param('s', $placaVehiculo);
  $stmtObtenerIdVehiculo->execute();
  $resultadoIdVehiculo = $stmtObtenerIdVehiculo->get_result();

  if ($rowIdVehiculo = $resultadoIdVehiculo->fetch_assoc()) {
      $idVehiculo = $rowIdVehiculo['id_vehiculo'];

      // Registrar la clase programada
      $sqlInsertarProgramacion = "INSERT INTO programacion_clases (id_estudiante, id_instructor, id_vehiculo, fecha_clase, duracion_clase, kilometraje_inicial, kilometraje_final) VALUES (?, ?, ?, ?, ?, ?, ?)";
      $stmtProgramacion = $conn->prepare($sqlInsertarProgramacion);
      $stmtProgramacion->bind_param('iiissii', $estudiante['id'], $idInstructor, $idVehiculo, $fechaClase, $duracionClase, $kilometrajeActual, $kilometrajeActual); // Corregido

      if ($stmtProgramacion->execute()) {
          echo json_encode(["success" => true, "message" => "Clase programada con éxito.", "kilometraje_inicial" => $kilometrajeActual, "kilometraje_final" => $nuevoKilometrajeActual]);
      } else {
          echo json_encode(["success" => false, "message" => "Error al programar la clase."]);
      }
      $stmtProgramacion->close();
  } else {
      echo json_encode(["success" => false, "message" => "Estudiante no encontrado."]);
      exit;
  }
} else {
  echo json_encode(["success" => false, "message" => "Solicitud no válida."]);
  exit;
}

}
$conn->close();
?>