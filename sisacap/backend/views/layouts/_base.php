<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this \yii\web\View */
/* @var $content string */

\backend\assets\BackendAsset::register($this);

$this->params['body-class'] = array_key_exists('body-class', $this->params) ?
    $this->params['body-class']
    : null;

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

<!--    <link href="css/morris/morris.css" rel="stylesheet" type="text/css" />
    <link href="css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <link href="css/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />
    <link href="css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />-->


</head>
<body class="<?= ArrayHelper::getValue($this->params, 'body-class') ?> 
<?php if ( Yii::$app->user->can('administrator') ){
	echo Yii::$app->keyStorage->get('backend.theme-skin', 'skin-blue'); 
}elseif (Yii::$app->user->can('manager') ){
	echo Yii::$app->keyStorage->get('backend.theme-skin-manager', 'skin-blue');	
}elseif (Yii::$app->user->can('instructor')) {
	echo Yii::$app->keyStorage->get('backend.theme-skin-instructor', 'skin-purple');
}elseif (Yii::$app->user->can('submanager')) {
		echo Yii::$app->keyStorage->get('backend.theme-skin-instructor', 'skin-green-light');
}

?>">
<?php $this->beginBody() ?>

    <?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>