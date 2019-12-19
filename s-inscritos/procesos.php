<?php


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
    if (isset($_POST['aula'])) {
        if($_POST['aula']!='' || $_POST['aula']!=0)
        $sql ="WHERE ap.aula_id=".$_POST['aula'];
        else
         $sql = "";   
    } else {
        $sql = "";
    }
    //$familiar = $db->select('z.*')->from('vista_estudiante_familiar z')->where('id_estudiante', $id_estudiante)->order_by('z.id_estudiante_familiar', 'asc')->fetch();
    $familiar = $db->query("SELECT p.id_paralelo,p.nombre_paralelo FROM ins_inscripcion i
 INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo=i.aula_paralelo_id
  INNER JOIN ins_paralelo p ON p.id_paralelo=ap.paralelo_id  
  INNER JOIN ins_aula au ON au.id_aula=ap.aula_id  
  ".$sql."
   GROUP BY p.nombre_paralelo")->fetch();
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
 

if ($boton == "listar_paralelos_asignados") {
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
    $paralelo=isset($_POST['paralelo'])?$_POST['paralelo']:0;//turno
   // $todo=isset($_POST['todo'])?$_POST['todo']:false;//turno
    
    $sql="";
    if($turno){
        $sql.=" and ap.turno_id=$turno";
       // $sql.=" and ins_aula_paralelo.turno_id=$turno";
    } 
    if($aula){
        $sql.=" and  ap.aula_id=$aula";
    } if($paralelo){
        $sql.=" and  pa.id_paralelo=$paralelo";
    }
 
// Obtiene los estudiantes
 
/*$consulta="SELECT z.*,p.numero_documento, CONCAT(z.primer_apellido,' ',z.segundo_apellido,' ',z.nombres) nombre_completo, f.*
    FROM vista_inscripciones z
    INNER JOIN ins_estudiante e ON z.estudiante_id=e.id_estudiante
    INNER JOIN sys_persona p ON e.persona_id=p.id_persona
    LEFT JOIN 
    (SELECT GROUP_CONCAT( CONCAT(pp.nombres,' ', pp.primer_apellido,' ', pp.segundo_apellido) SEPARATOR ' | ') AS nombres_familiar,
    GROUP_CONCAT(f.telefono_oficina SEPARATOR ' | ') AS contacto, ef.estudiante_id
    FROM ins_familiar f 
    INNER JOIN sys_persona pp ON f.persona_id=pp.id_persona
    INNER JOIN ins_estudiante_familiar ef ON ef.familiar_id=f.id_familiar
    GROUP BY ef.estudiante_id
    ) f ON e.id_estudiante=f.estudiante_id
    
    WHERE z.gestion_id=1
    
    ORDER BY z.primer_apellido ASC";*/
    
    $consulta="SELECT z.*,p.numero_documento,ap.*,au.*,pa.*,ni.*,te.*,tu.*, CONCAT(p.primer_apellido,' ',p.segundo_apellido,' ',p.nombres) nombre_completo, f.*
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
    
    WHERE z.gestion_id=1 
    ".$sql;// ORDER BY p.primer_apellido ASC
    $inscritos = $db->query($consulta)->fetch();
       
    echo json_encode($inscritos); 
 
}

?>