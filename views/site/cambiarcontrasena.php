<?php
//IMPORTACIONES
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CambiarContrasenaForm */
/* @var $form ActiveForm */

$this->title = 'Cambiar Contraseña';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-cambiarcontrasena">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    // FORMULARIO PARA EL CAMBIO DE CONTRASEÑA DEL USUARIO
    $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'oldPassword')->passwordInput() ?>
        <?= $form->field($model, 'newPassword')->passwordInput() ?>
        <?= $form->field($model, 'repeatNewPassword')->passwordInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Cambiar', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
