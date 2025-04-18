<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Url;
\hail812\adminlte3\assets\AdminLteAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700');
$this->registerCssFile('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css');
\hail812\adminlte3\assets\PluginAsset::register($this)->add(['fontawesome', 'icheck-bootstrap']);
$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CAPASU | Iniciar sesión</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <?php $this->head() ?>
    <?php $this->head() ?>
    <style>
        .login-logo img {
            width: 100px; /* Ajusta el tamaño según sea necesario */
            height: auto;
        }
    </style>
</head>
<body class="hold-transition login-page bg-white">
<?php $this->beginBody() ?>
<div class="login-box">
    <div class="login-logo">
        <a href="#">

            <img src="<?= Url::to('@web/img/logo-capasu.png') ?>" alt="Capasu Logo" class="brand-image img-circle elevation-3" style="opacity: 1.0">
            <span class="brand-text font-weight-light"></span>
        </a>    </div>
    <!-- /.login-logo -->
    <?= $content ?>
</div>
<!-- /.login-box -->



<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
