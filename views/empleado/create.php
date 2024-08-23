<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Empleado */

$this->title = 'Create Empleado';
$this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">
   
            <div class="row">
                <div class="col-md-12">
                    <?=
                    // RENDERIZA EL _FORM Y CARGA LOS MODELOS DE USUARIO, INFORMACION_LABORAL
                    // Y JUNTA_GOBIERNO
                    $this->render('_form', [
                        'model' => $model,
                        'usuario' => $usuario,
                           'informacion_laboral' => $informacion_laboral,
                           'juntaGobiernoModel' => $juntaGobiernoModel

                    ]) ?>
                </div>
            </div>
        
</div>