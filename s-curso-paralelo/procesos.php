<?php

//var_dump($_POST);die;
//require_once libraries . '/phpexcel-2.1/controlador.php';



$boton = $_POST['boton'];
// Obtiene el id de la gestion actual
$id_gestion = $_gestion['id_gestion'];

if ($boton == "listar_turno") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;
    if (isset($_POST['id_estudiante'])) {
        $id_estudiante = $_POST['id_estudiante'];
    } else {
        $id_estudiante = "";
    }
    //$familiar = $db->select('z.*')->from('vista_estudiante_familiar z')->where('id_estudiante', $id_estudiante)->order_by('z.id_estudiante_familiar', 'asc')->fetch();
    $familiar = $db->query("SELECT * FROM ins_turno  order by hora_inicio asc ")->fetch();
    echo json_encode($familiar);// order by nombre_aula asc
}

if ($boton == "listar_paralelos") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;
    if (isset($_POST['id_estudiante'])) {
        $id_estudiante = $_POST['id_estudiante'];
    } else {
        $id_estudiante = "";
    }
    //$familiar = $db->select('z.*')->from('vista_estudiante_familiar z')->where('id_estudiante', $id_estudiante)->order_by('z.id_estudiante_familiar', 'asc')->fetch();
    $familiar = $db->query("SELECT * FROM ins_paralelo  order by nombre_paralelo asc ")->fetch();
    echo json_encode($familiar);// order by nombre_aula asc
}
if ($boton == "listar_nivel") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;
    if (isset($_POST['turno'])) {
        //$turno = $_POST['turno'];
        // $familiar = $db->query("SELECT * FROM ins_nivel_academico    order by nombre_nivel asc")->fetch();
        /*$familiar = $db->query("SELECT ins_nivel_academico.id_nivel_academico AS id_nivel_academico,ins_nivel_academico.nombre_nivel AS nombre_nivel
        
        FROM ins_aula_paralelo,ins_aula,ins_nivel_academico
        WHERE  ins_aula_paralelo.aula_id=ins_aula.id_aula
            AND ins_aula.nivel_academico_id=ins_nivel_academico.id_nivel_academico
            AND ins_aula_paralelo.turno_id=$turno
        GROUP BY nombre_nivel 
        ORDER BY nombre_nivel ASC")->fetch();*/
        
        
        //NIVEL ins_aula id_aula, nivel_academico_id
        
        
    } else {
        $turno = "";
    }
    $familiar = $db->query("SELECT * FROM ins_nivel_academico    order by nombre_nivel asc")->fetch();
    //$familiar = $db->select('z.*')->from('vista_estudiante_familiar z')->where('id_estudiante', $id_estudiante)->order_by('z.id_estudiante_familiar', 'asc')->fetch();
    echo json_encode($familiar);// order by nombre_aula asc
}

if ($boton == "listar_aulas") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;
    if (isset($_POST['id_estudiante'])) {
        $id_estudiante = $_POST['id_estudiante'];
         
    } else {
        $id_estudiante = "";
    }
    
     if (isset($_POST['nivel'])) {//||$_POST['nivel']!=''||$_POST['nivel']!=0
        $nivel = $_POST['nivel'];
         
    } else {
        $nivel = "";
        
    }
    
    if($nivel==''||$nivel==0){
          $familiar = $db->query("SELECT ins_aula.id_aula,ins_aula.nombre_aula as nombre_aula,ins_nivel_academico.nombre_nivel as nombre_nivel FROM ins_aula,ins_nivel_academico 
    where ins_aula.nivel_academico_id=ins_nivel_academico.id_nivel_academico 
  
    
    order by ins_aula.nivel_academico_id asc,nombre_aula asc")->fetch();
         }else{
              
         $familiar = $db->query("SELECT ins_aula.id_aula,ins_aula.nombre_aula as nombre_aula,ins_nivel_academico.nombre_nivel as nombre_nivel FROM ins_aula,ins_nivel_academico 
    where ins_aula.nivel_academico_id=ins_nivel_academico.id_nivel_academico 
    
    and ins_aula.nivel_academico_id=$nivel
    
    order by ins_aula.nivel_academico_id asc,nombre_aula asc")->fetch();
             
         }
    
    
     //$nivel = isset($_POST['nivel'])?$_POST['nivel']:0;
    //$familiar = $db->select('z.*')->from('vista_estudiante_familiar z')->where('id_estudiante', $id_estudiante)->order_by('z.id_estudiante_familiar', 'asc')->fetch();
   
    /* $familiar = $db->query("SELECT ins_aula.id_aula,ins_aula.nombre_aula as nombre_aula,ins_nivel_academico.nombre_nivel as nombre_nivel 
    
    FROM ins_aula_paralelo,ins_aula,ins_nivel_academico,ins_turno
    
    WHERE ins_aula_paralelo.turno_id=ins_turno.id_turno
    and ins_aula_paralelo.aula_id=ins_aula.id_aula
    and ins_aula.nivel_academico_id=ins_nivel_academico.id_nivel_academico 
    and ins_aula_paralelo.turno_id=1
    
    order by ins_aula.nivel_academico_id asc,nombre_aula asc")->fetch();*/
    echo json_encode($familiar);// order by nombre_aula asc
} 

if ($boton == "listar_paralelos_todos_T") {
  // $familiar = $db->query("SELECT * FROM ins_paralelo")->fetch();
    if (isset($_POST['aula'])){//&&isset($_POST['turno'])&&isset($_POST['nivel'])) {
        $aula = $_POST['aula'];
        //$turno = $_POST['turno'];
        //$nivel = $_POST['nivel'];
    } else {
        $aula = 0;
        echo 'esto es un error';
    } 
    
    $turno=isset($_POST['turno'])?$_POST['turno']:0;//turno
    //$nivel=isset($_POST['nivel'])?$_POST['nivel']:0;//turno
    
    $sql="";
    if($turno){
        $sql.=" and ins_aula_paralelo.turno_id=$turno";
    } 
    if($aula){
        $sql.=" and  ins_aula_paralelo.aula_id=$aula";
    }
    $familiar = $db->query("SELECT ins_aula.nombre_aula as nombre_aula,ins_paralelo.descripcion AS descripcion, capacidad,ins_turno.nombre_turno as turno , capacidad,ins_turno.nombre_turno as turno,ins_nivel_academico.nombre_nivel as nombre_nivel, ins_aula_paralelo.id_aula_paralelo as id_aula_paralelo
    FROM ins_aula_paralelo,ins_aula,ins_paralelo,ins_turno,ins_nivel_academico                  
    WHERE 
                       ins_aula_paralelo.aula_id=ins_aula.id_aula
                       and ins_aula_paralelo.paralelo_id=ins_paralelo.id_paralelo 
                       and ins_turno.id_turno=ins_aula_paralelo.turno_id 
                       and ins_aula.nivel_academico_id=ins_nivel_academico.id_nivel_academico 
                       and ins_aula_paralelo.estado='A'
                        ".$sql)->fetch(); 
    
    
        // ins_turno.nombre_turno as turno    --- and ins_turno.id_turno=ins_aula_paralelo.turno_id
    echo json_encode($familiar);
 
}

if ($boton == "listar_paralelos_T") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;
    if (isset($_POST['aula'])){//&&isset($_POST['turno'])&&isset($_POST['nivel'])) {
        $aula = $_POST['aula'];
        //$turno = $_POST['turno'];
        //$nivel = $_POST['nivel'];
    } else {
        $aula = 0;
        echo 'esto es un error';
    } 
    
    $turno=isset($_POST['turno'])?$_POST['turno']:0;//turno
    //$nivel=isset($_POST['nivel'])?$_POST['nivel']:0;//turno
    
    $sql="";
    if($turno){
        $sql.=" and ins_aula_paralelo.turno_id=$turno";
    } 
    if($aula){
        $sql.=" and  ins_aula_paralelo.aula_id=$aula";
    }
    $familiar = $db->query("SELECT ins_aula.nombre_aula as nombre_aula,ins_paralelo.descripcion AS descripcion, capacidad,ins_turno.nombre_turno as turno , capacidad,ins_turno.nombre_turno as turno,ins_nivel_academico.nombre_nivel as nombre_nivel, ins_aula_paralelo.id_aula_paralelo as id_aula_paralelo
    FROM ins_aula_paralelo,ins_aula,ins_paralelo,ins_turno,ins_nivel_academico                  
    WHERE 
                       ins_aula_paralelo.aula_id=ins_aula.id_aula
                       and ins_aula_paralelo.paralelo_id=ins_paralelo.id_paralelo 
                       and ins_turno.id_turno=ins_aula_paralelo.turno_id 
                        and ins_aula_paralelo.estado='A'
                       and ins_aula.nivel_academico_id=ins_nivel_academico.id_nivel_academico ".$sql)->fetch(); 
    
    /*$familiar = $db->query("SELECT ins_aula.nombre_aula as nombre_aula,ins_paralelo.descripcion AS descripcion, capacidad ,ins_aula_paralelo.turno_id as turno
    FROM ins_aula_paralelo,ins_aula,ins_paralelo                   
    WHERE 
                       ins_aula_paralelo.aula_id=ins_aula.id_aula
                       and ins_aula_paralelo.paralelo_id=ins_paralelo.id_paralelo 
                        ".$sql)->fetch();*/// and  aula_id=$nivel 
    echo json_encode($familiar);
    
    /*$familiar = $db->query("SELECT id_aula_paralelo,aula_id,paralelo_id,capacidad FROM ins_aula_paralelo where aula_id=$aula and estado='A'")->fetch();
    echo json_encode($familiar);*/
}

if ($boton == "crear_curso_paralelo") {
   if (isset($_POST['aula'])&&isset($_POST['paralelo'])&&isset($_POST['Iparacidad'])) {
        $turno = $_POST['turno'];
        $aula = $_POST['aula'];
        $paralelo = $_POST['paralelo'];
        $Iparacidad = $_POST['Iparacidad'];
       
        //$familiar = $db->query("INSERT INTO `ins_aula_paralelo` (`id_aula_paralelo`, `aula_id`, `paralelo_id`, `capacidad`, `estado`, `usuario_registro`, `fecha_registro`, `usuario_modificacion`, `fecha_modificacion`) VALUES (NULL, '$aula', '$paralelo','$Iparacidad', 'A', '1', NOW(), '', NOW());")->fetch();
       //ver si no existe
       
       $sql = "SELECT * FROM ins_aula_paralelo WHERE aula_id='$aula' and paralelo_id='$paralelo'";
       $versiexiste=$db->query($sql);
       
     
       // $sql = "INSERT INTO ins_aula_paralelo (id_aula_paralelo, aula_id, paralelo_id, capacidad, estado, usuario_registro, fecha_registro, usuario_modificacion, fecha_modificacion) VALUES (NULL, '$aula', '$paralelo','$Iparacidad', 'A', '1', NOW(), '', NOW())";
       //$dato=$db->query($sql);
       
  if($versiexiste){
           
       $data = array(
    'id_aula_paralelo' => NULL,
    'aula_id' => $aula, 
    'paralelo_id' => $paralelo,
    'capacidad' => $Iparacidad,
    'estado' => 'A',
    'usuario_registro' => '1',
    'fecha_registro' => 'NOW()',
    'usuario_modificacion' => '',
    'fecha_modificacion' => 'NOW()',
    'turno_id' => $turno
     );
    $dato = $db->insert('ins_aula_paralelo', $data) ;
}
       
       
       
       if ($dato) {
            echo 1;//$dato;//"Se guardo con exito22";
        } else {
            echo "Error: ".$sql . "<br>" . $db->error;
        }
       
       
       
       
    } else {
        
        echo 'No se envio los datos correctante';
    }
    
 
}



?>
