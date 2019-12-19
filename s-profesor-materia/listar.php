<?php

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los profesor_materia
$profesor_materia = $db->select('z.*, a.nombre_materia as materia')->from('vista_profesor_materia z')->join('pro_materia a', 'z.materia_id = a.id_materia', 'left')->order_by('z.id_profesor_materia', 'asc')->fetch();

// Obtiene los profesores
$profesores = $db->query("SELECT p.numero_documento, p.primer_apellido, p.segundo_apellido, p.nombres, pr.id_profesor, p.id_persona
								FROM sys_persona AS p
								INNER JOIN pro_profesor AS pr ON p.id_persona = pr.persona_id")->fetch();

$asignaturas = $db->query("SELECT m.id_materia, m.nombre_materia, m.estado
								FROM pro_materia AS m
								WHERE m.estado = 'A'")->fetch();



$aula_paralelos = $db->query("SELECT ap.id_aula_paralelo , a.nombre_aula, p.nombre_paralelo
								FROM ins_aula_paralelo AS ap
								INNER JOIN ins_aula AS a ON ap.aula_id = a.id_aula
								INNER JOIN ins_paralelo AS p ON p.id_paralelo = ap.paralelo_id ")->fetch();



// Obtiene los permisos
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_modificar = in_array('modificar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);

?>
<?php require_once show_template('header-design'); ?>
<link rel="stylesheet" href="<?= css; ?>/selectize.bootstrap3.min.css">

<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"> 
        <div class="page-header">
            <h2 class="pageheader-title">Profesor - Materia</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Gestión</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Asignaciones</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Docente/Materia</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Listar</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- end pageheader -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- row -->
<!-- ============================================================== -->
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
						<div class="text-label hidden-xs">Seleccione:</div>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
						<div class="btn-group">
								<div class="input-group">
								<div class="input-group-append be-addon">
									<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
									<div class="dropdown-menu">
										<a class="dropdown-item">Seleccionar acción</a>
										<?php if ($permiso_crear) : ?>
										<div class="dropdown-divider"></div>
										<a href="#"  onclick="abrir_crear();" class="dropdown-item">Asignar Profesor Materia</a>
										<?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-profesor-materia/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Exportar pdf</a>
										<a href="?/s-profesor-materia/imprimirexcel" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Exportar Excel</a>
										<?php endif ?>
									</div>
								</div>
							</div>
						</div> 
					</div>
				</div>
			</div>
			<!-- ============================================================== -->
			<!-- datos --> 
			<!-- ============================================================== -->
			<div class="card-body">
				<?php if ($message = get_notification()) : ?>
				<div class="alert alert-<?= $message['type']; ?>">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong><?= $message['title']; ?></strong>
					<p><?= $message['content']; ?></p>
				</div>
				<?php endif ?>
				<?php if ($profesor_materia) : ?>
				<table id="table" class="table table-bordered table-condensed table-striped table-hover">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<th class="text-nowrap">Código profesor</th>
							<th class="text-nowrap">Nombre profesor</th>
							<th class="text-nowrap">Materia</th>
							<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
							<th class="text-nowrap">Opciones</th>
							<?php endif ?>
						</tr>
					</thead>
					<tfoot>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<th class="text-nowrap text-middle">Código profesor</th>
							<th class="text-nowrap text-middle">Nombre profesor</th>
							<th class="text-nowrap text-middle">Materia</th>
							<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
							<th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
							<?php endif ?>
						</tr>
					</tfoot>
					<tbody>
						<?php foreach ($profesor_materia as $nro => $profesor_materia) : ?>
						<tr>
							<th class="text-nowrap"><?= $nro + 1; ?></th>
							<td class="text-nowrap"><?= escape($profesor_materia['codigo_profesor']); ?></td>
							<td class="text-nowrap"><?= escape($profesor_materia['nombre_profesor']); ?></td>
							<td class="text-nowrap"><?= escape($profesor_materia['materia']); ?></td>
							<?php if ($permiso_ver || $permiso_modificar || $permiso_eliminar) : ?>
							<td class="text-nowrap">
								<?php if ($permiso_ver) : ?>
								<a href="?/profesor_materia/ver/<?= $profesor_materia['id_profesor_materia']; ?>" data-toggle="tooltip" data-title="Ver a profesor materia"><span class="glyphicon glyphicon-search"></span></a>
								<?php endif ?>
								<?php if ($permiso_modificar) : ?>
								<a href="?/profesor_materia/modificar/<?= $profesor_materia['id_profesor_materia']; ?>" data-toggle="tooltip" data-title="Modificar a profesor materia"><span class="glyphicon glyphicon-edit"></span></a>
								<?php endif ?>
								<?php if ($permiso_eliminar) : ?>
								<a href="?/profesor_materia/eliminar/<?= $profesor_materia['id_profesor_materia']; ?>" data-toggle="tooltip" data-title="Eliminar a profesor materia" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span></a>
								<?php endif ?>
							</td>
							<?php endif ?>
						</tr>
						<?php endforeach ?>
					</tbody>
				</table>
				<?php else : ?>
				<div class="alert alert-info">
					<strong>Atención!</strong>
					<ul>
						<li>No existen a profesor materia registrados en la base de datos.</li>
						<li>Para crear nuevos a profesor materia debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
					</ul>
				</div>
				<?php endif ?>
			</div>
			<!-- ============================================================== -->
			<!-- end datos -->
			<!-- ============================================================== -->
		</div>
	</div>
</div>


<!--modal para eliminar-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_eliminar">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
	  	<input type="hidden" id="gestion_eliminar">
        <p>¿Esta seguro de eliminar la gestion <span id="texto_gestion"></span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn_eliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<!--script src="<?= js; ?>/jquery.dataTables.min.js"></script>
<script src="<?= js; ?>/dataTables.bootstrap.min.js"></script-->
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<!--script src="<?= js; ?>/jquery.dataFilters.min.js"></script-->
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>
<script src="<?= js; ?>/buzz.min.js"></script>
<script src="<?= js; ?>/bootbox.min.js"></script>
<script src="<?= $ruta?>/s-gestion-escolar.js"></script>
<?php require_once show_template('footer-design'); ?>
<?php 
	if($permiso_modificar){
		require_once ("editar.php");
	}
	if($permiso_ver){
		require_once ("ver.php");
	}
?>


<script>
$(function () {
	$('#id_docente').selectize({
        persist: false,
        createOnBlur: true,
        create: false,
        onInitialize: function () {
            $('#id_docente').css({
                display: 'block',
                left: '-10000px',
                opacity: '0',
                position: 'absolute',
                top: '-10000px'
            });
        },
        onChange: function () {
            $('#id_docente').trigger('blur');
        },
        onBlur: function () {
            $('#id_docente').trigger('blur');
        }
    });

    $('#id_curso_paralelo').selectize({
        persist: false,
        createOnBlur: true,
        create: false,
        onInitialize: function () {
            $('#id_curso_paralelo').css({
                display: 'block',
                left: '-10000px',
                opacity: '0',
                position: 'absolute',
                top: '-10000px'
            });
        },
        onChange: function () {
            $('#id_curso_paralelo').trigger('blur');
        },
        onBlur: function () {
            $('#id_curso_paralelo').trigger('blur');
        }
    });
});

$(function () {
    $('#id_asignatura').selectize({
        persist: false,
        createOnBlur: true,
        create: false,
        onInitialize: function () {
            $('#id_asignatura').css({
                display: 'block',
                left: '-10000px',
                opacity: '0',
                position: 'absolute',
                top: '-10000px'
            });
        },
        onChange: function () {
            $('#id_asignatura').trigger('blur');
        },
        onBlur: function () {
            $('#id_asignatura').trigger('blur');
        }
    });
});
</script>



<script>
<?php if ($permiso_modificar) : ?>
function abrir_editar(contenido){
	$("#form_gestion")[0].reset();
	//validator.resetForm();
	$("#modal_gestion").modal("show");
	$("#titulo_gestion").text("Editar ");
	$('#table tbody').off();
	var d = contenido.split("*");
	$("#id_gestion").val(d[0]);
	$("#nombre_gestion").val(d[1]);
	$("#inicio_gestion").val(moment(d[2]).format('YYYY-MM-DD'));
	$("#final_gestion").val(moment(d[3]).format('YYYY-MM-DD'));
	$("#inicio_vacaciones").val(moment(d[4]).format('YYYY-MM-DD'));
	$("#final_vacaciones").val(moment(d[5]).format('YYYY-MM-DD'));
	$("#btn_nuevo").hide();
	$("#btn_modificar").show();
}
<?php endif ?>

<?php if ($permiso_crear) : ?>
function abrir_crear(){
	$("#modal_gestion").modal("show");
	$("#form_gestion")[0].reset();
	//$("#form_gestion").reset();
	$("#titulo_gestion").text("Crear ");
	
	$("#btn_modificar").hide();
	$("#btn_nuevo").show();
}
<?php endif ?>

$(function () {
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
	
	<?php if ($profesor_materia) : ?>
	/*$('#table').DataFilter({
		filter: true,
		name: 'profesor_materia',
		reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
	});*/
			var dataTable = $('#table').DataTable({
	language: dataTableTraduccion,
	searching: true,
	paging:true,
	"lengthChange": true,
	"responsive": true
	});
	<?php endif ?>
});
</script>