<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'layouts/session.php';
include 'layouts/config.php';

function limpiar($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredFields = [
        'nombre', 'apellido', 'documento', 'numero', 'telefono', 'email',
        'id_licencia_actual', 'id_licencia_postula', 'grado_instruccion', 'fecha_nacimiento',
        'domicilio', 'proceso'
    ];

    $missingFields = [];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $missingFields[] = $field;
        } else {
            $_POST[$field] = limpiar($_POST[$field]);
        }
    }

    if (!empty($missingFields)) {
        echo json_encode(['error' => "Error: Faltan campos obligatorios - " . implode(', ', $missingFields)]);
        exit;
    }

    if (!empty($_POST['studentId'])) {
        $studentId = $_POST['studentId'];
        $sql = "UPDATE estudiantes SET 
            nombre = ?, apellido = ?, documento = ?, numero = ?, telefono = ?, email = ?, 
            id_licencia_actual = ?, id_licencia_postula = ?, grado_instruccion = ?, 
            fecha_nacimiento = ?, domicilio = ?, proceso = ? 
            WHERE id = ?";
    } else {
        $sql = "INSERT INTO estudiantes (
            nombre, apellido, documento, numero, telefono, email, 
            id_licencia_actual, id_licencia_postula, grado_instruccion, fecha_nacimiento, 
            domicilio, proceso
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(['error' => "Error al preparar la consulta: " . $conn->error]);
        exit;
    }

    if (isset($studentId)) {
        $stmt->bind_param(
            "ssssssssssssi", 
            $_POST['nombre'], $_POST['apellido'], $_POST['documento'], $_POST['numero'], 
            $_POST['telefono'], $_POST['email'], $_POST['id_licencia_actual'], $_POST['id_licencia_postula'], 
            $_POST['grado_instruccion'], $_POST['fecha_nacimiento'], $_POST['domicilio'], 
            $_POST['proceso'], $studentId
        );
    } else {
        $stmt->bind_param(
            "ssssssssssss", 
            $_POST['nombre'], $_POST['apellido'], $_POST['documento'], $_POST['numero'], 
            $_POST['telefono'], $_POST['email'], $_POST['id_licencia_actual'], $_POST['id_licencia_postula'], 
            $_POST['grado_instruccion'], $_POST['fecha_nacimiento'], $_POST['domicilio'], 
            $_POST['proceso']
        );
    }

    if ($stmt->execute()) {
        $response = [
            'id' => isset($studentId) ? $studentId : $stmt->insert_id,
            'nombre' => $_POST['nombre'],
            'apellido' => $_POST['apellido']
            // Aquí se podría agregar otros campos según sea necesario
        ];
        echo json_encode($response);
    } else {
        echo json_encode(['error' => "Error al guardar los datos: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
