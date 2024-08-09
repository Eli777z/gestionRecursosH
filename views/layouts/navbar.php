<?php

use yii\helpers\Html;
use app\models\Notificacion;
use yii\helpers\Url;

?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light ">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars" style="color: #007bff;"></i></a>
        </li>
        <?php              if (Yii::$app->user->can('gestor-rh')) {
?>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="../site/portalgestionrh" class="nav-link text-primary"><i class="fas fa-home" style="color: #007bff;"></i> &nbsp;Inicio </a>
        </li>

        <?php }elseif(Yii::$app->user->can('medico')) {
?>
<li class="nav-item d-none d-sm-inline-block">
            <a href="../site/portal-medico" class="nav-link text-primary"><i class="fas fa-home" style="color: #007bff;"></i> &nbsp;Inicio </a>
        </li>
        <?php }elseif(Yii::$app->user->can('medico')) {
?>

<li class="nav-item d-none d-sm-inline-block">
            <a href="../site/portalempleado" class="nav-link text-primary"><i class="fas fa-home" style="color: #007bff;"></i> &nbsp;Inicio </a>
        </li>


<?php }?>
  
    </ul>

    <!-- SEARCH FORM -->
    <?php              if (Yii::$app->user->can('gestor-rh')) {
?>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
      

        <!-- Messages Dropdown Menu -->
        
        <!-- Notifications Dropdown Menu -->
       
    

        <li class="nav-item">
            <?= Html::a('<i class="fas fa-sign-out-alt" style="color: #007bff;" ></i>', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt" style="color: #007bff;"></i>
            </a>
        </li>
     
    </ul>
</nav>
<!-- /.navbar -->
<?php }elseif(Yii::$app->user->can('medico')) {
?>
<ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search " style="color: #007bff;"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search" style="color: #007bff;"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"  style="color: #007bff;" ></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments  " style="color: #007bff;" ></i>
                <span class="badge badge-danger navbar-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="<?=$assetDir?>/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Brad Diesel
                                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">Call me whenever you can...</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="<?=$assetDir?>/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                John Pierce
                                <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">I got your message bro</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="<?=$assetDir?>/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Nora Silvester
                                <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">The subject goes here</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
            </div>
        </li>
        <!-- Notifications Dropdown Menu -->
       
        <li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell" style="color: #007bff;"></i>
        <span class="badge badge-warning navbar-badge"></span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-header">Notificaciones</span>
        <div class="dropdown-divider"></div>
       
        <a href="<?= \yii\helpers\Url::to(['site/view-notificaciones']) ?>" class="dropdown-item dropdown-footer">Ver todas las notificaciones</a>
    </div>
</li>

        <li class="nav-item">
            <?= Html::a('<i class="fas fa-sign-out-alt" style="color: #007bff;" ></i>', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt" style="color: #007bff;"></i>
            </a>
        </li>
        -<li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large" style="color: #007bff;"></i>
            </a>
        </li>
    </ul>
</nav>


<?php }elseif(Yii::$app->user->can('menu-formatos')) {
?>
<ul class="navbar-nav ml-auto">
    <div class="dropdown">
  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
    FORMATOS DE INCIDENCIAS
  </button>
  <div class="dropdown-menu">
  <?= Html::a('CITA MEDICA', Url::to(['cita-medica/index']), ['class' => 'dropdown-item text-primary']) ?>

    <?= Html::a('PERMISO FUERA DEL TRABAJO', Url::to(['permiso-fuera-trabajo/index']), ['class' => 'dropdown-item text-primary']) ?>
   

    <?= Html::a('COMISIÓN ESPECIAL', Url::to(['comision-especial/index']), ['class' => 'dropdown-item text-primary']) ?>
    
    <?= Html::a('CAMBIO DE DÍA LABORAL', Url::to(['cambio-dia-laboral/index']), ['class' => 'dropdown-item text-primary']) ?>
    <?= Html::a('CAMBIO DE HORARIO DE TRABAJO', Url::to(['cambio-horario-trabajo/index']), ['class' => 'dropdown-item text-primary']) ?>
    <?= Html::a('PERMISO ECONÓMICO', Url::to(['permiso-economico/index']), ['class' => 'dropdown-item text-primary']) ?>
    <?= Html::a('PERMISO SIN GOCE DE SUELDO', Url::to(['permiso-sin-sueldo/index']), ['class' => 'dropdown-item text-primary']) ?>
    <?= Html::a('CAMBIO PERIODO VACACIONAL', Url::to(['cambio-periodo-vacacional/index']), ['class' => 'dropdown-item text-primary']) ?>
    <?= Html::a('REPORTE DE TIEMPO EXTRA', Url::to(['reporte-tiempo-extra/index']), ['class' => 'dropdown-item text-primary']) ?>
    <?= Html::a('REPORTE DE TIEMPO EXTRA GENERAL', Url::to(['reporte-tiempo-extra-general/index']), ['class' => 'dropdown-item text-primary']) ?>

   
  </div>
</div>
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
        <span class="badge badge-warning navbar-badge"></span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-header"> Notificaciones</span>
        <div class="dropdown-divider"></div>
       
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
<?php }?>