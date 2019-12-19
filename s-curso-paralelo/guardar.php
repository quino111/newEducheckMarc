<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion post
if (is_post()) {
	// Verifica la cadena csrf
	//if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos id_asignacion
 
		if (isset($_POST['id_asignacion']) && isset($_POST['capacidad_aula'])) {
			// Obtiene los datos
			//$id_aula = (isset($_POST['id_aula'])) ? clear($_POST['id_aula']) : 0;
			$id_asignacion = clear($_POST['id_asignacion']);
			$capacidad_aula = clear($_POST['capacidad_aula']); 
			// Instancia el aula
			$aula = array( 
				'capacidad' => $capacidad_aula
			);
			
			// Verifica si es creacion o modificacion
			if ($id_asignacion > 0) {
				// Modifica el aula
				$db->where('id_aula_paralelo', $id_asignacion)->update('ins_aula_paralelo', $aula);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó ña sigancion de aula_paralelo con identificador número ' . $id_asignacion . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				//redirect('?/aula/ver/' . $id_aula);
				echo 2;
			} else {
				// Crea el aula
				$id_aula = $db->insert('ins_aula', $aula);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el aula con identificador número ' . $id_aula . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				//redirect('?/aula/listar');
				echo 1;
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	//} else {
		// Redirecciona la pagina
		//redirect('?/aula/listar');
	//}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>