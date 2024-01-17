<?php
// utils.php

function actualizarHorasRequeridas(&$estudiante, $duracionClase) {
    // Determinar las horas teóricas y prácticas requeridas según la categoría
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

    // Calcular y añadir las horas de práctica
    $horasPracticasPosibles = $estudiante['horas_practicas_requeridas'] - $estudiante['horas_practicas_completadas'];
    $horasParaAgregar = min((int)$duracionClase, $horasPracticasPosibles);
    $estudiante['horas_practicas_completadas'] += $horasParaAgregar;

    // Actualizar el estado del estudiante
    $estudiante['estado_estudiante'] = $estudiante['horas_practicas_completadas'] >= $estudiante['horas_practicas_requeridas'] ? 'TERMINADO' : 'EN CURSO';
}
