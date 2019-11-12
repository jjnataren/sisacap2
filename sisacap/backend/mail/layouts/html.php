<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message bing composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
	<div>
		<h1>Notificación de SISACAP</h1>
	</div>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>
    
    

   <hr /> 
    
    <div>
    	<p>
    	Ayuda<br />
    	
    	Si tiene alguna duda y/o comentario acerca de este correo, puede  contactarse con nosotros en:
    	
   <address>
  <strong><?= Yii::$app->keyStorage->get('com.sisacap.empresa.contacto.nombre', '');?></strong><br />
  <?= Yii::$app->keyStorage->get('com.sisacap.empresa.contacto.direccion', '');?>
  <abbr title="Phone">Tel:</abbr> <?= Yii::$app->keyStorage->get('com.sisacap.empresa.contacto.telefono', '');?>
</address>

<address>
  <strong>Correo Electronico</strong><br />
  <a href="mailto:#"><?= Yii::$app->keyStorage->get('com.sisacap.empresa.contacto.correo', '');?></a>
</address>
    	
  </p>
    
    </div>
    
    <div>
    	<small>
    		
    		SISACAP<br/>
			Este correo electronico es de uso confidencial y puede contener información privada.
			Si usted no es su destinatario y/o no reconoce la operación, le pedimos porfavor responda este correo informandonolos  y enseguida elimine el correo.
				
		</small>
    
    </div>
</body>
</html>
<?php $this->endPage() ?>
