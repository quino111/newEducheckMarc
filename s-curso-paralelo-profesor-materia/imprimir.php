<?php

// Obtiene los parametros
$id_aula_paralelo = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

// Verifica si existen los parametros
if ($id_aula_paralelo == 0) {
	// Obtiene los aula_paralelo
	$aula_paralelo = $db->query('SELECT 
            ins_turno.nombre_turno,
            c.nombre_aula,
            e.nombre_paralelo,
            d.descripcion,
            d.nombre_nivel,
            h.nombres,
            h.primer_apellido,
            h.segundo_apellido,
            i.nombre_materia,
            a.profesor_materia_id,
            a.id_aula_paralelo_profesor_materia,
            a.aula_paralelo_id,
            a.estado,
            a.usuario_registro,
            a.fecha_registro,
            a.usuario_modificacion,
            a.fecha_modificacion
            
    FROM int_aula_paralelo_profesor_materia a,ins_aula_paralelo b ,ins_aula c,ins_nivel_academico d,ins_paralelo e,
    pro_profesor_materia f,pro_profesor g,sys_persona h,pro_materia i,ins_turno
    WHERE
    a.aula_paralelo_id=b.id_aula_paralelo AND
    ins_turno.id_turno=b.turno_id AND
    b.aula_id=c.id_aula AND
    c.nivel_academico_id= d.id_nivel_academico AND
    b.paralelo_id= e.id_paralelo AND
    a.profesor_materia_id=f.id_profesor_materia AND
    f.profesor_id= g.id_profesor AND
    g.persona_id=h.id_persona AND
    f.materia_id=i.id_materia 
    ORDER BY 
       ins_turno.id_turno asc,
       d.id_nivel_academico asc,
       nombre_aula asc,
       nombre_paralelo asc,
       h.primer_apellido asc
    ')->fetch();
/*ORDER BY 
       ins_turno.id_turno asc,
       ins_nivel_academico.id_nivel_academico asc,
       nombre_aula asc,
       nombre_paralelo asc*/
	// Ejecuta un error 404 si no existe los aula_paralelo
	if (!$permiso_listar) { require_once not_found(); exit; }
} else {
	// Obtiene el aula_paralelo
	$aula_paralelo = $db->select('z.*, a.nombre_paralelo as paralelo')->from('ins_aula_paralelo z')->join('ins_paralelo a', 'z.paralelo_id = a.id_paralelo', 'left')->where('z.id_aula_paralelo', $id_aula_paralelo)->fetch_first();
	
	// Ejecuta un error 404 si no existe el aula_paralelo
	if (!$aula_paralelo || !$permiso_ver) { require_once not_found(); exit; }
}

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
if ($id_aula_paralelo == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('L');//L H

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'ASIGNACION DE AULA PROFESOR MATERIA', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($aula_paralelo as $nro => $aula_paralelo) {
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($aula_paralelo[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		$body .= '<td>' . escape($aula_paralelo['nombre_turno']) . '</td>';
		$body .= '<td>' . escape($aula_paralelo['nombre_aula']) . '</td>';
		$body .= '<td>' . escape($aula_paralelo['nombre_nivel']) . '</td>';
		$body .= '<td>Paralelo ' . escape($aula_paralelo['nombre_paralelo']) . '</td>';
		$body .= '<td>' . escape($aula_paralelo['primer_apellido']) . ' '.escape($aula_paralelo['segundo_apellido']) . ' '.escape($aula_paralelo['nombres']) .'</td>'; 
		$body .= '<td>' . escape($aula_paralelo['nombre_materia']) . '</td>';
		/*$body .= '<td>' . escape($aula_paralelo['estado']) . '</td>';
        $body .= '<td>' . escape($aula_paralelo['usuario_registro']) . '</td>';
        $body .= '<td>' . escape($aula_paralelo['fecha_registro']) . '</td>';
        $body .= '<td>' . escape($aula_paralelo['usuario_modificacion']) . '</td>';
        $body .= '<td>' . escape($aula_paralelo['fecha_modificacion']) . '</td>';*/
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="9">No existen aula paralelo registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5"  border="0.5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="5%">#</th>';
	$tabla .= '<th width="10%"><h3>TURNO</h3></th>';
	$tabla .= '<th width="7%"><h3>AULA</h3></th>';
	$tabla .= '<th width="10%"><h3>NIVEL</h3></th>';
	$tabla .= '<th width="15%"><h3>PARALELO</h3></th>';
	$tabla .= '<th width="23%"><h3>DOCENTE </h3></th>'; 
	$tabla .= '<th width="30%"><h3>MATERIA</h3> </th>'; 
	/*$tabla .= '<th width="11%">Usuario modificacion</th>';
	$tabla .= '<th width="5%">Fecha modificacion</th>'; 
	$tabla .= '<th width="5%">usuario_registro</th>';
	$tabla .= '<th width="14%">fecha_registro</th>';
	$tabla .= '<th width="6%">usuario_modificacion</th>';
	$tabla .= '<th width="14%">fecha_modificacion</th>';*/
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'aula_paralelo_' . date('Y-m-d_H-i-s') . '.pdf';
} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'AULA PARALELO # ' . $id_aula_paralelo, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define las variables
	$valor_aula_id = escape($aula_paralelo['aula_id']);
	$valor_paralelo_id = escape($aula_paralelo['paralelo']);
	$valor_capacidad = escape($aula_paralelo['capacidad']);
	$valor_estado = escape($aula_paralelo['estado']);
	$valor_usuario_registro = ($aula_paralelo['usuario_registro'] != '') ? escape($aula_paralelo['usuario_registro']) : 'No asignado';
	$valor_fecha_registro = ($aula_paralelo['fecha_registro'] != '') ? escape($aula_paralelo['fecha_registro']) : 'No asignado';
	$valor_usuario_modificacion = ($aula_paralelo['usuario_modificacion'] != '') ? escape($aula_paralelo['usuario_modificacion']) : 'No asignado';
	$valor_fecha_modificacion = ($aula_paralelo['fecha_modificacion'] != '') ? escape($aula_paralelo['fecha_modificacion']) : 'No asignado';
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5"  border="0.5">';
	$tabla .= '<tr class="first"><th class="left">Aula:</th><td class="right">' . $valor_aula_id . '</td></tr>';
	$tabla .= '<tr><th class="left">Paralelo:</th><td class="right">' . $valor_paralelo_id . '</td></tr>';
	$tabla .= '<tr><th class="left">Capacidad:</th><td class="right">' . $valor_capacidad . '</td></tr>';
	$tabla .= '<tr><th class="left">Estado:</th><td class="right">' . $valor_estado . '</td></tr>';
	$tabla .= '<tr><th class="left">Usuario registro:</th><td class="right">' . $valor_usuario_registro . '</td></tr>';
	$tabla .= '<tr><th class="left">Fecha registro:</th><td class="right">' . $valor_fecha_registro . '</td></tr>';
	$tabla .= '<tr><th class="left">Usuario modificacion:</th><td class="right">' . $valor_usuario_modificacion . '</td></tr>';
	$tabla .= '<tr class="last"><th class="left">Fecha modificacion:</th><td class="right">' . $valor_fecha_modificacion . '</td></tr>';
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'aula_paralelo_' . $id_aula_paralelo . '_' . date('Y-m-d_H-i-s') . '.pdf';
}

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>