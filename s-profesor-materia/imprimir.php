<?php

// Obtiene los parametros
$id_profesor_materia = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

// Verifica si existen los parametros
if ($id_profesor_materia == 0) {
	// Obtiene los profesor_materia
	$profesor_materia = $db->select('z.*, a.nombre_materia as materia')->from('vista_profesor_materia z')->join('pro_materia a', 'z.materia_id = a.id_materia', 'left')->order_by('z.id_profesor_materia', 'asc')->fetch();

	// Ejecuta un error 404 si no existe los profesor_materia
	if (!$permiso_listar) { require_once not_found(); exit; }
} else {
	// Obtiene el profesor_materia
	$profesor_materia = $db->select('z.*, a.nombre_materia as materia')->from('vista_profesor_materia z')->join('pro_materia a', 'z.materia_id = a.id_materia', 'left')->where('z.id_profesor_materia', $id_profesor_materia)->fetch_first();
	
	// Ejecuta un error 404 si no existe el profesor_materia
	if (!$profesor_materia || !$permiso_ver) { require_once not_found(); exit; }
}

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
if ($id_profesor_materia == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'REPORTE PROFESOR MATERIA', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($profesor_materia as $nro => $profesor_materia) {
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($profesor_materia[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		/*$body .= '<td>' . escape($profesor_materia['id_profesor']) . '</td>';*/
		$body .= '<td>' . escape($profesor_materia['codigo_profesor']) . '</td>';
		$body .= '<td>' . escape($profesor_materia['nombre_profesor']) . '</td>';
	/*	$body .= '<td>' . escape($profesor_materia['materia']) . '</td>';*/
		$body .= '<td>' . escape($profesor_materia['nombre_materia']) . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="6">No existen a profesor materia registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5" border="0.5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="10%"><h3>N</h3></th>';
/*	$tabla .= '<th width="14%"><h3>Id profesor</h3></th>';*/
	$tabla .= '<th width="15%"><h3>CODIGO PROFESOR</h3></th>';
	$tabla .= '<th width="45%"><h3>NOMBRE PROFESOR</h3></th>';
/*	$tabla .= '<th width="10.61%">Materia</th>';*/
	$tabla .= '<th width="30%"><h3>MATERIA NOMBRE</h3></th>';
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'profesor_materia_' . date('Y-m-d_H-i-s') . '.pdf';
} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'A PROFESOR MATERIA # ' . $id_profesor_materia, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define las variables
	$valor_id_profesor = escape($profesor_materia['id_profesor']);
	$valor_codigo_profesor = escape($profesor_materia['codigo_profesor']);
	$valor_nombre_profesor = escape($profesor_materia['nombre_profesor']);
	$valor_materia_id = escape($profesor_materia['materia']);
	$valor_nombre_materia = escape($profesor_materia['nombre_materia']);
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5"  border="1">';
	$tabla .= '<tr class="first"><th class="left">Id profesor:</th><td class="right">' . $valor_id_profesor . '</td></tr>';
	$tabla .= '<tr><th class="left">Codigo profesor:</th><td class="right">' . $valor_codigo_profesor . '</td></tr>';
	$tabla .= '<tr><th class="left">Nombre profesor:</th><td class="right">' . $valor_nombre_profesor . '</td></tr>';
	$tabla .= '<tr><th class="left">Materia:</th><td class="right">' . $valor_materia_id . '</td></tr>';
	$tabla .= '<tr class="last"><th class="left">Nombre materia:</th><td class="right">' . $valor_nombre_materia . '</td></tr>';
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'profesor_materia_' . $id_profesor_materia . '_' . date('Y-m-d_H-i-s') . '.pdf';
}

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>