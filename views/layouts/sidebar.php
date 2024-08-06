<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>
<aside class="main-sidebar sidebar-light-primary elevation-4">
<!-- Brand Logo -->
    <a href="#" class="brand-link bg-light ">
    <img src="<?= Url::to('@web/img/logo-capasu.png') ?>" alt="Capasu Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">CAPASU</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar bg-light">
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
            <?=
                Yii::$app->user->isGuest ?
                    Html::a(
                        'Log in',
                        ['/site/login'],
                        ['class'=>'d-block']
                    ) :
                    Html::a(
                        'Cerrar sesión ('.Yii::$app->user->identity->username.')',
                        ['/site/logout'],
                        ['class'=>'d-block','data-method'=>'post']
                    );
            ?>
        </div>
    </div>


        <nav class="mt-2">
            <?php
             if (Yii::$app->user->can('gestor-rh') || Yii::$app->user->can('administrador') ) {
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    ['label' => 'Inicio', 'icon' => 'home', 'url' => ['site/portalgestionrh']],
                    ['label' => 'Mi Perfil', 'icon' => 'user', 'url' => ['site/portalempleado']],


                    ['label' => 'Empleados', 'icon' => 'users', 'url' => ['empleado/index']],
                    
                   // ['label' => 'Formatos', 'icon' => 'file', 'url' => ['empleado/formatos']],
                    ['label' => 'Solicitudes', 'icon' => 'envelope-square', 'url' => ['solicitud/index']],

                    ['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
                    //['label' => 'Administrador', 'icon' => 'user-tie', 'url' => ['/admin']],
                 
                ],
            ]);
        }elseif (Yii::$app->user->can('medico')) {


            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    ['label' => 'Inicio', 'icon' => 'home', 'url' => ['site/portal-medico']],

                    ['label' => 'Empleados', 'icon' => 'users', 'url' => ['empleado/index']],
                    
                    ['label' => 'Mi Perfil', 'icon' => 'user', 'url' => ['site/portalempleado']],

                ],
            ]);
        }elseif (Yii::$app->user->can('ver-todos-empleados') || Yii::$app->user->can('ver-empleados-departamento') || Yii::$app->user->can('ver-empleados-direccion')) {
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    ['label' => 'Inicio', 'icon' => 'home', 'url' => ['site/portalempleado']],
                    ['label' => 'Empleados', 'icon' => 'users', 'url' => ['empleado/index']],
                    // Otras opciones de menú según necesidad
                ],
            ]);
        } elseif (Yii::$app->user->can('empleado')) {
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    ['label' => 'Inicio', 'icon' => 'home', 'url' => ['site/portalempleado']],
                    // Otras opciones de menú según necesidad
                ],
            ]);
        }
        

            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>