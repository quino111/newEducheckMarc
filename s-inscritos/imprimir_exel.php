 
<?php

//var_dump($_POST);die;
require_once libraries . '/phpexcel-2.1/controlador.php';



$boton = $_POST['boton'];
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
        $id_estudiante = 1;
    
    //$familiar = $db->select('z.*')->from('vista_estudiante_familiar z')->where('id_estudiante', $id_estudiante)->order_by('z.id_estudiante_familiar', 'asc')->fetch();
    //$familiar = $db->query("SELECT * FROM vista_estudiante_familiar WHERE id_estudiante = '$id_estudiante'")->fetch();
      $familiar = $db->query("SELECT * FROM vista_estudiante_familiar    WHERE id_estudiante = 1")->fetch();
    echo json_encode($familiar);
}

if ($boton == "listar_tipo_documento") {
    $respuesta = $db->query("SELECT * FROM catalogo_detalle WHERE catalogo_id = 1")->fetch();
    echo json_encode($respuesta);
}

if ($boton == "datos_estudiante") {

    $id_estudiante = $_POST['id_estudiante'];

    $array = array();

    $datos_personales = $db->query("SELECT e.id_estudiante,
                                               e.codigo_estudiante,
                                               e.rude,
                                               e.aula_paralelo_id,
                                               p.nombres,
                                               p.primer_apellido,
                                               p.segundo_apellido,
                                               p.tipo_documento,
                                               p.numero_documento,
                                               p.complemento,
                                               p.genero,
                                               p.fecha_nacimiento,
                                               p.direccion,
                                               p.foto
                                        FROM ins_estudiante e 
                                        LEFT JOIN sys_persona p ON p.id_persona = e.persona_id
                                        WHERE e.id_estudiante = $id_estudiante")->fetch_first();
    $array['datos_personales'] = $datos_personales;

    $vacunas = $db->query("SELECT * FROM ins_vacunas WHERE estudiante_id = $id_estudiante")->fetch_first();
    $array['vacunas'] = $vacunas;

    echo json_encode($array);
}

if ($boton == "vacunas") {
    $id_estudiante = $_POST['id_estudiante'];
    if (isset($_POST['bcg'])) {
        $bcg = 'SI';
    } else {
        $bcg = 'NO';
    }

    if (isset($_POST['a1'])) {
        $a1 = "SI";
    } else {
        $a1 = "NO";
    }

    if (isset($_POST['a2'])) {
        $a2 = "SI";
    } else {
        $a2 = "NO";
    }

    if (isset($_POST['a3'])) {
        $a3 = "SI";
    } else {
        $a3 = "NO";
    }

    if (isset($_POST['p1'])) {
        $p1 = "SI";
    } else {
        $p1 = "NO";
    }

    if (isset($_POST['p2'])) {
        $p2 = "SI";
    } else {
        $p2 = "NO";
    }

    if (isset($_POST['p3'])) {
        $p3 = "SI";
    } else {
        $p3 = "NO";
    }

    if (isset($_POST['am1'])) {
        $am1 = "SI";
    } else {
        $am1 = "NO";
    }

    if (isset($_POST['srp1'])) {
        $srp1 = "SI";
    } else {
        $srp1 = "NO";
    }

    if (isset($_POST['o1'])) {
        $o1 = "SI";
    } else {
        $o1 = "NO";
    }

    if (isset($_POST['observaciones_vacunas'])) {
        $observaciones_vacunas = $_POST['observaciones_vacunas'];
    } else {
        $observaciones_vacunas = "";
    }


    $busqueda = $db->query("SELECT v.id_vacuna, v.estudiante_id 
								FROM ins_vacunas v
								WHERE v.estudiante_id = $id_estudiante ")->fetch_first();
    $id_vacuna = $busqueda['id_vacuna'];

    if ($id_vacuna != null) {
        // Modifica los datos del certificado de nacimiento
        $db->where('id_vacuna', $id_vacuna)->update('ins_vacunas', array(
            'estudiante_id' => $id_estudiante,
            'tuberculosis' => $bcg,
            'antipolio_1' => $a1,
            'antipolio_2' => $a2,
            'antipolio_3' => $a3,
            'pentavalente_1' => $p1,
            'pentavalente_2' => $p2,
            'pentavalente_3' => $p3,
            'antiamarilla' => $am1,
            'mmr_unica' => $srp1,
            'otra' => $o1,
            'observaciones' => $observaciones_vacunas,
            'estado' => 'A',
            'usuario_registro' => $_user['id_user'],
            'fecha_registro' => date('Y-m-d H:i:s')
        ));

        $cadena = array(
            'id_estudiante' => $id_estudiante,
            'estado' => 2
        );
    } else {
        //var_dump($_POST);die;
        // Guarda el proceso de vacunas
        $db->insert('ins_vacunas', array(
            'estudiante_id' => $id_estudiante,
            'tuberculosis' => $bcg,
            'antipolio_1' => $a1,
            'antipolio_2' => $a2,
            'antipolio_3' => $a3,
            'pentavalente_1' => $p1,
            'pentavalente_2' => $p2,
            'pentavalente_3' => $p3,
            'antiamarilla' => $am1,
            'mmr_unica' => $srp1,
            'otra' => $o1,
            'observaciones' => $observaciones_vacunas,
            'estado' => 'A',
            'usuario_registro' => $_user['id_user'],
            'fecha_registro' => date('Y-m-d H:i:s')
        ));
        //var_dump($id_persona);die;
        $cadena = array(
            'id_estudiante' => $id_estudiante,
            'estado' => 1
        );
    }

    echo json_encode($cadena);
}

if ($boton == "agregar_familiar") {

    //var_dump($_POST);
    $nombres           = $_POST['f_nombres'];
    $primer_apellido   = $_POST['f_primer_apellido'];
    $segundo_apellido  = $_POST['f_segundo_apellido'];
    $tipo_documento    = $_POST['f_tipo_documento'];
    $numero_documento  = $_POST['f_numero_documento'];
    $complemento       = $_POST['f_complemento'];
    $nit               = $_POST['f_nit'];
    $genero            = $_POST['f_genero'];
    $fecha_nacimiento  = date_encode($_POST['f_fecha_nacimiento_tutor']);
    $telefono          = $_POST['f_telefono'];
    $profesion         = $_POST['f_profesion'];
    $direccion_oficina = $_POST['f_direccion_oficina'];
    $parentesco        = $_POST['f_parentesco'];
    $tutor             = $_POST['f_tutor'];
    //var_dump($fecha_nacimiento);exit();
    //id_estudiante por el momento sin valor 
    $id_estudiante     = 0;

    //Recibimos si es tutor responsable o no
    /*if ($tutor) {
        $valor_tutor = 1;
    } else {
        $valor_tutor = 0;
    }*/

    //Primero agregamos a la tabla sys_persona 
    $persona = array(
        'nombres' => $nombres,
        'primer_apellido' => $primer_apellido,
        'segundo_apellido' => $segundo_apellido,
        'tipo_documento' => $tipo_documento,
        'numero_documento' => $numero_documento,
        'complemento' => $complemento,
        'genero' => $genero,
        'fecha_nacimiento' => $fecha_nacimiento,
        'nit' => $nit
    );
    //Obtenemos el Id
    $id_persona = $db->insert('sys_persona', $persona);

    //var_dump($id_persona);exit;

    if ($id_persona) {
        //echo 1;
        $codigo_mayor = $db->query("SELECT MAX(id_familiar) as id_familiar FROM ins_familiar")->fetch_first();
        $id_anterior = $codigo_mayor['id_familiar']; //id_comunicado mayor

        if (is_null($id_anterior)) {
            $nuevo_codigo = "M-1";
        } else {
            //recupera los datos del ultimo registro para asignarle un codigo
            $familiar_mayor = $db->query("SELECT id_familiar, codigo_familia FROM ins_familiar WHERE id_familiar = $id_anterior ")->fetch_first();
            $codigo_anterior = $familiar_mayor['codigo_familia']; //codigo anterior
            $separado = explode('-', $codigo_anterior);
            $nuevo_codigo = "M-" . ($separado[1] + 1);
        }

        //var_dump($nuevo_codigo);die;
        //Armanos el todo los campos para crear el registro en la tabla familiar
        $familiar = array(
            'profesion' => $profesion,
            'direccion_oficina' => $direccion_oficina,
            'telefono_oficina' => $telefono,
            'parentesco' => $parentesco,
            'persona_id' => $id_persona,
            'codigo_familia' => $nuevo_codigo,
            'estado' => 'A',
            'usuario_registro' => $_user['id_user'],
            'fecha_registro' => Date('Y-m-d H:i:s')
        );

        //var_dump($familiar);die;
        //Registramos el familiar y obtenemos el id del familiar
        $id_familiar = $db->insert('ins_familiar', $familiar);

        if ($id_familiar) {
            //armomos el array que devolvera el id_familiar
            /*$respuesta = array('id_familiar' => $id_familiar,
                               'valor_tutor' => $tutor,                              
                               'estado'      => 1 );*/
            if ($tutor == 1) {
                $respuesta = array(
                    'id_familiar' => $id_familiar,
                    'valor_tutor' => $tutor,
                    'familiar'    =>   "<h6>" . $nombres . " " . $primer_apellido . " " . $segundo_apellido . "  Tutor : Si </h6><br>",
                    'estado'      => 1
                );
            } else {
                $respuesta = array(
                    'id_familiar' => $id_familiar,
                    'valor_tutor' => $tutor,
                    'familiar'    =>   "<h6>" . $nombres . " " . $primer_apellido . " " . $segundo_apellido . "  Tutor : No </h6><br>",
                    'estado'      => 1
                );
            }
        } else {
            $respuesta = array(
                'id_familiar' => "0",
                'valor_tutor' => "0",
                'familiar'    => "0",
                'estado'      => 2
            );
        }

        echo json_encode($respuesta);
        /*if ($id_familiar) {
            //Registramos en la tabla familiar por el momento sin el estudiante
            $estudiante_familiar = array(
                'familiar_id' => $id_familiar,
                'estudiante_id' => $id_estudiante,
                'tutor' => $valor_tutor
            );
            $id_estudiante_familiar = $db->insert('ins_estudiante_familiar', $estudiante_familiar);

            if ($id_estudiante_familiar) {
                echo 1;
            } else {
                echo 2;
            }
            //echo 1;
        } else {
            echo 3;
        }*/
    } else {
        echo 4;
    }
}

//obtiene el listado de cursos
if ($boton == "listar_cursos") {
    //obtiene el nivel
    $nivel = $_POST['nivel'];
    //obtiene los cursos segun el nivel
    $cursos = $db->query("SELECT * FROM ins_aula
                            WHERE nivel_academico_id = $nivel AND estado = 'A'
                            ORDER BY nombre_aula")->fetch();
    
/*    $cursos = $db->query("SELECT ap.id_aula_paralelo, ap.capacidad, a.nombre_aula , p.nombre_paralelo , p.descripcion
                            FROM ins_aula_paralelo AS ap
                            INNER JOIN ins_aula AS a ON a.id_aula = ap.aula_id
                            INNER JOIN ins_paralelo AS p ON p.id_paralelo = ap.paralelo_id
                            WHERE a.nivel_academico_id = $nivel AND a.estado = 'A'
                            ORDER BY a.id_aula, p.id_paralelo")->fetch();*/
    
    
    echo json_encode($cursos);
}

//obtiene el nro de varones y mujeres inscritos en un curso y gestion especifico
if ($boton == "nro_varones_mujeres") {
    //obtiene el nivel
    $id_aula_paralelo = $_POST['id_aula_paralelo'];
    //obtiene los cursos segun el nivel
    $nroVM = $db->query("SELECT IFNULL(SUM(p.genero= 'v'),0) AS nro_varones, IFNULL(SUM(p.genero= 'm'),0) AS nro_mujeres,  COUNT(i.id_inscripcion) AS inscritos, IFNULL(ap.capacidad,0) AS cupo_total
                            FROM ins_inscripcion AS i
                            INNER JOIN ins_estudiante e ON e.id_estudiante = i.estudiante_id
                            INNER JOIN sys_persona p ON p.id_persona = e.persona_id
                            INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo = i.aula_paralelo_id
                            WHERE i.aula_paralelo_id = $id_aula_paralelo AND i.gestion_id = $id_gestion AND i.estado = 'A'")->fetch();
    echo json_encode($nroVM);
}

//obtiene el listado de paralelos
if ($boton == "listar_paralelos") {
    $id_curso = $_POST['id_curso'];
     $paralelo = $db->query("SELECT ins_aula_paralelo.paralelo_id as paralelo_id,ins_paralelo.nombre_paralelo AS nombre_paralelo FROM ins_aula_paralelo,ins_paralelo WHERE ins_aula_paralelo.paralelo_id=ins_paralelo.id_paralelo and aula_id = $id_curso")->fetch();
   // $paralelo = $db->query("SELECT * FROM vista_aula_paralelo WHERE id_aula = $id_curso")->fetch();
    echo json_encode($paralelo);
}

//obtiene el listado de vacantes segun curso/paralelo
if ($boton == "listar_vacantes") {
    //$id_aula_paralelo = $_POST['id_aula_paralelo'];
    $id_aula = $_POST['id_aula'];
    $id_paralelo = $_POST['id_paralelo'];
    
    $consulta_aula = $db->query("SELECT * FROM ins_aula_paralelo WHERE aula_id = $id_aula and paralelo_id=$id_paralelo")->fetch_first();
    $id_aula_paralelo=$consulta_aula['id_aula_paralelo'];
    
    $consulta = $db->query("SELECT COUNT(aula_paralelo_id) as contador FROM ins_inscripcion WHERE aula_paralelo_id = $id_aula_paralelo")->fetch_first();
    
    //obtiene el total de vacantes del curso paralelo        
    $vacantes =$consulta_aula['capacidad'] - $consulta['contador'];
    echo json_encode($vacantes);
}

if ($boton == "seleccionar_tutor") {
    //var_dump($_POST);die;
    $id_estudiante_familiar = $_POST['id_estudiante_familiar'];
    $id_estudiante = $_POST['id_estudiante'];
    $id_tutor = $_POST['id_tutor'];

    $tutor = array('tutor' => 1); //instancia tutor

    //selecciona al tutor
    $db->where('id_estudiante_familiar', $id_estudiante_familiar)->update('ins_estudiante_familiar', $tutor);
    //$consulta = "UPDATE ins_estudiante_familiar SET tutor = 1 WHERE id_estudiante_familiar = $id_estudiante_familiar";

    $familiar = array('tutor' => 0);
    //$consulta ="UPDATE ins_estudiante_familiar SET tutor = 0 WHERE id_estudiante_familiar <> $id_estudiante_familiar AND estudiante_id = $id_estudiante";
    $db->query("UPDATE ins_estudiante_familiar SET tutor = 0 WHERE id_estudiante_familiar <> $id_estudiante_familiar AND estudiante_id = $id_estudiante")->execute();

    echo 1;
}

if ($boton == "identificar_familiar") {
    //var_dump($_POST);die;
    $id_estudiante = $_POST['id_estudiante'];
    $id_familiar = $_POST['id_familiar'];

    if ($id_estudiante and $id_familiar) {
        $estudiante_familiar = array(
            'familiar_id' => $id_familiar,
            'estudiante_id' => $id_estudiante,
            'tutor' => 0
        );

        $id_estudiante_familiar = $db->insert('ins_estudiante_familiar', $estudiante_familiar);

        if ($id_estudiante_familiar) {
            echo 1;
        } else {
            echo 2;
        }
    } else {
        echo 3;
    }
}

if ($boton == "guardar_inscripcion") {
    //Capturamos el curso elegido
    //var_dump($_POST);    
    $id_aula_paralelo   = $_POST['select_curso'];
    $capacidad          = $_POST['vacantes'];
    $capacidad          = $capacidad - 1;
    $id_aula_paralelo_A = $_POST['id_aula_paralelo_A'];
    $estado             = 0;
    //$id_aula_paralelo_N = $_POST['id_aula_paralelo_N'];   

    $busqueda = $db->query("SELECT * FROM ins_aula_paralelo WHERE id_aula_paralelo = $id_aula_paralelo_A")->fetch_first();
    //var_dump($busqueda);

    if ($busqueda == null) {
        //Editamos el cupo del curso elegido para descontar el cupo
        $db->where('id_aula_paralelo', $id_aula_paralelo)->update('ins_aula_paralelo', array('capacidad' => $capacidad));
        $estado = 1;
    } else {
        $capacidad = $busqueda['capacidad'] + 1;
        //Devolvemos el cupo del curso anterior
        $db->where('id_aula_paralelo', $id_aula_paralelo_A)->update('ins_aula_paralelo', array('capacidad' => $capacidad));
        //Editamos el cupo del curso elegido para descontar el cupo
        $db->where('id_aula_paralelo', $id_aula_paralelo)->update('ins_aula_paralelo', array('capacidad' => $capacidad));
        $estado = 2;
    }

    $respuesta = array(
        'id_aula_paralelo' => ($id_aula_paralelo*1),
        'estado' => ($estado*1)
    );
    //var_dump($respuesta);

    echo json_encode($respuesta);
}

/****************************************************/
// el metodo guardar antiguo una inscripcion
/****************************************************/
if ($boton == "registrar_inscripcion_estudiante") {
    
    var_dump($_POST);die;    
    $id_tipo_estudiante = $_POST['tipo_estudiante'];
    $id_nivel_academico = $_POST['nivel_academico'];
    $id_aula            = $_POST['select_curso'];
    $id_aula_paralelo   = $_POST['select_paralelo'];
    //Datos para relacionar el estudiante con los tutores
    $id_estudiante      = $_POST['id_estudiante'];
    $id_familiar_tutor  = $_POST['id_familiar_tutor'];
    $ids_familiar       = $_POST['ids_familar'];


    //var_dump($_POST);die;
    $busqueda = $db->query("SELECT COUNT(codigo_inscripcion) as codigo_inscripcion FROM ins_inscripcion WHERE estudiante_id = $id_estudiante AND gestion_id = $id_gestion")->fetch_first();
    $contador = $busqueda['codigo_inscripcion'];
    if ($contador > 0) {
        //echo 3;
        $respuesta = array('id_inscripcion' => 0,
                            'estado' => 0 );
    } else {
        //echo 1;
        $inscripcion = array(
            'fecha_inscripcion' => Date('Y-m-d H:i:s'),
            'aula_paralelo_id' => $id_aula_paralelo,
            'estudiante_id' => $id_estudiante,
            'tipo_estudiante_id' => $id_tipo_estudiante,
            'nivel_academico_id' => $id_nivel_academico,
            'gestion_id' => $id_gestion,
            'codigo_inscripcion' => $id_estudiante . "-" . $_gestion['gestion'],
            'estado' => 'A',
            'usuario_registro' => $_user['id_user'],
            'fecha_registro' => Date('Y-m-d H:i:s')
        );

        $id_inscripcion = $db->insert('ins_inscripcion', $inscripcion);
        $respuesta = array('id_inscripcion' => $id_inscripcion,
                            'estado' => 1 );

        if ($id_inscripcion) {

            //Luego de Confirmarse la inscripcion procedemos a la relacion turor estudiante
            $id_fami = explode("/", $ids_familiar);
            $cant    = count($id_fami);
            $i       = 0;
            foreach ($id_fami as $key ) {
                if($key == $id_familiar_tutor){
                    //Aqui verificamos quien es el tutor
                    $valor_tutor = 1;
                    $estudiante_familiar = array(
                        'familiar_id' => $key,
                        'estudiante_id' => $id_estudiante,
                        'tutor' => $valor_tutor
                    );
                    $id_estudiante_familiar = $db->insert('ins_estudiante_familiar', $estudiante_familiar);
                    $i++; 
                }else{
                    //Aqui solo registramos al los demas tutores
                    $valor_tutor = 0;
                    $estudiante_familiar = array(
                        'familiar_id' => $key,
                        'estudiante_id' => $id_estudiante,
                        'tutor' => $valor_tutor
                    );
                    $id_estudiante_familiar = $db->insert('ins_estudiante_familiar', $estudiante_familiar);
                    $i++;
                }
            }

            //var_dump($cant."/".$i);die;
            if($cant == $i){
                $respuesta = array('id_inscripcion' => $id_inscripcion,
                            'estado' => 1 );
            }else{
                $respuesta = array('id_inscripcion' => $id_inscripcion,
                            'estado' => 0 );    
            }       
            
        } else {
            $respuesta = array('id_inscripcion' => 0,
                            'estado' => 0 );
        }        
    }
    //var_dump($busqueda);
    //echo json_encode($respuesta);
}

if ($boton == "eliminar_familiar") {
    //var_dump($_POST);die;
    $id_estudiante_familiar = $_POST['id_estudiante_familiar'];
    if ($id_estudiante_familiar) {
        $db->delete()->from('ins_estudiante_familiar')->where('id_estudiante_familiar', $id_estudiante_familiar)->limit(1)->execute();
        if ($db->affected_rows) {
            echo 1;
        } else {
            echo 2;
        }
    } else {
        echo 3;
    }
}

if ($boton == "guardar_certificado") {
    //var_dump($_POST);die;
    $nro_rude      = $_POST['nro_rude'];
    $discapacidad  = $_POST['discapacidad'];
    $nro_ibc       = $_POST['nro_ibc'];
    $tipo_discapacidad  = $_POST['tipo_discapacidad'];
    $grado_discapacidad = $_POST['grado_discapacidad'];
    $oficialia     = $_POST['oficialia'];
    $libro         = $_POST['libro'];
    $partida       = $_POST['partida'];
    $folio         = $_POST['folio'];
    $departamento  = $_POST['departamento'];
    $provincia     = $_POST['provincia'];
    $seccion       = $_POST['seccion'];
    $localidad     = $_POST['localidad'];
    $zona          = $_POST['zona'];
    $avenida       = $_POST['avenida'];
    $nrovivienda   = $_POST['nrovivienda'];
    $telefono      = $_POST['telefono'];
    $celular       = $_POST['celular'];
    $id_estudiante = $_POST['id_estudiante'];
    $id_inscripcion_rude = (isset($_POST['id_inscripcion_rude'])) ? clear($_POST['id_inscripcion_rude']) : 0;

    //$id_estudiante = "442";
    /*$aula_paralelo = $db->query("SELECT * FROM ins_aula_paralelo WHERE id_aula_paralelo = $id_aula_paralelo")->fetch_first();
        $id_aula_paralelo = $aula_paralelo['id_aula_paralelo'];*/

    //var_dump($_POST);die;
    //$busqueda = $db->query("SELECT COUNT(codigo_inscripcion) as codigo_inscripcion FROM ins_inscripcion WHERE estudiante_id = $id_estudiante AND gestion_id = $id_gestion")->fetch_first();
    //$contador = $busqueda['codigo_inscripcion'];


    if ($id_inscripcion_rude > 0) {
        $vacio = "";
        $inscripcion_rude = array(
            'ins_estudiante_id' => $id_estudiante,
            'nro_rude' => $nro_rude,
            'discapacidad' => $discapacidad,
            'nro_ibc' => $nro_ibc,
            'tipo_discapacidad' => $tipo_discapacidad,
            'grado_discapacidad' => $grado_discapacidad,
            'oficialia' => $oficialia,
            'partida' => $partida,
            'libro' => $libro,
            'folio' => $folio,
            'departamento' => $departamento,
            'provincia' => $provincia,
            'seccion' => $seccion,
            'localidad' => $localidad,
            'zona' => $zona,
            'avenida' => $avenida,
            'nrovivienda' => $nrovivienda,
            'telefono' => $telefono,
            'celular' => $celular,
            '411' => $vacio,
            '412' => $vacio,
            '413' => $vacio,
            '421' => $vacio,
            '422' => $vacio,
            '423' => $vacio,
            '424' => $vacio,
            '431' => $vacio,
            '432' => $vacio,
            '433' => $vacio,
            '434' => $vacio,
            '435' => $vacio,
            '436' => $vacio,
            '441' => $vacio,
            '442' => $vacio,
            '451' => $vacio,
            '4511' => $vacio,
            '452' => $vacio,
            '4521' => $vacio,
            '453' => $vacio,
            '454' => $vacio,
            '455' => $vacio,
            '4551a' => $vacio,
            '51' => $vacio,
        );
        // Modifica los datos del certificado de nacimiento
        $db->where('id_ins_inscripcion_rude', $id_inscripcion_rude)->update('ins_inscripcion_rude', $inscripcion_rude);
        $estado = 2;
        $cadena = array(
            'id_inscripcion_rude' => $id_inscripcion_rude,
            'estado' => $estado
        );
        echo json_encode($cadena);
    } else {
        //echo 1;
        //Date('Y-m-d H:i:s'),
        $vacio = "";
        $inscripcion_rude = array(
            'ins_estudiante_id' => $id_estudiante,
            'nro_rude' => $nro_rude,
            'discapacidad' => $discapacidad,
            'nro_ibc' => $nro_ibc,
            'tipo_discapacidad' => $tipo_discapacidad,
            'grado_discapacidad' => $grado_discapacidad,
            'oficialia' => $oficialia,
            'partida' => $partida,
            'libro' => $libro,
            'folio' => $folio,
            'departamento' => $departamento,
            'provincia' => $provincia,
            'seccion' => $seccion,
            'localidad' => $localidad,
            'zona' => $zona,
            'avenida' => $avenida,
            'nrovivienda' => $nrovivienda,
            'telefono' => $telefono,
            'celular' => $celular,
            '411' => $vacio,
            '412' => $vacio,
            '413' => $vacio,
            '421' => $vacio,
            '422' => $vacio,
            '423' => $vacio,
            '424' => $vacio,
            '431' => $vacio,
            '432' => $vacio,
            '433' => $vacio,
            '434' => $vacio,
            '435' => $vacio,
            '436' => $vacio,
            '441' => $vacio,
            '442' => $vacio,
            '451' => $vacio,
            '4511' => $vacio,
            '452' => $vacio,
            '4521' => $vacio,
            '453' => $vacio,
            '454' => $vacio,
            '455' => $vacio,
            '4551a' => $vacio,
            '51' => $vacio,
        );

        $id_inscripcion_rude = $db->insert('ins_inscripcion_rude', $inscripcion_rude);
        if ($id_inscripcion_rude) {
            $estado = 1;
        } else {
            $estado = 3;
        }
        //Creamos un array para devolver el id_ins_inscripcion_rude que se acaba de crear
        $cadena = array(
            'id_inscripcion_rude' => $id_inscripcion_rude,
            'estado' => $estado
        );
        echo json_encode($cadena);
    }
}

if ($boton == "guardar_rude") {
    /*echo "<pre>";
        var_dump($_POST);
        echo "</pre>";
        die;*/
    $a       = $_POST['a'];
    $b       = $_POST['b'];
    $c       = $_POST['c'];
    $d       = $_POST['d'];
    $salud   = implode(",", $_POST['salud']); //array
    $e       = $_POST['e'];
    $f       = $_POST['f'];
    $g       = $_POST['g'];
    $h       = $_POST['h'];
    $i       = $_POST['i'];
    $j       = $_POST['j'];
    $k       = $_POST['k'];
    $l       = $_POST['l'];
    $internet = implode(",", $_POST['internet']); //array
    $m       = $_POST['m'];
    $n       = $_POST['n'];
    $mes     = implode("", $_POST['mes']); //array
    $trabajo = implode("", $_POST['trabajo']); //array
    $o       = $_POST['o'];
    $turno   = implode(",", $_POST['turno']); //array
    $p       = $_POST['p'];
    $q       = $_POST['q'];
    $pago    = implode(",", $_POST['pago']); //array
    $r       = $_POST['r'];
    $id_inscripcion_rude = $_POST['id_inscripcion_rude'];
    //$id_inscripcion_rude = "5";       
    $contador = 0;
    if ($contador > 0) {
        echo 3;
    } else {
        //echo 1;
        //Date('Y-m-d H:i:s'),
        $vacio = "";
        $inscripcion_rude_update = array(
            '411' => $a,
            '412' => $b,
            '413' => $c,
            '421' => $d,
            '422' => $salud,
            '423' => $e,
            '424' => $f,
            '431' => $g,
            '432' => $h,
            '433' => $i,
            '434' => $j,
            '435' => $k,
            '436' => $l,
            '441' => $internet,
            '442' => $m,
            '451' => $n,
            '4511' => $mes,
            '452' => $trabajo,
            '4521' => $o,
            '453' => $turno,
            '454' => $p,
            '455' => $q,
            '4551a' => $pago,
            '51' => $r
        );

        $db->where('id_ins_inscripcion_rude', $id_inscripcion_rude)->update('ins_inscripcion_rude', $inscripcion_rude_update);
        $estado = 1;
        //$id_inscripcion_rude = $db->insert('ins_inscripcion_rude', $inscripcion_rude);
        /*if ($id_inscripcion_rude) {
                $estado = 1;
            } else {
                $estado = 2;
            }*/
        //Creamos un array para devolver el id_ins_inscripcion_rude que se acaba de crear
        $cadena = array(
            'id_inscripcion_rude' => $id_inscripcion_rude,
            'estado' => $estado
        );
        echo json_encode($cadena);
    }
}

if ($boton == "guardar_concepto_pago") {
    //var_dump($_POST);die;
    $id_estudiante  = $_POST['id_estudiante'];
    //$id_inscripcion  = $_POST['id_inscripcion']; 
    $id_pensiones    = (isset($_POST['id_pensiones'])) ? $_POST['id_pensiones'] : array();
    $tipo_concepto = (isset($_POST['tipo_concepto'])) ? $_POST['tipo_concepto'] : array();

    $busqueda = $db->query("SELECT COUNT(codigo_inscripcion) as codigo_inscripcion, id_inscripcion FROM ins_inscripcion WHERE estudiante_id = $id_estudiante AND gestion_id = $id_gestion")->fetch_first();

    // Obtiene datos de los pagos
    foreach ($id_pensiones as $nro => $elemento) {
        $pagos = $db->query("SELECT * FROM pen_pensiones p inner join pen_pensiones_detalle pd on p.id_pensiones=pd.pensiones_id where p.id_pensiones='$id_pensiones[$nro]' ORDER BY p.nombre_pension")->fetch();
        //var_dump($pagos);exit();
        $contador = $busqueda['codigo_inscripcion'];

        foreach ($pagos as $value) {
            $detalle_estudiante = array(
                'detalle_pension_id' => $value['id_pensiones_detalle'],
                'inscripcion_id' => $busqueda['id_inscripcion'],
                'tipo_concepto'  => $value['tipo_concepto'],
                'fecha_registro' => date('Y-m-d H:i:s'),
                'fecha_modificacion'   => '',
                'usuario_registro'     => $_user['id_user'],
                'usuario_modificacion' => '',
                'cuota'                => $value['cuota'],
                'descuento_porcentaje' => $value['descuento_porcentaje'],
                'descuento_bs' => $value['descuento_bs'],
                'monto'        => $value['monto'],
                'mora_dia'     => $value['mora_dia'],
                'fecha_inicio' => $value['fecha_inicio'],
                'fecha_final'  => $value['fecha_final'],
            );
            //var_dump($detalle_estudiante);
            $id_pensiones_estudiante = $db->insert('pen_pensiones_estudiante', $detalle_estudiante);
        }
    }
    if ($id_pensiones_estudiante) {
        echo 1;
    } else {
        echo 2;
    }
}

//CODIGO QUINO ---------------------------------------
//obtiene el listado de paices
if ($boton == "listar_paices") { 
    $nivel = $_POST['nivel']; 
    $pais = $db->select('z.*')->from('sys_paises z')->order_by('id_pais')->fetch();
    echo json_encode($pais);
}
if ($boton == "listar_departamentos") {
    //obtiene el nivel
    $nivel = $_POST['nivel'];
    $id_pais = $_POST['idpais'];
 
$departamento = $db->select('z.*')->from('sys_departamentos z')->where('piases_id',$id_pais)->order_by('nombre')->fetch();//corregir columna is pais ->where('piases_id',$id_pais)
    echo json_encode($departamento);
}

if ($boton == "listar_provincias") {
    //obtiene el nivel
    $nivel = $_POST['nivel'];
    $id_pais = $_POST['idpais'];
 
$departamento = $db->select('z.*')->from('sys_provincias z')->where('departamento_id',$id_pais)->order_by('nombre')->fetch();//corregir columna is pais ->where('piases_id',$id_pais)
    echo json_encode($departamento);
}

if ($boton == "listar_localidades") {
    //obtiene el nivel
    $nivel = $_POST['nivel'];
    $idprovincia = $_POST['idpais'];
 
$localidad = $db->select('z.*')->from('sys_localidades z')->where('provincia_id',$idprovincia)->order_by('nombre')->fetch();//corregir columna is pais ->where('piases_id',$id_pais)
 
    echo json_encode($localidad);
}

 

//--------------------------------









if ($boton == "reporte_rude") {

    $id_estudiante       = $_REQUEST['id_estudiante'];
    $id_inscripcion_rude = $_REQUEST['id_inscripcion_rude'];
    $id_aula_paralelo    = $_REQUEST['id_inscripcion'];
    //var_dump($_REQUEST);exit();

    //$id_inscripcion_rude = $_REQUEST['id_inscripcion_rude'];

    $columna = array(
        '1' => 'A', '2' => 'B', '3' => 'C', '4' => 'D', '5' => 'E', '6' => 'F', '7' => 'G', '8' => 'H', '9' => 'I', '10' => 'J', '11' => 'K', '12' => 'L', '13' => 'M', '14' => 'N', '15' => 'O', '16' => 'P', '17' => 'Q', '18' => 'R', '19' => 'S', '20' => 'T', '21' => 'U', '22' => 'V', '23' => 'W', '24' => 'X', '25' => 'Y', '26' => 'Z', '27' => 'AA', '27' => 'AA', '28' => 'AB', '29' => 'AC', '30' => 'AD', '31' => 'AE', '32' => 'AF', '33' => 'AG', '34' => 'AH', '35' => 'AI', '36' => 'AJ', '37' => 'AK', '38' => 'AL', '39' => 'AM', '40' => 'AN', '41' => 'AO', '42' => 'AP', '43' => 'AQ', '44' => 'AR', '45' => 'AS', '46' => 'AT', '47' => 'AU', '48' => 'AV', '49' => 'AW', '50' => 'AX'
    );

    //Colores RGB
    $aColores = array('1' => 'ECEA5C', '2' => '8AE245', '3' => 'F577F5', '4' => '537AF5', '5' => 'F35F7F', '6' => 'F752F5', '7' => 'AAFF00');


    $objPHPExcel = excel_iniciar("plantilla_rude.xls");
    //var_dump($objPHPExcel);die;  

    //Consultamos al estudiante
    $resEstudiante = $db->query("SELECT sp.nombres, sp.primer_apellido, sp.segundo_apellido, sp.numero_documento, sp.complemento, sp.fecha_nacimiento, sp.genero, sp.fecha_nacimiento
            FROM ins_inscripcion_rude AS ir
            INNER JOIN ins_estudiante AS ie ON ie.id_estudiante = ir.ins_estudiante_id
            INNER JOIN sys_persona AS sp ON sp.id_persona = ie.persona_id
            WHERE ir.id_ins_inscripcion_rude = $id_inscripcion_rude")->fetch();



    //Consultamos todos los datos del rude
    $resRUDE = $db->query("SELECT * FROM ins_inscripcion_rude WHERE id_ins_inscripcion_rude = $id_inscripcion_rude")->fetch();
    /*echo "<pre>";
        var_dump($resRUDE);exit;
        echo "</pre>"; */

    $total = sizeof($resRUDE);

    $filaExcel = 9;  //indice de fila en excel
    //si hay registros, colocar datos en las celdas de la hoja actual

    if ($total > 0) {

        $dep = $resEstudiante[0]['primer_apellido'];
        $col = 7;
        for ($i = 0; $i < strlen($resEstudiante[0]['primer_apellido']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '16', $dep[$i]);
            $col++;
        }

        $dep = $resEstudiante[0]['segundo_apellido'];
        $col = 7;
        for ($i = 0; $i < strlen($resEstudiante[0]['segundo_apellido']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '18', $dep[$i]);
            $col++;
        }

        $dep = $resEstudiante[0]['nombres'];
        $col = 7;
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
        }



        $objPHPExcel->getActiveSheet()->setCellValue('B30', $resRUDE[0]['oficialia']);
        $objPHPExcel->getActiveSheet()->setCellValue('E30', $resRUDE[0]['partida']);
        $objPHPExcel->getActiveSheet()->setCellValue('I30', $resRUDE[0]['libro']);
        $objPHPExcel->getActiveSheet()->setCellValue('M69', $resRUDE[0]['folio']);

        $dep = $resRUDE[0]['departamento'];
        $col = 7;
        for ($i = 0; $i < strlen($resRUDE[0]['departamento']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '42', $dep[$i]);
            $col++;
        }

        $dep = $resRUDE[0]['provincia'];
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


        /*$objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['411']);
            $objPHPExcel->getActiveSheet()->setCellValue('B79',$resRUDE[0]['412']);
            $objPHPExcel->getActiveSheet()->setCellValue('I62',$resRUDE[0]['413']);*/


        if ($resRUDE[0]['421'] == "on") {
            $objPHPExcel->getActiveSheet()->setCellValue('AR61', "X");
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue('AR63', "X");
        }

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
        }

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
    excel_finalizar($objPHPExcel, "Rude.xls");
}

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