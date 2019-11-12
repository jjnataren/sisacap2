<?php

use yii\helpers\Html;


/** @var $this yii\web\View
 * @var $model common\models\WidgetCarouselItem
 * @var $carousel common\models\WidgetCarousel
 */

$this->title = Yii::t('backend', 'Agregar {modelClass}', [
    'modelClass' => 'elemento a carrusel',
]);
//$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Elemntos carrusel'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $carousel->key, 'url' => ['update', 'id' => $carousel->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Crear');
?>
<div class="widget-carousel-item-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
