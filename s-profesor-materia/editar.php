<?php
$csrf = set_csrf();
?>
<form id="form_gestion">
  <div class="modal fade" id="modal_gestion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><span id="titulo_gestion"></span> Gestión Escolar</h5>
          <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </a>
        </div>
        <div class="modal-body">
          <div class="control-group">
            <label class="control-label">Seleccione Docente: </label>
            <div class="controls">
              <input type="hidden" name="<?= $csrf; ?>">
              <input id="id_gestion" name="id_gestion" type="hidden" class="form-control">
              <!--input id="id_docente" name="id_docente" type="text" class="form-control"-->
              <select name="id_docente" id="id_docente" class="form-control">
                <option value="">Buscar</option>
                <?php foreach ($profesores as $profesor) { ?>
                  <option value="<?= escape($profesor['id_profesor']); ?>"><?= escape($profesor['numero_documento']) . ' &mdash; ' . escape($profesor['nombres']) . ' &mdash; ' . escape($profesor['primer_apellido']) . ' &mdash; ' . escape($profesor['segundo_apellido']); ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="control-group margen">
            <label class="control-label">Seleccione Curso: </label>
            <div class="controls">
              <!--input id="id_curso_paralelo" name="id_curso_paralelo" type="text" class="form-control"-->
              <select name="id_curso_paralelo" id="id_curso_paralelo" class="form-control">
                <option value="">Buscar</option>
                <?php foreach ($aula_paralelos as $aula_paralelo) { ?>
                  <option value="<?= escape($aula_paralelo['id_aula_paralelo']); ?>"><?= escape($aula_paralelo['nombre_aula']) . ' &mdash; ' . escape($aula_paralelo['nombre_paralelo']); ?></option>
                <?php } ?>
              </select>    

            </div>
          </div>
          <div class="control-group margen">
            <label class="control-label">Seleccione Asignatura: </label>
            <div class="controls">
              <!--input id="id_asignatura" name="id_asignatura" type="text" class="form-control"-->
              <select name="id_asignatura" id="id_asignatura" class="form-control">
                <option value="">Buscar</option>
                <?php foreach ($asignaturas as $asignatura) { ?>
                  <option value="<?= escape($asignatura['id_materia']); ?>"><?= escape($asignatura['nombre_materia']); ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light pull-left" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary pull-right" id="btn_nuevo">Guardar</button>
            <button type="submit" class="btn btn-primary pull-right" id="btn_modificar">Editar</button>
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
<script>
  $("#form_gestion").validate({
    rules: {
      id_docente: {
        required: true
      },
      id_curso_paralelo: {
        required: true
      },
      id_asignatura: {
        required: true
      }
      //id_gestion: {required: true}
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: highlight,
    unhighlight: unhighlight,
    messages: {
      id_docente: "Debe seleccionar un docente.",
      id_curso_paralelo: "Debe seleccionar un curso.",
      id_asignatura: "Debe seleccionar la asignatura."
    },
    //una ves validado guardamos los datos en la DB
    submitHandler: function(form) {

      //alert();
      var datos = $("#form_gestion").serialize();
      $.ajax({
        type: 'POST',
        url: "?/s-profesor-materia/guardar",
        data: datos,
        success: function(resp) {
          console.log(resp);
          cont = 0;
          switch (resp) {

            case '2':
              dataTable.ajax.reload();
              $("#modal_gestion").modal("hide");
              alertify.success('Se asigno correctamente  el Docente paralelo y materia');
              break;
            case '1':
              dataTable.ajax.reload();
              $("#modal_gestion").modal("hide");
              alertify.success('Se edito correctamente  el Docente paralelo y materia');
              break;
          }
          //pruebaa();
        }

      });

    }
  })
</script>