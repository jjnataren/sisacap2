	<?php
/**
 * Author: Eugine Terentev <eugine@terentev.net>
 * @var $this \yii\web\View
 */
use common\models\User;
use trntv\systeminfo\SI;
use backend\models\Catalogo;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
use yii\base\Model;
use kartik\file\FileInput;
use yii\web\UploadedFile;
use yii\helpers\Json;
use trntv\filekit\actions\UploadAction;
use backend\models\ListaPlan;
use yii\widgets\ActiveForm;
use yii\web\View;
use backend\models\Curso;
use backend\models\Trabajador;

$this->params['titleIcon'] = '<span class="fa-stack fa-lg">
  								<i class="fa fa-square-o fa-stack-2x"></i>
  								<i class="fa fa-calendar fa-stack-1x"></i>
							   </span>';

$this->title =  Yii::t('backend', 'Plan de capacitación').' Id ' .$model->ID_PLAN .' - '.$model->ALIAS;



$this->params['breadcrumbs'][] = ['label' => 'Comisión ID '.$model->ID_COMISION, 'url'=>['comision-mixta-cap/dashboard', 'id'=>$model->ID_COMISION]];
$this->params['breadcrumbs'][] = ['label' => 'Plan ID '.$model->ID_PLAN, 'url'=>['', 'id'=>$model->ID_PLAN]];


/**
 * Items for menu tabs of PLANS
 *
 */

$this->registerJs("

		var isChecked = $('#chk_puesto').val();

		if (isChecked==1){
			$('#userButton').val('');
   			$('#userButton').attr('disabled',false);
		}

	", View::POS_READY, 'myonload');
/*
$planItems  = [];
$i = 0;

foreach ($model->plans as $planModel){

$planItems[]= 	[
	'label' => ($planModel->ALIAS===null)?'Plan #'.$planModel->ID_PLAN:$planModel->ALIAS,
	'content' => \Yii::$app->view->renderFile('@app/views/plan/_plan_detail.php',
			 ['id'=>1, 'model'=>$planModel,'searchPlanEstablecimientoModel'=>$searchPlanEstablecimientoModel,
			'dataproviderPlanEstablecimiento'=>$dataproviderPlanEstablecimiento]),
	'active' => !$i++
	];


}

*/


?>

<div class="row">

 <div class="col-md-3 col-xs-6 col-sm-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>
                       <?php

							echo count($model->cursos);

                        // todo: change after #5146 will be implemented ?>
                    </h3>
                    <p>
                        <?= Yii::t('backend', 'Cursos') ?>
                    </p>
                </div>
                <div class="icon">
                    <i class="fa fa-laptop"></i>
                </div>
                 <a href="#anchor_curso" class="small-box-footer">
                               			 <strong>   Agregados a este plan <i class="fa fa-arrow-circle-right"></i></strong>
                            		    </a>

            </div>
        </div>





</div>


<div class="row">
         <div class="col-md-6 col-xs-12 col-sm-12">
            <div class="box box-primary">
                <div class="box-header">
                    <i class="fa fa-calendar"></i>
                    <h3 class="box-title"><?= Yii::t('backend', 'Detalles del plan y programa') ?></h3>

                    <div class="box-tools pull-right">
            <button title="ocultar/mostrar" data-toggle="tooltip" data-widget="collapse" class="btn btn-default btn-xs" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
            <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-default btn-xs" data-original-title="Remove"><i class="fa fa-times"></i></button>
          </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <dl class="dl-horizontal">

                        <dt><?= Yii::t('backend', 'Id') ?></dt>
                        <dd><?= $model->ID_PLAN ?></dd>

                        <dt><?= Yii::t('backend', 'Alias') ?></dt>
                        <dd><?= $model->ALIAS ?></dd>

                          <dt><?= Yii::t('backend', 'Descripción') ?></dt>
                        <dd><?= $model->DESCRIPCION_PLAN ?></dd>

                         <dt><?= Yii::t('backend', 'Etapas del plan') ?></dt>
                        <dd><?= $model->NUMERO_ETAPAS ?></dd>

                         <dt><?= Yii::t('backend', 'Vigencia de inicio') ?></dt>
                           <dd><?=($model->VIGENCIA_INICIO === null)?'<i class="text-muted">no establecido</i>':date("d/m/Y",strtotime($model->VIGENCIA_INICIO)) ;?></dd>

                         <dt><?= Yii::t('backend', 'Vigencia termino') ?></dt>
                         <dd><?=($model->VIGENCIA_FIN === null)?'<i class="text-muted">no establecido</i>':date("d/m/Y",strtotime($model->VIGENCIA_FIN)) ;?></dd>

                        <dt><?= Yii::t('backend', 'Lugar elaboración del informe') ?></dt>
                        <dd><?= $model->LUGAR_INFORME ?></dd>

                         <dt><?= Yii::t('backend', 'Fecha elaboración del informe') ?></dt>
                         <dd><?=($model->FECHA_INFO === null)?'<i class="text-muted">no establecido</i>':date("d/m/Y",strtotime($model->FECHA_INFO)) ;?></dd>


                      	<dt>&nbsp;</dt>
                       	<dd>&nbsp;</dd>

                        <dt><i><?= Yii::t('backend', 'Agregado desde') ?></i></dt>
                         <dd><?=($model->FECHA_AGREGO === null)?'<i class="text-muted">no establecido</i>':date("d/m/Y h:i A",strtotime($model->FECHA_AGREGO)) ;?></dd>


                          <dt><i><?= Yii::t('backend', 'Estatus') ?></i></dt>
                        <dd><span class="label label-success"><?= $model->getStatus(); ?></span></dd>


                    </dl>





                </div><!-- /.box-body -->

                   <div class="box-footer">
			       </div>
            </div>
        </div>




          <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                   <i class="glyphicon glyphicon-copyright-mark"></i>
                    <h3 class="box-title"><?= Yii::t('backend', 'Datos de la comisión mixta de capacitación. ') ?><small> &nbsp; La cual rige y revisa este plan y programa</small></h3>

               <div class="box-tools pull-right">
            <button title="ocultar/mostrar" data-toggle="tooltip" data-widget="collapse" class="btn btn-default btn-xs" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
            <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-default btn-xs" data-original-title="Remove"><i class="fa fa-times"></i></button>
          </div><!-- /.box-tools -->


                </div><!-- /.box-header -->
                <div class="box-body">
                    <dl class="dl-horizontal">
                        <dt><?= Yii::t('backend', 'ID') ?></dt>
                        <dd><?= $model->iDCOMISION->ID_COMISION_MIXTA ?></dd>

                        <dt><?= Yii::t('backend', 'Alias') ?></dt>
                        <dd><?= $model->iDCOMISION->ALIAS ?></dd>

                   		<dt><?= Yii::t('backend', 'Descripción') ?></dt>
                        <dd><?= $model->iDCOMISION->DESCRIPCION ?></dd>


                        <dt><?= Yii::t('backend', 'Fecha elaboración') ?></dt>
                        <dd><?=($model->iDCOMISION->FECHA_ELABORACION  === null)?'<i class="text-muted">no establecido</i>':date("d/m/Y",strtotime($model->iDCOMISION->FECHA_ELABORACION)) ;?></dd>



                         <dt><?= Yii::t('backend', 'Fecha constitución') ?></dt>
                         <dd><?=($model->iDCOMISION->FECHA_CONSTITUCION  === null)?'<i class="text-muted">no establecido</i>':date("d/m/Y",strtotime($model->iDCOMISION->FECHA_CONSTITUCION)) ;?></dd>


                         <dt><?= Yii::t('backend', 'Numero integrantes') ?></dt>
                        <dd><?= $model->iDCOMISION->NUMERO_INTEGRANTES ?></dd>


                       <dt><i><?= Yii::t('backend', 'Estatus') ?></i></dt>
                        <dd><span class="label label-success"><?= $model->iDCOMISION-> getStatus(); ?></span></dd>
                    </dl>
               </div><!-- /.box-body -->

                 <div class= "box-footer">


				</div>



            </div>
        </div>






      </div>

	<h4 class="page-header" id="anchor_trabajadores">
     Información de los trabajadores que serán considerados en este plan.
   		<small>Puestos de trabajo que considera este plan y detalle de los trabajadores</small>
   </h4>


    <div class="row">

        	<div class="col-md-6 col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                  <i class="fa fa-user-secret"></i>
              <h2 class="box-title"><?= Yii::t('backend', 'Puestos  de trabajo') ?>  <small> a los que estará dirigido este plan</small> </h2>

              <div class="box-tools pull-right">
            <button title="ocultar/mostrar" data-toggle="tooltip" data-widget="collapse" class="btn btn-default btn-xs" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
            <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-default btn-xs" data-original-title="Remove"><i class="fa fa-times"></i></button>
          </div><!-- /.box-tools -->

                </div><!-- /.box-header -->
                <div class="box-body">

                <div class="box-body table-responsive">
         <table class="table table-hover" >

         <thead>
	         <tr>
		         <th>Clave</th>
		         <th>Nombre</th>
		         <th>Descripción</th>

	         </tr>
         </thead>

         <tbody>

         	<?php $i = 0; foreach ($model->iDPUESTOs as $puestoEmpresa){ $i++;?>
         	<tr>
         		<td><?= $puestoEmpresa->CLAVE_PUESTO;?></td>
         		<td><?= $puestoEmpresa->NOMBRE_PUESTO ?></td>
         		<td><?= $puestoEmpresa->DESCRIPCION_PUESTO ?></td>

         	</tr>
         	<?php }?>
         </tbody>

        </table>
        </div>

                </div><!-- /.box-body -->


            </div>
        </div>





	  <div class="col-md-12 col-xs-12 col-sm-12">
            <div class="box box-info">
                <div class="box-header">
                  <i class="fa fa-users "></i>
                  <h2 class="box-title"><?= Yii::t('backend', 'Trabajadores que serán considerados en este plan ') ?>  </h2>
                <div class="box-tools pull-right">
            <button title="ocultar/mostrar" data-toggle="tooltip" data-widget="collapse" class="btn btn-default btn-xs" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
            <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-default btn-xs" data-original-title="Remove"><i class="fa fa-times"></i></button>
          </div><!-- /.box-tools -->

            <div class="box-body">


			   <?php  foreach ($model->planEstablecimientos as $establecimiento):?>

			    <table class="table table-hover table-bordered" >
		            <thead>
		            <tr>
		            	<th colspan="6"  style="text-align: left;" ><i class="fa fa-university"></i>Establecimiento ID <?=$establecimiento->ID_EMPRESA ?> -
		            	<?= $establecimiento->NOMBRE_COMERCIAL?></th>
		            </tr>
			         <tr>
			          <th>Id</th>
				      <th>Nombre</th>
				      <th>RFC</th>
				      <th>Correo e</th>
				      <th>Puesto</th>
				     <!--  <th>No. constancias</th> -->
			         </tr>
			         </thead>
			          <tbody>

			          <?php

			          if (!$model->TIPO_PLAN){
			          	$trabajadors = Trabajador::findBySql('select * from tbl_trabajador where puesto IN
																(select ID_PUESTO from tbl_plan_puesto where id_plan = :id_plan and activo = 1) AND ID_EMPRESA = :id_empresa',[':id_empresa'=>$establecimiento->ID_EMPRESA, ':id_plan'=>$model->ID_PLAN])->all();
			          }else{

			          		$trabajadors = Trabajador::findBySql('select * from tbl_trabajador where puesto IN
																(select ID_PUESTO from tbl_puesto_empresa WHERE ID_EMPRESA = :id_empresa_padre)
															  AND ID_EMPRESA = :id_empresa',[':id_empresa'=>$establecimiento->ID_EMPRESA, ':id_empresa_padre'=>$model->iDCOMISION->ID_EMPRESA])->all();
			         	}
			          ?>
			          <?php foreach ($trabajadors as $myTrabajador): ?>
				         	<tr>
				         		<td><?= $myTrabajador->ID_TRABAJADOR;?></td>
								<td><?= $myTrabajador->NOMBRE . ' '. $myTrabajador->APP . ' '.$myTrabajador->APM  ?></td>
								<td><?= $myTrabajador->RFC;?></td>
								<td><?= $myTrabajador->CORREO_ELECTRONICO;?></td>
								<td><?= isset( $myTrabajador->pUESTO)?$myTrabajador->pUESTO->NOMBRE_PUESTO : '<i>no asignado</i>' ;?></td>
							<!-- 	<td><?= count( $myTrabajador->constancias) ;?></td> -->
							</tr>

				      <?php endforeach;?>
		        	 </tbody>
			        </table>
			         <br />
			   <?php endforeach;?>





       		  </div>
	         </div>
	         </div>
	     </div>

  </div>





 <h4 class="page-header" id="anchor_curso">
     Información de los cursos que se brindaran a  los trabajadores.
   		<small>Capacitación que podrán recibir los trabajadores dentro de la empresa</small>
 </h4>





<div class="row">
	<div class="col-md-12 col-xs-12 col-sm-12">
		 <div class="box box-info" id="controls">

				<div class="box-header">
					 <i class="fa fa-laptop"></i>


								<div class="box-tools pull-right">
            <button title="ocultar/mostrar" data-toggle="tooltip" data-widget="collapse" class="btn btn-default btn-xs" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
            <button title="" data-toggle="tooltip" data-widget="remove" class="btn btn-default btn-xs" data-original-title="Remove"><i class="fa fa-times"></i></button>
          </div><!-- /.box-tools -->


			    	 <h3 class="box-title">

							<?= Yii::t('backend', 'Cursos') ?><small> que serán  impartidos  en  este plan</small>



					 </h3>
			   	</div>

				<div class="box-body table-responsive">

					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Id</th>
								<th>Nombre</th>
								<th>Fecha de inicio</th>
								<th>Fecha de fin</th>
								<th>Modalidad capacitación</th>
								<th>Instructor</th>
							    <th>Número de registro agente externo</th>
							    <th>Área temática</th>


							 </tr>
						</thead>

						<tbody>

								<?php foreach ($model->cursos as $curso):?>
								<tr>

									 <td><?= $curso->ID_CURSO ?></td>

									 <td><?= $curso->NOMBRE ?></td>

			                            <td><?=($curso->FECHA_INICIO === null)?'<i class="text-muted">no establecido</i>':date("d/m/Y",strtotime($curso->FECHA_INICIO)) ;?></td>


								     <td><?=($curso->FECHA_TERMINO === null)?'<i class="text-muted">no establecido</i>':date("d/m/Y",strtotime($curso->FECHA_TERMINO)) ;?></td>


									 <td><?php

										$modalidades = Curso::getModalidad();
						    			  echo  isset($modalidades[$curso->MODALIDAD_CAPACITACION])?$modalidades[$curso->MODALIDAD_CAPACITACION]:'no asignado';
									        ?>
									 </td>

									 <td>
										<?php if (isset($curso->iDINSTRUCTOR)){?>
											<?= $curso->iDINSTRUCTOR->NOMBRE ?>
										<?php }?>
									 </td>

					            	 <td>
										<?php if(isset($curso->iDINSTRUCTOR)){?>

										<?=$curso->iDINSTRUCTOR->NUM_REGISTRO_AGENTE_EXTERNO ?>

										<?php }?>
									 </td>

									   <td><?php
                                    $catalog = Catalogo::findOne(['ID_ELEMENTO'=>$curso->AREA_TEMATICA, 'CATEGORIA'=>6, 'ACTIVO'=>1]);
         			               echo isset($catalog)?$catalog->NOMBRE: 'no asignado'; ?></td>






									<td>
										<?= Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['constancias/create-sub','id'=>$curso->ID_CURSO],
				         			     ['class' => 'btn btn-info btn-xs'])?>
									</td>




							</tr>
							<?php endforeach;?>
						</tbody>
					</table>

				</div>

				<div class="box-footer">
					 <?= Html::a('<i class="fa fa-plus-square"></i> Crear nuevo curso', ['curso/createbyplan','id_plan'=>$model->ID_PLAN], ['class' => 'btn btn-success']) ?>


				</div>

			</div>
  </div>
</div>








<!-- Modal Implementations  -->

<div class="modal fade" id="create_report" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                    <?php $form = ActiveForm::begin(['action'=>['lista-plan/create-by-plan', 'id'=>$model->ID_PLAN]]); ?>
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="myModalLabel"><i class="fa fa-bookmark-o"></i>&nbsp;<?=Yii::t('backend', 'Crear nuevo reporte') ?></h4>
                                        </div>
	                                        <div class="modal-body">

													<?php $modelLista = new ListaPlan();?>

												    <?= $form->field($modelLista, 'ALIAS')->textInput(['maxlength' => 45]) ?>

													<?= $form->field($modelLista, 'DESCRIPCION')->textarea(['rows' => 5]) ?>

													<?= $form->field($modelLista, 'CONSTANCIAS_HOMBRES')->textInput(['maxlength' => 5]) ?>

													<?= $form->field($modelLista, 'CONSTANCIAS_MUJERES')->textInput(['maxlength' => 5]) ?>

												    <?= $form->field($modelLista, 'FECHA_ELABORACION')->textInput(['maxlength' => 45]) ?>

    										</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal"> <?= Yii::t('backend', 'Cerrar')?></button>
                                            <?= Html::submitButton( 'Crear', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

                                        </div>
                                      <?php ActiveForm::end(); ?>
                                    </div>
                                </div>
</div>




