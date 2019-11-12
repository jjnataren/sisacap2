<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\grid\GridView;
use backend\models\Instructor;
use kartik\checkbox\CheckboxX;
use kartik\password\PasswordInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Instructor */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs ( "$('#helppop1').popover('hide');", View::POS_END, 'my-options2' );
$this->registerJs ( "$('#help_popup_registro_externo').popover('hide');", View::POS_END, 'my-options1' );

$this->title = 'Acceso usuario establecimiento Id ' . $establishmentModel->ID_EMPRESA ;
$this->params['titleIcon'] = '<span class="fa-stack fa-lg">
  								<i class="fa fa-square-o fa-stack-2x"></i>
  								<i class="fa fa-key -lg fa-stack-1x"></i>
							   </span>';

$this->params['breadcrumbs'][] = ['label' => 'Establecimiento Id ' . $establishmentModel->ID_EMPRESA, 'url' => ['viewbystablishment', 'id'=>$establishmentModel->ID_EMPRESA]];
$this->params['breadcrumbs'][] = $this->title;
?>




    <?php $form = ActiveForm::begin(); ?>
<div class="row">

	<div class="col-md-8 col-xs-12 col-sm-12 col-md-offset-1">
		

				
					
					
							 <div class="box box-warning">
				                <div class="box-header">
				                
				       				<i class="fa fa-key"></i>
				                    <h3 class="box-title"><?= Yii::t('backend', 'Datos de acceso al sistema ') ?><small> para este establecimiento   </small>
				                  
				                    </h3>
				                    <div class="box-tools pull-right">
				                    
				                    <?php if($model->model->isNewRecord):?>
				                    	  <strong class="badge pull-right bg-blue">Nuevo</strong>
				                    <?php endif;?>	  
				                    </div><!-- /.box-tools -->
				                </div><!-- /.box-header -->
				                <div class="box-body">
				                		
				                		
				                		<?php // if($model->iDUSUARIO === null):?>
				                		
										    <?= $form->field($model, 'username')->textInput(['maxlength' => 100,])->label('Nombre de usuario'); ?> 
											
				    						<?=  $form->field($model, 'password')->widget(
							 										   PasswordInput::classname(),[
																						    		'language' => 'es',
																						    		'pluginOptions' => [
																						    				'verdictTitles' => [
																						    						0 => 'No establecida',
																						    						1 => 'Muy débil',
																						    						2 => 'Débil',
																						    						3 => 'Normal',
																						    						4 => 'Buena',
																						    						5 => 'Excelente'
																						    				]]
																						    		]
																								)->label('Constraseña de acceso'); ?>

											 <?= $form->field($model, 'email')->textInput(['maxlength' => 100,]) ?>
											 
											 <?= $form->field($model, 'status')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false,], ])->label('Activo'); ?>							
								
				        		        
				       			</div>
				       			
				       			 <div class="box-footer">
				       			 	<button id="helppop1" tabindex="0" type="button" class="btn" data-toggle="popover" title="Ayuda" data-content="<?=Yii::t('backend', 'Aquí puedes dar acceso a un usuario') ?>"><i class="fa fa-question-circle"></i>
										</button>
            						 <?= Html::submitButton( '<i class="fa fa-floppy-o"></i> Guardar', ['class' => $model->model->isNewRecord ? 'btn btn-primary' : 'btn btn-warning']) ?>
				       			 </div>
				       		</div>	
					
					</div>

	</div>







<?php ActiveForm::end(); ?>