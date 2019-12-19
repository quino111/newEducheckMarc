<?php

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene el modelo materia
$materia = $db->from('pro_materia')->order_by('nombre_materia', 'asc')->fetch();

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);

?>
<?php require_once show_template('header-full'); ?>
<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Crear a profesor materia</strong>
	</h3>
</div>
<div class="panel-body">
	<?php if ($permiso_listar) : ?>
	<div class="row">
		<div class="col-xs-6">
			<div class="text-label hidden-xs">Seleccionar acción:</div>
			<div class="text-label visible-xs-block">Acciones:</div>
		</div>
		<div class="col-xs-6 text-right">
			<div class="btn-group">
				<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
					<span class="glyphicon glyphicon-menu-hamburger"></span>
					<span class="hidden-xs">Acciones</span>
				</button>
				<ul class="dropdown-menu dropdown-menu-right">
					<li class="dropdown-header visible-xs-block">Seleccionar acción</li>
					<li><a href="?/profesor_materia/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar profesor_materia</a></li>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<?php endif ?>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<form method="post" action="?/profesor_materia/guardar" autocomplete="off">
				<input type="hidden" name="<?= $csrf; ?>">
				<div class="form-group">
					<label for="id_profesor" class="control-label">Id profesor:</label>
					<input type="text" value="" name="id_profesor" id="id_profesor" class="form-control" autofocus="autofocus" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="codigo_profesor" class="control-label">Codigo profesor:</label>
					<input type="text" value="" name="codigo_profesor" id="codigo_profesor" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max45">
				</div>
				<div class="form-group">
					<label for="nombre_profesor" class="control-label">Nombre profesor:</label>
					<input type="text" value="" name="nombre_profesor" id="nombre_profesor" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max102">
				</div>
				<div class="form-group">
					<label for="materia_id" class="control-label">Materia:</label>
					<select name="materia_id" id="materia_id" class="form-control" data-validation="required">
						<option value="" selected="selected">Seleccionar</option>
						<?php foreach ($materia as $elemento) : ?>
						<option value="<?= $elemento['id_materia']; ?>"><?= escape($elemento['nombre_materia']); ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="form-group">
					<label for="nombre_materia" class="control-label">Nombre materia:</label>
					<input type="text" value="" name="nombre_materia" id="nombre_materia" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max300">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-danger">
						<span class="glyphicon glyphicon-floppy-disk"></span>
						<span>Guardar</span>
					</button>
					<button type="reset" class="btn btn-default">
						<span class="glyphicon glyphicon-refresh"></span>
						<span>Restablecer</span>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script>
$(function () {
	$.validate({
		modules: 'basic'
	});
});
</script>
<?php require_once show_template('footer-full'); ?>