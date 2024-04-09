<?php
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\Pjax;;
/* @var $this yii\web\View */
/* @var $model app\models\Expediente */
/* @var $form yii\bootstrap4\ActiveForm */
?>


<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="expediente-form">

                        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                        <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'ruta')->widget(FileInput::classname(), ['options' => ['accept' => 'file/*'],]) ?>

                        <?= $form->field($model, 'tipo')->textInput(['maxlength' => true]) ?>

                     
                     

                        <div class="form-group">
                            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
                <!--.col-md-12-->
            </div>
            <!--.row-->
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
