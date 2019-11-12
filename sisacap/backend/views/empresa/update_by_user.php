<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Empresa */

$this->title = 'Editar empresa matriz'; //$model->ID_EMPRESA;
$this->params['subtitle'] = '';

$this->params['titleIcon'] = '<span class="fa-stack fa-lg">
  								<i class="fa fa-square-o fa-stack-2x"></i>
  								<i class="fa fa-building    fa-stack-1x"></i>
							   </span>';

//$this->params['breadcrumbs'][] = ['label' => 'Empresas', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->ID_EMPRESA, 'url' => ['view', 'id' => $model->ID_EMPRESA]];
$this->params['breadcrumbs'][] = $this->title;
?>


<?= $this->render('_form_by_user', [
        'model' => $model,
    ]) ?>