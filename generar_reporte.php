<?php
// Incluye la biblioteca TCPDF (ajusta la ruta según tu configuración)
require_once __DIR__ . '../vendor/tecnickcom/tcpdf/tcpdf.php';

// Incluye la configuración de la base de datos
include 'layouts/config.php';

// Verifica si el ID del estudiante se pasó a través de la URL
if (isset($_GET['id'])) {
    $studentId = $_GET['id'];
} else {
    die('No se proporcionó el ID del estudiante.');
}

// Consulta para obtener la información del estudiante
$query = "SELECT e.*, 
                 l1.categoria_licencia AS licencia_actual, 
                 l2.categoria_licencia AS licencia_postula,
                 c.nombre_modulo,
                 (SELECT MIN(fecha_clase) FROM programacion_clases WHERE id_estudiante = e.id) AS fecha_inicio,
                 (SELECT MAX(fecha_clase) FROM programacion_clases WHERE id_estudiante = e.id) AS fecha_termino,
                 e.foto
          FROM estudiantes e 
          LEFT JOIN licencias l1 ON e.id_licencia_actual = l1.id_licencia 
          LEFT JOIN licencias l2 ON e.id_licencia_postula = l2.id_licencia
          LEFT JOIN cursos c ON e.id_curso = c.id_curso
          WHERE e.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Verifica si se obtuvieron resultados
if (!$student) {
    die('No se encontró al estudiante.');
}

// Cerrar la conexión de la base de datos
$stmt->close();
$conn->close();

// Crear una nueva instancia de PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Configurar la información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nombre de la Escuela de Conducción');
$pdf->SetTitle('Reporte de Ficha de Estudiante');
$pdf->SetSubject('Ficha del Estudiante');
$pdf->SetKeywords('TCPDF, PDF, reporte, test, guia');
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 10);

// Eliminar encabezado y pie de página predeterminados
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);


$fechaMatricula = date('d/m/Y', strtotime($student['fecha_registro'])); // Cambiar el formato de fecha según se requiera

// HTML para las imágenes y el texto del cabezal
$logoMinisterioSrc = 'assets/images/ministerio.jpg';
$logoMtcSrc = 'assets/images/mtc.jpg';
$logoAloLicenciaSrc = 'assets/images/alolicencia.jpg';
$codigoRD = 'R.D. N° 0743 - 2022-MTC/17.03';

$nombreCompleto = strtoupper($student['apellido'] . ' ' . $student['nombre']);
$tipoDocumento = strtoupper($student['documento']);
$numeroDocumento = strtoupper($student['numero']);
$gradoInstruccion = strtoupper($student['grado_instruccion']);
$fechaNacimiento = strtoupper($student['fecha_nacimiento']);
$numeroCelular = strtoupper($student['telefono']);
$domicilio = strtoupper($student['domicilio']);
$fotoEstudiante = $student['foto']; // Asume que la foto está almacenada y accesible en la ruta proporcionada
$fotoEstudiante = 'uploads/'.$student['foto']; // Asegúrate de que 'foto' es la columna correcta en tu base de datos

$categoriaActual = $student['licencia_actual'];
$categoriaPostula = $student['licencia_postula'];
$horasTeoricasRequeridas = 0;
$horasPracticasRequeridas = 0;
$nombreDelCurso = "N/A"; // Default value if none of the conditions match

if ($categoriaActual === $categoriaPostula) {
    $horasTeoricasRequeridas = 15;
    $horasPracticasRequeridas = 8;
    $nombreDelCurso = 'Revalidacion ' . $categoriaPostula;
} elseif ($categoriaActual === 'AIIIB' && $categoriaPostula === 'AIIIC') {
    $horasTeoricasRequeridas = 50;
    $horasPracticasRequeridas = 50;
    $nombreDelCurso = 'Recategorización ' . $categoriaPostula;
} elseif (
    ($categoriaActual === 'AI' && $categoriaPostula === 'AIIA') ||
    ($categoriaActual === 'AIIA' && $categoriaPostula === 'AIIB') ||
    ($categoriaActual === 'AIIB' && $categoriaPostula === 'AIIIA') ||
    ($categoriaActual === 'AIIIA' && $categoriaPostula === 'AIIIB') ||
    ($categoriaActual === 'AIIIB' && $categoriaPostula === 'AIIIC')
) {
    $horasTeoricasRequeridas = 30;
    $horasPracticasRequeridas = 25;
    $nombreDelCurso = 'Recategorización ' . $categoriaPostula;
}

$horasTotalesDelCurso = $horasTeoricasRequeridas + $horasPracticasRequeridas;


// Dates of the course
$fechaDeInicioDelCurso = date('d/m/Y', strtotime($student['fecha_inicio']));
$fechaDeTerminoDelCurso = date('d/m/Y', strtotime($student['fecha_termino']));



// Verifica que la foto existe y es accesible
if (!file_exists($fotoEstudiante) || !is_readable($fotoEstudiante)) {
    die('La foto del estudiante no se encuentra o no es accesible.');
}

// HTML para el cabezal
$html = <<<EOD
<table cellspacing="0" cellpadding="3" border="0">
    <tr>
        <td style="width: 105px;">
            <img src="$logoMinisterioSrc" width="120" height="65" />
        </td>
        <td style="width: 105px;">
            <img src="$logoMtcSrc" width="120" height="65" />
        </td>
        <td style="font-size: 5px; width: 210px; text-align: center;">
            &nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td style="width: 100px; text-align: right;">
            <img src="$logoAloLicenciaSrc" width="100" height="auto" />
        </td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: 9px; text-align: center;">
            $codigoRD
        </td>
        <td colspan="2" style="text-align: right;">
        </td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: 9px; text-align: center;">
        FECHA DE MATRICULA: $fechaMatricula
        </td>
       
    </tr>
    <br>
    <tr>
        <td colspan="4" style="font-size: 12px; font-weight: bold; text-align: center;">
            INFORMACIÓN DEL POSTULANTE
        </td>
    </tr>

    <br>
    <tr>
        <td colspan="3" style="font-size: 9px;">
            APELLIDOS Y NOMBRES: &nbsp; &nbsp; $nombreCompleto
        </td>
        <td rowspan="6">
            <img src="$fotoEstudiante" width="80" height="100" style="padding-left: 10px;"/>
        </td>
    </tr>
    <br>
     <tr>
        <td colspan="3" style="font-size: 9px;">
        TIPO DE DOCUMENTO: &nbsp; &nbsp; $tipoDocumento
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;
        N° DE DOCUMENTO: &nbsp; &nbsp; $numeroDocumento
        </td> 
    </tr>
    <br>
	<tr>
    <td colspan="3" style="font-size: 9px;">
    GRADO DE INSTRUCCIÓN: &nbsp; &nbsp; $gradoInstruccion
    </td>
 </tr>
 <br>
<tr>
    <td colspan="3" style="font-size: 9px;">
    FECHA DE NACIMIENTO: &nbsp; &nbsp;$fechaNacimiento
    </td>
 </tr>
 <br>
<tr>
    <td colspan="3" style="font-size: 9px;">
    N° DE CELULAR: &nbsp; &nbsp; $numeroCelular
    </td>
</tr>
<br>
 <tr>
    <td colspan="3" style="font-size: 9px;">
    DOMICILIO: &nbsp; &nbsp; $domicilio
    </td>
</tr>
<br/>
<tr>
        <th colspan="4" style="text-align: center; font-weight: bold; font-size: 12px;">DATOS DE LA CAPACITACIÓN</th>
    </tr>
    <br/>

   <tr>
        <td style="font-size:9px;">NOMBRE DEL CURSO:</td>
        <td style="font-size:9px;" colspan="3">$nombreDelCurso</td>
    </tr>
    <br>
    <tr>
        <td style="font-size:9px;">FECHA DE INICIO DEL CURSO:</td>
        <td style="font-size:9px;">$fechaDeInicioDelCurso</td>
        <td style="font-size:9px;">FECHA DE TÉRMINO DEL CURSO:</td>
        <td style="font-size:9px;">$fechaDeTerminoDelCurso</td>
    </tr>
    <br>
    <tr>
        <td style="font-size:9px;">HORA TOTAL DEL CURSO:</td>
        <td style="font-size:9px;">$horasTotalesDelCurso hr</td>
        <td style="font-size:9px;" colspan="2"></td>
    </tr>
    <br>
    <tr>
        <td style="font-size:9px;">CLASE/CATEGORÍA DE LICENCIA QUE POSEE:</td>
        <td style="font-size:9px;">$categoriaActual</td>
        <td style="font-size:9px;">CLASE/CATEGORÍA DE LICENCIA QUE POSTULA:</td>
        <td style="font-size:9px;">$categoriaPostula</td>
    </tr>

    <br />
    <tr>
        <th colspan="4" style="text-align: center; font-weight: bold; font-size: 13px;">RESULTADOS DE LOS EXAMENES</th>
    </tr>
    <br />
    <table cellspacing="0" cellpadding="4" border="1" style="text-align:center;">
        
    <tr>
    <td style="width: 20%; font-size: 10px; text-align: center;">NOTA CURSOS GENERALES</td>
    <td style="width: 20%; font-size: 10px; text-align: center;">NOTA SEGURIDAD VIAL</td>
    <td style="width: 20%; font-size: 10px; text-align: center;">NOTA CURSOS ESPECÍFICOS</td>
    <td style="width: 20%; font-size: 10px; text-align: center;">NOTA DE EXAMEN MANEJO</td>
    <td style="width: 20%; font-size: 10px; text-align: center;">NOTA PROMEDIO FINAL</td>
</tr>
<tr>
    <td style="height: 50px;"></td>
    <td style="height: 50px;"></td>
    <td style="height: 50px;"></td>
    <td style="height: 50px;"></td>
    <td style="height: 50px;"></td>
</tr>

</table>
<br />
<br />
<table cellspacing="0" cellpadding="4" border="0" style="width:100%; text-align:center;">
    <tr>
        <!-- Espacio en blanco a la izquierda para centrar -->
        <td style="width: 25%;">
            <!-- Espacio en blanco -->
        </td>
        <!-- Espacio para la firma del evaluado -->
        <td style="width: 25%; border-bottom: 1px solid #000; height: 50px;">
            <!-- Espacio en blanco para la línea de firma -->
        </td>
        <!-- Espacio en blanco para separar firma y huella -->
        <td style="width: 5%;">
            <!-- Espacio en blanco -->
        </td>
        <!-- Espacio para la huella digital -->
        <td style="width: 20%; border: 1px solid #000; height: 70px;">
            <!-- Espacio en blanco para el cuadro de la huella digital -->
        </td>
        <!-- Espacio en blanco a la derecha para centrar -->
        <td style="width: 25%;">
            <!-- Espacio en blanco -->
        </td>
    </tr>
    <tr>
        <!-- Texto "Firma del Evaluado" -->
        <td style="width: 25%;">
            <!-- Espacio en blanco -->
        </td>
        <td style="font-size: 10px; text-align: center; padding-top: 5px;">
            Firma del Evaluado
        </td>
        <td style="width: 5%;">
            <!-- Espacio en blanco -->
        </td>
        <!-- Texto "Huella Digital" -->
        <td style="font-size: 10px; text-align: center; padding-top: 5px;">
            Huella Digital
        </td>
        <td style="width: 25%;">
            <!-- Espacio en blanco -->
        </td>
    </tr>
</table>
    
EOD;


// Imprimir el contenido HTML en el documento PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Cerrar y enviar el documento PDF
$pdf->Output('reporte_ficha_estudiante.pdf', 'I');

?>
