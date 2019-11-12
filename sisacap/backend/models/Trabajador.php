<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tbl_trabajador".
 *
 * @property integer $ID_TRABAJADOR
 * @property integer $ID_EMPRESA
 * @property integer $ROL
 * @property string $NOMBRE
 * @property string $APP
 * @property string $APM
 * @property string $CURP
 * @property string $RFC
 * @property string $NSS
 * @property string $NTCL
 * @property string $DOMICILIO
 * @property string $CORREO_ELECTRONICO
 * @property string $TELEFONO
 * @property integer $PUESTO
 * @property string $OCUPACION_ESPECIFICA
 * @property string $SEXO
 * @property string $FECHA_AGREGO
 * @property string $LUGAR_RESIDENCIA
 * @property integer $INSTITUCION_EDUCATIVA
 * @property integer $MUNICIPIO_DELEGACION
 * @property integer $ENTIDAD_FEDERATIVA
 * @property string $FECHA_EMISION_CERTIFICADO
 * @property integer $ACTIVO
 * @property integer $GRADO_ESTUDIO
 * @property integer $DOCUMENTO_PROBATORIO
 * @property string $OTRO_OCUPACION
 * @property string $SECTOR
 * @property string $SIGN_PIC
 * @property string $SIGN_PASSWD
 * @property string $SIGN_PIC_EXTENSION
 * @property string $SIGN_CREATED_AT
 * @property string $NUMERO_EXTERIOR
 * @property string $NUMERO_INTERIOR
 * @property string $COLONIA
 * @property string $CALLE
 * @property string $CODIGO_POSTAL
 *
 * @property ComisionMixtaCap[] $comisionMixtaCaps
 * @property Constancia[] $constancias
 * @property Empresa $iDEMPRESA
 * @property PuestoEmpresa $pUESTO
 * @property TrabajadorCurso[] $trabajadorCursos
 */
class Trabajador extends \yii\db\ActiveRecord
{
//itemsexo
	const SEX_HOMBRE=2;
	const SEX_MUJER=1;
	
	public $SEX_TIPO = [
	
			self::SEX_HOMBRE=>'HOMBRE',
			self::SEX_MUJER=>'MUJER',
	];
	//item grado
	
	const GRADO_NINGUNO=0;
	const GRADO_PRIMARIA=1;
	const GRADO_SECUNDARIA=2;
	const GRADO_BACHILLERATO=3;
	const GRADO_TECNICA=4;
	const GRADO_LIC=5;
	const GRADO_ESPECIALIDAD=6;
	const GRADO_MAESTRIA=7;
	const GRADO_DOC=8;
	
	public $GRADO_TIPO = [
	
			self::GRADO_NINGUNO=>'NINGUNO',
			self::GRADO_PRIMARIA=>'PRIMARIA',
			self::GRADO_SECUNDARIA=>'SECUNDARIA',
			self::GRADO_BACHILLERATO=>'BACHILLERATO',
			self::GRADO_TECNICA=>'CARRERA TECNICA',
			self::GRADO_LIC=>'LICENCIATURA',
			self::GRADO_ESPECIALIDAD=>'ESPECIALIDAD',
			self::GRADO_MAESTRIA=>'MAESTRIA',
			self::GRADO_DOC=>'DOCTORADO',
	
	];
	//item institucion
	const INST_PUBLICA=1;
	const INST_PRIVADA=2;
	
	public $INST_TIPO=[
	
			self::INST_PUBLICA=>'PUBLICA',
			self::INST_PRIVADA=>'PRIVADA',
	];
	//item documetos
	
	const DOC_TITULO=1;
	const DOC_CERTIFICADO=2;
	const DOC_DIPLOMA=3;
	const DOC_OTRO=4;
	
	public $DOC_TIPO = [
	
			self::DOC_TITULO => 'TITULO',
			self::DOC_CERTIFICADO => 'CERTIFICADO',
			self::DOC_DIPLOMA => 'DIPLOMA',
			self::DOC_OTRO => 'OTRO',
	];
	
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_trabajador';
    }

	 /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        		[['NOMBRE'],'required','message' =>'El nombre del trabajador es requerido'],
        		[[ 'RFC'],'required','message' =>'Este campo es requerido'],
        		 
            [['OCUPACION_ESPECIFICA','ID_EMPRESA', 'ROL', 'PUESTO', 'INSTITUCION_EDUCATIVA', 'MUNICIPIO_DELEGACION', 'ENTIDAD_FEDERATIVA', 'ACTIVO', 'GRADO_ESTUDIO', 'DOCUMENTO_PROBATORIO'], 'integer'],
            [['FECHA_AGREGO', 'FECHA_EMISION_CERTIFICADO'], 'safe'],
           // [['SECTOR'], 'required'],
            [['NOMBRE', 'APP', 'APM', 'TELEFONO','NUMERO_INTERIOR', 'NUMERO_EXTERIOR'], 'string', 'max' => 100],
            [['CURP'], 'string', 'max' => 18,'message'=>'Este curp no es valido'],
            [['RFC'], 'string', 'max' => 13],
            [['NSS'], 'string', 'max' => 20],
       		[['CODIGO_POSTAL'], 'string', 'max' => 6],
            [['NTCL', 'SECTOR', 'SIGN_PASSWD','COLONIA'], 'string', 'max' => 250],
            [['DOMICILIO', 'CORREO_ELECTRONICO','CALLE','COLONIA'], 'string', 'max' => 300],
            [['SEXO'], 'string', 'max' => 1],
            [['LUGAR_RESIDENCIA', 'OTRO_OCUPACION'], 'string', 'max' => 200],
           [['RFC'], 'unique','message' =>'Ya existe un trabajador con este RFC'],
            [['CORREO_ELECTRONICO'], 'email','message' =>'Formato invalido para este correo electronico'],
        		[['RFC'], 'match',
        		'pattern'=>'/^([A-Z&Ññ]{3}|[A-Z][AEIOU][A-Z]{2})\d{2}((01|03|05|07|08|10|12)(0[1-9]|[12]\d|3[01])|02(0[1-9]|[12]\d)|(04|06|09|11)(0[1-9]|[12]\d|30))([A-Z0-9]{2}[0-9A])?$/',
        		'message' =>'Este no parece ser un RFC valido'
        				],
          
        ];
    }

    
    public function scenarios()
    {
    	$scenarios = parent::scenarios();
    	$scenarios['load'] = ['RFC','NOMBRE','CORREO_ELECTRONICO'];
    	return $scenarios;
    }
    
    
      /**
     * @inheritdoc
     */
 public function attributeLabels()
    {
        return [
            'ID_TRABAJADOR' => 'Id  Trabajador',
            'ID_EMPRESA' => 'Id  Empresa',
            'ROL' => 'Rol',
            'NOMBRE' => 'Nombre (s)',
            'APP' => 'Apellido paterno',
            'APM' => 'Apellido materno',
            'CURP' => 'Clave única de registro de población (CURP) ',
            'RFC' => 'Registro federal de contribuyentes (RFC)',
            'NSS' => 'Número de seguridad social (NSS)',
            'DOMICILIO' => 'Domicilio',
            'CORREO_ELECTRONICO' => 'Correo  electrónico',
            'TELEFONO' => 'Teléfono(incluyendo LADA)',
            'PUESTO' => 'Puesto',
            'OCUPACION_ESPECIFICA' => 'Ocupación especifica',
            'SEXO' => 'Sexo',
            'FECHA_AGREGO' => 'Fecha de alta',
            'LUGAR_RESIDENCIA' => 'Lugar de residencia',
            'INSTITUCION_EDUCATIVA' => 'Institución  educativa',
            'MUNICIPIO_DELEGACION' => 'Municipio o delegación',
            'ENTIDAD_FEDERATIVA' => 'Entidad  federativa',
            'FECHA_EMISION_CERTIFICADO' => 'Fecha de emisión de certificado',
            'ACTIVO' => 'Activo',
            'GRADO_ESTUDIO' => 'Nivel máximo de estudios terminados',
            'DOCUMENTO_PROBATORIO' => 'Documento  probatorio',
            'OTRO_OCUPACION'=>'Otro',
            'NTCL'=>'Norma tecnica de competencia laboral',
            'SIGN_PIC' => 'Archivo con la firma digitalizada',
            'SIGN_PASSWD' => 'Contraseña de encriptación',
            'SIGN_PIC_EXTENSION' => '',
            'SIGN_CREATED_AT' => 'Sign  Created  At',
        		'CALLE' => 'Calle',
        		'NUMERO_EXTERIOR' => 'Número exterior',
        		'NUMERO_INTERIOR' => 'Número interior',
        		'CODIGO_POSTAL'=>'Codigo postal',
        		'COLONIA'=>'Colonia'
        		
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComisionMixtaCaps()
    {
        return $this->hasMany(ComisionMixtaCap::className(), ['ID_REPRESENTANTE_TRABAJADORES' => 'ID_TRABAJADOR']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConstancias()
    {
        return $this->hasMany(Constancia::className(), ['ID_TRABAJADOR' => 'ID_TRABAJADOR']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDEMPRESA()
    {
        return $this->hasOne(Empresa::className(), ['ID_EMPRESA' => 'ID_EMPRESA']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPUESTO()
    {
        return $this->hasOne(PuestoEmpresa::className(), ['ID_PUESTO' => 'PUESTO']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrabajadorCursos()
    {
        return $this->hasMany(TrabajadorCurso::className(), ['ID_TRABAJADOR' => 'ID_TRABAJADOR']);
    }
    /**
     * Gets  singning image binary base64
     */
    public function getSigningBinary(){
    
    	/*
    	 * into a reproducable iv/key pair
    	 */
    
    	$image64Data = null;
    
    
    	if($this->SIGN_PIC !== null){
    
    		try {
    
    
    			$passphrase  =  $this->SIGN_PASSWD;
    
    			$iv = substr(md5('iv'.$passphrase, true), 0, 8);
    			$key = substr(md5('pass1'.$passphrase, true) .
    					md5('pass2'.$passphrase, true), 0, 24);
    			$opts = array('iv'=>$iv, 'key'=>$key);
    
    			$fp = @fopen($this->SIGN_PIC, 'rb');
    
    			if (!$fp) {
    
    				return null;
    			}
    			stream_filter_append($fp, 'mdecrypt.tripledes', STREAM_FILTER_READ, $opts);
    			$data = rtrim(stream_get_contents($fp));
    			fclose($fp);
    
    			$image64Data =  $data;
    
    		} catch (Exception $e) {
    
    			return null;
    		}
    	}
    
    	return base64_encode($image64Data);
    
    }
}
