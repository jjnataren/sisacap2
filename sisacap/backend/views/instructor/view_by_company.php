<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Instructor */

$this->title = 'Ver instructor.'.' '.'ID'.'-' . $model->ID_INSTRUCTOR;
$this->params ['titleIcon'] = '<span class="fa-stack fa-lg">
  								<i class="fa fa-square-o fa-stack-2x"></i>
  								<i class="fa fa-graduation-cap -lg  fa-stack-1x"></i>
							   </span>';

/*$this->params ['breadcrumbs'] [] = [ 
		'label' => 'Instructores',
		'url' => [ 
				'indexbycompany' 
		] 
];
*/
$this->params ['breadcrumbs'] [] = $this->title;
?>
<div class="row">

	<div class=" col-xs-12 col-sm-12 col-md-12">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3>
					<i class="fa fa-eye"></i>
						<?= Yii::t('backend', 'Detalles') ?> <small>del instructor / capacitador</small>
				</h3>
			</div>

		<div class="panel-body">
					
							<div class="col-md-6 col-xs-12">
           <div class="box box-default">
				                <div class="box-header">
				                
				       				<i class="fa fa-graduation-cap"></i>
				                    <h3 class="box-title text-primary"><?= Yii::t('backend', 'Datos del instructor') ?></h3>
				                    
				                </div><!-- /.box-header -->
				                <div class="box-body">  
				
						
						 <?=DetailView::widget ( [ 'model' => $model,'attributes' => [//
						'ID_INSTRUCTOR','NOMBRE','APP','APM','NUM_REGISTRO_AGENTE_EXTERNO','NOMBRE_AGENTE_EXTERNO' ] ] ) ?>
					</div>
			</div>
			</div>
			
			
		
                  <div class="col-md-6 col-xs-12">
           <div class="box box-default">
				                <div class="box-header">
				                
				       				<i class="fa fa-mobile"></i>
				                    <h3 class="box-title text-primary"><?= Yii::t('backend', 'Contacto ') ?></h3>
				                    
				                </div><!-- /.box-header -->
				                <div class="box-body">
					<?=DetailView::widget ( [ 'model' => $model,'attributes' => [ 'DOMICILIO','TELEFONO','CORREO_ELECTRONICO' ] ] )?>
				</div>
				</div>
				</div>
				
				
			
				
						    <div class="col-md-6 col-xs-12">
           <div class="box box-default">
				                <div class="box-header">
				                
				       				<i class="fa fa-file"></i>
				                    <h3 class="box-title text-primary"><?= Yii::t('backend', 'Documento probatorio') ?></h3>
				                    
				                </div><!-- /.box-header -->
				                <div class="box-body">
				   			 	
				   			 <div class="panel">
								
				               
				                <div class="panel-body">
				                	<?php if($model->DOCUMENTO_PROBATORIO !== null && strlen($model->DOCUMENTO_PROBATORIO)> 1):?>
				                	
				                							<object data="<?=$model->DOCUMENTO_PROBATORIO ?>" type="image/jpeg" width="300px" height="160px">
															 <param name="movie" value="{caption}" />
																	<div class="file-preview-other">
																		<i class="glyphicon glyphicon-file"></i>
																	</div>
															 </object>
															 <div class="file-thumbnail-footer">
															    <div class="file-caption-name"><?= $model->NOMBRE_DOC_PROB;?></div>
															    <div class="file-actions">
															</div>
															</div>
									<?php else:?>
									
										<label><i>Sin documento probatorio</i></label>		
										
									<?php endif;?>					
				                
				                </div>
				                
				                 <?php if ($model->DOCUMENTO_PROBATORIO !== null):?>
		                 			<div class="panel-footer">
					    					<a href="<?= $model->DOCUMENTO_PROBATORIO ?>" target="_blank" class="btn btn-default"><i class="fa fa-download"> </i> Descargar documento</a>
		        	        
		                 			 </div>
				             	 <?php endif;?>
				             </div>
		
						</div>	
						</div>
						</div>
						
						
						<?php if(isset($model->iDUSUARIO)) :?>
						
						
						 <div class="col-md-6 col-xs-12">
           <div class="box box-default">
				                <div class="box-header">
				                
				       				<i class="fa fa-key"></i>
				                    <h3 class="box-title text-primary"><?= Yii::t('backend', 'Datos de acceso al sistema') ?></h3>
				                    
				                </div><!-- /.box-header -->
				                <div class="box-body">
				   			 
				   			 <div class="panel">
								
				               
				                <div class="panel-body">
				                	<?=DetailView::widget ( [ 'model' => $model->iDUSUARIO,'attributes' => 
				                			[ 
				                			 ['label'=>'Nombre de usuario',
				                			 	'value'=>$model->iDUSUARIO->username	
				                				],
				                			'email',
				                			['label'=>'Activo',
				                				'value'=>($model->iDUSUARIO->status)?'SI':'NO'	
				                					
				                			] ] ] )?>				
				                
				                </div>
				                
				            
				             </div>
		
						</div>	
						
						<?php endif;?>
				
				</div>
				</div>
				</div>
					
				
								<div class="panel-footer">
					    				 <?= Html::a('<i class="fa fa-pencil"></i> Editar', ['updatebycompany', 'id' => $model->ID_INSTRUCTOR], ['class' => 'btn btn-warning']) ?>
		        	        
		                 			 </div>
			</div>
		</div>

	</div>











