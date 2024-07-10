<?php
/* @var $content string */

use yii\bootstrap5\Breadcrumbs;

?>
<div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <?php
                        if (!is_null($this->title)) {
                         //   echo \yii\helpers\Html::encode($this->title);
                        } else {
                            echo \yii\helpers\Inflector::camelize($this->context->id);
                        }
                        ?>
                    </h1>
                </div><!-- /.col -->
               
                <div class="col-sm-6">
    <?php
   
    
    // Verifica si el usuario tiene el rol 'medico'
    if (Yii::$app->user->can('medico')) {
        echo Breadcrumbs::widget([
            'homeLink' => ['url' => ['/site/portal-medico'], 'label' => 'Inicio'],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'options' => [
                'class' => 'breadcrumb float-sm-right'
            ]
        ]);
    } elseif (Yii::$app->user->can('gestor-rh')) { // Verifica si el usuario tiene el rol 'gestor-rh'
        echo Breadcrumbs::widget([
            'homeLink' => ['url' => ['/site/portalgestionrh'], 'label' => 'Inicio'],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'options' => [
                'class' => 'breadcrumb float-sm-right'
            ]
        ]);
    } else {
        // Default breadcrumbs if user role is not 'medico' or 'gestor-rh'
        echo Breadcrumbs::widget([
            'homeLink' => ['label' => 'Inicio'],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'options' => [
                'class' => 'breadcrumb float-sm-right'
            ]
        ]);
    }
    ?>
</div><!-- /.col -->

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <?= $content ?><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>