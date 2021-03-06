<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Empresa;
use backend\models\Catalogo;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use yii\helpers\Url;
use kartik\checkbox\CheckboxX;
use yii\web\View;




		$this->registerJs("$('#help_popup_carnera').popover('hide');", View::POS_END, 'noneoptions234');
		$this->registerJs("$('#help_popup_telefono').popover('hide');", View::POS_END, 'noneoptions2345');
		$this->registerJs("$('#help_popup_correo').popover('hide');", View::POS_END, 'noneoptions25');
		$itemsSex = [1=>'IMSS',2=>'ISSSTE',3=>'PROPIO',4=>'NO CUENTA'];

		$this->registerJs("
				var seguridad= $('#seguridadSocial').val();
				if(seguridad == 4){

				$('#txt_nss').val('');
   				$('#txt_nss').attr('disabled','true');
				}else{
				$('#txt_nss').removeAttr('disabled');
				}
				;",View::POS_READY, 'seguridad');

		$this->registerJs("$('#seguridadSocial').change(function(){
				if(this.value == 4){

				$('#txt_nss').val('');
   				$('#txt_nss').attr('disabled','true');
				}else{
				$('#txt_nss').removeAttr('disabled');
				}


				});",View::POS_END, 'seguridad');

		$this->registerJs("

				var giro = $('#drop_giro').val();
				if( giro == 66666){

				//activacion
				$('#txt_giro_otro').removeAttr('disabled');
		}else{
					$('#txt_giro_otro').val('');
   				    $('#txt_giro_otro').attr('disabled','true');

		}

         ", View::POS_READY, 'my_lod');

		$this->registerJs("$('#drop_giro').change(function(){

		if(this.value == 66666){

				//activacion
				$('#txt_giro_otro').removeAttr('disabled');
		}else{
					$('#txt_giro_otro').val('');
   				    $('#txt_giro_otro').attr('disabled','true');

		}


});", View::POS_END, 'noneoptions_drop_functions');


$this->registerJs("

		var isChecked = $('#chk_moral').val();

		if (isChecked==1){
			$('#txt_curp').val('');
   			$('#txt_curp').attr('disabled',true);
		}

        $('#txt_nss').mask('AAA-YYYY-YY-Y', {'translation': {
    A: {pattern: /[A-Za-z0-9]/},
    S: {pattern: /[A-Za-z]/},
    Y: {pattern: /[0-9]/}
  }
});

	", View::POS_READY, 'myonload');



/* @var $this yii\web\View */
/* @var $model backend\models\Empresa */
/* @var $form yii\widgets\ActiveForm */

$dataListEntidad=ArrayHelper::map(Catalogo::findAll(['CATEGORIA'=>1,'ACTIVO'=>1]), 'ID_ELEMENTO', 'NOMBRE');

$dataListMunicipios=ArrayHelper::map(Catalogo::findAll(['CATEGORIA'=>2,'ACTIVO'=>1, 'ELEMENTO_PADRE'=>$model->ENTIDAD_FEDERATIVA]), 'ID_ELEMENTO', 'NOMBRE');

$dataListGiro=ArrayHelper::map(Catalogo::findAll(['CATEGORIA'=>4,'ACTIVO'=>1]), 'ID_ELEMENTO', 'NOMBRE');

$this->registerJs("$('#chk_moral').change(function(){

		var ischecked = false;

		if(this.value == 1) ischecked = true;
	$('#txt_curp').val('');
   $('#txt_curp').attr('disabled',ischecked);
});", View::POS_END, 'noneoptions');

?>


    <?php $form = ActiveForm::begin(); ?>
    <div class ="row">
      <div class=" col-xs-12 col-sm-12 col-md-12">
				<div class="panel panel-warning">
					<div class="panel-heading">


					</div>
	<div class="panel-body">

     <div class=" col-xs-12 col-sm-12 col-md-3">

  	 <?= $form->field($model, 'PICTURE')->widget(\trntv\filekit\widget\SingleFileUpload::classname(), [
        'url'=>['avatar-upload', 'category'=>'none']
    ]) ?>



    </div>

  			<div class="col-md-9 col-xs-12">
           <div class="box box-default">
				                <div class="box-header">

				       				<i class="fa fa-university"></i>
				                    <h3 class="box-title text-primary"><?= Yii::t('backend', 'Datos de la empresa') ?></h3>

				                </div><!-- /.box-header -->
				                <div class="box-body">
			<?= $form->field($model, 'MORAL')->widget(CheckboxX::classname(), ['options'=>['id'=>'chk_moral'],'pluginOptions'=>['threeState'=>false]]); ?>

		<div class="row">
			<div class=" col-xs-12 col-sm-12 col-md-5">
	   			 <?= $form->field($model, 'NOMBRE_RAZON_SOCIAL')->textInput(['maxlength' => 300]) ?>
			</div>



			<div class=" col-xs-12 col-sm-12 col-md-5">
		    <?= $form->field($model, 'RFC')->textInput(['maxlength' => 13]) ?>
		    </div>
		     <div class=" col-xs-12 col-sm-12 col-md-5">
		     <?= $form->field($model, 'GIRO_PRINCIPAL')->dropDownList($dataListGiro,['prompt'=>'-- Seleccione  --','maxlength' => 300, 'id'=>'drop_giro']) ?>

		     </div>
		    <div class=" col-xs-12 col-sm-12 col-md-5">
		   	 <?= $form->field($model, 'ESQUEMA_SEGURIDAD_SOCIAL')->dropDownList($itemsSex,['prompt'=>'-- Seleccione  --','id' => 'seguridadSocial']) ?>
		    	</div>

		    	 <div class=" col-xs-12 col-sm-12 col-md-5">
		     <?= $form->field($model, 'OTRO_GIRO')->textInput(['maxlength' => 200,'id'=>'txt_giro_otro']) ?>

		     </div>

		    	<div class=" col-xs-12 col-sm-12 col-md-5">
		    <?= $form->field($model, 'NSS')->textInput(['maxlength' => 20, 'id'=>'txt_nss']) ?>
		    	</div>

		     <div class=" col-xs-12 col-sm-12 col-md-5">
		     <?= $form->field($model, 'CURP')->textInput(['maxlength' => 18,'id'=>"txt_curp"]) ?>
		     </div>

    	<div class=" col-xs-12 col-sm-12 col-md-5">
    		<strong>Número trabajadores</strong>
	   		 <span class="badge bg-blue"><?= $model->NUMERO_TRABAJADORES;?></span>
	 		<label><small><i>Si desea agregar más trabajares, póngase en contacto con el administrador</i></small></label>
	     </div>


		     <div class=" col-xs-12 col-sm-12 col-md-5">
		   	  <?= $form->field($model, 'NOMBRE_COMERCIAL')->textInput(['maxlength' => 200,]) ?>
		     </div>


	  <div class=" col-xs-12 col-sm-12 col-md-5">
	     	<?= $form->field($model, 'FECHA_INICIO_OPERACIONES')->widget('trntv\yii\datetimepicker\DatetimepickerWidget', ['clientOptions'=>['format' => 'DD/MM/YYYY', 'locale'=>'es','showClear'=>true, 'keepOpen'=>false]]) ?>
    					 </div>
	   </div>


    	</div>
    	</div>
    </div>
   	  <div class="col-md-6 col-xs-12">
           <div class="box box-default">
				                <div class="box-header">

				       				<i class="fa fa-map-marker"></i>
				                    <h3 class="box-title text-primary"><?= Yii::t('backend', 'Domicilio') ?></h3>

				                </div><!-- /.box-header -->
				                <div class="box-body">



    <?= $form->field($model, 'CALLE')->textInput(['maxlength' => 300]) ?>

    <?= $form->field($model, 'NUMERO_EXTERIOR')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'NUMERO_INTERIOR')->textInput(['maxlength' => 100]) ?>

 	<!-- <?= $form->field($model, 'DOMICILIO')->textArea(['maxlength' => 100]) ?> -->

       <?= $form->field($model, 'COLONIA')->textInput(['maxlength' => 300]) ?>

     <?= $form->field($model, 'ENTIDAD_FEDERATIVA')->dropDownList($dataListEntidad,['prompt'=>'-- Seleccione  --','id' => 'cat-id']) ?>

<!--     <?= $form->field($model, 'MUNICIPIO_DELEGACION')->textInput(['maxlength' => 300]) ?> -->

    <?= $form->field($model, 'MUNICIPIO_DELEGACION')->widget(DepDrop::classname(), [
    'options' => ['id' => 'subcat-id'],
    'data'=>$dataListMunicipios,
    'pluginOptions' => [
        'depends' => ['cat-id'],
        'placeholder' => 'Seleccione ...',
        'url' => Url::to(['getmunicipios'])
    ]
]); ?>

     <?= $form->field($model, 'LOCALIDAD')->textInput(['maxlength' => 300]) ?>



             <?= $form->field($model, 'CODIGO_POSTAL')->textInput() ?>
        </div>
        </div>
        </div>
 <div class="col-md-6 col-xs-12">
           <div class="box box-default">
				                <div class="box-header">

				       				<i class="fa fa-mobile"></i>
				                    <h3 class="box-title text-primary"><?= Yii::t('backend', 'Contacto del establecimiento') ?></h3>

				                </div><!-- /.box-header -->
				                <div class="box-body">

	 <?= $form->field($model, 'NOMBRE_CONTACTO')->textInput(['maxlength' => 300]) ?>
	 <?= $form->field($model, 'NUM_CONTACTO')->textArea(['maxlength' => 300]) ?>
	 <div class="row">
		<div class="col-xs-10 col-md-10">
   <?= $form->field($model, 'TELEFONO')->textArea(['maxlength' => 300]) ?>


    </div>


    <div class="col-xs-2 col-md-2">
    	<br />

    	<button id="help_popup_telefono" data-placement="top" tabindex="0" type="button" class="btn btn-info btn-sm" data-toggle="popover" title="Ayuda" data-content="Se requiere intoducir lada."><i class="fa fa-question-circle"></i>
	</button>
    </div>
 </div>

     <div class="row">
		    <div class="col-xs-10 col-md-10">
		     	<?= $form->field($model, 'CORREO_ELECTRONICO')->textInput(['maxlength' => 300]) ?>
			     	</div>
		    	<br />
		    	<div class="col-xs-2 col-md-2">

		    	<button id="help_popup_correo" data-placement="top" tabindex="0" type="button" class="btn btn-info btn-sm" data-toggle="popover" title="Ayuda" data-content="
Correo electrónico del contacto."><i class="fa fa-question-circle"></i>
			  </button>
			</div>
    	</div>

       </div>
		</div>
		</div>

       </div>

        <div>
       </div>
    </div>

    <?php ActiveForm::end(); ?>
	<div class="panel-footer">

						<button id="help_popup_carnera" tabindex="0" type="button" class="btn" data-toggle="popover" title="Ayuda" data-content="<?=Yii::t('backend', 'Presiona el botón [Guardar] para salvar los cambios ') ?>"><i class="fa fa-question-circle"></i>
						</button>
        <?= Html::submitButton(Yii::t('backend', '<i class="fa fa-floppy-o" ></i>  Guardar'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

</div>
</div>