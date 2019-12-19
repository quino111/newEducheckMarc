<?php

// Obtiene los parametros
$id_profesor_materia = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene el profesor_materia
$profesor_materia = $db->select('z.*, a.nombre_materia as materia')->from('vista_profesor_materia z')->join('pro_materia a', 'z.materia_id = a.id_materia', 'left')->where('z.id_profesor_materia', $id_profesor_materia)->fetch_first();

// Ejecuta un error 404 si no existe el profesor_materia
if (!$profesor_materia) { require_once not_found(); exit; }

// Obtiene el modelo materia
$materia = $db->from('pro_materia')->order_by('nombre_materia', 'asc')->fetch();

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);

?>
<?php require_once show_template('header-full'); ?>
<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Modificar a profesor materia</strong>
	</h3>
</div>
<div class="panel-body">
	<?php if ($permiso_listar || $permiso_crear || $permiso_ver || $permiso_eliminar || $permiso_imprimir) : ?>
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
					<?php if ($permiso_listar) : ?>
					<li><a href="?/profesor_materia/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar a profesor materia</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/profesor_materia/crear"><span class="glyphicon glyphicon-plus"></span> Crear a profesor materia</a></li>
					<?php endif ?>
					<?php if ($permiso_ver) : ?>
					<li><a href="?/profesor_materia/ver/<?= $id_profesor_materia; ?>"><span class="glyphicon glyphicon-search"></span> Ver a profesor materia</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<li><a href="?/profesor_materia/eliminar/<?= $id_profesor_materia; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar a profesor materia</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/profesor_materia/imprimir/<?= $id_profesor_materia; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir a profesor materia</a></li>
					<?php endif ?>
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
					<input type="text" value="<?= $profesor_materia['id_profesor']; ?>" name="id_profesor" id="id_profesor" class="form-control" autofocus="autofocus" data-validation="required number">
					<input type="text" value="<?= $id_profesor_materia; ?>" name="id_profesor_materia" id="id_profesor_materia" class="translate" tabindex="-1" data-validation="required number" data-validation-error-msg="El campo no es válido">
				</div>
				<div class="form-group">
					<label for="codigo_profesor" class="control-label">Codigo profesor:</label>
					<input type="text" value="<?= $profesor_materia['codigo_profesor']; ?>" name="codigo_profesor" id="codigo_profesor" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max45">
				</div>
				<div class="form-group">
					<label for="nombre_profesor" class="control-label">Nombre profesor:</label>
					<input type="text" value="<?= $profesor_materia['nombre_profesor']; ?>" name="nombre_profesor" id="nombre_profesor" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max102">
				</div>
				<div class="form-group">
					<label for="materia_id" class="control-label">Materia:</label>
					<select name="materia_id" id="materia_id" class="form-control" data-validation="required">
						<option value="">Seleccionar</option>
						<?php foreach ($materia as $elemento) : ?>
						<?php if ($elemento['id_materia'] == $profesor_materia['materia_id']) : ?>
						<option value="<?= $elemento['id_materia']; ?>" selected="selected"><?= escape($elemento['nombre_materia']); ?></option>
						<?php else : ?>
						<option value="<?= $elemento['id_materia']; ?>"><?= escape($elemento['nombre_materia']); ?></option>
						<?php endif ?>
						<?php endforeach ?>
					</select>
				</div>
				<div class="form-group">
					<label for="nombre_materia" class="control-label">Nombre materia:</label>
					<input type="text" value="<?= $profesor_materia['nombre_materia']; ?>" name="nombre_materia" id="nombre_materia" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max300">
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
	
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/profesor_materia/crear';
				break;
			}
		}
	});
	<?php endif ?>
	
	<?php if ($permiso_eliminar) : ?>
	$('[data-eliminar]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var csrf = '<?= $csrf; ?>';
		bootbox.confirm('Está seguro que desea eliminar el a profesor materia?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-full'); ?>