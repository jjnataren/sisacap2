<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WidgetCarousel */

$this->title = Yii::t('backend', 'Actualizar {modelClass}: ', [
    'modelClass' => 'carrusel',
]) . ' ' . $model->key;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Carruseles'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Actualizar');
?>
<div class="widget-carousel-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Crear {modelClass}', [
            'modelClass' => 'elemento de carrusel',
        ]), ['/widget-carousel-item/create', 'carousel_id'=>$model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $carouselItemsProvider,
        'columns' => [
            'order',
            [
                'attribute'=>'path',
                'format'=>'raw',
                'value'=>function($model){
                    return $model->path ? Html::img($model->path, ['style'=>'width: 100%']) : null;
                }
            ],
            'url:url',
            [
                'format'=>'html',
                'attribute'=>'caption',
                'options'=>['style'=>'width: 20%']
            ],
            'status',

            [
                'class' => 'yii\grid\ActionColumn',
                'controller'=>'/widget-carousel-item',
                'template'=>'{update} {delete}'
            ],
        ],
    ]); ?>


</div>
