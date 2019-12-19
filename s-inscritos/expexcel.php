 
<?php

//var_dump($_POST);die;
require_once libraries . '/phpexcel-2.1/controlador.php';



/*$boton = $_POST['boton'];
// Obtiene el id de la gestion actual
$id_gestion = $_gestion['id_gestion'];

if ($boton == "listar_familiares") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;
    if (isset($_POST['id_estudiante'])) {
        $id_estudiante = $_POST['id_estudiante'];
    } else {
        $id_estudiante = "";
    }
        $id_estudiante = 1;*/
    
    //$familiar = $db->select('z.*')->from('vista_estudiante_familiar z')->where('id_estudiante', $id_estudiante)->order_by('z.id_estudiante_familiar', 'asc')->fetch();
    //$familiar = $db->query("SELECT * FROM vista_estudiante_familiar WHERE id_estudiante = '$id_estudiante'")->fetch();
/*      $familiar = $db->query("SELECT * FROM vista_estudiante_familiar    WHERE id_estudiante = 1")->fetch();
    echo json_encode($familiar);*/
//}



//
//
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
   
    $objPHPExcel = excel_iniciar("plantilla_inscritos.xls");
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



    //Consultamos todos los datos del rude
    //$resRUDE = $db->query("SELECT * FROM ins_inscripcion")->fetch();
    /*echo "<pre>";
        var_dump($resRUDE);exit;
        echo "</pre>"; */

    $total = sizeof($aula_paralelo);


    //si hay registros, colocar datos en las celdas de la hoja actual

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
             $objPHPExcel->getActiveSheet()->setCellValue( 'B' . $fil, escape($row['nombre_completo']));//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'C' . $fil, $row['numero_documento']);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'd' . $fil, $row['nombre_turno']);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'E' . $fil, $row['nombre_nivel']);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'F' . $fil, $row['nombre_aula']);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'G' . $fil, $row['nombre_paralelo']);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'H' . $fil, $row['nombre_tipo_estudiante']);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'I' . $fil, $row['nombres_familiar']);//('A10', "X");
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
        /*
           $filaExcel = 9;  //indice de fila en excel
           for ($i = 0; $i < $filaExcel; $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '16', $total);
            $num++;
        }*/

       /* $dep = $resEstudiante[0]['segundo_apellido'];
        $col = 2;
        for ($i = 0; $i < strlen($resEstudiante[0]['segundo_apellido']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '18', $dep[$i]);
            $col++;
        }

        $dep = $resEstudiante[0]['nombres'];
        $col = 2;
        for ($i = 0; $i < strlen($resEstudiante[0]['nombres']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '20', $dep[$i]);
            $col++;
        }

        $dep = $resEstudiante[0]['genero'];
        $col = 34;
        if ($dep == 'v') {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '18', "X");
            $col++;
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '20', "X");
            $col++;
        }*/



    /*    $objPHPExcel->getActiveSheet()->setCellValue('B30', $resRUDE[0]['oficialia']);
        $objPHPExcel->getActiveSheet()->setCellValue('E30', $resRUDE[0]['partida']);
        $objPHPExcel->getActiveSheet()->setCellValue('I30', $resRUDE[0]['libro']);
        $objPHPExcel->getActiveSheet()->setCellValue('M69', $resRUDE[0]['folio']);
*/
      /*  $dep = $resRUDE[0]['departamento'];
        $col = 7;
        for ($i = 0; $i < strlen($resRUDE[0]['departamento']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '42', $dep[$i]);
            $col++;
        }*/

     /*  if ($resRUDE[0]['421'] == "on") {
            $objPHPExcel->getActiveSheet()->setCellValue('A10', "X");
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue('A10', "X");
        }*/
        /*$dep = $resRUDE[0]['provincia'];
        $col = 7;
        for ($i = 0; $i < strlen($resRUDE[0]['provincia']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '44', $dep[$i]);
            $col++;
        }

        $dep = $resRUDE[0]['seccion'];
        $col = 7;
        for ($i = 0; $i < strlen($resRUDE[0]['seccion']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '46', $dep[$i]);
            $col++;
        }

        $dep = $resRUDE[0]['localidad'];
        $col = 7;
        for ($i = 0; $i < strlen($resRUDE[0]['localidad']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '48', $dep[$i]);
            $col++;
        }


        $dep = $resRUDE[0]['zona'];
        $col = 7;
        for ($i = 0; $i < strlen($resRUDE[0]['zona']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '50', $dep[$i]);
            $col++;
        }

        $dep = $resRUDE[0]['avenida'];
        $col = 7;
        for ($i = 0; $i < strlen($resRUDE[0]['avenida']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '52', $dep[$i]);
            $col++;
        }

        $dep = $resRUDE[0]['nrovivienda'];
        $col = 7;
        for ($i = 0; $i < strlen($resRUDE[0]['nrovivienda']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '54', $dep[$i]);
            $col++;
        }

        $dep = $resRUDE[0]['telefono'];
        $col = 22;
        for ($i = 0; $i < strlen($resRUDE[0]['telefono']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '54', $dep[$i]);
            $col++;
        }

        $dep = $resRUDE[0]['celular'];
        $col = 37;
        for ($i = 0; $i < strlen($resRUDE[0]['celular']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '54', $dep[$i]);
            $col++;
        }

*/
        /*$objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['411']);
            $objPHPExcel->getActiveSheet()->setCellValue('B79',$resRUDE[0]['412']);
            $objPHPExcel->getActiveSheet()->setCellValue('I62',$resRUDE[0]['413']);*/

/*
       if ($resRUDE[0]['421'] == "on") {
            $objPHPExcel->getActiveSheet()->setCellValue('A10', "X");
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue('A10', "X");
        }*/
 /*
        $objPHPExcel->getActiveSheet()->setCellValue('B69', $resRUDE[0]['422']);
        $objPHPExcel->getActiveSheet()->setCellValue('B69', $resRUDE[0]['423']);

        if ($resRUDE[0]['424'] == "on") {
            $objPHPExcel->getActiveSheet()->setCellValue('AK83', "X");
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue('AQ83', "X");
        }

        if ($resRUDE[0]['431'] == "on") {
            $objPHPExcel->getActiveSheet()->setCellValue('D89', "X");
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue('H89', "X");
        }

        if ($resRUDE[0]['432'] == "on") {
            $objPHPExcel->getActiveSheet()->setCellValue('D93', "X");
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue('H93', "X");
        }

        if ($resRUDE[0]['433'] == "on") {
            $objPHPExcel->getActiveSheet()->setCellValue('D97', "X");
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue('H97', "X");
        }

        if ($resRUDE[0]['434'] == "on") {
            $objPHPExcel->getActiveSheet()->setCellValue('S89', "X");
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue('W89', "X");
        }

        if ($resRUDE[0]['435'] == "on") {
            $objPHPExcel->getActiveSheet()->setCellValue('S95', "X");
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue('W95', "X");
        }*/

        /*$objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['436']);
            
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['441']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['442']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['451']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['4511']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['452']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['4521']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['453']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['454']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['455']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['4551a']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['51']);*/



        //$objPHPExcel->getActiveSheet()->setCellValue('B' . $filaNotas,$fila_estudiante['apellidos'].' '.$fila_estudiante['nombres']);

    }

    //exit;     
    //-------------------------------------------------- finalizar
    //mostrar la primera hoja de excel
    //seleccionar una hoja
    $objPHPExcel->setActiveSheetIndex(0);   //la primera Hoja de excel se numera como 0
    $objPHPExcel->getActiveSheet()->setCellValue('A1', '');
    excel_finalizar($objPHPExcel, "inscritos.xls");
//}

/*
//include_once('config.php'); include('Classes/PHPExcel.php');
 require_once libraries . '/phpexcel-2.1/controlador.php';
$objPHPExcel    =   new PHPExcel();
$result         =   $db->query("SELECT * FROM ins_inscripcion") or die(mysql_error());
 
$objPHPExcel->setActiveSheetIndex(0);
 
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Country Code');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Country Name');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Capital');
 
$objPHPExcel->getActiveSheet()->getStyle("A1:C1")->getFont()->setBold(true);
 
$rowCount   =   2;
while($row  =   $result->fetch_assoc()){
    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, mb_strtoupper($row['countryCode'],'UTF-8'));
    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, mb_strtoupper($row['countryName'],'UTF-8'));
    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, mb_strtoupper($row['capital'],'UTF-8'));
    $rowCount++;
}
 
 
$objWriter  =   new PHPExcel_Writer_Excel2007($objPHPExcel);
 
 
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="you-file-name.xlsx"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
$objWriter->save('php://output');
*/

?>