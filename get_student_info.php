<?php
include 'layouts/config.php';

function actualizarHorasRequeridas(&$estudiante, $duracionClase) {
     $categoriaActual = $estudiante['categoria_actual'];
    $categoriaPostula = $estudiante['categoria_postula'];

    if ($categoriaActual === $categoriaPostula) {
        $estudiante['horas_teoricas_requeridas'] = 15;
        $estudiante['horas_practicas_requeridas'] = 8;
    } elseif ($categoriaActual === 'AIIIB' && $categoriaPostula === 'AIIIC') {
        $estudiante['horas_teoricas_requeridas'] = 50;
        $estudiante['horas_practicas_requeridas'] = 50;
    } elseif (($categoriaActual === 'AI' && $categoriaPostula === 'AIIA') ||
            ($categoriaActual === 'AIIA' && $categoriaPostula === 'AIIB') ||
            ($categoriaActual === 'AIIB' && $categoriaPostula === 'AIIIA') ||
            ($categoriaActual === 'AIIIA' && $categoriaPostula === 'AIIIB') ||
            ($categoriaActual === 'AIIIB' && $categoriaPostula === 'AIIIC')) {
        $estudiante['horas_teoricas_requeridas'] = 30;
        $estudiante['horas_practicas_requeridas'] = 25;
    } else {
        // Define valores por defecto o maneja un error
    }
    // Después de llamar a actualizarHorasRequeridas($estudiante)
    $horasTeoricasRequeridas = $estudiante['horas_teoricas_requeridas'];
    $horasPracticasRequeridas = $estudiante['horas_practicas_requeridas'];

    // Calcular las horas prácticas completadas
    $estudiante['horas_practicas_completadas'] = (int)$estudiante['horas_practicas_completadas'];
    $duracionClase = (int)$duracionClase;
    
    $estudiante['horas_practicas_completadas'] += $duracionClase;
    if ($estudiante['horas_practicas_completadas'] > $estudiante['horas_practicas_requeridas']) {
        $estudiante['horas_practicas_completadas'] = $estudiante['horas_practicas_requeridas'];
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
    actualizarHorasRequeridas($estudiante, $_POST['horasClase']);
        
        // Actualizar la base de datos con las horas completadas
        $sqlActualizarEstudiante = "UPDATE estudiantes SET 
                                    horas_practicas_completadas = ?
                                    WHERE numero = ?";
        $stmtActualizarEstudiante = $conn->prepare($sqlActualizarEstudiante);
        $stmtActualizarEstudiante->bind_param("is", 
            $estudiante['horas_practicas_completadas'], 
            $numeroDocumento);
        $stmtActualizarEstudiante->execute();
        $stmtActualizarEstudiante->close();
    } else {
        echo "Estudiante no encontrado.";
        exit;
    }

$sqlVehiculo = "SELECT * FROM vehiculos WHERE placa = ?";
    $stmtVehiculo = $conn->prepare($sqlVehiculo);
    $stmtVehiculo->bind_param('s', $placaVehiculo);
    $stmtVehiculo->execute();
    $resultadoVehiculo = $stmtVehiculo->get_result();

    if ($vehiculo = $resultadoVehiculo->fetch_assoc()) {
        if ($vehiculo['tiempo_maximo'] >= $duracionClase) {
            $vehiculo['tiempo_maximo'] = (int)$vehiculo['tiempo_maximo'];
            $duracionClase = (int)$duracionClase;
            $nuevoTiempoMaximo = $vehiculo['tiempo_maximo'] - $duracionClase;             
            $sqlActualizarVehiculo = "UPDATE vehiculos SET tiempo_maximo = ? WHERE placa = ?";
            $stmtVehiculo = $conn->prepare($sqlActualizarVehiculo);
            $stmtVehiculo->bind_param('is', $nuevoTiempoMaximo, $placaVehiculo);
            $stmtVehiculo->execute();
            $stmtVehiculo->close();
        } else {
            echo "El vehículo no está disponible.";
            exit;
        }
    } else {
        echo "Vehículo no encontrado.";
        exit;
    }

    // Registrar la clase programada
    $sqlInsertarProgramacion = "INSERT INTO programacion_clases (id_estudiante, id_instructor, id_vehiculo, fecha_clase, hora_clase) VALUES (?, ?, ?, ?, ?)";
    $stmtProgramacion = $conn->prepare($sqlInsertarProgramacion);
    $stmtProgramacion->bind_param('iiiss', $estudiante['id'], $idInstructor, $vehiculo['id_vehiculo'], $fechaClase, $horaClase);
    if ($stmtProgramacion->execute()) {
        echo json_encode(["success" => true, "message" => "Clase programada con éxito."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al programar la clase."]);
    }
    $stmtProgramacion->close();
} else {
    echo json_encode(["success" => false, "message" => "Solicitud no válida."]);
}

$conn->close();
?>