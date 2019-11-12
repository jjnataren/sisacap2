<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Trabajador */

$this->title = 'Actualizar trabajador: ' . ' Id  ' . $model->ID_TRABAJADOR;
$this->params['titleIcon'] = '<span class="fa-stack fa-lg">
  								<i class="fa fa-square-o fa-stack-2x"></i>
  								<i class="fa fa-users fa-lg  fa-stack-1x"></i>
							   </span>';
$this->params['breadcrumbs'][] = ['label' => 'Administrar trabajadores', 'url' => ['index-by-sub']];
$this->params['breadcrumbs'][] = ['label' =>'Trabajador Id ' . $model->ID_TRABAJADOR, 'url' => ['view-by-sub', 'id' => $model->ID_TRABAJADOR]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>

   

    <?= $this->render('_company_form', [
        'model' => $model,
    ]) ?>

