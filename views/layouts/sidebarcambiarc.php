<?php
use yii\helpers\Html;
?>
<aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="<?= $assetDir ?>/img/logo-capasu.png" alt="Capasu Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">CAPASU</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <?php
            $usuario = Yii::$app->user->identity;
            $empleado = $usuario->empleado; // Utilizando la relación definida en el modelo Usuario

            if ($empleado && $empleado->foto) {
                $fotoPath = $empleado->nombre . '_' . $empleado->apellido . '/foto_empleado/' . basename($empleado->foto);
                $fotoUrl = Yii::$app->urlManager->createUrl(['site/get-empleado-foto', 'filename' => $fotoPath]);
            } else {
                $fotoUrl = Yii::getAlias('@web') . '/path/to/default-image.jpg'; // Ruta a una imagen por defecto
            }
            ?>
            <img src="<?= $fotoUrl ?>" class="img-circle elevation-2" alt="User Image">
        </div>
          
            <div class="info">
                <!--<a href="#" class="d-block">Alexander Pierce</a>-->
                <?=
                            Yii::$app->user->isGuest ? 
                                Html::a(
                                    'Log in',
                                    ['/site/login'],
                                    ['class'=>'d-block']
                                ) : 
                                Html::a(
                                    'Salir de la sesion ('.Yii::$app->user->identity->username.')',
                                    ['/site/logout'],
                                    ['class'=>'d-block','data-method'=>'post']
                                );
                        ?>
                
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                   
                        
                    
                 //   ['label' => 'Simple Link', 'icon' => 'th', 'badge' => '<span class="right badge badge-danger">New</span>'],
                 //   ['label' => 'Volver al inicio de sesion', 'header' => true],
                   // ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt'],
                   // ['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
                    //['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank'],
                   // ['label' => 'Empleados', 'icon' => 'user', 'url' => ['empleado/index']],
                   
                   
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>