<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Notificacion;


$notificaciones = Notificacion::find()->where(['usuario_id' => Yii::$app->user->identity->id, 'leido' => 0])->orderBy(['created_at' => SORT_DESC])->all();
$count = count($notificaciones);
?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-primary">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?=\yii\helpers\Url::home()?>" class="nav-link">Inicio</a>
        </li>
        
        <li class="nav-item dropdown">
    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Formatos de Incidencias</a>
    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
        <li class="list-group-item">
            <?php echo Html::a('PERMISO FUERA DEL TRABAJO', Url::to(['permiso-fuera-trabajo/index']), ['class' => 'dropdown-item']); ?>
                    </li>
                    <li class="list-group-item">
                        <a href="#" class="dropdown-item">COMISION ESPECIAL</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#" class="dropdown-item">PERMISO ECONÓMICO</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#" class="dropdown-item">PERMISO SIN GOCE DE SUELDO</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#" class="dropdown-item">CAMBIO DE DÍA LABORAL</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#" class="dropdown-item">CAMBIO DE HORARIO DE TRABAJO</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#" class="dropdown-item">CAMBIO DE PERIODO VACACIONAL</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#" class="dropdown-item">REPORTE DE TIEMPO EXTRA</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#" class="dropdown-item">REPORTE GENERAL DE TIEMPO EXTRA</a>
                    </li>
             

                <li class="dropdown-divider"></li>

                <!-- Level two dropdown-->
                
                <!-- End Level two -->
            </ul>
        </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
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
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
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
    <?= Html::a('<i class="fas fa-sign-out-alt"></i>', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
</li>
<li class="nav-item">
    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
        <i class="fas fa-th-large"></i>
    </a>
</li>
    </ul>
</nav>
<!-- /.navbar -->