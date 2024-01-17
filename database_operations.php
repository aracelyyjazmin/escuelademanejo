<?php
// Este archivo manejará todas las operaciones de base de datos.

function guardarHorasEstudiante($conn, $estudiante) {
    // Actualizar la base de datos con las horas y estado actualizados
    $sqlActualizarEstudiante = "UPDATE estudiantes SET 
        horas_teoricas_requeridas = ?, 
        horas_practicas_requeridas = ?, 
        horas_practicas_completadas = ?, 
        estado_estudiante = ? 
        WHERE numero = ?";
    $stmtActualizarEstudiante = $conn->prepare($sqlActualizarEstudiante);
    $stmtActualizarEstudiante->bind_param("iiiis", 
        $estudiante['horas_teoricas_requeridas'], 
        $estudiante['horas_practicas_requeridas'], 
        $estudiante['horas_practicas_completadas'],
        $estudiante['estado_estudiante'],
        $estudiante['numero']);
    $stmtActualizarEstudiante->execute();
    $stmtActualizarEstudiante->close();
}

// Este archivo manejará todas las operaciones de la base de datos relacionadas con vehículos.

function verificarYActualizarUsoVehiculo($conn, $idVehiculo, $fechaClase, $duracionClase) {
    // Verificar el uso actual del vehículo para la fecha dada
    $sqlVerificarUso = "SELECT tiempo_usado FROM registro_uso_vehiculos WHERE id_vehiculo = ? AND fecha = ?";
    $stmtVerificarUso = $conn->prepare($sqlVerificarUso);
    $stmtVerificarUso->bind_param('is', $idVehiculo, $fechaClase);
    $stmtVerificarUso->execute();
    $resultado = $stmtVerificarUso->get_result();
    $usoVehiculo = $resultado->fetch_assoc();
    
    // Calcular el tiempo de uso nuevo incluyendo la duración de la clase actual
    $tiempoUsadoHoy = $usoVehiculo ? $usoVehiculo['tiempo_usado'] + $duracionClase : $duracionClase;
    
    // Verificar si el vehículo ha excedido el límite de uso diario de 8 horas
    if ($tiempoUsadoHoy > 8) {
        return false; // El vehículo ha excedido el límite de uso para el día
    }

    // Insertar o actualizar el registro de uso del vehículo
    if ($usoVehiculo) {
        $sqlActualizarUso = "UPDATE registro_uso_vehiculos SET tiempo_usado = ? WHERE id_vehiculo = ? AND fecha = ?";
    } else {
        $sqlActualizarUso = "INSERT INTO registro_uso_vehiculos (id_vehiculo, fecha, tiempo_usado) VALUES (?, ?, ?)";
    }
    $stmtActualizarUso = $conn->prepare($sqlActualizarUso);
    if ($usoVehiculo) {
        $stmtActualizarUso->bind_param('iis', $tiempoUsadoHoy, $idVehiculo, $fechaClase);
    } else {
        $stmtActualizarUso->bind_param('iss', $idVehiculo, $fechaClase, $tiempoUsadoHoy);
    }
    $stmtActualizarUso->execute();
    $stmtActualizarUso->close();

    // Actualizar el kilometraje del vehículo sumando 1 km por cada hora de clase
    $sqlActualizarKilometraje = "UPDATE vehiculos SET kilometraje_actual = kilometraje_actual + ? WHERE id_vehiculo = ?";
    $stmtActualizarKilometraje = $conn->prepare($sqlActualizarKilometraje);
    $stmtActualizarKilometraje->bind_param('ii', $duracionClase, $idVehiculo);
    $stmtActualizarKilometraje->execute();
    $stmtActualizarKilometraje->close();
    
    return true; // Actualización exitosa
}
function registrarClaseProgramada($conn, $datosClase) {
    // Suponiendo que $datosClase contiene toda la información necesaria para registrar la clase
    $sqlInsertarProgramacion = "INSERT INTO programacion_clases (id_estudiante, id_instructor, id_vehiculo, fecha_clase, hora_clase) VALUES (?, ?, ?, ?, ?)";
    $stmtProgramacion = $conn->prepare($sqlInsertarProgramacion);
    $stmtProgramacion->bind_param('iiiss', 
        $datosClase['idEstudiante'], 
        $datosClase['idInstructor'], 
        $datosClase['idVehiculo'], 
        $datosClase['fechaClase'], 
        $datosClase['horaClase']
    );

    if ($stmtProgramacion->execute()) {
        $stmtProgramacion->close();
        return true; // Clase programada con éxito
    } else {
        $stmtProgramacion->close();
        return false; // Error al programar la clase
    }
}
function obtenerEstudiantePorId($conn, $numero) {
    $sql = "SELECT * FROM estudiantes WHERE numero = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $numero);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows > 0) {
        return $resultado->fetch_assoc();
    } else {
        return null; // o manejar como se desee
    }
}


