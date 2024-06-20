<?php

use yii\helpers\Html;
?>
<aside class="main-sidebar sidebar-light-primary elevation-4">
<!-- Brand Logo -->
    <a href="../portalgestionrh" class="brand-link bg-light ">
        <img src="<?= $assetDir ?>/img/logo-capasu.png" alt="Capasu Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
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
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    ['label' => 'Inicio', 'icon' => 'home', 'url' => ['site/portalgestionrh']],

                    ['label' => 'Empleados', 'icon' => 'user', 'url' => ['empleado/index']],
                    ['label' => 'Formatos', 'icon' => 'file', 'url' => ['empleado/formatos']],
                    ['label' => 'Solicitudes', 'icon' => 'thumbs-up', 'url' => ['solicitud/index']],

                    ['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
                    ['label' => 'Administrador', 'icon' => 'user-tie', 'url' => ['/admin']],
                    ['label' => 'MULTI LEVEL EXAMPLE', 'header' => true],
                    ['label' => 'Level1'],
                    [
                        'label' => 'Empleados',
                        'items' => [
                            ['label' => 'Junta de gobierno', 'iconStyle' => 'far'],
                            
                            
                        ]
                    ],
                    ['label' => 'Level1'],
                    ['label' => 'LABELS', 'header' => true],
                    ['label' => 'Important', 'iconStyle' => 'far', 'iconClassAdded' => 'text-danger'],
                    ['label' => 'Warning', 'iconClass' => 'nav-icon far fa-circle text-warning'],
                    ['label' => 'Informational', 'iconStyle' => 'far', 'iconClassAdded' => 'text-info'],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>