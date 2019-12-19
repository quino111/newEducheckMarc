<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   MARIBEL, JORGE, LUIS
 */
//Variables 
$fecha_hora = date('Y-m-d')." ".date('H:i:s');

// Verifica la peticion post
if (is_post()) {
	//verificamos las existencias de las variables
	if (isset($_POST['id_docente']) && isset($_POST['id_curso_paralelo']) && isset($_POST['id_asignatura'])) { 
	//Recibimos las variables para añadir
	$id_gestion = (isset($_POST['id_gestion'])) ? clear($_POST['id_gestion']) : 0;
	$id_docente = clear($_POST['id_docente']);
	$id_asignatura = clear($_POST['id_asignatura']);
	$id_curso_paralelo = clear($_POST['id_curso_paralelo']);
	
	//Instancias para añadir a la tabla pro_profesor_materia
	$aPPM = array("profesor_id"=>$id_docente, "materia_id"=>$id_asignatura);
	$profesor_materia_id = $db->insert('pro_profesor_materia', $aPPM);//Devuelve el id insertado

	//var_dump($aPPM); exit;	

	//Instancia int_aula_paralelo_profesor_materia => aIAPPM
	$aIAPPM = array(
		'aula_paralelo_id' =>$id_curso_paralelo ,
		'profesor_materia_id' => $profesor_materia_id,
		'estado' => "A",
		'usuario_registro' =>"1",
		'fecha_registro' =>$fecha_hora
	);

	//var_dump($aIAPPM); exit;

	//Verificamos si es creacio o modificacion
		// Guarda el proceso a la tabla int_aula_paralelo_profesor_materia
		$db->insert('int_aula_paralelo_profesor_materia', $aIAPPM);			
		echo 2;
	
	/*if($id_gestion > 0){

	}else{

	}*/




	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>