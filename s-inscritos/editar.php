<?php
  $csrf = set_csrf(); 
  
?>
<form id="form_estudiante"> 
<div class="modal fade" id="modal_estudiante" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar Estudiante</h5>
        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </a>
      </div>
      <div class="modal-body">
        <div class="control-group">
          <label class="control-label">Nombres: </label>
          <div class="controls">
            <input type="hidden" name="<?= $csrf; ?>">
            <input id="id_estudiante" name="id_estudiante" type="hidden" class="form-control">
            <input id="nombre_estudiante" name="nombre_estudiante" type="text" class="form-control">            
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Primer Apellido: </label>
          <div class="controls">
            <input id="primer_apellido" name="primer_apellido" type="text" class="form-control">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Segundo Apellido: </label>
          <div class="controls">
            <input id="segundo_apellido" name="segundo_apellido" type="text" class="form-control">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Tipo de Documento: </label>
          <div class="controls">
            <input id="tipo_documento" name="tipo_documento" type="text" class="form-control">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Número de Documento: </label>
          <div class="controls">
            <input id="numero_documento" name="numero_documento" type="text" class="form-control">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Complemento: </label>
          <div class="controls">
            <input id="complemento" name="complemento" type="text" class="form-control">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Genero: </label>
          <div class="controls">
            <input id="genero" name="genero" type="text" class="form-control">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Fecha de Nacimiento: </label>
          <div class="controls">
            <input id="fecha_nacimiento" name="fecha_nacimiento" type="text" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary pull-right" id="btn_nuevo">Registrar</button>
        <button type="submit" class="btn btn-primary pull-right" id="btn_editar">Editar</button>
      </div>
    </div>
  </div>
</div>
</form>

<script>
$(function () {
    $("#form_estudiante").validate({
      rules: {
        nombre_estudiante: {required: true},
        segundo_apellido: {required: true},
        tipo_documento: {required: true},
        numero_documento: {required: true},
        complemento: {required: true},
        genero: {required: true},
        fecha_nacimiento: {required: true}
      },
      errorClass: "help-inline",
      errorElement: "span",
      highlight: highlight,
      unhighlight: unhighlight,
      messages: {
        nombre_estudiante: "Debe ingresar nombre(s) de estudiante",
        segundo_apellido: "Debe ingresar segundo apellido",
        tipo_documento: "Debe ingresar tipo de documento",
        numero_documento: "Debe ingresar número de documento",
        complemento: "Debe ingresar complemento",
        genero: "Debe ingresar género",
        fecha_nacimiento: "Debe ingresar fecha de nacimeinto"
      },
      //una ves validado guardamos los datos en la DB
      submitHandler: function(form){
          var datos = $("#form_estudiante").serialize();
          $.ajax({
              type: 'POST',
              url: "?/s-inscripciones/guardar",
              data: datos,
              success: function (resp) {
                console.log(resp); 
                switch(resp){
                  case '1':
                            dataTable.ajax.reload();
                            $("#modal_inscripcion").modal("hide");
                            alertify.success('Se registro el estudiante correctamente');
                            break;
                  case '2': dataTable.ajax.reload();
                            $("#modal_inscripcion").modal("hide"); 
                            alertify.success('Se editó el estudiante correctamente'); 
                            break;
                }
              }
          });
      }
    })
  })
</script>