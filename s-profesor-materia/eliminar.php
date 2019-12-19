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
	if (isset($_POST[get_csrf()])) {
		// Obtiene los parametros
		$id_profesor_materia = (isset($_params[0])) ? $_params[0] : 0;
		
		// Obtiene el profesor_materia
		$profesor_materia = $db->from('vista_profesor_materia')->where('id_profesor_materia', $id_profesor_materia)->fetch_first();
		
		// Verifica si existe el profesor_materia
		if ($profesor_materia) {
			// Elimina el profesor_materia
			$db->delete()->from('vista_profesor_materia')->where('id_profesor_materia', $id_profesor_materia)->limit(1)->execute();
			
			// Verifica la eliminacion
			if ($db->affected_rows) {
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'd',
					'nivel' => 'm',
					'detalle' => 'Se eliminó el a profesor materia con identificador número ' . $id_profesor_materia . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Eliminación exitosa!', 'El registro se eliminó satisfactoriamente.');
			} else {
				// Crea la notificacion
				set_notification('danger', 'Eliminación fallida!', 'El registro no pudo ser eliminado.');
			}
			
			// Redirecciona la pagina
			redirect('?/profesor_materia/listar');
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	} else {
		// Redirecciona la pagina
		redirect('?/profesor_materia/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>