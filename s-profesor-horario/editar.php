<?php
$csrf = set_csrf();
?>
<form id="form_gestion">
  <div class="modal fade" id="modal_gestion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><span id="titulo_gestion"></span>  Docente-Materia</h5>
          <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </a>
        </div>
        <div class="modal-body">
          <div class="control-group">
            <div class="controls">
              <input type="hidden" name="<?= $csrf; ?>">
              <input name="aula_paralelo_id" id="aula_paralelo_id"  type="hidden" placeholder="aula_paralelo_id" >
              <input name="tipoAc" id="tipoAc"  type="hidden"  placeholder="tipoAc">
              <input name="aula_par_prof_mat_id" id="aula_par_prof_mat_id"  type="hidden"  placeholder="id_horario_profesor_materia">
             <!-- <input id="id_docente" name="id_docente" type="hidden" class="form-control">-->
              <!--input id="id_docente" name="id_docente" type="text" class="form-control"-->
              <select name="id_docente" id="id_docente" class="form-control">
               
              </select> <br>
            </div>
          </div>
           <div class="control-group">
            <label class="control-label">Hora Inicial: </label>
            <input required type="time" placeholder="" name="hora_inicio" id="hora_inicio" class="form-control" onchange="comparar_horas()" >
        <!-- <input id="timepicker" width="276" />-->
            </div>
            <div class="control-group">
          
            <label class="control-label">Hora Final: <span class="msgErrorHora" style="color:red;display:none">La hora final deve ser manor a la hora inicial</span> </label>
            <input required type="time" placeholder="" name="hora_fin" id="hora_fin" class="form-control" onchange="comparar_horas()" >
            </div> 
         <!--  <div class="control-group">
            <label class="control-label">Seleccione curso paralelo: </label>
            <div class="controls">
              <input type="hidden" name="<?//= $csrf; ?>">
              <input id="id_gestion" name="id_gestion" type="hidden" class="form-control">
             
              <select name="id_docente" id="id_docente" class="form-control">
               
              </select>
            </div>
          </div>-->
          <p>* Para asignar un Docente materia deve accedere al menu Gestion>Asignaciones y seleccionar <a href="?/s-profesor-materia/listar">Profesor/Materia</a></p>
           </div>
        <hr>
          
          
          <div class="modal-footer">
            <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary pull-right" id="btn_nuevo" >Guardar</button>
            <button type="submit" class="btn btn-primary pull-right" id="btn_modificar" >Editar</button>
          </div>
        </div>
      </div>
    </div>
</form>

<style>
  .margen {
    margin-top: 15px;
  }
</style> 
<link rel="stylesheet" href="assets/css/gijgo.css"> 
<script src="assets/gijgo.min.js"></script>
 

<script>
    $('#hora_inicio').timepicker();
    $('#hora_fin').timepicker();
</script>
<script>
//CARGAR SIGANCIONES MATERIA DOCENTE 
  // listar_asignacion_docente_materia de php 
    listar_asignacion_docente_materia();
	function listar_asignacion_docente_materia() {
	 nivel = 0;//$("#nivel_academico option:selected").val()
		$.ajax({
			url: '?/s-curso-paralelo-profesor-materia/procesos',
			type: 'POST',
			data: {
				'boton': 'listar_asignacion_docente_materia',
				'nivel': nivel
			},
			dataType: 'JSON',
			success: function(resp){
		    console.log('Listar aula'+ resp); 
               // alert('ejemplo');
				//alert(resp[0]['id_catalogo_detalle']); 
				//console.log(resp);
                var cont=0;
				$("#id_docente").html("");
				$("#id_docente").append('<option value="">Seleccionar</option>');
				for (var i = 0; i < resp.length; i++) {
/*id_profesor_materia
nombres
primer_apellido
nombre_materia*/
                        $("#id_docente").append('<option  value="' + resp[i]["id_profesor_materia"] + '">' + resp[i]["primer_apellido"]+' ' + resp[i]["nombres"]+' - ' + resp[i]["nombre_materia"]+'</option>');
                    
                   // listar_nivel();
                   // listar_aulas();
				}
				//console.log(resp[0]);
                   

			}
		});
        
	} 
    
    //Guardar
  $("#form_gestion").validate({
    rules: {
      id_docente: {
        required: true
      }/*,
      id_curso_paralelo: {
        required: true
      },
      id_asignatura: {
        required: true
      }*/
      //id_gestion: {required: true}
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
      id_docente: "Debe seleccionar una asignacion."/*,
      id_curso_paralelo: "Debe seleccionar un curso.",
      id_asignatura: "Debe seleccionar la asignatura."*/
    },
    //una ves validado guardamos los datos en la DB
    submitHandler: function(form) {

       
      var datos = $("#form_gestion").serialize();
      $.ajax({
        type: 'POST',
        url: "?/s-profesor-horario/guardar",
        data: datos,
        success: function(resp) {
         //alert(resp);
          cont = 0;
          switch (resp) {
            case '1':
              //dataTable.ajax.reload();
              $("#modal_gestion").modal("hide");
              alertify.success('Se asigno correctamente  el Docente paralelo y materia');
                  
    listar_paralelos_tabla();
              break;
            case '2':
              //dataTable.ajax.reload();
              $("#modal_gestion").modal("hide");
              alertify.success('Se edito correctamente  el Docente paralelo y materia');
    listar_paralelos_tabla();
              break;
            case '3':
              //dataTable.ajax.reload();
              //$("#modal_gestion").modal("hide");
              alertify.warning('Ya se asigno anteriormente a este docente en la mismo curso');
              break;
            default : alertify.error('No se pudo guardar'+resp);
          }
          //pruebaa();
        }

      });

    }
  })
function comparar_horas(){ 
//$('#hora_fin').change(function(){
    
     // alert('vlivkk');
   var hora_inicio=$('#hora_inicio').val();
   var hora_fin=$('#hora_fin').val();
    console.log('a hora es '+hora_inicio+' - '+hora_fin);
    
    if(hora_inicio>=hora_fin && hora_fin!=''){
    $('.msgErrorHora').show();
    $('#btn_nuevo').attr('disabled',true);
        
    }else{
    $('.msgErrorHora').hide();
    $('#btn_nuevo').attr('disabled',false);
        
    }
   
//});
}
    

    
    
    
</script>