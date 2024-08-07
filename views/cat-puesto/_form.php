<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CatPuesto */
/* @var $form yii\bootstrap4\ActiveForm */
?>
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-md-6">
            <div class="card">
            <div class="card-header bg-info text-white">
                    <h3>Nombramientos:</h3>
                   
                  <?php  echo Html::a('<i class="fa fa-chevron-left"></i> Volver', ['//site/configuracion'], [
                'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
                'encode' => false,
            ]);

            ?>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                       

                        </div>
                    </div>
<div class="cat-puesto-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-6 col-sm-10">
    <?= $form->field($model, 'nombre_puesto')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-6 col-sm-6">
    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>
    </div>
    <?php ActiveForm::end(); ?>
    </div>

</div>
<!--.card-body-->
</div>
<!--.card-->
</div>
<!--.col-md-12-->
</div>
<!--.row-->
</div>
