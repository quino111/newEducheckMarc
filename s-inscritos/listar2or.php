<?php

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los estudiantes
$estudiantes = $db->select('z.*,p.numero_documento, concat(z.primer_apellido," ",z.segundo_apellido," ",z.nombres) nombre_completo')->from('vista_inscripciones z')
->join('ins_estudiante e','z.estudiante_id=e.id_estudiante')
->join('sys_persona p','e.persona_id=p.id_persona')
->where('z.gestion_id',1)->order_by('z.primer_apellido', 'asc')->fetch();


//var_dump($estudiantes);exit();
// Obtiene los permisos   
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_editar = in_array('editar', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);

?>
<?php require_once show_template('header-design'); ?>
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Estudiantes Inscritos</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Inscripción</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Inscritos</a></li>
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
<div class="row">
    <!-- ============================================================== -->
    <!-- row -->
    <!-- ============================================================== -->
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <!-- <h5 class="card-header">Generador de menús</h5> --> 
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
                                        <a href="#" onclick="abrir_crear();" class="dropdown-item">Registrar Estudiante</a>
                                        <?php endif ?>  
										<?php if ($permiso_imprimir) : ?>
										<div class="dropdown-divider"></div>
										<a href="?/s-nivel-academico/imprimir" class="dropdown-item" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
										<?php endif ?>
									</div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <!-- ============================================================== -->
                <!-- datos --> 
                <!-- ============================================================== -->
				<?php if ($message = get_notification()) : ?>
				<div class="alert alert-<?= $message['type']; ?>">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong><?= $message['title']; ?></strong>
					<p><?= $message['content']; ?></p>
				</div>
				<?php endif ?>
				<?php if ($estudiantes) : ?>
				<table id="table" class="table table-bordered table-condensed table-striped table-hover">
					<thead>
						<tr class="active">
							<th class="text-nowrap">#</th>
							<th class="text-nowrap">Nombre completo</th>
							<th class="text-nowrap">Documento</th>
							<th class="text-nowrap">Nivel</th>
							<th class="text-nowrap">Curso</th>
							<th class="text-nowrap">Paralelo</th>
							<th class="text-nowrap">Tipo estudiante</th>
							<th class="text-nowrap">Tutor</th>
							<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
							<th class="text-nowrap">Opciones</th>
							<?php endif ?>
						</tr>
					</thead>
					<tfoot>
						<tr class="active">
							<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
							<th class="text-nowrap text-middle">Nombre completo</th>
							<th class="text-nowrap text-middle">Documento</th>
							<th class="text-nowrap text-middle">Nivel</th>
							<th class="text-nowrap text-middle">Curso</th>
							<th class="text-nowrap text-middle">Paralelo</th>
							<th class="text-nowrap text-middle">Tipo estudiante</th>
							<th class="text-nowrap text-middle">Tutor</th>
							<?php if ($permiso_ver || $permiso_editar || $permiso_eliminar) : ?>
							<th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th>
							<?php endif ?>
						</tr>
					</tfoot>
					<tbody>
					</tbody>
				</table>
				<?php else : ?>
				<div class="alert alert-info">
					<strong>Atención!</strong>
					<ul>
						<li>No existen inscripción registrados en la base de datos.</li>
						<li>Para crear nuevos inscripción debe hacer clic en el botón de acciones y seleccionar la opción correspondiente o puede presionar las teclas <kbd>alt + n</kbd>.</li>
					</ul>
				</div>
				<?php endif ?>
                <!-- ============================================================== -->
                <!-- end datos -->
                <!-- ============================================================== -->
				</div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- end row -->
<!-- ============================================================== --> 
<!--modal para eliminar-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_eliminar">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
	  	<input type="hidden" id="estudiante_eliminar">
        <p>¿Esta seguro de eliminar estudiante <span id="texto_estudiante"></span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-rounded btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-rounded btn-primary" id="btn_eliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<!-- <script src="<?= js; ?>/jquery.dataFilters.min.js"></script> -->
<script src="<?= themes; ?>/concept/assets/vendor/full-calendar/js/moment.min.js"></script>
<script src="<?= js; ?>/selectize.min.js"></script>
<script src="<?= js; ?>/jquery.validate.js"></script>
<script src="<?= js; ?>/matrix.form_validation.js"></script>
<script src="<?= js; ?>/educheck.js"></script>
<!--script src="<?= $ruta?>/s-gestion-escolar.js"></script-->
<?php require_once show_template('footer-design'); ?>
<?php 
	if($permiso_editar){
		require_once ("editar.php");
	}	
	if($permiso_ver){
		require_once ("ver.php");
	}
?>
<script>
$(function () {
	
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e){
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/gestiones/crear';
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
		bootbox.confirm('Está seguro que desea eliminar el gestion?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
	 
	<?php if ($estudiantes) : ?>
	// $('#nivel_academico').DataFilter({
	// 	filter: true,
	// 	name: 'niveles',
	// 	reports: '<?= ($permiso_imprimir) ? "excel|word|pdf|html" : ""; ?>'
	// });
	<?php endif ?>
	//carga toda la lista de grupo proyecto con DataTable
});

<?php if ($permiso_editar) : ?>
function abrir_editar(contenido){
	$("#form_estudiante")[0].reset();
	$("#modal_estudiante").modal("show");
	var d = contenido.split("*");
	$("#id_estudiante").val(d[0]);
	$("#nombre_estudiante").val(d[1]);
	$("#primer_apellido").val(d[2]);
	$("#segundo_apellido").val(d[3]);
	$("#tipo_documento").val(d[4]);
	$("#numero_documento").val(d[5]);
	$("#complemento").val(d[6]);
	$("#genero").val(d[7]);
	$("#fecha_nacimiento").val(d[8]);
	$("#btn_nuevo").hide(); 
	$("#btn_editar").show();
}
<?php endif ?>

<?php if ($permiso_crear) : ?>
function abrir_crear(){
	$("#modal_estudiante").modal("show");
	$("#form_estudiante")[0].reset();
	$("#btn_editar").hide(); 
	$("#btn_nuevo").show();
}
<?php endif ?>

var columns=[
	{data: 'estudiante_id'},
	{data: 'nombre_completo'},
	{data: 'numero_documento'},
	{data: 'nombre_nivel'},
	{data: 'nombre_aula'},
	{data: 'nombre_paralelo'} ,
	{data: 'nombre_tipo_estudiante'} ,
	{data: 'nombres_familiar'} 
];
var cont = 0;
//function listarr(){
var dataTable = $('#table').DataTable({
	language: dataTableTraduccion,
	searching: true,
	paging:true,
	"lengthChange": true,
	"responsive": true,
	ajax: {
		url: '?/s-inscritos/busqueda',
		dataSrc: '',
		type:'POST',
		dataType: 'json'
	},
	columns: columns,

	"columnDefs": [
			{
					"render": function (data, type, row) {
						var result = "";
						var contenido = row['estudiante_id'] + "*" +  row['nombre_completo'] + "*" + row['numero_documento'] + "*" + row['nombre_nivel'] + "*" + row['nombre_aula'] + "*" + row['nombre_paralelo'] + "*" + row['nombre_tipo_estudiante'] + "*" + row['nombres_familiar'];
						//var id_estudiante = row['estudiante_id'];
						//console.log(id_estudiante);
						result+="<?php if ($permiso_ver) : ?><a href='#' class='btn btn-xs btn-info' onclick = 'ver()'><span class='icon-eye'></span></a><?php endif ?> &nbsp";
						return result;
					},
					"targets": 8
			},
			{
					"render": function (data, type, row) {
						cont = cont +1;
						return cont;
					},
					"targets": 0
			}
	]
});
//} 
//" if ($permiso_eliminar) : ?><a href='#' onclick='abrir_eliminar("+'"'+contenido+'"'+")'><span class='icon-trash'></span></a> &nbsp" +
<?php if ($permiso_ver) : ?>
function ver(){
	$('#table tbody').on('click', 'tr', function () {
		var data = dataTable.row( this ).data();
		//alert( 'You clicked on '+data[0]+'\'s row' );
		$("#estudiante_ver").modal("show");
		$("#nombre_estudiante").text(data['nombres']);
		$("#tipo_documento").text(data['tipo_documento']);
		$("#numero_documento").text(data['numero_documento']);
		$("#complemento").text(data['complemento']);
		$("#genero").text(data['genero']);
		$("#fecha_nacimiento").text(data['fecha_nacimiento']);
	});
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
function abrir_eliminar(contenido){
	$("#modal_eliminar").modal("show");
	var d = contenido.split("*");
	$("#estudiante_eliminar").val(d[0]);
	$("#texto_estudiante").text(d[1]);
}
<?php endif ?>

<?php if ($permiso_eliminar) : ?>
// $("#btn_eliminar").on('click', function(){
// 	//alert($("#gestion_eliminar").val())
// 	id_estudiante = $("#estudiante_eliminar").val();
// 	$.ajax({lificacion
// 		url: '?/s-inscripciones/eliminar',
// 		type:'POST',
// 		data: {'id_estudiante':id_estudiante},
// 		success: function(resp){
// 			//alert(resp)
// 			switch(resp){
// 				case '1': $("#modal_eliminar").modal("hide");
// 							dataTable.ajax.reload();
// 							alertify.success('Se elimino el estudiante correctamente');break;
// 				case '2': $("#modal_eliminar").modal("hide");
// 							alertify.error('No se pudo eliminar ');
// 							break;
// 			}
// 		}
// 	})
// });
<?php endif ?>
</script>