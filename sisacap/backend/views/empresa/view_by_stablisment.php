<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Catalogo;

/* @var $this yii\web\View */
/* @var $model backend\models\Empresa */
$this->title = 'Ver establecimiento  Id ' . $model->ID_EMPRESA;
$this->params ['titleIcon'] = '<span class="fa-stack fa-lg">
  								<i class="fa fa-square-o fa-stack-2x"></i>
  								<i class="fa fa-university  fa-stack-1x"></i>
								   </span>';
// $this->title = $model->ID_EMPRESA;
$this->params ['breadcrumbs'] [] = [
		'label' => 'Establecimientos',
		'url' => [
				'indexestablishment'
		]
];
$this->params ['breadcrumbs'] [] = $this->title;
$entidadFederativa = Catalogo::findOne ( [
		'ID_ELEMENTO' => $model->ENTIDAD_FEDERATIVA,
		'CATEGORIA' => 1,
		'ACTIVO' => 1
] );
$municipioDelegacion = Catalogo::findOne ( [
		'ID_ELEMENTO' => $model->MUNICIPIO_DELEGACION,
		'CATEGORIA' => 2,
		'ACTIVO' => 1
] );
// $giroPrincipal = Catalogo::findOne(['ID_ELEMENTO'=>$model->GIRO_PRINCIPAL,'CATEGORIA'=>4,'ACTIVO'=>1]);
$tmp_otroGiro = '';
if ($model->GIRO_PRINCIPAL === 66666) {

	$tmp_otroGiro = $model->OTRO_GIRO;
} else {
	// consulta por el catalogo

	$catalogo = Catalogo::findOne ( [
			'ID_ELEMENTO' => $model->GIRO_PRINCIPAL,
			'CATEGORIA' => 4,
			'ACTIVO' => 1
	] );

	$tmp_otroGiro = isset ( $catalogo ) ? $catalogo->NOMBRE : 'no especificado';
}
?>
<div class="row">




	<div class=" col-xs-12 col-sm-12 col-md-12">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3>
					<i class="fa fa-eye"></i>

						<?= Yii::t('backend', 'Detalles') ?> <small>Del establecimiento</small>
				</h3>

			</div>
			<div class="panel-body">
				<div class="col-md-6 col-xs-12">
           <div class="box box-default">
				                <div class="box-header">

				       				<i class="fa fa-university"></i>
				                    <h3 class="box-title text-primary"><?= Yii::t('backend', 'Datos del establecimiento') ?></h3>

				                </div><!-- /.box-header -->
				                <div class="box-body">

    <?=DetailView::widget ( [ 'model' => $model,'attributes' =>
        [ 'ID_EMPRESA',
        'NOMBRE_CENTRO_TRABAJO',
        'RFC',
        'CURP',
        'NOMBRE_COMERCIAL',
        [ 'attribute' => 'GIRO_PRINCIPAL','type' => 'raw','value' => $tmp_otroGiro ],
        [ 'attribute' => 'ESQUEMA_SEGURIDAD_SOCIAL','type' => 'raw','value' => isset ( $model->SGS_TIPOS [$model->ESQUEMA_SEGURIDAD_SOCIAL] ) ? $model->SGS_TIPOS [$model->ESQUEMA_SEGURIDAD_SOCIAL] : 'no establecido' ],'NSS',[ 'attribute' => 'FECHA_INICIO_OPERACIONES','type' => 'html','value' => isset ( $model->FECHA_INICIO_OPERACIONES ) ? $model->FECHA_INICIO_OPERACIONES : '' ] ] ] )?>


    </div>
					</div>
				</div>

				     <div class="col-md-6 col-xs-12">
           <div class="box box-default">
				                <div class="box-header">

				       				<i class="fa fa-mobile"></i>
				                    <h3 class="box-title text-primary"><?= Yii::t('backend', 'Contacto del establecimiento') ?></h3>

				                </div><!-- /.box-header -->
				                <div class="box-body">
<?=DetailView::widget ( [ 'model' => $model,'attributes' => [ 'NOMBRE_CONTACTO','NUM_CONTACTO','TELEFONO','CORREO_ELECTRONICO' ] ] )?>
    <br>
							<br>
						</div>
					</div>
				</div>



				  <div class="col-md-6 col-xs-12">
           <div class="box box-default">
				                <div class="box-header">

				       				<i class="fa fa-map-marker"></i>
				                    <h3 class="box-title text-primary"><?= Yii::t('backend', 'Domicilio') ?></h3>

				                </div><!-- /.box-header -->
				                <div class="box-body">

<?=DetailView::widget ( [ 'model' => $model,'attributes' => [ 'CALLE','NUMERO_EXTERIOR','NUMERO_INTERIOR','COLONIA',[ 'attribute' => 'ENTIDAD_FEDERATIVA','type' => 'raw','value' => isset ( $entidadFederativa ) ? $entidadFederativa->NOMBRE : 'no establecido' ],[ 'attribute' => 'MUNICIPIO_DELEGACION','type' => 'raw','value' => isset ( $municipioDelegacion ) ? $municipioDelegacion->NOMBRE : 'no establecido' ],[ 'attribute' => 'CODIGO_POSTAL','type' => 'raw','value' => (null !== $model->CODIGO_POSTAL) ? $model->CODIGO_POSTAL : 'no establecido' ] ] ] )?>
</div>
					</div>
				</div>

			</div>

			<div class="panel-footer">
			   <?= Html::a('<i class="fa fa-pencil"></i> Editar', ['updatebystableshiment', 'id' => $model->ID_EMPRESA], ['class' => 'btn btn-warning'])?>
			         &nbsp;
			       <?=Html::a ( '<i class="fa fa-trash-o"></i> Eliminar', [ 'deletebyuser','id' => $model->ID_EMPRESA ], [ 'class' => 'btn btn-danger','data' => [ 'confirm' => '¿Seguro que quieres borrar este elemento? ¡Los datos del establecimiento no son recuperables!','method' => 'post' ] ] )?>

			</div>

		</div>



	</div>

</div>

