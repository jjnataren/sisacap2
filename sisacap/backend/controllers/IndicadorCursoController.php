<?php

namespace backend\controllers;

use Yii;
use backend\models\IndicadorCurso;
use backend\models\search\IndicadorCursoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\EmpresaUsuario;
use yii\base\Object;
use yii\data\ActiveDataProvider;
use backend\models\Instructor;

/**
 * IndicadorCursoController implements the CRUD actions for IndicadorCurso model.
 */
class IndicadorCursoController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all IndicadorCurso models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new IndicadorCursoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single IndicadorCurso model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new IndicadorCurso model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new IndicadorCurso();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID_EVENTO]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing IndicadorCurso model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID_EVENTO]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing IndicadorCurso model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    
    

    /**
     * Deletes an existing IndicadorCurso model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteByInstructor($id)
    {
    	
    	$modelInstructor = Instructor::getOwnData();
    	
    	$model = $this->findModel($id);
    	
		if ($modelInstructor->ID_INSTRUCTOR ==! $model->iDCURSO->ID_INSTRUCTOR) {   	
			throw new NotFoundHttpException('The requested page does not exist.');
    
		}

		
		$model->delete();
		
			
    	return $this->redirect(['index-by-instructor']);
    	
    }
    
    
    /**
     * Deletes an existing IndicadorCurso model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteByCompany($id)
    {
    	 
    	$companyModel = EmpresaUsuario::getMyCompany();
    	 
    	$model = $this->findModel($id);
    	 
    	if ($companyModel->ID_EMPRESA ==! $model->iDCURSO->iDPLAN->ID_EMPRESA) {
    		throw new NotFoundHttpException('The requested page does not exist.');
    
    	}
    
    
    	$model->delete();
    
    		
    	return $this->redirect(['index-by-company']);
    	 
    }
    
    
    
    /**
     * Lists all IndicadorCurso models.
     * @return mixed
     */
    public function actionIndexByCompany()
    {
    
    	$companyModel = EmpresaUsuario::getMyCompany();
    	$searchModel = new IndicadorCursoSearch();
    	
    	
    	
    	$query = IndicadorCurso::findBySql('select * from tbl_indicador_curso where id_curso in 
                            		(select id_curso from tbl_curso where id_plan in 
                            			(select id_plan from tbl_plan where id_comision in 
                            				(select id_comision from tbl_comision_mixta_cap where id_empresa = '.$companyModel->ID_EMPRESA.' and ACTIVO=1) AND ACTIVO=1)) '
    			.' AND curdate() >= fecha_inicio_vigencia   AND curdate() <= fecha_fin_vigencia
    											 ');
    	
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    	
    
    	return $this->render('index_by_company', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	]);
    }
    
    
    
   
    /**
     * Lists all IndicadorCurso models.
     * @return mixed
     */
    public function actionIndexByInstructor()
    {
    
    	
    	$instructorModel = Instructor::getOwnData();
    	$companyModel = EmpresaUsuario::getMyCompany();
    	$searchModel = new IndicadorCursoSearch();
    	 
    	 
    	 
    	$query = IndicadorCurso::findBySql('select * from tbl_indicador_curso where id_curso in
                            		(select id_curso from tbl_curso where id_plan in
                            			(select id_plan from tbl_plan where id_comision in
                            				(select id_comision from tbl_comision_mixta_cap where id_empresa = '.$companyModel->ID_EMPRESA.' and ACTIVO=1) AND ACTIVO=1) AND id_instructor  = '.$instructorModel->ID_INSTRUCTOR.' ) '
    			.' AND (CLAVE = \'CUR0003\'  OR CLAVE = \'CUR0004\') AND  curdate() >= fecha_inicio_vigencia   AND curdate() <= fecha_fin_vigencia
    											 ');
    	 
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    	 
    
    	return $this->render('index_by_instructor', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	]);
    }
    
    
    
    /**
     * Displays a single IndicadorCurso model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewByInstructor($id)
    {
    
    	$companyModel = EmpresaUsuario::getMyCompany();
    
    	$instructorModel = Instructor::getOwnData();
    	
    	$model = $this->findModel($id);
    
    	if ($companyModel->ID_EMPRESA !== $model->iDCURSO->iDPLAN->iDCOMISION->ID_EMPRESA    ||   
    			$instructorModel->ID_INSTRUCTOR =! $model->iDCURSO->ID_INSTRUCTOR     ){

    			throw new NotFoundHttpException('The requested page does not exist.');
    	}
    

    	if ( !($model->CLAVE === 'CUR0003' || $model->CLAVE === 'CUR0004' )){
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    	
    	return $this->render('view_by_instructor', [
    			'model' => $model
    	]);
    }
    
    
    
    
    /**
     * Displays a single IndicadorCurso in roll Instructor model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewByUserInstructor($id)
    {
    
    	$companyModel = EmpresaUsuario::getMyCompany();
    	
    	
    	$model = $this->findModel($id);
    	
    	if ($companyModel->ID_EMPRESA !== $model->iDCURSO->iDPLAN->iDCOMISION->ID_EMPRESA ){
    	
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    	
    	return $this->render('view_by_user_instructor', [
    			'model' => $model
    			]);
    }
    
    
    
    /**
     * Displays a single IndicadorCurso model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewByCompany($id)
    {
    
    	$companyModel = EmpresaUsuario::getMyCompany();
    
    
    	$model = $this->findModel($id);
    
    	if ($companyModel->ID_EMPRESA !== $model->iDCURSO->iDPLAN->iDCOMISION->ID_EMPRESA ){
    
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    
    	return $this->render('view_by_company', [
    			'model' => $model
    	]);
    }

    /**
     * Finds the IndicadorCurso model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IndicadorCurso the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IndicadorCurso::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
