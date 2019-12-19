<?php

// Obtiene los parametros
//$id_aula_paralelo = (isset($_params[0])) ? $_params[0] : 0;



    $turno=isset($_params[0])?$_params[0]:0;
    $nivel=isset($_params[1])?$_params[1]:0;
    $aula=isset($_params[2])?$_params[2]:0; 
   $paralelo=isset($_params[3])?$_params[3]:0; 

//turno+'/'+nivel+'/'+aula+'/'+paralelo);

    
// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);
 
/*    $aula=isset($_POST['aula'])?$_POST['aula']:0; 
    $turno=isset($_POST['turno'])?$_POST['turno']:0; 
    $paralelo=isset($_POST['paralelo'])?$_POST['paralelo']:0; 
    */
    $sql="";
    if($turno){
        $sql.=" and ap.turno_id=$turno";
    }

    if($aula){
        $sql.=" and  ap.aula_id=$aula";
    } if($paralelo){
        $sql.=" and  pa.id_paralelo=$paralelo";
    }

// Verifica si existen los parametros
//if ($id_aula_paralelo == 0) {
	// Obtiene los aula_paralelo
	$aula_paralelo = $db->query("SELECT z.*,p.numero_documento,ap.*,au.*,pa.*,ni.*,te.*,tu.*, CONCAT(p.primer_apellido,' ',p.segundo_apellido,' ',p.nombres) nombre_completo, f.*
    FROM ins_inscripcion z
    INNER JOIN ins_estudiante e ON z.estudiante_id=e.id_estudiante
    INNER JOIN sys_persona p ON e.persona_id=p.id_persona  
    INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo=z.aula_paralelo_id  
    INNER JOIN ins_aula au ON au.id_aula=ap.aula_id  
    INNER JOIN ins_paralelo pa ON pa.id_paralelo=ap.paralelo_id
    INNER JOIN ins_turno tu ON tu.id_turno=ap.turno_id
    INNER JOIN ins_nivel_academico ni ON ni.id_nivel_academico=au.nivel_academico_id
    INNER JOIN ins_tipo_estudiante te ON te.id_tipo_estudiante=z.tipo_estudiante_id
    LEFT JOIN 
    (SELECT GROUP_CONCAT( CONCAT(pp.nombres,' ', pp.primer_apellido,' ', pp.segundo_apellido) SEPARATOR ' | ') AS nombres_familiar,
    GROUP_CONCAT(f.telefono_oficina SEPARATOR ' | ') AS contacto, ef.estudiante_id
    FROM ins_familiar f 
    INNER JOIN sys_persona pp ON f.persona_id=pp.id_persona
    INNER JOIN ins_estudiante_familiar ef ON ef.familiar_id=f.id_familiar
    GROUP BY ef.estudiante_id
    ) f ON e.id_estudiante=f.estudiante_id
    
    WHERE z.gestion_id=1 ".$sql." 
    ORDER BY 
    p.primer_apellido ASC")->fetch();
/*ORDER BY 
       ins_turno.id_turno asc,
       ins_nivel_academico.id_nivel_academico asc,
       nombre_aula asc,
       nombre_paralelo asc*/
    //pa.nombre_paralelo ASC 
	// Ejecuta un error 404 si no existe los aula_paralelo
	if (!$permiso_listar) { require_once not_found(); exit; }
/*} else {
	// Obtiene el aula_paralelo
	$aula_paralelo = $db->select('z.*, a.nombre_paralelo as paralelo')->from('ins_aula_paralelo z')->join('ins_paralelo a', 'z.paralelo_id = a.id_paralelo', 'left')->where('z.id_aula_paralelo', $id_aula_paralelo)->fetch_first();
	
	// Ejecuta un error 404 si no existe el aula_paralelo
	if (!$aula_paralelo || !$permiso_ver) { require_once not_found(); exit; }
}*/

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
//if ($id_aula_paralelo == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('L');//L H

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'INSCRITOS', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
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
		$body .= '<td>' . escape($aula_paralelo['nombre_completo']) . '</td>';
		$body .= '<td>' . escape($aula_paralelo['numero_documento']) . '</td>';
		$body .= '<td>' . escape($aula_paralelo['nombre_turno']) . '</td>';
		$body .= '<td>' . escape($aula_paralelo['nombre_nivel']) . '</td>';
		$body .= '<td>' . escape($aula_paralelo['nombre_aula']) .'</td>'; 
		$body .= '<td>' . escape($aula_paralelo['nombre_paralelo']) . '</td>';
		$body .= '<td>' . escape($aula_paralelo['nombre_tipo_estudiante']) . '</td>';
		$body .= '<td>' . escape($aula_paralelo['nombres_familiar']) . '</td>';
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
	$tabla .= '<th width="3%">#</th>';
	$tabla .= '<th width="20%"><h3>NOMBRE COMPLETO</h3></th>';
	$tabla .= '<th width="10%"><h3>DOCUMENTO</h3></th>';
	$tabla .= '<th width="7%"><h3>TURNO</h3></th>';
	$tabla .= '<th width="8%"><h3>NIVEL</h3></th>';
	$tabla .= '<th width="7%"><h3>CURSO </h3></th>'; 
	$tabla .= '<th width="7%"><h3>PARALELO</h3> </th>'; 
	$tabla .= '<th width="10%"><h3>TIPO ESTUDAINTE</h3> </th>'; 
	$tabla .= '<th width="27%"><h3>TUTOR</h3> </th>'; 
 
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'aula_paralelo_' . date('Y-m-d_H-i-s') . '.pdf';
/*} else {
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
	$valor_aula_id = escape($aula_paralelo['nombre_completo']);
	$valor_paralelo_id = escape($aula_paralelo['nombre_completo']);
	$valor_capacidad = escape($aula_paralelo['nombre_completo']);
	$valor_estado = escape($aula_paralelo['nombre_completo']);
	$valor_usuario_registro = ($aula_paralelo['nombre_completo'] != '') ? escape($aula_paralelo['nombre_completo']) : 'No anombre_completosignado';
	$valor_fecha_registro = ($aula_paralelo['nombre_completo'] != '') ? escape($aula_paralelo['nombre_completo']) : 'No asignado';
	$valor_usuario_modificacion = ($aula_paralelo['nombre_completo'] != '') ? escape($aula_paralelo['nombre_completo']) : 'No asignado';
	$valor_fecha_modificacion = ($aula_paralelo['fecha_modificacion'] != '') ? escape($aula_paralelo['nombre_completo']) : 'No asignado';
	
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
}*/

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>