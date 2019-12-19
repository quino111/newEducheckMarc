 
<?php

//var_dump($_POST);die;
require_once libraries . '/phpexcel-2.1/controlador.php';
 
$inst = $db->query("SELECT * FROM SYS_INSTITUCIONES WHERE id_institucion=1")->fetch();


//if ($boton == "reporte_rude") {

   // $id_estudiante       =1; //$_REQUEST['id_estudiante'];
   // $id_inscripcion_rude =1;// $_REQUEST['id_inscripcion_rude'];
   // $id_aula_paralelo    = 1;//$_REQUEST['id_inscripcion'];

    $turno=isset($_params[0])?$_params[0]:0;
    $nivel=isset($_params[1])?$_params[1]:0;
    $aula=isset($_params[2])?$_params[2]:0; 
   $paralelo=isset($_params[3])?$_params[3]:0; 
    //var_dump($_REQUEST);exit();

    //$id_inscripcion_rude = $_REQUEST['id_inscripcion_rude'];

/*    $columna = array(
        '1' => 'A', '2' => 'B', '3' => 'C', '4' => 'D', '5' => 'E', '6' => 'F', '7' => 'G', '8' => 'H', '9' => 'I', '10' => 'J', '11' => 'K', '12' => 'L', '13' => 'M', '14' => 'N', '15' => 'O', '16' => 'P', '17' => 'Q', '18' => 'R', '19' => 'S', '20' => 'T', '21' => 'U', '22' => 'V', '23' => 'W', '24' => 'X', '25' => 'Y', '26' => 'Z', '27' => 'AA', '27' => 'AA', '28' => 'AB', '29' => 'AC', '30' => 'AD', '31' => 'AE', '32' => 'AF', '33' => 'AG', '34' => 'AH', '35' => 'AI', '36' => 'AJ', '37' => 'AK', '38' => 'AL', '39' => 'AM', '40' => 'AN', '41' => 'AO', '42' => 'AP', '43' => 'AQ', '44' => 'AR', '45' => 'AS', '46' => 'AT', '47' => 'AU', '48' => 'AV', '49' => 'AW', '50' => 'AX'
    );*/

    //Colores RGB
/*    $aColores = array('1' => 'ECEA5C', '2' => '8AE245', '3' => 'F577F5', '4' => '537AF5', '5' => 'F35F7F', '6' => 'F752F5', '7' => 'AAFF00');*/

 $sql="";
    if($turno){
        $sql.=" and ap.turno_id=$turno";
    }

    if($aula){
        $sql.=" and  ap.aula_id=$aula";
    } if($paralelo){
        $sql.=" and  pa.id_paralelo=$paralelo";
    }
   
    $objPHPExcel = excel_iniciar("plantilla_curso_paralelo_profesor_materia.xls");
//plantilla_inscritosant.xls");//plantilla_inscritos.xlsx");

 foreach($inst as $rowins )
        {
     //$objPHPExcel->getActiveSheet()->setRowHigth(1,10);
     $objPHPExcel->getActiveSheet()->setCellValue( 'e1', $rowins['nombre']);
     $objPHPExcel->getActiveSheet()->setCellValue( 'e2', $rowins['lema']);
     $objPHPExcel->getActiveSheet()->setCellValue( 'e3',date('Y-m-d').' '.date('H:i:s'));
     }
    //var_dump($objPHPExcel);die;  

    //Consultamos al estudiante
    	$aula_paralelo = $db->query("SELECT 
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
       h.primer_apellido asc")->fetch();

 

    $total = sizeof($aula_paralelo);
 
    if ($total > 0) {

        //$dep = $aula_paralelo[0]['primer_apellido'];
        $num=1;
        $col = 1;
        $fil = 7;
        /*while($row=mysqli_fetch_array($resEstudiante)){
             $objPHPExcel->getActiveSheet()->setCellValue('A10', $total);//('A10', "X");
            $col++;
        }*/
        foreach($aula_paralelo as $row )
        {
             $objPHPExcel->getActiveSheet()->setCellValue( 'A' . $fil, $num);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'B' . $fil, escape($row['nombre_turno']));//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'C' . $fil, $row['nombre_aula']);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'd' . $fil, $row['nombre_nivel']);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'E' . $fil, 'Paralelo '.$row['nombre_paralelo']);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'F' . $fil, $row['primer_apellido'] . ' '.$row['segundo_apellido'] . ' '.$row['nombres']);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'G' . $fil, $row['nombre_materia'] );//('A10', "X");
             //$objPHPExcel->getActiveSheet()->setCellValue( 'H' . $fil, $row['nombre_materia']);//('A10', "X");
             //$objPHPExcel->getActiveSheet()->setCellValue( 'I' . $fil, $row['nombres_familiar']);//('A10', "X");
            $fil++;
            $num++;
        }
/*nombre_completo
numero_documento
nombre_turno
nombre_nivel
nombre_aula
nombre_paralelo
nombre_tipo_estudiante
nombres_familiar*/
  

    }

    //exit;     
    //-------------------------------------------------- finalizar
    //mostrar la primera hoja de excel
    //seleccionar una hoja
    $objPHPExcel->setActiveSheetIndex(0);   //la primera Hoja de excel se numera como 0
    $objPHPExcel->getActiveSheet()->setCellValue('A1', '');
    excel_finalizar($objPHPExcel, "curso_paralelo_profesor_materia.xls");


?>