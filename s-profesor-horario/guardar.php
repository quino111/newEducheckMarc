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
 
		if (isset($_POST['aula_paralelo_id']) && isset($_POST['id_docente'])) {
			// Obtiene los datos
			//$id_aula = (isset($_POST['id_aula'])) ? clear($_POST['id_aula']) : 0;
			$aula_paralelo_id = clear($_POST['aula_paralelo_id']);
			$profesor_materia_id = isset(($_POST['id_docente']))?($_POST['id_docente']):0;
            $tipoAc = clear($_POST['tipoAc']); //new
            $hora_inicio = clear($_POST['hora_inicio']); //horaInicio
            $hora_fin = clear($_POST['hora_fin']); //hora_fin
            $fecha_actual = date('Y-m-d H:i:s');
            
			$id_horario_profesor_materia =  isset(($_POST['aula_par_prof_mat_id']))?($_POST['aula_par_prof_mat_id']):0;//solo en edit?
            
            //ver dubplicados
             $where = array(
                     'curso_paralelo_id' => $aula_paralelo_id,
                    'profesor_materia_id' => $profesor_materia_id,
                    'estado'=>'A'
                );
  
                
           
			// Verifica si es creacion o modificacion
			if ($tipoAc=='edit') {
                // Instancia el aula
                $aula = array(  
                     
                    'hora_inicio' => $hora_inicio,
                    'hora_fin' => $hora_fin,
                    'curso_paralelo_id' => $aula_paralelo_id,
                    'profesor_materia_id' => $profesor_materia_id,
                   
                    'usuario_modificacion' => $_user['id_user']
                );

				// Modifica el aula
				$db->where('id_horario_profesor_materia', $id_horario_profesor_materia)->update('ins_horario_profesor_materia', $aula);
				//echo 'idaula..'.$aula_paralelo_id.' profesor:'.$profesor_materia_id;
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó la sigancion de horario_paralelo_materiacon identificador número ' . $id_horario_profesor_materia . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				//redirect('?/aula/ver/' . $id_aula);
				echo 2;
			} else 
            
            if ($tipoAc=='new')
            {
                //verificar que no exista
              $result = $db->query("SELECT * FROM ins_horario_profesor_materia   WHERE curso_paralelo_id=$aula_paralelo_id AND profesor_materia_id=$profesor_materia_id AND 'estado'!='I'")->fetch();
               // $result = $db->query("SELECT * FROM ins_horario_profesor_materia")->where($where)->fetch();
           //$num=$db->affected_row;
            //echo 'num filas afect'.$num;
            //echo '---'.$aula_paralelo_id.' ---'.$profesor_materia_id;
            if(!$result){
           // if($db->affected_row==0){
               $aula = array(  
                    'hora_inicio' => $hora_inicio,
                    'hora_fin' => $hora_fin,
                    'curso_paralelo_id' => $aula_paralelo_id,
                    'profesor_materia_id' => $profesor_materia_id,
                    'estado' => 'A',
                    'usuario_registro' => $_user['id_user'],
                    'fecha_registro'=>$fecha_actual

                );
 
                //verificar que no exista
               
                   $id_aula = $db->insert('ins_horario_profesor_materia', $aula);

                    // Guarda el proceso
                    $db->insert('sys_procesos', array(
                        'fecha_proceso' => date('Y-m-d'),
                        'hora_proceso' => date('H:i:s'),
                        'proceso' => 'c',
                        'nivel' => 'l',
                        'detalle' => 'Se creó el horarios con identificador número ' . $id_aula . '.',
                        'direccion' => $_location,
                        'usuario_id' => $_user['id_user']
                    ));

                    // Crea la notificacion
                    //set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');

                    // Redirecciona la pagina
                    //redirect('?/aula/listar');
                    echo 1;
              
            } else{
                   echo 3;
            }   
                
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