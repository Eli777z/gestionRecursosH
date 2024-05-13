<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Countries */
/* @var $form yii\bootstrap4\ActiveForm */
?>
<?php

$this->registerJs(
   '$("document").ready(function(){ 
		$("#new_country").on("pjax:end", function() {
			$.pjax.reload({container:"#countries"});  //Reload GridView
		});
    });'
);
?>

<div class="countries-form">

<?php yii\widgets\Pjax::begin(['id' => 'new_country']) ?>
<?php $form = ActiveForm::begin(['options' => ['data-pjax' => true ]]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php yii\widgets\Pjax::end() ?>
</div>
