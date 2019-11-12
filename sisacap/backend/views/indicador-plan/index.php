<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\IndicadorPlanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Indicador Plans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="indicador-plan-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Indicador Plan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ID_EVENTO',
            'ID_PLAN',
            'TITULO',
            'DATA',
            'CATEGORIA',
            // 'ID_ALERTA',
            // 'FECHA_CREACION',
            // 'FECHA_INICIO_VIGENCIA',
            // 'FECHA_FIN_VIGENCIA',
            // 'ESTATUS',
            // 'ACTIVO',
            // 'ID_USUARIO',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
