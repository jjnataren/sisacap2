<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model backend\models\ComisionMixtaCap */

$this->title = 'Comisión Id ' . $model->ID_COMISION_MIXTA;

$this->params['titleIcon'] = '<span class="fa-stack fa-lg">
  								<i class="fa fa-square-o fa-stack-2x"></i>
  								<i class="glyphicon glyphicon-copyright-mark -lg  fa-stack-1x"></i>
							   </span>';
$this->params['breadcrumbs'][] = ['label' => 'Comision Mixta Caps', 'url' => ['indexbycompany']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comision-mixta-cap-view">
	<div class=" col-xs-12 col-sm-12 col-md-9">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3><i class="fa fa-eye"></i>
						
						<?= Yii::t('backend', 'Detalles') ?> <small>de la comisión</small> </h3>
						
					</div>
					<div class="panel-body">
					

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'ID_COMISION_MIXTA',
            //'ID_EMPRESA',
            //'COMISION_MIXTA_PADRE',
			'ALIAS',	
            'NUMERO_INTEGRANTES',
            'FECHA_CONSTITUCION:date',
			'FECHA_AGREGO:date',
            'FECHA_ELABORACION:date',
			'DESCRIPCION',
			'LUGAR_INFORME',
            //'ACTIVO',
        ],
    ]) ?>
<p>
        <?= Html::a('<i class="fa fa-pencil"> Editar </i>', ['updatebyuser', 'id' => $model->ID_COMISION_MIXTA], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('<i class="fa fa-trash-o"></i> Eliminar', ['deletebyuser', 'id' => $model->ID_COMISION_MIXTA], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Seguro que quieres borrar esta comisión? ¡Los datos de la comisión no son recuperables¡',
                'method' => 'post',
            ],
        ]) ?>
    </p>
</div>
</div>
</div>
</div>
