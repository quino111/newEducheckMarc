
<?php
  $csrf = set_csrf();
?>
<form id="form_paralelo">
<div class="modal fade" id="modal_paralelo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><span id="titulo_gestion"></span> Paralelo</h5>
        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </a>
      </div>
      <div class="modal-body"> 
        <div class="control-group">
          <label class="control-label">Nombre Paralelo: </label>
          <div class="controls">
            <input type="hidden" name="<?= $csrf; ?>">
            <input id="id_paralelo" name="id_paralelo" type="hidden" class="form-control">            
            <input id="nombre_paralelo" name="nombre_paralelo" type="text" class="form-control">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Descripción: </label>
          <div class="controls">
            <input id="descripcion" name="descripcion" type="text" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary pull-right" id="btn_nuevo">Registrar</button>
        <button type="submit" class="btn btn-primary pull-right" id="btn_editar">Editar</button>
      </div>
    </div>
  </div>
</div>
</form>

<script>

$("#form_paralelo").validate({
  rules: {
      nombre_paralelo: {required: true},
      descripcion: {required: true}
  },
  errorClass: "help-inline",
  errorElement: "span",
  highlight: highlight,
  unhighlight: unhighlight,
  messages: {
      nombre_paralelo: "Debe ingresar el nombre de paralelo.",
      descripcion: "Debe ingresar una descripcion."
  },
  //una ves validado guardamos los datos en la DB
  submitHandler: function(form){
      //alert();
      var datos = $("#form_paralelo").serialize();
      $.ajax({
          type: 'POST',
          url: "?/s-paralelo/guardar",
          data: datos,
          success: function (resp) {
            console.log(resp);
            switch(resp){
              case '2': dataTable.ajax.reload();
                        $("#modal_gestion").modal("hide");
                        alertify.success('Se registro el paralelo correctamente');
                        break;
              case '1': dataTable.ajax.reload();
                        $("#modal_gestion").modal("hide");
                        alertify.success('Se editó el paralelo correctamente'); break;
            }
            //pruebaa();
          }
          
      });
      
  }
})

</script>