<?php


use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Instructor;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\InstructorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ver instructores';
$this->params['titleIcon'] = '<span class="fa-stack fa-lg">
  								<i class="fa fa-square-o fa-stack-2x"></i>
  								<i class="fa fa-graduation-cap -lg  fa-stack-1x"></i>
							   </span>';

$this->params['breadcrumbs'][] = $this->title;
?>
  
<div class=" col-xs-12 col-sm-12 col-md-12">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3><i class="fa fa-eye"></i>
						<?= Yii::t('backend', 'Detalles') ?> <small>de instructores</small> </h3>
					</div>
					<div class="panel-body">
<div class="instructor-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],
		 	
           'ID_INSTRUCTOR',
			//'ID_EMPRESA',
			
            'NOMBRE',
    		'APP',
			'APM',
			//'TELEFONO',
			
    		
    		[
    		'attribute'=>'TIPO_INSTRUCTOR',
    		
	    		'content'=>function ($data){

	    			$tipoInstructores = Instructor::getTypeInstructor();
	    			
	    			$tipoInstructor = isset($tipoInstructores[$data->TIPO_INSTRUCTOR]) ? $tipoInstructores[$data->TIPO_INSTRUCTOR] : 'sin asignar';
	    		
	    			return $tipoInstructor;
	    		},

	    	'filter'=>Instructor::getTypeInstructor(),
	    		
			],
    		
    		
    		'NOMBRE_AGENTE_EXTERNO',
    		 
            ['class' => 'yii\grid\ActionColumn',
			
    		'template' => ' {view}   {update}   {delete}', // Template de los botones. Aqui se indica que botones apareceran y el orden en el que apareceran
			
    		'buttons' => [ // Aqui se indica  que icono tendran los botones y/o texto si asi se desea
				
    			'update' => function ($url, $model, $id) { //Boton actualizar
					return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Actualizar')]);
				},
				
				'view' => function ($url, $model, $id) {//Boton Ver
					return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'Ver')]);
				},
				
				'delete' => function ($url, $model, $id) {//Boton borrar
					return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => Yii::t('app', 'Eliminar'), 'data' => ['confirm' => '¿Realmente quiere borrar este instructor? ¡Los datos del instructor no son recuperables¡','method' => 'post',]]);
				},
				
				],
								
				/*En esta seccion se definen las acciones que tendran los botones*/
				'urlCreator' => function ($action, $model, $key, $index) {
					if ($action === 'update') {
						return Yii::$app->urlManager->createUrl(['/instructor/updatebycompany', 'id' => $key]); // Aqui es donde se crean las urls con las acciones personalizadas
						
					}
					
					if ($action === 'view') {
						return Yii::$app->urlManager->createUrl(['/instructor/viewbycompany', 'id' => $key]); // Aqui es donde se crean las urls con las acciones personalizadas
					
					}
					
					if ($action === 'delete') {
						return Yii::$app->urlManager->createUrl(['/instructor/deletebycompany', 'id' => $key]); // Aqui es donde se crean las urls con las acciones personalizadas
								
					}
					
					
				}			
			],
        ],
    ]); ?>

</div>
</div>
</div>
</div>
