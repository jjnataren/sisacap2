<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\KeyStorageItem */

$this->title = Yii::t('backend', 'Nueva variable global', [
    'modelClass' => 'Key Storage Item',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Datos globales'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="key-storage-item-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
