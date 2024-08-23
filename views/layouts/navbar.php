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
            <?= Html::a('Cerrar Sesión <i class="fas fa-sign-out-alt" style="color: #007bff;" ></i>', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
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
            <?= Html::a('Cerrar Sesión <i class="fas fa-sign-out-alt" style="color: #007bff;" ></i>', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt" style="color: #007bff;"></i>
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
    <?php 
$usuario = Yii::$app->user->identity;
$empleado = $usuario->empleado;

if ($empleado->informacionLaboral->catTipoContrato->nombre_tipo === 'Eventual'): ?>
    <?= Html::a('SOLICITUD DE CONTRATO PARA PERSONAL EVENTUAL', Url::to(['contrato-para-personal-eventual/index']), ['class' => 'dropdown-item text-primary']) ?>
<?php endif; ?>

    <?= Html::a('REPORTE DE TIEMPO EXTRA', Url::to(['reporte-tiempo-extra/index']), ['class' => 'dropdown-item text-primary']) ?>
    <?= Html::a('REPORTE DE TIEMPO EXTRA GENERAL', Url::to(['reporte-tiempo-extra-general/index']), ['class' => 'dropdown-item text-primary']) ?>

   
  </div>
</div>
        <!-- Navbar Search -->
      



<li class="nav-item">
    <?= Html::a('Cerrar Sesión <i class="fas fa-sign-out-alt" style="color: #007bff;"></i>', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
</li>
<li class="nav-item">
    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt" style="color: #007bff;"></i>
    </a>
</li>

    </ul>
</nav>
<!-- /.navbar -->
<?php }?>