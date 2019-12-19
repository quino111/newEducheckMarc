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
			$aula_par_prof_mat_id = isset(($_POST['aula_par_prof_mat_id']))?($_POST['aula_par_prof_mat_id']):0;
			$profesor_materia_id = clear($_POST['id_docente']); 
			$tipoAc = clear($_POST['tipoAc']); 
            
            //ver dubplicados
             $where = array(
                     'aula_paralelo_id' => $aula_paralelo_id,
                    'profesor_materia_id' => $profesor_materia_id
                );
               $result = $db->query("SELECT * FROM int_aula_paralelo_profesor_materia   WHERE aula_paralelo_id=$aula_paralelo_id AND profesor_materia_id=$profesor_materia_id")->fetch();
            if($db->affected_rows==0){
                
           
			// Verifica si es creacion o modificacion
			if ($tipoAc=='edit') {
                // Instancia el aula
                $aula = array(  
                    'profesor_materia_id' => $profesor_materia_id,
                    'usuario_modificacion' => $_user['id_user']

                );

				// Modifica el aula
				$db->where('id_aula_paralelo_profesor_materia', $aula_par_prof_mat_id)->update('int_aula_paralelo_profesor_materia', $aula);
				//echo 'idaula..'.$aula_paralelo_id.' profesor:'.$profesor_materia_id;
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó la sigancion de aula_paralelo_docente con identificador número ' . $aula_paralelo_id . '.',
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
               $aula = array(  
                    'aula_paralelo_id' => $aula_paralelo_id,
                    'profesor_materia_id' => $profesor_materia_id,
                    'usuario_modificacion' => $_user['id_user']

                );
               //$result = $db->query("SELECT * FROM int_aula_paralelo_profesor_materia")->where($where)->fetch();
                                                              
     //->where('profesor_materia_id',$profesor_materia_id)->fetch();
           //echo 'aula_paralelo_id:'.$aula_paralelo_id.' profesor_materia_id:'.$profesor_materia_id;
           //  echo '-nfilas'.$db->affected_rows.'-';
               /*  $res = $db->query("SELECT * FROM int_aula_paralelo_profesor_materia WHERE int_aula_paralelo_profesor_materia.aula_paralelo_id=".$aula_paralelo_id.",int_aula_paralelo_profesor_materia.profesor_materia_id=".$profesor_materia_id)->fetch();*/
               // $row_cnt=0;
                //if ($result = mysqli_query($db, "SELECT * FROM int_aula_paralelo_profesor_materia")) {
               // $row_cnt= mysqli_num_rows($result);
               //     echo $row_cnt;
               // }

               // printf("Result set has %d rows.\n", $row_cnt);
				// Crea el aula
                //verificar que no exista
               
                   $id_aula = $db->insert('int_aula_paralelo_profesor_materia', $aula);

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
           }else{
                   echo 3;
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