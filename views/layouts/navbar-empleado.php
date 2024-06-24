<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Notificacion;


$notificaciones = Notificacion::find()->where(['usuario_id' => Yii::$app->user->identity->id, 'leido' => 0])->orderBy(['created_at' => SORT_DESC])->all();
$count = count($notificaciones);
?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light ">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars" style="color: #007bff;"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="../site/portalempleado" class="nav-link text-primary"><i class="fas fa-home" style="color: #007bff;"></i> &nbsp;Inicio </a>
        </li>
        
       <div class="dropdown">
  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
    FORMATOS DE INCIDENCIAS
  </button>
  <div class="dropdown-menu">
    <?= Html::a('PERMISO FUERA DEL TRABAJO', Url::to(['permiso-fuera-trabajo/index']), ['class' => 'dropdown-item text-primary']) ?>
   

    <?= Html::a('COMISIÓN ESPECIAL', Url::to(['comision-especial/index']), ['class' => 'dropdown-item text-primary']) ?>
    
    <?= Html::a('CAMBIO DE DÍA LABORAL', Url::to(['cambio-dia-laboral/index']), ['class' => 'dropdown-item text-primary']) ?>
    <?= Html::a('CAMBIO DE HORARIO DE TRABAJO', Url::to(['cambio-horario-trabajo/index']), ['class' => 'dropdown-item text-primary']) ?>
    <?= Html::a('PERMISO ECONÓMICO', Url::to(['permiso-economico/index']), ['class' => 'dropdown-item text-primary']) ?>
    <?= Html::a('PERMISO SIN GOCE DE SUELDO', Url::to(['permiso-sin-sueldo/index']), ['class' => 'dropdown-item text-primary']) ?>
    <?= Html::a('CAMBIO PERIODO VACACIONAL', Url::to(['cambio-periodo-vacacional/index']), ['class' => 'dropdown-item text-primary']) ?>
    <a class="dropdown-item" href="#">REPORTE DE TIEMPO EXTRA</a>
    <a class="dropdown-item" href="#">REPORTE GENERAL DE TIEMPO EXTRA</a>
  </div>
</div>




    </ul>

 

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search" style="color: #007bff;"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times" style="color: #007bff;"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell" style="color: #007bff;"></i>
        <span class="badge badge-warning navbar-badge"><?= $count ?></span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-header"><?= $count ?> Notificaciones</span>
        <div class="dropdown-divider"></div>
        <?php foreach ($notificaciones as $notificacion): ?>
            <a href="#" class="dropdown-item">
                <i class="fas fa-envelope mr-2"></i> <?= Html::encode($notificacion->mensaje) ?>
                <span class="float-right text-muted text-sm"><?= Yii::$app->formatter->asRelativeTime($notificacion->created_at) ?></span>
            </a>
            <div class="dropdown-divider"></div>
        <?php endforeach; ?>
        <a href="<?= \yii\helpers\Url::to(['site/view-notificaciones']) ?>" class="dropdown-item dropdown-footer">Ver todas las notificaciones</a>
    </div>
</li>

<li class="nav-item">
    <?= Html::a('<i class="fas fa-sign-out-alt" style="color: #007bff;"></i>', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
</li>
<li class="nav-item">
    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt" style="color: #007bff;"></i>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
        <i class="fas fa-th-large"style="color: #007bff;"></i>
    </a>
</li>
    </ul>
</nav>
<!-- /.navbar -->