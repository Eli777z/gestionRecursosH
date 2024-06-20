<?php
use yii\helpers\Html;
?>
<aside class="main-sidebar sidebar-light-primary elevation-4">
<!-- Brand Logo -->
    <a href="index3.html" class="brand-link bg-light ">
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
                    [
                        'label' => 'Starter Pages',
                        'icon' => 'tachometer-alt',
                        'badge' => '<span class="right badge badge-info">2</span>',
                        'items' => [
                            ['label' => 'Active Page', 'url' => ['site/index'], 'iconStyle' => 'far'],
                            ['label' => 'Inactive Page', 'iconStyle' => 'far'],
                        ]
                    ],
                    ['label' => 'Simple Link', 'icon' => 'th', 'badge' => '<span class="right badge badge-danger">New</span>'],
                    ['label' => 'Yii2 PROVIDED', 'header' => true],
                    ['label' => 'Inicia sesion', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],
                    ['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
                    ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank'],
                   // ['label' => 'Empleados', 'icon' => 'user', 'url' => ['empleado/index']],
                //   [
                  //  'label' => 'Formatos De Incidencias',
                    //'items' => [
                      //  ['label' => 'PERMISO FUERA DEL TRABAJO', 'iconStyle' => 'far'],
            //            ['label' => 'COMISION ESPECIAL', 'iconStyle' => 'far'],
              //          ['label' => 'PERMISO ECONÓMICO', 'iconStyle' => 'far'],
                //        ['label' => 'PERMISO SIN GOCE DE SUELDO', 'iconStyle' => 'far'],
                  //      ['label' => 'CAMBIO DE DÍA LABORAL', 'iconStyle' => 'far'],
                    //    ['label' => 'CAMBIO DE HORARIO DE TRABAJO', 'iconStyle' => 'far'],
                    //    ['label' => 'CAMBIO DE PERIODO VACACIONAL', 'iconStyle' => 'far'],
                  //      ['label' => 'REPORTE DE TIEMPO EXTRA', 'iconStyle' => 'far'],
                      //  ['label' => 'REPORTE GENERAL DE TIEMPO EXTRA', 'iconStyle' => 'far'],
                        
              //      ]
            //    ],
                   
              
                   
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>