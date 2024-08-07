<?php

use app\models\CatDireccion;
use app\models\CatDptoCargo;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\CatDepartamento */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-md-6">
            <div class="card">
            <div class="card-header bg-info text-white">
                    <h3>Departamento:</h3>
                   
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
<div class="cat-departamento-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-6 col-sm-10">

    <?= $form->field($model, 'nombre_departamento')->textInput(['maxlength' => true]) ?>

    </div>
    <div class="col-6 col-sm-4">
  
    <?= $form->field($model, 'cat_direccion_id')->widget(Select2::classname(), [
                                        'data' => ArrayHelper::map(CatDireccion::find()->all(), 'id', 'nombre_direccion'), 
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                        'theme' => Select2::THEME_KRAJEE_BS3, 
                                    ])->label('Dirección:'); ?>
    </div>
    
<div class="col-6 col-sm-4">

<?= $form->field($model, 'cat_dpto_id')->widget(Select2::classname(), [
                                        'data' => ArrayHelper::map(CatDptoCargo::find()->all(), 'id', 'nombre_dpto'), 
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                        'theme' => Select2::THEME_KRAJEE_BS3, 
                                    ])->label('DPTO:'); ?>
</div>
<div class="col-6 col-sm-4">
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
