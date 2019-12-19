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
		// Obtiene los parametros
		//$id_gestion = (isset($_params[0])) ? $_params[0] : 0;
		$id_aula_paralelo = (isset($_POST['id_aula_paralelo'])) ? $_POST['id_aula_paralelo'] : 0;
		// Obtiene el modo
		$modo = $db->from('ins_aula_paralelo')->where('id_aula_paralelo', $id_aula_paralelo)->fetch_first();
		
		// Verifica si existe el modo
		if ($modo) {
			$fecha_actual = date('Y-m-d H:i:s');//obtiene la fecha actual

			//ejecuta la eliminacion logica de la modo de calioficación 
			$est=$db->query("UPDATE ins_aula_paralelo SET estado = 'I', usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".$fecha_actual."' WHERE id_aula_paralelo = '".$id_aula_paralelo."'")->execute();
			//$est=$db->query("UPDATE ins_aula_paralelo SET estado = 'I' WHERE id_aula_paralelo =13")->execute();
            /*$datass = array(
                'estado' => 'I' 
            );
             
            $est=$db->where('id_aula_paralelo', $id_aula_paralelo)->update('ins_aula_paralelo', $datass);
            */
			if($est){
               // echo 'ok'.$id_aula_paralelo;
            }else{
                echo 'error al eliminar'.$id_aula_paralelo;
                
            }

			// Verifica la eliminacion
			if ($db->affected_rows) {
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'd',
					'nivel' => 'm',
					'detalle' => 'Se eliminó el modo la asignacion con identificador número ' . $id_aula_paralelo . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Eliminación exitosa!', 'El registro se eliminó satisfactoriamente.');
			} else {
				// Crea la notificacion
				//set_notification('danger', 'Eliminación fallida!', 'El registro no pudo ser eliminado.');
			}
			
			// Redirecciona la pagina
			//redirect('?/gestiones/listar');
			echo 1; //se elimino correctamente
		} else {
			// Error 400
			/*require_once bad_request();
			exit;*/
			echo 2; //no se encontro el registro que se quiere eliminar
		}
	//} else {
		// Redirecciona la pagina
		//redirect('?/gestiones/listar');
	//}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>