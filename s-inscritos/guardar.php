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
		// Verifica la existencia de datos
		if (isset($_POST['codigo_estudiante']) && isset($_POST['nombre_estudiante'])) {
			// Obtiene los datos
			$id_estudiante = (isset($_POST['id_estudiante'])) ? clear($_POST['id_estudiante']) : 0;
			$codigo_estudiante = clear($_POST['codigo_estudiante']);
			$nombre_estudiante = clear($_POST['nombre_estudiante']);
			
			// Instancia el estudiantes
			$estudiantes = array(
				'codigo_estudiante' => $codigo_estudiante,
				'nombre_estudiante' => $nombre_estudiante
			);
			
			// Verifica si es creacion o modificacion
			if ($id_estudiante > 0) {
				// Modifica el estudiantes
				$db->where('id_estudiante', $id_estudiante)->update('vista_estudiantes', $estudiantes);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el estudiantes con identificador número ' . $id_estudiante . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/estudiantes/ver/' . $id_estudiante);
			} else {
				// Crea el estudiantes
				$id_estudiante = $db->insert('vista_estudiantes', $estudiantes);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el estudiantes con identificador número ' . $id_estudiante . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/estudiantes/listar');
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	} else {
		// Redirecciona la pagina
		redirect('?/estudiantes/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>