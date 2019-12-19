<?php
 
// Obtiene la cadena csrf 
$csrf = set_csrf();

// Obtiene el modelo paralelo
$paralelo = $db->query('SELECT c.nombre_aula,e.`nombre_paralelo`,d.`descripcion`,h.`nombres`,h.`primer_apellido`,i.`nombre_materia` FROM int_aula_paralelo_profesor_materia a,ins_aula_paralelo b ,ins_aula c,ins_nivel_academico d,`ins_paralelo` e,
pro_profesor_materia f,pro_profesor g,sys_persona h,pro_materia i 
WHERE
a.`aula_paralelo_id`=b.`id_aula_paralelo` AND
b.`aula_id`=c.`id_aula` AND
c.`nivel_academico_id`= d.`id_nivel_academico` AND
b.`paralelo_id`= e.`id_paralelo` AND
a.`profesor_materia_id`=f.`id_profesor_materia` AND
f.`profesor_id`= g.`id_profesor`AND
g.`persona_id`=h.`id_persona` AND
f.`materia_id`=i.`id_materia`')->fetch();
//$paralelo = $db->from('ins_paralelo')->order_by('nombre_paralelo', 'asc')->fetch();

 
?>
<?php require_once show_template('header-design'); ?>

<link rel="stylesheet" href="assets/themes/concept/assets/vendor/multi-select/css/multi-select.css">
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Crear Asignación Profesor/Horario </h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Asignaciones</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Curso/Materia</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear Asignación</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- end pageheader -->
<!-- ============================================================== -->
<div class="row">
    <!-- ============================================================== -->
    <!-- validation form -->
    <!-- ============================================================== -->
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            
            <div class="card-header">
                <!-- <h5 class="card-header">Generador de menús</h5> -->
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                        <div class="text-label hidden-xs">Seleccionar acción:</div>
                        <!-- <div class="text-label visible-xs-block">Acciones:</div> -->
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
						<div class="btn-group">
								<div class="input-group">
								<div class="input-group-append be-addon">
									<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
									<div class="dropdown-menu">
										<a class="dropdown-item">Seleccionar acción</a>
										<?php //if ($permiso_crear) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-profesor-horario/listar" class="dropdown-item" >Ver lista Asignación</a>
										<?php //endif ?>  
										<?php //if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-profesor-horario/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir Asignacion</a>
										<?php// endif ?>
									</div>
								</div>
							</div>
						</div> 
					</div>
                   <!-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
                        <div class="btn-group">
                             <div class="input-group">
                                <div class="input-group-append be-addon">
                                    <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item">Seleccionar acción</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="?/s-curso-paralelo/principal" class="dropdown-item">Listar Curso/Paralelo</a>
                                        <a href="?/s-curso-paralelo/crear" class="dropdown-item">Crear Curso/Paralelo</a>
                                        <a href="?/s-curso-paralelo/imprimir" class="dropdown-item">Imprimir</a>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>-->
                </div>
            </div>

            <div class="card-body">
               <!--<h3>froma 1</h3>
                <form class="" id="form-menu" method="post" action="?/s-curso-paralelo/guardar" autocomplete="off">
                    <input type="hidden" name="<?= $csrf; ?>">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="aula_id" class="control-label">Aula:</label>
                                <input type="text" value="" name="aula_id" id="aula_id" class="form-control" autofocus="autofocus" data-validation="required number">
                            </div>
                            
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="capacidad" class="control-label">Capacidad:</label>
                                <input type="text" value="" name="capacidad" id="capacidad" class="form-control" data-validation="required number">
                            </div>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                           <div class="form-group">
                                <label for="paralelo_id" class="control-label">Paralelo:</label>
                                <div class="card">
                                    <div class="card-body">
                                       <select multiple="multiple" id="my-select" name="my-select[]" style="position: absolute; left: -9999px;">
                                            <?php foreach ($paralelo as $elemento) : ?>
                                        <option value="<?= $elemento['id_paralelo']; ?>"><?= escape($elemento['nombre_paralelo']); ?></option>
                                        <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
					    </div>

 
                    </div>
                    <div class="row">
                    	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                        	<div class="form-group text-center">
								<button type="submit" class="btn btn-primary">
									<i class="icon-check"></i>
									<span>Registrar</span>
								</button>
								<button type="reset" class="btn btn-light">
									<i class="icon-arrow-left-circle"></i>
									<span>Restablecer</span>
								</button>
							</div>
						</div>
                    </div>
                </form>
                <button onclick="hola()">Ver seleccionados</button>
                <p>Como obtengo el id de paralelo</p>
                -->
                <h3>Nueva asignacion de aula-paralelo:</h3>
      
               <!-- <form class="" id="form-menu" method="post" action="?/s-curso-paralelo/guardar" autocomplete="off">-->
                    <input type="hidden" name="<?= $csrf; ?>">
                    <form action="" class="modal_nuevo_paralelo">
                    <div class="row">
                        
                           <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 ">
                         <div class="form-group">
                                <label for="aula_id" class="control-label">Seleccione turno:</label>
                               <select required name="turno" id="turno" onchange="listar_paralelos_tabla();" class="form-control">
									 <option value="" selected="selected">Seleccionar t</option>
										 
								</select>
                           
                            </div>
                          
                             
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="aula_id" class="control-label">Seleccione nivel:</label>
                               <select required name="nivel" id="nivel" onchange="listar_aulas();" class="form-control">
									 <option value="0" selected="selected">Seleccionar nivel</option>
										 
								</select>
                           <!-- <button type="reset" class="btn btn-success" id="btnNuevo" data-toggle="modal" data-target="#myModal" style="display:none">
									<i class="icon-star"></i>
									<span>Nuevo paralelo</span>
					        </button>-->
                            </div>
                             
                        </div>
                           <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 ">
                            <div class="form-group">
                                <label for="aula_id" class="control-label">Seleccione aula:</label>
                               <select required name="aula" id="aula" onchange="listar_paralelos_tabla();" class="form-control">
									 <option value="" selected="selected">Seleccionar aula</option>
										 
								</select>
                            
                            </div>
                             
                        </div>
                         
                       
                       <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                           
                               <div class="form-group">
                               <!-- <label for="aula_id" class="control-label">Nuevo</label>-->
                               <!-- <button type="submit" class="btn btn-success" id="btnNuevo" >
								 
									<span>Nuevo paralelo</span>
					        </button>-->
                            
                            </div>
                        </div> 
                        
                    </div>
                    </form>
                  
      <!-- data-toggle="modal" data-target="#myModal" style="display:block"-->
                   
                   <form class="" id="form-menu" method="post" action="?/s-curso-paralelo/guardar" autocomplete="off">
                    <input type="hidden" name="<?= $csrf; ?>">
                    
                   <table class="table" id="Tabla_paralelos">
                       <thead>
                          <tr> <th>n</th>
                           <th>Turno</th>
                           <th>Curso</th>
                           <th>Paralelo</th>
                           <th>Nivel</th>
                           <th>Asignar</th> 
                           
                           
                           <!--<th>n</th>
                           <th>nombres</th>
                           <th>primer_apellido</th>
                           <th>nombre_materia</th>
                            
                           <th>Asignar</th>-->
                           </tr>
                       </thead><tbody>
                        <!--   <tr><td>1 </td>
                           <td>1 </td>
                           <td>A</td>
                           <td><input type="text" class="form-control"></td></tr>  -->
                       </tbody>
                   </table>
                    <!-- <div class="row">
                    	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                        	<div class="form-group text-center">
								<button type="submit" class="btn btn-primary">
									<i class="icon-check"></i>
									<span>Registrar</span>
								</button>
								<button type="reset" class="btn btn-light">
									<i class="icon-arrow-left-circle"></i>
									<span>Restablecer</span>
								</button>
							</div>
						</div>
                    </div>-->
                </form> 
             
                
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end validation form -->
    <!-- ============================================================== -->
</div>
 
       <!-- Button to Open the Modal -->
<!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
  Open modal
</button>-->
<!-- The Modal -->
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Asigne un paralelo</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
    <form action="" class="agregar_paralelo">
      <!-- Modal body -->
      <div class="modal-body">
            <label for="aula_id" class="control-label">Paralelo: </label>
            <select required name="paralelo" id="paralelo"   class="form-control">
            <option value="" selected="selected">Seleccionar paralelo</option>
            </select>
            <label for="Iparacidad" class="control-label">Capacidad de aula: </label>
            <input  required type="number" class="form-control" value="" id="Iparacidad" placeholder="Ingrese capacidad de aula"/>
                        
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success" id="btnNuevo" >
                         <i class="icon-plus"></i>
                         <span>Agrerar</span>
        </button><!--onclick="agregar_paralelo()"-->

    </div>
    </form>
  </div>
</div>
    
</div>
 
<!--modal para eliminar-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_eliminar">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
	  	<input type="hidden" id="curso_eliminar">
        <p>¿Esta seguro de eliminar el curso <span id="texto_curso"></span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn_eliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<form id="form_curso">
<div class="modal fade" id="modal_curso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="titulo_curso"></span> Curso </h5>
				<a href="#" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</a>
			</div>
			<div class="modal-body">
				<div class="">
					<label class="control-label">Capacidad del aula: </label>
					<div class="control-group">
            <input type="hidden" name="<?= $csrf; ?>">
            <input id="id_aula" name="id_asignacion" type="hidden" class="form-control">						
						<input id="capacidad_aula" name="capacidad_aula" type="number" class="form-control" placeholder="Ej: 35">
					</div>
				</div>
				 
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
        <!--<button type="submit" class="btn btn-primary pull-right" id="btn_nuevo">Guardar</button>-->
				<button type="submit" class="btn btn-primary pull-right" id="btn_editar">Editar</button>
			</div>
		</div>
	</div>
</div>
</form>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/js/jquery.validate.js"></script>
<script src="application/modules/generador-menus/generador-menu.js"></script>    
<script src="assets/themes/concept/assets/vendor/multi-select/js/jquery.multi-select.js"></script>
<?php 
//	if($permiso_modificar){
		require_once ("editar.php");
//	}
	/*if($permiso_ver){
		require_once ("ver.php");
	}*/
?>
<script>
 var dataTable = $('#Tabla_paralelos').DataTable({
  language: dataTableTraduccion,
  searching: true,
  paging:true,
  stateSave:true,
  "lengthChange": true,
  "responsive": true
  });


    listar_turno();
    var contT=0;   
	function listar_turno() {
	 nivel = 0;//$("#nivel_academico option:selected").val()
		$.ajax({
			url: '?/s-curso-paralelo-profesor-materia/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_turno',
				'nivel': nivel
			},
			dataType: 'JSON',
			success: function(resp){
		    console.log('Listar aula'+ resp); 
               // alert('ejemplo');
				//alert(resp[0]['id_catalogo_detalle']); 
				//console.log(resp);
                var cont=0;
				$("#turno").html("");
				$("#turno").append('<option value="">(Todos)Seleccionar</option>');
				for (var i = 0; i < resp.length; i++) {
                   // if(cont<1){
					//$("#turno").append('<option selected value="' + resp[i]["id_turno"] + '">' + resp[i]["nombre_turno"]+'</option>');
                    //}else{
                        $("#turno").append('<option  value="' + resp[i]["id_turno"] + '">' + resp[i]["nombre_turno"]+'</option>');
                   //}
                   // cont++;
                    listar_nivel();
                    listar_aulas();
				}
				//console.log(resp[0]);
                   

			}
		});
        
	} 
   //listar_nivel();
    var contN=0;   
	function listar_nivel() {
	 //turno = $("#turno option:selected").val()
     //alert(turno);
		$.ajax({
			url: '?/s-curso-paralelo/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_nivel'//,
				//'turno': turno
			},
			dataType: 'JSON',
			success: function(resp){
		    console.log('Listar aula'+ resp); 
               // alert('ejemplo');
				//alert(resp[0]['id_catalogo_detalle']); 
				//console.log(resp);
                
				$("#nivel").html("");
				$("#nivel").append('<option value="' + 0 + '">Seleccionar nivel</option>');
				for (var i = 0; i < resp.length; i++) {
                   // if(contN<1){
					//$("#nivel").append('<option selected value="' + resp[i]["id_nivel_academico"] + '">' + resp[i]["nombre_nivel"]+'</option>');
                   // }else{
                        $("#nivel").append('<option  value="' + resp[i]["id_nivel_academico"] + '">' + resp[i]["nombre_nivel"]+'</option>');
                   // }contT++;
				}
				//console.log(resp[0]);
                   

			}
		});
        
	}  

//LLENADOP DE SELECTS CON AULA Y SIS ID
	listar_aulas();
	function listar_aulas() {
	// nivel = 0;//$("#nivel_academico option:selected").val()
	//var turno = $("#turno option:selected").val();//mañána tarde noche
	var nivel = $("#nivel option:selected").val();//primaria  sec
       // alert('ejemplo'+nivel);
        
		$.ajax({
			url: '?/s-curso-paralelo/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_aulas',
				'nivel': nivel, 
			},
			dataType: 'JSON',
			success: function(resp){
		    console.log('Listar aula'+ resp); 
               //alert('rest aulaas');
				//alert(resp[0]['id_catalogo_detalle']); 
				//console.log(resp);
				$("#aula").html("");
				$("#aula").append('<option value="">(Todos)Seleccionar</option>');////' + 0 +
				for (var i = 0; i < resp.length; i++) {
					$("#aula").append('<option value="' + resp[i]["id_aula"] + '">' + resp[i]["nombre_aula"] +' '+ resp[i]["nombre_nivel"]+'</option>');
				}
				//console.log(resp[0]);
                   

			}
		});
        
	}
    listar_paralelos();
	function listar_paralelos() {
	// nivel = 0;//$("#nivel_academico option:selected").val()
	//var turno = $("#turno option:selected").val();//mañána tarde noche
	var nivel = $("#nivel option:selected").val();//primaria  sec
       // alert('ejemplo'+nivel);
        
		$.ajax({
			url: '?/s-curso-paralelo/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_paralelos',
				'nivel': nivel, 
			},
			dataType: 'JSON',
			success: function(resp){
		    console.log('Listar aula'+ resp); 
               //alert('rest aulaas');
				//alert(resp[0]['id_catalogo_detalle']); 
				//console.log(resp);
				$("#paralelo").html("");
				$("#paralelo").append('<option value="">(Todos)Seleccionar</option>');////' + 0 +
				for (var i = 0; i < resp.length; i++) {
					$("#paralelo").append('<option value="' + resp[i]["id_paralelo"] + '">' + resp[i]["nombre_paralelo"]+'</option>');
				}
				//console.log(resp[0]);
                   

			}
		});
        
	}
    
   listar_paralelos_tabla();
	function listar_paralelos_tabla() {
    listar_paralelos();
    //alert('paralelos');
	var aula = $("#aula option:selected").val();//this
	var turno = $("#turno option:selected").val();//this
	var nivel = $("#nivel option:selected").val();//this
        var boton='';
        
       /* if(aula>0)
            todo=false;
            else
            todo=true;//listar_paralelos_todos_T';
        */
		$.ajax({
			url: '?/s-profesor-horario/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_paralelos_T',
                
				'aula': aula,
                'turno':turno,
                'nivel':nivel
			},
			dataType: 'JSON',
			success: function(resp){
		    console.log('Listar paralelos '+ resp);
                
            //alert('ejemplo');
				//alert(resp[0]['id_catalogo_detalle']); 
				//console.log(resp);
			//	$("#Tabla_paralelos").html("");
        //        $("#Tabla_paralelos").find('thead').append('<tr><th>N</th><th>Turno</th><th>Aula</th>                           <th>Paralelo</th> <th>Capacidad<th> <th>act<th></tr>');
				//$("#Tabla_paralelos").append('<option value="' + 0 + '">Seleccionar</option>');
        var counter=1;
        dataTable.clear().draw();//limpia y actualisa la tabla
				for (var i = 0; i < resp.length; i++) {
                    var contenido = resp[i]['id_aula_paralelo'] + "*" + resp[i]['capacidad'];//;+ "*" + row['descripcion']+ "*" + row['nombre_nivel'];
                    
/*nombre_turno
id_aula_paralelo
nombre_aula
nombre_paralelo
descripcion
nombres
primer_apellido
nombre_materia*/
   //lista aula paralelo
 dataTable.row.add( [
            counter,
            resp[i]["nombre_turno"],
            resp[i]["nombre_aula"],
            resp[i]["nombre_nivel"],
            'Paralelo '+resp[i]["nombre_paralelo"],
            "<a class='btn btn-info btn-xs' ONCLICK='abrir_crear("+resp[i]["id_aula_paralelo"]+")'><span class='icon-trash'></span>Asigar</a>"
        ] ).draw( false );
/*dataTable.row.add( [
            counter,
            resp[i]["nombre_turno"],
            resp[i]["nombre_aula"],
            resp[i]["nombre_paralelo"],
            resp[i]["descripcion"],
            "<a class='btn btn-info btn-xs' ONCLICK='abrir_crear("+resp[i]["id_aula_paralelo"]+")'><span class='icon-trash'></span>Asigar</a>"
        ] ).draw( false );*/
                    //doncente materia
        /*dataTable.row.add( [
            counter,
            resp[i]["nombres"],
            resp[i]["primer_apellido"],
            resp[i]["nombre_materia"],
            "<a class='btn btn-info btn-xs' ONCLICK='abrir_crear("+resp[i]["id_aula_paralelo"]+")'><span class='icon-trash'></span>Asigar</a>"
        ] ).draw( false );*/// resp[i]["nombres"],  resp[i]["primer_apellido"], resp[i]["nombre_materia"],
 
         counter++;
  
			//		$("#Tabla_paralelos").find('tbody').append('<tr><td>1</td>            <td>' + resp[i]["turno"] + '</td> <td>' + resp[i]["nombre_aula"] +' '+resp[i]["nombre_nivel"]+'</td>                                <td>' + resp[i]["descripcion"] + '</td> <td><input disabled type="text" class="form-control" value="' + resp[i]["capacidad"] + '"/></td><td> <a href="#" class="btn btn-warning btn-xs" style="color:white" onclick="abrir_editar('+"'"+contenido+"'"+')"><span class="icon-note"></span></a><a href='+"'#' class='btn btn-danger btn-xs' onclick='abrir_eliminar("+resp[i]["id_aula_paralelo"] +")'><span class='icon-trash'></span></a>"+'</td></tr>');//
				}
                
                
                if(aula>0){
                   
                  //  $('#btnNuevo').css('display','block'); nombre_nivel

                }else{
                  //  $('#btnNuevo').css('display','none');
                    
                }
				 
			}
		});
	}   

$("form.modal_nuevo_paralelo").submit(function(e){
     e.preventDefault();
    $("#myModal").modal("show");
    
});

  $("form.agregar_paralelo").submit(function(e){

    //function agregar_paralelo(){
        e.preventDefault();
        paralelo = $("#paralelo option:selected").val();
        turno = $("#turno option:selected").val();
        aula = $("#aula option:selected").val();
        Iparacidad = $("#Iparacidad").val();
        
        //alert(Iparacidad);
        $.ajax({
			url: '?/s-curso-paralelo/procesos',
			type: 'POST',
			data: {
				'boton': 'crear_curso_paralelo',
				'aula': aula,
                'paralelo':paralelo,
                'turno':turno,
                'Iparacidad':Iparacidad
			},
			 
			success: function(resp){
                if(resp){
                    listar_paralelos_tabla();
                    $("#myModal").modal("hide");
                   }else{
                    alert('RESP:'+ resp);
                   
                   }
		    console.log('RESP:'+ resp);
				//$("#Tabla_paralelos").html("");
				
			}
		});
        
        
        //guardar a la BD
        
    //}
    });
        
    var id_aula_paralelo=0;
//modal ASIGNACION
<?php //if ($permiso_crear) : ?>
function abrir_crear(x){
	$("#modal_gestion").modal("show");
	$("#form_gestion")[0].reset();
	//$("#form_gestion").reset();
	$("#titulo_gestion").text("Asignar ");
	id_aula_paralelo=x;
     $("#tipoAc").val('new');
    $("#aula_paralelo_id").val(id_aula_paralelo);
	$("#btn_modificar").hide();
	$("#btn_nuevo").show();
}
<?php //endif ?>
    /*
        
    $('#my-select, #pre-selected-options').multiSelect()
 
    $('#callbacks').multiSelect({
        afterSelect: function(values) {
            alert("Select value: " + values);
        },
        afterDeselect: function(values) {
            alert("Deselect value: " + values);
        }
    });
    
    $('#keep-order').multiSelect({ keepOrder: true });
 
    $('#public-methods').multiSelect();
    $('#select-all').click(function() {
        $('#public-methods').multiSelect('select_all');
        return false;
    });
    $('#deselect-all').click(function() {
        $('#public-methods').multiSelect('deselect_all');
        return false;
    });
 //  $('#select-100').click(function() {
  //      $('#public-methods').multiSelect('select', ['elem_0', 'elem_1'..., 'elem_99']);
  //      return false;
  //  });
  //  $('#deselect-100').click(function() {
  //      $('#public-methods').multiSelect('deselect', ['elem_0', 'elem_1'..., 'elem_99']);
  //      return false;
   // });
    $('#refresh').on('click', function() {
        $('#public-methods').multiSelect('refresh');
        return false;
    });
    $('#add-option').on('click', function() {
        $('#public-methods').multiSelect('addOption', { value: 42, text: 'test 42', index: 0 });
        return false;
    });
 
    $('#optgroup').multiSelect({ selectableOptgroup: true });
    
    $('#disabled-attribute').multiSelect();
   
    $('#custom-headers').multiSelect({
        selectableHeader: "<div class='custom-header'>Selectable items</div>",
        selectionHeader: "<div class='custom-header'>Selection items</div>",
        selectableFooter: "<div class='custom-header'>Selectable footer</div>",
        selectionFooter: "<div class='custom-header'>Selection footer</div>"
    });*/
    
   function abrir_eliminar(contenido){
	$("#modal_eliminar").modal("show");
	//var d = contenido.split("*");
	//$("#curso_eliminar").val(d[0]);
	$("#curso_eliminar").val(contenido);
	//$("#texto_curso").text(d[1]);
}
    
function abrir_editar(contenido){
   //alert(contenido);
	$("#modal_curso").modal("show"); 
	var d = contenido.split("*");
	$("#form_curso")[0].reset();
	$("#id_aula").val(d[0]);
	$("#capacidad_aula").val(d[1]); 
    
	//$("#btn_editar").show();
}
    
    $("#btn_eliminar").on('click', function(){
	//alert($("#gestion_eliminar").val())
     //   alert('ejemplo');
	 var id_aula_paralelo = $("#curso_eliminar").val();
   //alert(id_aula_paralelo);
	$.ajax({
		url: '?/s-curso-paralelo/eliminar',
		type:'POST',
		data: {'id_aula_paralelo':id_aula_paralelo},
		success: function(resp){
			 // alert(resp)
			switch(resp){
				case '1': $("#modal_eliminar").modal("hide");
							//dataTable.ajax.reload();
							alertify.success('Se elimino el curso correctamente');
                            listar_paralelos_tabla();
                    
                    break;
				case '2': $("#modal_eliminar").modal("hide");
							alertify.error('No se pudo eliminar '+resp);
							break;
			}
		}
	})
})
    
$("#form_curso").validate({
  rules: {
      nombre_aula: {required: true},
      nombre_nivel: {required: true}
      //id_gestion: {required: true}
  },
  errorClass: "help-inline",
  errorElement: "span",
  highlight: highlight,
  unhighlight: unhighlight,
  messages: {
      nombre_aula: "Debe ingresar nombre de curso.",
      nombre_nivel: "Debe seleccionar un nivel académico"
  },
  //una ves validado guardamos los datos en la DB
  submitHandler: function(form){
      //alert();
      var datos = $("#form_curso").serialize();
        //  alert('envio'+datos);
      $.ajax({
          type: 'POST',
          url: "?/s-curso-paralelo/guardar",
          data: datos,
          success: function (resp) {
             // alert(resp);
            cont = 0;
            switch(resp){
              case '1': //dataTable.ajax.reload();
                        $("#modal_curso").modal("hide");
                        alertify.success('Se registro el curso correctamente');
                        listar_paralelos_tabla();
                        break;
              case '2': //dataTable.ajax.reload();
                        $("#modal_curso").modal("hide");
                        alertify.success('Se editó el curso correctamente');
                        listar_paralelos_tabla();
                        break;
            }
            //pruebaa();
          }
          
      });
      
  }
})
        </script>

