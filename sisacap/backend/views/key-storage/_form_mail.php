<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\KeyStorageItem */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="key-storage-item-form">

    <?php $form = ActiveForm::begin(); ?>


	<div class="col-md-6">
    	<?= $form->field($username, '[0]value')->textInput()->label("Usuario") ?>
	</div>
    <div class="col-md-6">
	    <?= $form->field($password, '[1]value')->textInput()->label("Contraseña") ?>
	</div>
    <div class="col-md-6">
    	<?= $form->field($host, '[2]value')->textInput()->label("Servidor SMTP") ?>
	</div>
	<div class="col-md-6">
	    <?= $form->field($port, '[3]value')->textInput()->label("Puerto") ?>
	</div>
    <div class="col-md-6">
    	<?= $form->field($encryption, '[4]value')->dropDownList(['tls'=>'tls', 'ssl'=>'ssl'],['id' => 'tex-sex'])->label("Tipo encriptación") ?>



	</div>

	<div class="col-md-8">
    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Guardar') , ['class' => 'btn btn-success' ]) ?>
    </div>
	</div>
    <?php ActiveForm::end(); ?>

</div>
