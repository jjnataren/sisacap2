<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\KeyStorageItem */

$this->title = Yii::t('backend', 'Configuración de correo');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="key-storage-item-create">

    <?= $this->render('_form_mail', [
        'username' => $username,
        'password' => $password,
        'host' => $host,
        'port' => $port,
        'encryption' => $encryption,
    ]) ?>

</div>
