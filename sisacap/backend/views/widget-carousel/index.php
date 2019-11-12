<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\WidgetCarouselSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Carruseles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-carousel-index">

    <p>
        <?= Html::a(Yii::t('backend', 'Nuevo {modelClass}', [
    'modelClass' => 'Carrusel',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'key',
            [
                'class'=>\common\components\grid\EnumColumn::className(),
                'attribute'=>'status',
                'enum'=>[
                    Yii::t('backend', 'Disabled'),
                    Yii::t('backend', 'Enabled')
                ],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {delete}'
            ],
        ],
    ]); ?>

</div>
