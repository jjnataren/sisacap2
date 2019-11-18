<?php

namespace backend\controllers;

use Yii;
use common\models\KeyStorageItem;
use backend\models\search\KeyStorageItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KeyStorageController implements the CRUD actions for KeyStorageItem model.
 */
class KeyStorageController extends Controller
{


	public function beforeAction($action) {
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}

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
     * Lists all KeyStorageItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KeyStorageItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = [
            'defaultOrder'=>['key'=>SORT_DESC]
        ];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new KeyStorageItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KeyStorageItem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new KeyStorageItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionMail()
    {



        $username = KeyStorageItem::findOne(['key'=>'com.sisacap.mail.username']);

        $password = KeyStorageItem::findOne(['key'=>'com.sisacap.mail.password']);

        $host = KeyStorageItem::findOne(['key'=>'com.sisacap.mail.host']);

        $port = KeyStorageItem::findOne(['key'=>'com.sisacap.mail.port']);

        $encryption = KeyStorageItem::findOne(['key'=>'com.sisacap.mail.encryption']);



        if ( Yii::$app->request->post() ) {

            $keys = Yii::$app->request->post('KeyStorageItem');

            $username->value = $keys[0]['value'];
            $password->value = $keys[1]['value'];
            $host->value = $keys[2]['value'];
            $port->value = $keys[3]['value'];
            $encryption->value = $keys[4]['value'];

            $username->save();
            $password->save();
            $host->save();
            $port->save();
            $encryption->save();



            Yii::$app->session->setFlash('alert', [
                'options'=>['class'=>'alert-success'],

                'body'=> '<i class="fa fa-check"></i> Configuración guardada correctamente.',
            ]);


            return $this->redirect(['/']);
        } else {
            return $this->render('create_mail', [
                'username' => $username,
                'password' => $password,
                'host' => $host,
                'port' => $port,
                'encryption' => $encryption,
            ]);
        }
    }


    /**
     * Updates an existing KeyStorageItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing KeyStorageItem model.
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
     * Finds the KeyStorageItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KeyStorageItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KeyStorageItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
