<?php


namespace backend\controllers;

use Yii;
use backend\models\Trabajador;
use backend\models\EmpresaUsuario;
use backend\models\search\TrabajadorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Empresa;
use backend\models\FileForm;
use yii\base\DynamicModel;
use yii\web\UploadedFile;
use backend\models\Constancia;
use backend\models\Indicadores;
use backend\models\Catalogo;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use backend\models\PuestoEmpresa;
use backend\models\ComisionMixtaCap;


/**
 * TrabajadorController implements the CRUD actions for Trabajador model.
 */
class TrabajadorController extends Controller
{


	public function beforeAction($action) {

	//	return false;
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
     * Lists all Trabajador models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TrabajadorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }



    /**
     * shows signing picture
     * @throws NotFoundHttpException
     * @return \yii\web\Response|string
     */
    public function actionViewSignPic($id, $id_comision){

    	$modelEmpresa = EmpresaUsuario::getMyCompany();

    	$trabajadorModel = $this->findModel($id);

    	$comisionModel = ComisionMixtaCap::findOne($id_comision);

    	if ($trabajadorModel->ID_EMPRESA !== $modelEmpresa->ID_EMPRESA   &&  $trabajadorModel->iDEMPRESA->ID_EMPRESA_PADRE !== $modelEmpresa->ID_EMPRESA  ){
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}

    	if ($comisionModel  === null   ||   $comisionModel->ID_EMPRESA !== $modelEmpresa->ID_EMPRESA  ){
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}



    	$image64Data = null;

    	$passwordoriginal  = $trabajadorModel->SIGN_PASSWD;

    	if ($trabajadorModel->load(Yii::$app->request->post())) {

    		$passphrase = md5($trabajadorModel->SIGN_PASSWD);

    		if($passwordoriginal  !==  $passphrase){


    			Yii::$app->session->setFlash('alert', [
    					'options'=>['class'=>'alert-warning'],
    					'body'=> '<i class="fa fa-exclamation-triangle fa-lg"></i> <b>La constraseña proporcionada no puede des encriptar la firma </b>',
    			]);

    		}else{


    			Yii::$app->session->setFlash('alert', [
    					'options'=>['class'=>'alert-success'],

    					'body'=> '<i class="fa fa-check"></i> Firma des encriptada correctamente.',
    			]);

    			/* Turn a human readable passphrase
    			 * into a reproducable iv/key pair
    			*/
    			$iv = substr(md5('iv'.$passphrase, true), 0, 8);
    			$key = substr(md5('pass1'.$passphrase, true) .
    					md5('pass2'.$passphrase, true), 0, 24);
    			$opts = array('iv'=>$iv, 'key'=>$key);

    			$fp = fopen($trabajadorModel->SIGN_PIC, 'r');
    			stream_filter_append($fp, 'mdecrypt.tripledes', STREAM_FILTER_READ, $opts);
    			$data = rtrim(stream_get_contents($fp));
    			fclose($fp);

    			$image64Data =  $data;

    		}

    	}

    	return $this->render('view_sign_pic',['model'=>$trabajadorModel, 'SIGN_IMAGE'=> base64_encode($image64Data),  'comisionModel'=>$comisionModel]);

    }

    /**
     * Manages signing picture
     * @throws NotFoundHttpException
     * @return \yii\web\Response|string
     */
    public function actionManageSignPic($id,$id_comision){

    	$model = EmpresaUsuario::getMyCompany();

    	$comisionModel = ComisionMixtaCap::findOne($id_comision);




    	$company= $model->iDEMPRESA;

    	$trabajadorModel = $this->findModel($id);


    	if ($trabajadorModel->ID_EMPRESA !== $model->ID_EMPRESA   &&  $trabajadorModel->iDEMPRESA->ID_EMPRESA_PADRE !== $model->ID_EMPRESA  ){
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}

    	if ($comisionModel  === null   ||   $comisionModel->ID_EMPRESA !== $model->ID_EMPRESA  ){
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}

    	$image64Data = null;

    	if ($trabajadorModel->load(Yii::$app->request->post())) {

    		$file = UploadedFile::getInstance($trabajadorModel,'SIGN_PIC');

    		if ($file === null){

    			Yii::$app->session->setFlash('alert', [
    					'options'=>['class'=>'alert-danger'],

    					'body'=> '<i class="fa fa-info"></i> Debe seleccionar un archivo con la firma digitalizada',
    			]);
    			return $this->render('manage_sign_pic',['model'=>$trabajadorModel, 'SIGN_IMAGE'=> base64_encode($image64Data),'comisionModel'=>$comisionModel]);

    		}


    		if ($trabajadorModel->SIGN_PASSWD === null || !strlen($trabajadorModel->SIGN_PASSWD)   ){

    		    Yii::$app->session->setFlash('alert', [
    		        'options'=>['class'=>'alert-danger'],

    		        'body'=> '<i class="fa fa-info"></i> Debe asignar una contraseña de encripción.',
    		    ]);
    		    return $this->render('manage-sign-pic',['model'=>$trabajadorModel, 'SIGN_IMAGE'=> base64_encode($image64Data)]);

    		}



    		$passphrase = md5($trabajadorModel->SIGN_PASSWD);



    		/* Turn a human readable passphrase
    		 * into a reproducable iv/key pair
    		*/
    		$iv = substr(md5('iv'.$passphrase, true), 0, 8);
    		$key = substr(md5('pass1'.$passphrase, true) .
    				md5('pass2'.$passphrase, true), 0, 24);
    		$opts = array('iv'=>$iv, 'key'=>$key);

    		$fp = fopen($file->tempName, "r");

    		$fileStream  = stream_get_contents($fp); //  fgets($fp,$file->size);

    		fclose($fp);

    		$fpw = fopen($file->tempName, "w");


    		stream_filter_append($fpw, 'mcrypt.tripledes', STREAM_FILTER_WRITE, $opts);

    		fwrite($fpw, $fileStream);

    		fclose($fpw);

    		$fileReturn = Yii::$app->fileStorage->save($file);

    		$trabajadorModel->SIGN_PIC = $fileReturn->url;
    		$trabajadorModel->SIGN_PASSWD = $passphrase;
    		$trabajadorModel->SIGN_PIC_EXTENSION = $file->extension;
    		$trabajadorModel->SIGN_CREATED_AT = date("Y-m-d H:i:s");


    		if($trabajadorModel->save() ) {

    			Yii::$app->session->setFlash('alert', [
    					'options'=>['class'=>'alert-success'],

    					'body'=> '<i class="fa fa-check"></i> Firma guardada y encriptada correctamente, Puede desencriptar la firma  proporcionando la constraseña nuevamente.',
    			]);

    			return $this->redirect(['view-sign-pic', 'id'=>$id,'id_comision'=>$id_comision]);
    		}


    	}

    	return $this->render('manage_sign_pic',['model'=>$trabajadorModel, 'SIGN_IMAGE'=> base64_encode($image64Data), 'comisionModel'=>$comisionModel,]);

    }



    /**
     * Gets all NTCL
     */
    public function actionGetNormas() {
    	$out = [];

    	if (isset($_POST['depdrop_parents'])) {
    		$parents = $_POST['depdrop_parents'];
    		if ($parents != null) {
    			$cat_id = $parents[0];
    			//$out = self::getSubCatList($cat_id);

    			//$catalogo = Catalogo::findOne($cat_id);

    			//$out=ArrayHelper::map($catalogo->catalogos, 'ID_ELEMENTO', 'NOMBRE');


    			$dataListNTCL=ArrayHelper::map(Catalogo::findBySql('select tcc.ID_ELEMENTO, CONCAT(tcc.CLAVE,\' - \' , tcc.NOMBRE) AS NOMBRE, (select NOMBRE FROM tbl_cat_catalogo where tcc.ELEMENTO_PADRE = ID_ELEMENTO) PADRE
						from tbl_cat_catalogo tcc where categoria=7 AND tcc.ELEMENTO_PADRE IN (select id_elemento from tbl_cat_catalogo where elemento_padre = :id_sector AND categoria = 8)
 						',[':id_sector'=>$cat_id])->all(), 'ID_ELEMENTO', 'NOMBRE','PADRE');

    			$items  = null;
    			$i= 0;
    			foreach ($dataListNTCL as $key=>$item){



    				$subItems = null;

    				foreach ($item as $sub_key=>$sub_item){

    					$subItems[] =  ['id'=>$sub_key, 'name'=>$sub_item];

    				}

    				$items[$key] =  $subItems;

    				$i++;

    			}

    			// the getSubCatList function will query the database based on the
    			// cat_id and return an array like below:
    			// [
    			// ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
    			// ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
    			// ]
    			echo Json::encode(['output'=>$items, 'selected'=>'']);
    			return;
    		}
    	}
    	echo Json::encode(['output'=>'', 'selected'=>'']);
    }




    /**
     * Lists all Trabajador models by company.
     * @return mixed
     */
    public function actionIndexcompany(){


    	$model = EmpresaUsuario::getMyCompany();


    	$companyModel = $model->iDEMPRESA;



    	if ($companyModel === null || $model === null)  throw new NotFoundHttpException('The requested page does not exist.');

    	$searchModel = new TrabajadorSearch();
    	$dataProvider = $searchModel->searchByCompany(Yii::$app->request->queryParams,$companyModel->ID_EMPRESA);

    	return $this->render('index_company', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'id_company'=>$companyModel->ID_EMPRESA
    			]);
    }




    /**
     * Lists all Trabajadors. this action will be performed by a submanager
     * @return mixed
     */
    public function actionIndexBySub(){


    	$model = EmpresaUsuario::getMyEstablishment();


    	$companyModel = $model->iDEMPRESA;

    	$searchModel = new TrabajadorSearch();


    	$dataProvider = $searchModel->searchByCompany(Yii::$app->request->queryParams,$model->ID_EMPRESA);

    	return $this->render('index_by_submanager', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'id_company'=>$model->ID_EMPRESA
    			]);
    }



    /**
     *
     * @param int $id_company
     * @return Ambigous <string, string>
     */
    public function actionLoadBySub(){

    	$model = EmpresaUsuario::getMyEstablishment();

    	$companyModel = $model->iDEMPRESA;

    	$fileModel = new FileForm();

    	$i= 0;


    	if($companyModel->iDEMPRESAPADRE->getTotalWorkers() >= $companyModel->iDEMPRESAPADRE->NUMERO_TRABAJADORES ){

    		Yii::$app->session->setFlash('alert', [
    				'options'=>['class'=>'alert-danger'],
    				'body'=> '<i class="fa fa-times fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Ha excedido el número total de trabajadores [ '.$companyModel->iDEMPRESAPADRE->NUMERO_TRABAJADORES.' ] . Contacte al administrador si desea aumentar el numero de trabajadores permitido <a href=\'#\' class=\'alert-link\'></a>',
    		]);

    		return $this->redirect(['index-by-sub']);

    	}


    	$workers = array();

    	if (Yii::$app->request->isPost) {


    		$fileModel->file  = UploadedFile::getInstance($fileModel, 'file');

    		if ($fileModel->file && $fileModel->validate()) {

    			try {

    				$data = \moonland\phpexcel\Excel::import($fileModel->file->tempName, [
    						'isMultipleSheet'=>false,
    						'setFirstRecordAsKeys' => true, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
    						'setIndexSheetByName' => false, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric.
    						'getOnlySheet' => 'trabajadores', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
    				]);



    				foreach ($data as $fila){

    					//foreach ($fila as $keyColumna=>$valueColumna){

    					$worker = new Trabajador();
    					$worker->scenario = 'load';



    					$worker->NOMBRE = $fila['NOMBRE'];
    					$worker->APP = $fila['APELLIDO PATERNO'];
    					$worker->APM = $fila['APELLIDO MATERNO'];
    					$worker->CURP = $fila['CURP'];
    					$worker->RFC = $fila['RFC'];
    					$worker->NSS = $fila['NSS'];

    					$worker->CORREO_ELECTRONICO = $fila['CORREO ELECTRONICO'];
    					$worker->TELEFONO = $fila['TELEFONO'];

    					$tmp_puesto = PuestoEmpresa::findOne(['CLAVE_PUESTO'=> trim($fila['PUESTO']), 'ID_EMPRESA'=>$model->ID_EMPRESA ]);
    					$worker->PUESTO = isset($tmp_puesto) ? $tmp_puesto->ID_PUESTO: null ;


    					$claveOc = explode('-', $fila['OCUPACION ESPECIFICA']) ;


    					if($claveOc[0]){

    						$tmp_ocupacion = Catalogo::findOne(['CLAVE'=>trim($claveOc[0]),  'CATEGORIA'=>Catalogo::CATEGORIA_OCUPACION]);
    						$worker->OCUPACION_ESPECIFICA = isset($tmp_ocupacion)?$tmp_ocupacion->ID_ELEMENTO : null;

    					}



    					$claveSe = explode('-', $fila['SEXO']);

    					$worker->SEXO =  isset ($claveSe[0])?trim($claveSe[0]) : null;


    					$claveEnMu = explode('/', $fila['ENTIDAD FEDERATIVA / MUNICIPIO']);

    					if(count($claveEnMu) > 1 ){

    						$claveMun  = explode('-',$claveEnMu[1] );
    						$claveEnt  = explode('-',$claveEnMu[0] );

    						//	list($claveEnt,$valor) = split('[-]',$claveEn );

    						if ($claveEnt[0]){
    							$tmp_federativa = Catalogo::findOne(['CLAVE'=>trim($claveEnt[0]),  'CATEGORIA'=>Catalogo::CATEGORIA_ENTIDADES_FEDERATIVAS]);
    							$worker->ENTIDAD_FEDERATIVA = isset($tmp_federativa)?$tmp_federativa->ID_ELEMENTO : null;
    						}

    						if ($claveMun[0]){
    							$tmp_municipio = Catalogo::findOne(['CLAVE'=>trim($claveMun[0]),  'CATEGORIA'=>Catalogo::CATEGORIA_MUNICIPIOS, 'ELEMENTO_PADRE'=>$worker->ENTIDAD_FEDERATIVA]);
    							$worker->MUNICIPIO_DELEGACION =  isset($tmp_municipio)?$tmp_municipio->ID_ELEMENTO : null;
    						}
    					}

    					$worker->CALLE = $fila['CALLE'];
    					$worker->NUMERO_INTERIOR = $fila['NUMERO INTERIOR'];
    					$worker->NUMERO_EXTERIOR = $fila['NUMERO EXTERIOR'];
    					$worker->COLONIA = $fila['COLONIA'];
    					$worker->CODIGO_POSTAL = $fila['CODIGO POSTAL'];


    					$claveGrado = explode('-', $fila['GRADO DE ESTUDIO']) ;

    					$worker->GRADO_ESTUDIO =  isset ($claveGrado[0])?trim($claveGrado[0]) : null;




    					$claveInst = explode('-', $fila['INSTITUCION EDUCATIVA']);

    					$worker->INSTITUCION_EDUCATIVA =  isset ($claveInst[0])?trim($claveInst[0]) : null;



    					$claveDoc = explode('-' ,  $fila['COMPROBANTE ACADEMICO']);

    					$worker->DOCUMENTO_PROBATORIO =  isset ($claveDoc[0])?trim($claveDoc[0]) : null;

    					$worker->ACTIVO = 1;

    					if ( !$worker->validate()  && false){


    						Yii::$app->session->setFlash('alert', [
    								'options'=>['class'=>'alert-danger'],
    								'body'=> '<i class="fa fa-exclamation-triangle fa-lg"></i> <a href=\'#\' class=\'alert-link\'> Existen errores en sus registros por favor revise los campos<a href=\'#\' class=\'alert-link\'></a>',
    						]);



    					}

    					$workers[] = $worker;

    				}



    				return $this->render('load_by_submanager', [
    						'model' => $companyModel,
    						'fileModel'=>$fileModel,
    						'workers'=>$workers
    				]);


    			}catch (\Exception $e){

    				Yii::$app->session->setFlash('alert', [
    						'options'=>['class'=>'alert-danger'],
    						'body'=> '<i class="fa fa-exclamation-triangle fa-lg"></i> <a href=\'#\' class=\'alert-link\'> El formato del documento es incorrecto ['.$e->getMessage().']<a href=\'#\' class=\'alert-link\'></a>',
    				]);



    			}


    		}


    	}

    	return $this->render('load_by_submanager', [
    			'model' => $companyModel,
    			'fileModel'=>$fileModel
    	]);

    }



    /**
     * Save all workers from excel file. This action will be performed by a submanager
     * @param int $id
     */
    public function actionSaveAllBySub(){


    	if( !Yii::$app->request->isPost) throw new NotFoundHttpException('The requested page does not exist.');


    	$model = EmpresaUsuario::getMyEstablishment();

    	$companyModel = $model->iDEMPRESA;

    	/**
    	@todo Validate that company belongs to specific user
    	*/


    	if ($companyModel === null)  throw new NotFoundHttpException('The requested page does not exist.');

    	$fileModel = new FileForm();

    	$i= 0;

    	$workersPost = Yii::$app->request->post('Trabajador');
    	$workers = [];

    	foreach ($workersPost as $workerPost){

    		$workers[] = new Trabajador();
    	}



    	if (Trabajador::loadMultiple($workers, Yii::$app->request->post(), null) && Trabajador::validateMultiple($workers)) {


    		if($companyModel->iDEMPRESAPADRE->getTotalWorkers() + count($workers)  > $companyModel->iDEMPRESAPADRE->NUMERO_TRABAJADORES ){

    			Yii::$app->session->setFlash('alert', [
    					'options'=>['class'=>'alert-danger'],
    					'body'=> '<i class="fa fa-times fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Ha excedido el número total de trabajadores [ '.$model->iDEMPRESA->NUMERO_TRABAJADORES.' ] . Contacte al administrador si desea aumentar el numero de trabajadores permitido <a href=\'#\' class=\'alert-link\'></a>',
    			]);

    			return $this->redirect(['index-by-sub',]); // redirect to your next desired page

    		}


    		$count = 0;

    		$connection = Yii::$app->db;

    		$transaction =   $connection->beginTransaction();

    		foreach ($workers as $worker) {
    			// populate and save records for each model

    			$worker->ACTIVO = 1;
    			$worker->ID_EMPRESA = $companyModel->ID_EMPRESA;



    			if ($worker->save()) {
    				// do something here after saving
    				$count++;

    			}else{

    				$transaction->rollback();
    				return $this->render('load_by_establishment', [
    						'model' => $companyModel,
    						'fileModel'=>$fileModel,
    						'workers'=>$workers

    				]);

    			}
    		}

    		$transaction->commit();


    		//Yii::$app->session->setFlash('success', "Processed {$count} records successfully.");

    		Yii::$app->session->setFlash('alert', [
    				'options'=>['class'=>'alert-success'],
    				'body'=>Yii::t('frontend', "{$count} Trabajadores guardados correctamente")
    				]);


    		return $this->redirect(['index-by-sub',]); // redirect to your next desired page


    	}

    	return $this->render('load_by_establishment', [
    			'model' => $companyModel,
    			'fileModel'=>$fileModel,
    			'workers'=>$workers

    	]);



    }


    /**
     * Deletes a particular trabajador. This action will be performed by a submanager
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return \yii\web\Response
     */
    public function actionDeleteBySub($id)
    {

    	$companyModel = EmpresaUsuario::getMyEstablishment();
    	$model = $this->findModel($id);

    	if ($model->ID_EMPRESA !== $companyModel->ID_EMPRESA)
    		throw new NotFoundHttpException('The requested page does not exist.');


    	$model->ACTIVO=0;

    	if ($model->delete()){

    		Yii::$app->session->setFlash('alert', [
    				'options'=>['class'=>'alert-success'],
    				'body'=> '<i class="fa fa-check fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Se ha eliminado  el trabajador correctamente</a>',
    		]);

    	}else{

    		Yii::$app->session->setFlash('alert', [
    				'options'=>['class'=>'alert-warning'],
    				'body'=> '<i class="fa fa-exclamation-triangle fa-lg"></i> <a href=\'#\' class=\'alert-link\'>No se ha podido eliminar el trabajador <a href=\'#\' class=\'alert-link\'></a>',
    		]);
    	}
    	return $this->redirect(['index-by-sub']);
    }


    /**
     * Creates a new Trabajador model. This action will be performed by a submanager
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateBySub()
    {


    	$trabajadorModel = new Trabajador();

    	$model = EmpresaUsuario::getMyEstablishment();

    	$companyModel = $model->iDEMPRESA;

    	if($companyModel->iDEMPRESAPADRE->getTotalWorkers() >= $companyModel->iDEMPRESAPADRE->NUMERO_TRABAJADORES ){

    		Yii::$app->session->setFlash('alert', [
    				'options'=>['class'=>'alert-danger'],
    				'body'=> '<i class="fa fa-times fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Ha excedido el número total de trabajadores [ '
    				.$companyModel->iDEMPRESAPADRE->NUMERO_TRABAJADORES .' ] .
    				Contacte al administrador si desea aumentar el numero de trabajadores permitido <a href=\'#\' class=\'alert-link\'></a>',
    		]);

    		return $this->redirect(['index-by-sub']);

    	}

    	$trabajadorModel->ID_EMPRESA = $model->ID_EMPRESA;

    	$trabajadorModel->ACTIVO = 1;

    	$trabajadorModel->FECHA_AGREGO = date("Y-m-d H:i:s");


    	if ($trabajadorModel->load(Yii::$app->request->post())) {

    		$tmpdate = \DateTime::createFromFormat('d/m/Y', $trabajadorModel->FECHA_EMISION_CERTIFICADO);

    		$trabajadorModel->FECHA_EMISION_CERTIFICADO = ($tmpdate === false)? null: $tmpdate->format('Y-m-d') ;

    		if( $trabajadorModel->save()) {


    			Yii::$app->session->setFlash('alert', [
    					'options'=>['class'=>'alert-success'],
    					'body'=> '<i class="fa fa-check fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Se ha creado el trabajador correctamente</a>',
    			]);


    			return $this->redirect(['view-by-sub', 'id' => $trabajadorModel->ID_TRABAJADOR]);

    		}else{


    			Yii::$app->session->setFlash('alert', [
    					'options'=>['class'=>'alert-danger'],
    					'body'=> '<i class="fa fa-exclamation-triangle fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Ha ocurrido un error, por favor revise los campos </a>',
    			]);


    		}


    	}
    	return $this->render('create_by_submanager', [
    			'model' => $trabajadorModel,
    	]);

    }

    /**
     * Updates an existing Trabajador model. this action will be performed by a submanager
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateBySub($id)
    {

    	$model = $this->findModel($id);

    	$companyModel = EmpresaUsuario::getMyEstablishment();

    	if ($model === null ||  $model->ID_EMPRESA !== $companyModel->ID_EMPRESA) {

    		throw new NotFoundHttpException('The requested page does not exist.');

    	}

    	if ($model->load(Yii::$app->request->post())){
    		$tmpdate = \DateTime::createFromFormat('d/m/Y', $model->FECHA_EMISION_CERTIFICADO);
    		$model->FECHA_EMISION_CERTIFICADO = ($tmpdate === false)? null : $tmpdate->format('Y-m-d') ;


    		if($model->save()) {
    			Yii::$app->session->setFlash('alert', [
    					'options'=>['class'=>'alert-success'],

    					'body'=> '<i class="fa fa-check"></i> Trabajador actualizado correctamente.',
    			]);
    			return $this->redirect(['view-by-sub', 'id' => $model->ID_TRABAJADOR]);
    		}else{

    			Yii::$app->session->setFlash('alert', [
    					'options'=>['class'=>'alert-warning'],
    					'body'=> '<i class="fa fa-exclamation-triangle fa-lg"></i> <a href=\'#\' class=\'alert-link\'>
    					No se ha podido guardar el trabajador, revise los campos <a href=\'#\' class=\'alert-link\'></a>',
    			]);

    		}
    	}
    	return $this->render('update_by_submanager', [

    			'model' => $model,
    	]);

    }


   /**
    * Shows all  associated workers by  an instructor
    * @throws NotFoundHttpException
    * @return string
    */
    public function actionIndexcompanyinstructor(){

    	$model = EmpresaUsuario::getMyCompany();

    	$companyModel = $model->iDEMPRESA;

    	if ($companyModel === null || $model === null)  throw new NotFoundHttpException('The requested page does not exist.');

    	$searchModel = new TrabajadorSearch();
    	$dataProvider = $searchModel->searchByCompany(Yii::$app->request->queryParams,$companyModel->ID_EMPRESA);

    	return $this->render('trab_instructor', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'id_company'=>$companyModel->ID_EMPRESA
    			]);
    }







    /**
     * Lists all Trabajador models by company.
     * @return mixed
     */
    public function actionIndexestablishment($id_establishment){


    	$model = EmpresaUsuario::findOne(['ID_USUARIO'=>Yii::$app->user->id, 'ACTIVO'=>1 ]);

    	$companyModel = $model->iDEMPRESA;

    	$establishmentModel = Empresa::findOne(['ID_EMPRESA'=>$id_establishment, 'ID_EMPRESA_PADRE'=>$companyModel->ID_EMPRESA ?: -1]);


    	if ($companyModel === null || $model === null || $establishmentModel===null)  throw new NotFoundHttpException('The requested page does not exist.');


    	$searchModel = new TrabajadorSearch();
    	$dataProvider = $searchModel->searchByCompany(Yii::$app->request->queryParams,$id_establishment);

    	return $this->render('index_establishment', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'id_company'=>$id_establishment
    			]);
    }


    /**
     * Lists all Trabajador todos los tranbajadores.
     * @return mixed
     */
    public function actionIndexallworkers(){


    	$model = EmpresaUsuario::getMyCompany();


    	$companyModel = $model->iDEMPRESA;



    	if ($companyModel === null || $model === null)  throw new NotFoundHttpException('The requested page does not exist.');

    	$searchModel = new TrabajadorSearch();
    	$dataProvider = $searchModel->searchRepresentanteTrabajadores(Yii::$app->request->queryParams,$companyModel->ID_EMPRESA);

    	return $this->render('index_all_workers', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'id_company'=>$companyModel->ID_EMPRESA
    			]);
    }

    /**
     *Load workers througt  a  file
     * @param int $id_company
     * @return Ambigous <string, string>
     */
    public function actionLoadbyestablishment($id){

    	$model = EmpresaUsuario::findOne(['ID_USUARIO'=>Yii::$app->user->id, 'ACTIVO'=>1 ]);

    	if ($model === null) throw new NotFoundHttpException('The requested page does not exist.');


    	$companyModel = Empresa::findOne(['ID_EMPRESA_PADRE'=> $model->ID_EMPRESA, 'ID_EMPRESA'=>$id]);

    	if ($companyModel === null)  throw new NotFoundHttpException('The requested page does not exist.');

    	$fileModel = new FileForm();

    	$i= 0;

    	$workers = array();

    	if (Yii::$app->request->isPost) {


    		$fileModel->file  = UploadedFile::getInstance($fileModel, 'file');

    		if ($fileModel->file && $fileModel->validate()) {

    			try {

    			$data = \moonland\phpexcel\Excel::import($fileModel->file->tempName, [
    					'isMultipleSheet'=>false,
    					'setFirstRecordAsKeys' => true, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
    					'setIndexSheetByName' => false, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric.
    					'getOnlySheet' => 'trabajadores', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
    			]);



    			foreach ($data as $fila){

    				//foreach ($fila as $keyColumna=>$valueColumna){

    					$worker = new Trabajador();
						$worker->scenario = 'load';



    						$worker->NOMBRE = $fila['NOMBRE'];
    						$worker->APP = $fila['APELLIDO PATERNO'];
    						$worker->APM = $fila['APELLIDO MATERNO'];
    						$worker->CURP = $fila['CURP'];
    						$worker->RFC = $fila['RFC'];
    						$worker->NSS = $fila['NSS'];

    						$worker->CORREO_ELECTRONICO = $fila['CORREO ELECTRONICO'];
    						$worker->TELEFONO = $fila['TELEFONO'];

    						$tmp_puesto = PuestoEmpresa::findOne(['CLAVE_PUESTO'=> trim($fila['PUESTO']), 'ID_EMPRESA'=>$model->ID_EMPRESA ]);
    						$worker->PUESTO = isset($tmp_puesto) ? $tmp_puesto->ID_PUESTO: null ;


    						$claveOc = explode('-', $fila['OCUPACION ESPECIFICA']) ;


    						if($claveOc[0]){

    							$tmp_ocupacion = Catalogo::findOne(['CLAVE'=>trim($claveOc[0]),  'CATEGORIA'=>Catalogo::CATEGORIA_OCUPACION]);
	    						$worker->OCUPACION_ESPECIFICA = isset($tmp_ocupacion)?$tmp_ocupacion->ID_ELEMENTO : null;

    						}



    						$claveSe = explode('-', $fila['SEXO']);

    						$worker->SEXO =  isset ($claveSe[0])?trim($claveSe[0]) : null;


    						$claveEnMu = explode('/', $fila['ENTIDAD FEDERATIVA / MUNICIPIO']);

    						if(count($claveEnMu) > 1 ){

		    						$claveMun  = explode('-',$claveEnMu[1] );
		    						$claveEnt  = explode('-',$claveEnMu[0] );

		    					//	list($claveEnt,$valor) = split('[-]',$claveEn );

		    						if ($claveEnt[0]){
			    						 $tmp_federativa = Catalogo::findOne(['CLAVE'=>trim($claveEnt[0]),  'CATEGORIA'=>Catalogo::CATEGORIA_ENTIDADES_FEDERATIVAS]);
			    						 $worker->ENTIDAD_FEDERATIVA = isset($tmp_federativa)?$tmp_federativa->ID_ELEMENTO : null;
		    						}

		    						if ($claveMun[0]){
			    						$tmp_municipio = Catalogo::findOne(['CLAVE'=>trim($claveMun[0]),  'CATEGORIA'=>Catalogo::CATEGORIA_MUNICIPIOS, 'ELEMENTO_PADRE'=>$worker->ENTIDAD_FEDERATIVA]);
			    						$worker->MUNICIPIO_DELEGACION =  isset($tmp_municipio)?$tmp_municipio->ID_ELEMENTO : null;
		    						}
	    					}

    						$worker->CALLE = $fila['CALLE'];
    						$worker->NUMERO_INTERIOR = $fila['NUMERO INTERIOR'];
    						$worker->NUMERO_EXTERIOR = $fila['NUMERO EXTERIOR'];
    						$worker->COLONIA = $fila['COLONIA'];
    						$worker->CODIGO_POSTAL = $fila['CODIGO POSTAL'];


    						$claveGrado = explode('-', $fila['GRADO DE ESTUDIO']) ;

    						$worker->GRADO_ESTUDIO =  isset ($claveGrado[0])?trim($claveGrado[0]) : null;




    						$claveInst = explode('-', $fila['INSTITUCION EDUCATIVA']);

    						$worker->INSTITUCION_EDUCATIVA =  isset ($claveInst[0])?trim($claveInst[0]) : null;



    						$claveDoc = explode('-' ,  $fila['COMPROBANTE ACADEMICO']);

    						$worker->DOCUMENTO_PROBATORIO =  isset ($claveDoc[0])?trim($claveDoc[0]) : null;

    						$worker->ACTIVO = 1;

    						if ( !$worker->validate()  && false){


    							Yii::$app->session->setFlash('alert', [
    									'options'=>['class'=>'alert-danger'],
    									'body'=> '<i class="fa fa-exclamation-triangle fa-lg"></i> <a href=\'#\' class=\'alert-link\'> Existen errores en sus registros por favor revise los campos<a href=\'#\' class=\'alert-link\'></a>',
    							]);



    						}

    						$workers[] = $worker;

    			}



    				return $this->render('load_by_establishment', [
    						'model' => $companyModel,
    						'fileModel'=>$fileModel,
    						'workers'=>$workers
    						]);


    				}catch (\Exception $e){

    					Yii::$app->session->setFlash('alert', [
    							'options'=>['class'=>'alert-danger'],
    							'body'=> '<i class="fa fa-exclamation-triangle fa-lg"></i> <a href=\'#\' class=\'alert-link\'> El formato del documento es incorrecto ['.$e->getMessage().']<a href=\'#\' class=\'alert-link\'></a>',
    					]);



    				}


    			}


    	}


    		return $this->render('load_by_establishment', [
    				'model' => $companyModel,
    				'fileModel'=>$fileModel
    				]);





    }


    /**
     *
     * @param int $id_company
     * @return Ambigous <string, string>
     */
    public function actionLoad(){


    	$model = EmpresaUsuario::getMyCompany();

    	if($model === null ) throw new NotFoundHttpException('The requested page does not exist.');

    	$companyModel = $model->iDEMPRESA;

       	$fileModel = new FileForm();

       	$i= 0;


       	if($model->iDEMPRESA->getTotalWorkers() >= $model->iDEMPRESA->NUMERO_TRABAJADORES ){

       		Yii::$app->session->setFlash('alert', [
       				'options'=>['class'=>'alert-danger'],
       				'body'=> '<i class="fa fa-times fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Ha excedido el número total de trabajadores [ '.$model->iDEMPRESA->NUMERO_TRABAJADORES.' ] . Contacte al administrador si desea aumentar el numero de trabajadores permitido <a href=\'#\' class=\'alert-link\'></a>',
       		]);

       		return $this->redirect(['indexcompany']);

       	}

    	$workers = array();

    	if (Yii::$app->request->isPost) {


    		try {


    		$fileModel->file  = UploadedFile::getInstance($fileModel, 'file');

    		if ($fileModel->file && $fileModel->validate()) {


    			$data = \moonland\phpexcel\Excel::import($fileModel->file->tempName, [
    					'isMultipleSheet'=>false,
    					'setFirstRecordAsKeys' => true, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
    					'setIndexSheetByName' => false, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric.
    					'getOnlySheet' => 'trabajadores', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
    			]);



    			foreach ($data as $fila){

    				//foreach ($fila as $keyColumna=>$valueColumna){

    					$worker = new Trabajador();
						$worker->scenario = 'load';


    						$worker->NOMBRE = $fila['NOMBRE'];
    						$worker->APP = $fila['APELLIDO PATERNO'];
    						$worker->APM = $fila['APELLIDO MATERNO'];
    						$worker->CURP = $fila['CURP'];
    						$worker->RFC = $fila['RFC'];
    						$worker->NSS = $fila['NSS'];

    						$worker->CORREO_ELECTRONICO = $fila['CORREO ELECTRONICO'];
    						$worker->TELEFONO = $fila['TELEFONO'];

    						$tmp_puesto = PuestoEmpresa::findOne(['CLAVE_PUESTO'=> $fila['PUESTO'], 'ID_EMPRESA'=>$model->ID_EMPRESA ]);
    						$worker->PUESTO = isset($tmp_puesto) ? $tmp_puesto->ID_PUESTO: null ;


    						$claveOc = explode('-', $fila['OCUPACION ESPECIFICA']) ;


    						if($claveOc[0]){

    							$tmp_ocupacion = Catalogo::findOne(['CLAVE'=>trim($claveOc[0]),  'CATEGORIA'=>Catalogo::CATEGORIA_OCUPACION]);
	    						$worker->OCUPACION_ESPECIFICA = isset($tmp_ocupacion)?$tmp_ocupacion->ID_ELEMENTO : null;

    						}



    						$claveSe = explode('-', $fila['SEXO']);

    						$worker->SEXO =  isset ($claveSe[0])?trim($claveSe[0]) : null;


    						$claveEnMu = explode('/', $fila['ENTIDAD FEDERATIVA / MUNICIPIO']);

    						if(count($claveEnMu) > 1 ){

		    						$claveMun  = explode('-',$claveEnMu[1] );
		    						$claveEnt  = explode('-',$claveEnMu[0] );

		    					//	list($claveEnt,$valor) = split('[-]',$claveEn );

		    						if ($claveEnt[0]){
			    						 $tmp_federativa = Catalogo::findOne(['CLAVE'=>trim($claveEnt[0]),  'CATEGORIA'=>Catalogo::CATEGORIA_ENTIDADES_FEDERATIVAS]);
			    						 $worker->ENTIDAD_FEDERATIVA = isset($tmp_federativa)?$tmp_federativa->ID_ELEMENTO : null;
		    						}

		    						if ($claveMun[0]){
			    						$tmp_municipio = Catalogo::findOne(['CLAVE'=>trim($claveMun[0]),  'CATEGORIA'=>Catalogo::CATEGORIA_MUNICIPIOS, 'ELEMENTO_PADRE'=>$worker->ENTIDAD_FEDERATIVA]);
			    						$worker->MUNICIPIO_DELEGACION =  isset($tmp_municipio)?$tmp_municipio->ID_ELEMENTO : null;
		    						}
	    					}

    						$worker->CALLE = $fila['CALLE'];
    						$worker->NUMERO_INTERIOR = $fila['NUMERO INTERIOR'];
    						$worker->NUMERO_EXTERIOR = $fila['NUMERO EXTERIOR'];
    						$worker->COLONIA = $fila['COLONIA'];
    						$worker->CODIGO_POSTAL = $fila['CODIGO POSTAL'];


    						$claveGrado = explode('-', $fila['GRADO DE ESTUDIO']) ;

    						$worker->GRADO_ESTUDIO =  isset ($claveGrado[0])?trim($claveGrado[0]) : null;




    						$claveInst = explode('-', $fila['INSTITUCION EDUCATIVA']);

    						$worker->INSTITUCION_EDUCATIVA =  isset ($claveInst[0])?trim($claveInst[0]) : null;



    						$claveDoc = explode('-' ,  $fila['COMPROBANTE ACADEMICO']);

    						$worker->DOCUMENTO_PROBATORIO =  isset ($claveDoc[0])?trim($claveDoc[0]) : null;

    						$worker->ACTIVO = 1;

    						if ( !$worker->validate()  && false){


    							Yii::$app->session->setFlash('alert', [
    									'options'=>['class'=>'alert-danger'],
    									'body'=> '<i class="fa fa-exclamation-triangle fa-lg"></i> <a href=\'#\' class=\'alert-link\'> Existen errores en sus registros por favor revise los campos<a href=\'#\' class=\'alert-link\'></a>',
    							]);



    						}

    						$workers[] = $worker;





    			}


    				return $this->render('load', [
    						'model' => $companyModel,
    						'fileModel'=>$fileModel,
    						'workers'=>$workers
    						]);

    			}

    			}catch (\Exception $e){

    				Yii::$app->session->setFlash('alert', [
    						'options'=>['class'=>'alert-danger'],
    						'body'=> '<i class="fa fa-exclamation-triangle fa-lg"></i> <a href=\'#\' class=\'alert-link\'> El formato del documento es incorrecto ['.$e->getMessage().']<a href=\'#\' class=\'alert-link\'></a>',
    				]);



    			}
    		}

    		return $this->render('load', [
    			'model' => $companyModel,
    			'fileModel'=>$fileModel
    			]);

    }



    /**
     * Save all workers by  company id
     * @param int $id
     */
    public function actionSaveallbyestablishment($id){


    	if( !Yii::$app->request->isPost) throw new NotFoundHttpException('The requested page does not exist.');


    	$model = EmpresaUsuario::getMyCompany();

    	if($model === null ) throw new NotFoundHttpException('The requested page does not exist.');

    	$companyModel = Empresa::findOne(['ID_EMPRESA_PADRE'=>$model->ID_EMPRESA, 'ID_EMPRESA'=>$id]);


    	/**
    	@todo Validate that company belongs to specific user
    	*/


    	if ($companyModel === null)  throw new NotFoundHttpException('The requested page does not exist.');

    	$fileModel = new FileForm();

    	$i= 0;

    	$workersPost = Yii::$app->request->post('Trabajador');
    	$workers = [];

    	foreach ($workersPost as $workerPost){

    		$workers[] = new Trabajador();
    	}



    	if (Trabajador::loadMultiple($workers, Yii::$app->request->post(), null) && Trabajador::validateMultiple($workers)) {


    		if($model->iDEMPRESA->getTotalWorkers() + count($workers)  > $model->iDEMPRESA->NUMERO_TRABAJADORES ){

    			Yii::$app->session->setFlash('alert', [
    					'options'=>['class'=>'alert-danger'],
    					'body'=> '<i class="fa fa-times fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Ha excedido el número total de trabajadores [ '.$model->iDEMPRESA->NUMERO_TRABAJADORES.' ] . Contacte al administrador si desea aumentar el numero de trabajadores permitido <a href=\'#\' class=\'alert-link\'></a>',
    			]);

    				return $this->redirect(['indexestablishment','id_establishment'=>$id]); // redirect to your next desired page

    		}


    		$count = 0;

    		$connection = Yii::$app->db;

    		$transaction =   $connection->beginTransaction();

    		foreach ($workers as $worker) {
    			// populate and save records for each model

    			$worker->ACTIVO = 1;
    			$worker->ID_EMPRESA = $companyModel->ID_EMPRESA;



    			if ($worker->save()) {
    				// do something here after saving
    				$count++;

    			}else{

    				$transaction->rollback();
    				return $this->render('load_by_establishment', [
    						'model' => $companyModel,
    						'fileModel'=>$fileModel,
    						'workers'=>$workers

    				]);

    			}
    		}

    		$transaction->commit();


    		//Yii::$app->session->setFlash('success', "Processed {$count} records successfully.");

    		Yii::$app->session->setFlash('alert', [
    		'options'=>['class'=>'alert-success'],
    		'body'=>Yii::t('frontend', "{$count} Trabajadores guardados correctamente")
    		]);


    		return $this->redirect(['indexestablishment','id_establishment'=>$id]); // redirect to your next desired page


    	}

    		return $this->render('load_by_establishment', [
    			'model' => $companyModel,
    			'fileModel'=>$fileModel,
    			'workers'=>$workers

    			]);



    }




    /**
     * Save all workers by  company id
     * @param unknown $id_company
     */
    public function actionSaveall(){


    	if( !Yii::$app->request->isPost) throw new NotFoundHttpException('The requested page does not exist.');


    	$model = EmpresaUsuario::getMyCompany();

    	if($model === null ) throw new NotFoundHttpException('The requested page does not exist.');




    	/**
		@todo Validate that company belongs to userid
    	 */
    	$companyModel = $model->iDEMPRESA;

    	if ($companyModel === null)  throw new NotFoundHttpException('The requested page does not exist.');

    	$fileModel = new FileForm();

    	$i= 0;

    	$workersPost = Yii::$app->request->post('Trabajador');
    	$workers = [];

    	foreach ($workersPost as $workerPost){

    		$workers[] = new Trabajador();
    	}



    	if (Trabajador::loadMultiple($workers, Yii::$app->request->post(), null) && Trabajador::validateMultiple($workers)) {



    		if($model->iDEMPRESA->getTotalWorkers() + count($workers)  > $model->iDEMPRESA->NUMERO_TRABAJADORES ){

    			Yii::$app->session->setFlash('alert', [
    					'options'=>['class'=>'alert-danger'],
    					'body'=> '<i class="fa fa-times fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Ha excedido el número total de trabajadores [ '.$model->iDEMPRESA->NUMERO_TRABAJADORES.' ] . Contacte al administrador si desea aumentar el numero de trabajadores permitido <a href=\'#\' class=\'alert-link\'></a>',
    			]);

    			return $this->redirect(['indexcompany']); // redirect to your next desired page

    		}


    		$count = 0;

    		$connection = Yii::$app->db;

    		$transaction =   $connection->beginTransaction();

    		foreach ($workers as $worker) {
    			// populate and save records for each model

    			$worker->ACTIVO = 1;
    			$worker->ID_EMPRESA = $companyModel->ID_EMPRESA;



    			if ($worker->save()) {
    				// do something here after saving
    				$count++;

    			}else{

    				$transaction->rollback();
    				return $this->render('load', [
    						'model' => $companyModel,
    						'fileModel'=>$fileModel,
    						'workers'=>$workers

    				]);

    			}

    		}

    		$transaction->commit();


    		//Yii::$app->session->setFlash('success', "Processed {$count} records successfully.");

    		Yii::$app->session->setFlash('alert', [
    		'options'=>['class'=>'alert-success'],
    		'body'=>Yii::t('frontend', "{$count} Trabajadores han sigo guardados correctamente")
    		]);


    		return $this->redirect(['indexcompany']); // redirect to your next desired page


    	}


    	return $this->render('load', [
            'model' => $companyModel,
    		'fileModel'=>$fileModel,
    		'workers'=>$workers

        ]);



    }


    /**
     * Displays a single Trabajador model.
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
     * Displays a single Trabajador model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewbycompany($id){

  		$model = EmpresaUsuario::getMyCompany();

    	$trabajadorModel = $this->findModel($id);

    	if (!($model->ID_EMPRESA === $trabajadorModel->ID_EMPRESA))
    		throw new NotFoundHttpException('The requested page does not exist.');

    	return $this->render('view_by_company', [
    			'model'=>$trabajadorModel		]);
    }


    /**
     * Displays a single Trabajador model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewbyall($id){

    	$model = EmpresaUsuario::getMyCompany();

    	$trabajadorModel = $this->findModel($id);

    	if (!($model->ID_EMPRESA === $trabajadorModel->ID_EMPRESA || $model->ID_EMPRESA == $trabajadorModel->iDEMPRESA->ID_EMPRESA_PADRE ))
    		throw new NotFoundHttpException('The requested page does not exist.');

    	return $this->render('view_by_all', [
    			'model'=>$trabajadorModel		]);
    }


    /**
     * Shows information of a particular trabajador
     * @param unknown $id
     * @throws NotFoundHttpException
     */
    public function actionViewbyinstructor($id){

    	$model = EmpresaUsuario::getMyCompany();

    	$trabajadorModel = $this->findModel($id);

    	if (!($model->ID_EMPRESA === $trabajadorModel->ID_EMPRESA))
    		throw new NotFoundHttpException('The requested page does not exist.');

    	return $this->render('view_by_instructor', [
    			'model'=>$trabajadorModel		]);
    }

    /**
     * Shows information of a particular trabajador. this action will be performed by a submanager
     * @param int $id
     * @throws NotFoundHttpException
     */
    public function actionViewBySub($id){

    	$model = EmpresaUsuario::getMyEstablishment();

    	$trabajadorModel = $this->findModel($id);

    	if ($model->ID_EMPRESA !== $trabajadorModel->ID_EMPRESA)
    		throw new NotFoundHttpException('The requested page does not exist.');

    	return $this->render('view_by_submanager', [
    			'model'=>$trabajadorModel		]);
    }



    /**
     * Displays a single Trabajador model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewbystablishment($id){

    	$model = EmpresaUsuario::getMyCompany();

    	$trabajadorModel = $this->findModel($id);

    	if (($model->ID_EMPRESA !== $trabajadorModel->iDEMPRESA->ID_EMPRESA_PADRE))
    		throw new NotFoundHttpException('The requested page does not exist.');

    	return $this->render('view_by_stablishment', [
    			'model'=>$trabajadorModel		]);
    }




    /**
     * Creates a new Trabajador model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateworkerbycompany()
    {


    	$trabajadorModel = new Trabajador();

    	$model = EmpresaUsuario::getMyCompany();

    	$companyModel = $model->iDEMPRESA;
    	$trabajadorModel->ID_EMPRESA = $model->ID_EMPRESA;

    	if($model === null || $companyModel === null) throw new NotFoundHttpException('The requested page does not exist.');


    	if($companyModel->getTotalWorkers() >= $companyModel->NUMERO_TRABAJADORES ){

    		Yii::$app->session->setFlash('alert', [
    		'options'=>['class'=>'alert-danger'],
    		'body'=> '<i class="fa fa-times fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Ha excedido el número total de trabajadores [ '.$companyModel->NUMERO_TRABAJADORES.' ] . Contacte al administrador si desea aumentar el numero de trabajadores permitido <a href=\'#\' class=\'alert-link\'></a>',
    		]);

    		return $this->redirect(['indexcompany']);

    	}


    	$trabajadorModel->ACTIVO = 1;


    	if ($trabajadorModel->load(Yii::$app->request->post())){
    		$tmpdate = \DateTime::createFromFormat('d/m/Y', $trabajadorModel->FECHA_EMISION_CERTIFICADO);

    		$trabajadorModel->FECHA_EMISION_CERTIFICADO = ($tmpdate=== false)? null : $tmpdate->format('Y-m-d') ;

    		$trabajadorModel->FECHA_AGREGO = date('Y-m-d H:i');

    	 if ($trabajadorModel->save()) {

    	 	Yii::$app->session->setFlash('alert', [
    	 			'options'=>['class'=>'alert-success'],
    	 			'body'=> '<i class="fa fa-check fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Trabajador guardado correctamente</a>',
    	 	]);


    		return $this->redirect(['viewbycompany', 'id' => $trabajadorModel->ID_TRABAJADOR]);
    	}else{


    		Yii::$app->session->setFlash('alert', [
    				'options'=>['class'=>'alert-danger'],
    				'body'=> '<i class="fa fa-exclamation-triangle fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Ha ocurrido un error, por favor revise los campos<a href=\'#\' class=\'alert-link\'></a>',
    		]);

    		return $this->render('create_by_company', [
    				'model' => $trabajadorModel,
    		]);


    	}



    	}

    	else {



    		return $this->render('create_by_company', [
    				'model' => $trabajadorModel,
    				]);
    	}
    }










    /**
     * Creates a new Trabajador model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateworkerbyestablishment($id)
    {


    	$trabajadorModel = new Trabajador();

    	$model = EmpresaUsuario::getMyCompany();

    	if($model === null) throw new NotFoundHttpException('The requested page does not exist.');

    	$companyModel = Empresa::findOne(['ID_EMPRESA_PADRE'=>$model->ID_EMPRESA, 'ID_EMPRESA'=>$id ]);

    	if( $companyModel === null) throw new NotFoundHttpException('The requested page does not exist.');


    	if($model->iDEMPRESA->getTotalWorkers() >= $model->iDEMPRESA->NUMERO_TRABAJADORES ){

    		Yii::$app->session->setFlash('alert', [
    				'options'=>['class'=>'alert-danger'],
    				'body'=> '<i class="fa fa-times fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Ha excedido el número total de trabajadores [ '.$model->iDEMPRESA->NUMERO_TRABAJADORES .' ] . Contacte al administrador si desea aumentar el numero de trabajadores permitido <a href=\'#\' class=\'alert-link\'></a>',
    		]);

    		return $this->redirect(['indexcompany']);

    	}



    	$trabajadorModel->ID_EMPRESA = $id;

    	$trabajadorModel->ACTIVO = 1;

    	$trabajadorModel->FECHA_AGREGO = date("Y-m-d H:i:s");


    	if ($trabajadorModel->load(Yii::$app->request->post())) {
    		$tmpdate = \DateTime::createFromFormat('d/m/Y', $trabajadorModel->FECHA_EMISION_CERTIFICADO);

    		$trabajadorModel->FECHA_EMISION_CERTIFICADO = ($tmpdate === false)? null: $tmpdate->format('Y-m-d') ;

    	if( $trabajadorModel->save()) {


    		Yii::$app->session->setFlash('alert', [
    				'options'=>['class'=>'alert-success'],
    				'body'=> '<i class="fa fa-check fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Se ha creado el trabajador correctamente</a>',
    		]);


    		return $this->redirect(['viewbystablishment', 'id' => $trabajadorModel->ID_TRABAJADOR]);

    	}else{


    		Yii::$app->session->setFlash('alert', [
    				'options'=>['class'=>'alert-danger'],
    				'body'=> '<i class="fa fa-exclamation-triangle fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Ha ocurrido un error, por favor revise los campos </a>',
    		]);


    	}


    	}
    		return $this->render('create_by_company', [
    				'model' => $trabajadorModel,
    				]);

    }



    /**
     * Creates a new Trabajador model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreatefromconst($id_course,$id_est)
    {


    	$trabajadorModel = new Trabajador();

    	$model = EmpresaUsuario::getMyCompany();

    	$companyModel = Empresa::findOne(['ID_EMPRESA_PADRE'=>$model->ID_EMPRESA, 'ID_EMPRESA'=>$id_est ]);

    	if( $companyModel === null) throw new NotFoundHttpException('The requested page does not exist.');


    	$trabajadorModel->ID_EMPRESA = $id_est;

    	$trabajadorModel->ACTIVO = 1;

    	$trabajadorModel->FECHA_AGREGO = date("Y-m-d H:i:s");


    	if ($trabajadorModel->load(Yii::$app->request->post()) &&  $trabajadorModel->validate()) {

    	 if($model->iDEMPRESA->getTotalWorkers() >= $model->iDEMPRESA->NUMERO_TRABAJADORES ){

    		Yii::$app->session->setFlash('alert', [
    				'options'=>['class'=>'alert-danger'],
    				'body'=> '<i class="fa fa-times fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Ha excedido el número total de trabajadores [ '.$model->iDEMPRESA->NUMERO_TRABAJADORES .' ] . Contacte al administrador si desea aumentar el numero de trabajadores permitido <a href=\'#\' class=\'alert-link\'></a>',
    		]);

    		return $this->redirect(['constancias/createbycourse', 'id' =>$id_course,'id_est'=>$id_est]);

    	}

    		$trabajadorModel->save();

    		$constanciaModel = new Constancia();

    		$constanciaModel->ID_CURSO = $id_course;

    		$constanciaModel->ACTIVO = 1;

    		$constanciaModel->ID_TRABAJADOR = $trabajadorModel->ID_TRABAJADOR;

    		$constanciaModel->ESTATUS = Constancia::STATUS_ALREADY;

    		$constanciaModel->FECHA_CREACION = date("Y-m-d H:i:s");

    			if ($constanciaModel->save() ){

    			Yii::$app->session->setFlash('alert', [
    			'options'=>['class'=>'alert-success'],
    			'body'=> '<i class="fa fa-check fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Se ha creado  la constancia correctamente <a href=\'#\' class=\'alert-link\'></a>',
    			]);

    			Indicadores::setIndicadorConstancia($constanciaModel);

    		}

    		return $this->redirect(['constancias/createbycourse', 'id' =>$id_course,'id_est'=>$id_est]);


    	} else {

    		Yii::$app->session->setFlash('alert', [
    		'options'=>['class'=>'alert-warning'],
    		'body'=> '<i class="fa fa-exclamation-triangle fa-lg"></i> <a href=\'#\' class=\'alert-link\'>
					No fue posible crear el trabajador, revise los errores '.json_encode($trabajadorModel->getErrors()).'
    				<a href=\'#\' class=\'alert-link\'></a>',
        				]);


    		return $this->redirect(['constancias/createbycourse', 'id' =>$id_course,'id_est'=>$id_est]);
    	}
    }



    /**
     * Creates a new Trabajador model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateFromConstCompany($id_course)
    {


    	$trabajadorModel = new Trabajador();

    	$model = EmpresaUsuario::getMyCompany();

    	/*$totalTrabajadores = count(Trabajador::findBySql('select * from tbl_trabajador where id_empresa in
    													(select id_empresa from tbl_empresa where id_empresa_padre = :empresa_padre OR ID_EMPRESA= :empresa_padre) ',[':empresa_padre'=>$model->ID_EMPRESA])->all());*/



    	$trabajadorModel->ID_EMPRESA = $model->ID_EMPRESA;

    	$trabajadorModel->ACTIVO = 1;

    	$trabajadorModel->FECHA_AGREGO = date("Y-m-d H:i:s");


    	if ($trabajadorModel->load(Yii::$app->request->post()) && $trabajadorModel->validate() ) {



    		if($model->iDEMPRESA->getTotalWorkers() >= $model->iDEMPRESA->NUMERO_TRABAJADORES ){

    			Yii::$app->session->setFlash('alert', [
    					'options'=>['class'=>'alert-danger'],
    					'body'=> '<i class="fa fa-times fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Ha excedido el número total de trabajadores [ '.$model->iDEMPRESA->NUMERO_TRABAJADORES .' ] . Contacte al administrador si desea aumentar el numero de trabajadores permitido <a href=\'#\' class=\'alert-link\'></a>',
    			]);

    			return $this->redirect(['constancias/createbycourse', 'id' =>$id_course]);

    		}

    		Yii::$app->session->setFlash('alert', [
    		'options'=>['class'=>'alert-success'],
    		'body'=> '<i class="fa fa-exclamation-triangle fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Se ha creado el trabajador correctamente <a href=\'#\' class=\'alert-link\'></a>',
    		]);

    		$trabajadorModel->save();//modificar esto para realizarlo en multi transaccion

    		$constanciaModel = new Constancia();

    		$constanciaModel->ID_CURSO = $id_course;

    		$constanciaModel->ACTIVO = 1;

    		$constanciaModel->ID_TRABAJADOR = $trabajadorModel->ID_TRABAJADOR;

    		$constanciaModel->ESTATUS = Constancia::STATUS_ALREADY;

    		$constanciaModel->FECHA_CREACION = date("Y-m-d H:i:s");

    		if ($constanciaModel->save() ){

    			Yii::$app->session->setFlash('alert', [
    			'options'=>['class'=>'alert-success'],
    			'body'=> '<i class="fa fa-check fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Se ha creado  la constancia correctamente <a href=\'#\' class=\'alert-link\'></a>',
    			]);

    			Indicadores::setIndicadorConstancia($constanciaModel);
    		}


    		return $this->redirect(['constancias/createbycourse', 'id' =>$id_course]);

    	} else {

    		Yii::$app->session->setFlash('alert', [
    		'options'=>['class'=>'alert-warning'],
    		'body'=> '<i class="fa fa-exclamation-triangle fa-lg"></i> <a href=\'#\' class=\'alert-link\'>
					No fue posible crear el trabajador, revise los errores '.json_encode($trabajadorModel->getErrors()).'
    				<a href=\'#\' class=\'alert-link\'></a>',
        				]);


    		return $this->redirect(['constancias/createbycourse', 'id' =>$id_course]);
    	}
    }


    /**
     * Creates a new Trabajador model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Trabajador();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID_TRABAJADOR]);
        } else {
            return $this->render('create_by_company', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Trabajador model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['viewbycompany', 'id' => $model->ID_TRABAJADOR]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Empresa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdatebyuser($id)
    {
    	$model = $this->findModel($id);
    	$companyModel = EmpresaUsuario::getMyCompany();



    	if ($model->load(Yii::$app->request->post())) {

    		$tmpdate = \DateTime::createFromFormat('d/m/Y', $model->FECHA_EMISION_CERTIFICADO);
    		$model->FECHA_EMISION_CERTIFICADO = ($tmpdate === false)? null : $tmpdate->format('Y-m-d') ;



    	if ( $model->save()) {
    		Yii::$app->session->setFlash('alert', [
    		'options'=>['class'=>'alert-success'],

    		'body'=> '<i class="fa fa-check fa-lg"></i> Trabajador actualizado correctamente.',
    		]);
    		return $this->redirect(['viewbycompany', 'id' => $model->ID_TRABAJADOR]);
    	}
    }
    		return $this->render('update_by_user', [
    				'model' => $model,
    				]);

    }


    /**
     * Updates an existing Empresa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdatebystablish($id)
    {

    	$model = $this->findModel($id);
    	$companyModel = EmpresaUsuario::getMyCompany();

    	//$trabajadorModel->ID_EMPRESA = $model->ID_EMPRESA;

    	if ($model->load(Yii::$app->request->post())){
    		$tmpdate = \DateTime::createFromFormat('d/m/Y', $model->FECHA_EMISION_CERTIFICADO);
    		$model->FECHA_EMISION_CERTIFICADO = ($tmpdate === false)? null : $tmpdate->format('Y-m-d') ;


    		if($model->save()) {
    			Yii::$app->session->setFlash('alert', [
    			'options'=>['class'=>'alert-success'],

    			'body'=> '<i class="fa fa-check"></i> Trabajador actualizado correctamente.',
    			]);
    		return $this->redirect(['viewbystablishment', 'id' => $model->ID_TRABAJADOR]);
    	}
    }
    		return $this->render('update_stablisment', [
    				'model' => $model,
    				]);

    }






    /**
     * Updates an existing Empresa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdatebyall($id)
    {
    	$model = $this->findModel($id);
    	$companyModel = EmpresaUsuario::getMyCompany();



    	if ($model->load(Yii::$app->request->post())) {

    		$tmpdate = \DateTime::createFromFormat('d/m/Y', $model->FECHA_EMISION_CERTIFICADO);
    		$model->FECHA_EMISION_CERTIFICADO = ($tmpdate === false)? null : $tmpdate->format('Y-m-d') ;



    		if ( $model->save()) {
    			Yii::$app->session->setFlash('alert', [
    			'options'=>['class'=>'alert-success'],

    			'body'=> '<i class="fa fa-check fa-lg"></i> Trabajador actualizado correctamente.',
    			]);
    			return $this->redirect(['viewbyall', 'id' => $model->ID_TRABAJADOR]);
    		}
    	}
    	return $this->render('update_all', [
    			'model' => $model,
    			]);

    }
    /**
     * Deletes an existing Trabajador model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['indexcompany']);
    }

    public function actionDeletebyuser($id)
    {
    	$model = $this->findModel($id);
    	$companyModel = EmpresaUsuario::getMyCompany();

    	if ($model->ID_EMPRESA !== $companyModel->ID_EMPRESA)
    		throw new NotFoundHttpException('The requested page does not exist.');


    	$model->ACTIVO=0;

    	if ($model->delete()){

    	 	Yii::$app->session->setFlash('alert', [
    	 			'options'=>['class'=>'alert-success'],
    	 			'body'=> '<i class="fa fa-check fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Se ha eliminado  el trabajador correctamente</a>',
    	 	]);

    	}else{

    			Yii::$app->session->setFlash('alert', [
    		'options'=>['class'=>'alert-warning'],
    		'body'=> '<i class="fa fa-exclamation-triangle fa-lg"></i> <a href=\'#\' class=\'alert-link\'>No se ha podido eliminar el trabajador <a href=\'#\' class=\'alert-link\'></a>',
    		]);

    	}
    	return $this->redirect(['indexcompany']);
    }


    public function actionDeletebyall($id)
    {
    	$model = $this->findModel($id);
    	$companyModel = EmpresaUsuario::getMyCompany();

    	if (!($model->ID_EMPRESA === $companyModel->ID_EMPRESA || $model->iDEMPRESA->ID_EMPRESA_PADRE === $companyModel->ID_EMPRESA))

    		//($model->ID_EMPRESA === $trabajadorModel->ID_EMPRESA || $model->ID_EMPRESA == $trabajadorModel->iDEMPRESA->ID_EMPRESA_PADRE )
    		throw new NotFoundHttpException('The requested page does not exist.');


    	$model->ACTIVO=0;


    	if ($model->delete()){

    		Yii::$app->session->setFlash('alert', [
    		'options'=>['class'=>'alert-success'],
    		'body'=> '<i class="fa fa-check fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Se ha eliminado  el trabajador correctamente</a>',
    		]);

    	}else{

    		Yii::$app->session->setFlash('alert', [
    		'options'=>['class'=>'alert-warning'],
    		'body'=> '<i class="fa fa-exclamation-triangle fa-lg"></i> <a href=\'#\' class=\'alert-link\'>No se ha podido eliminar el trabajador <a href=\'#\' class=\'alert-link\'></a>',
    		]);

    	}
    	return $this->redirect(['indexallworkers']);
    }

     /**
     * Deletes a particular trabajador.
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return \yii\web\Response
     */
    public function actionDeletebystablish($id)
    {

    	$companyModel = EmpresaUsuario::getMyCompany();
    	$model = $this->findModel($id);

    	if ($model->iDEMPRESA->ID_EMPRESA_PADRE !== $companyModel->ID_EMPRESA)
    		throw new NotFoundHttpException('The requested page does not exist.');


    	$model->ACTIVO=0;
    	$establishment = $model->ID_EMPRESA;

    	if ($model->delete()){

    			Yii::$app->session->setFlash('alert', [
    	 			'options'=>['class'=>'alert-success'],
    	 			'body'=> '<i class="fa fa-check fa-lg"></i> <a href=\'#\' class=\'alert-link\'>Se ha eliminado  el trabajador correctamente</a>',
    	 	]);
    	}else{

    			Yii::$app->session->setFlash('alert', [
    		'options'=>['class'=>'alert-warning'],
    		'body'=> '<i class="fa fa-exclamation-triangle fa-lg"></i> <a href=\'#\' class=\'alert-link\'>No se ha podido eliminar el trabajador <a href=\'#\' class=\'alert-link\'></a>',
    		]);
    	}
    	return $this->redirect(['indexestablishment','id_establishment'=>$establishment]);
    }







    /**
     * Finds the Trabajador model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Trabajador the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Trabajador::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
