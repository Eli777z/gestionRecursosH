<?php

use yii\helpers\Html;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\models\ConsultaMedica */
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);
$this->registerCssFile('@web/css/grid-view.css', ['position' => View::POS_HEAD]);
$this->title = 'Create Consulta Medica';
$this->params['breadcrumbs'][] = ['label' => 'Consulta Medicas', 'url' => ['index']];

if ($empleadoId !== null) {
    $this->params['breadcrumbs'][] = ['label' => 'Empleado: ' . $empleadoNombre, 'url' => ['empleado/view', 'id' => $empleadoId]];
}

$this->params['breadcrumbs'][] = $this->title;
?>

    
        
            <div class="row">
            <div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header gradient-blue text-white text-center">
                <h2>Consulta médica</h2>
                <p>  Empleado: <?= $empleadoNombre ?></p>

            </div>
            <div class="card-body bg-light">
                <?=$this->render('_form', [
                    'model' => $model,
                    'empleadoId' => $empleadoId,
                    'empleadoNombre' => $empleadoNombre,

                ]) ?>
            </div>
        </div>
    </div>
</div>

               
            </div>
        
        <!--.card-body-->
   
    <!--.card-->



