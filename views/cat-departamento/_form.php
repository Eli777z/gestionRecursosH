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

<div class="cat-departamento-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre_departamento')->textInput(['maxlength' => true]) ?>

  
    <?= $form->field($model, 'cat_direccion_id')->widget(Select2::classname(), [
                                        'data' => ArrayHelper::map(CatDireccion::find()->all(), 'id', 'nombre_direccion'), 
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                        'theme' => Select2::THEME_KRAJEE_BS3, 
                                    ])->label('Dirección:'); ?>

<?= $form->field($model, 'cat_dpto_id')->widget(Select2::classname(), [
                                        'data' => ArrayHelper::map(CatDptoCargo::find()->all(), 'id', 'nombre_dpto'), 
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                        'theme' => Select2::THEME_KRAJEE_BS3, 
                                    ])->label('DPTO:'); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
