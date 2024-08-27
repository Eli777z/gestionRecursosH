<?php 
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap5\Alert;
use yii\bootstrap4\Modal;

?>

<div class="card">
    <div class="card-body bg-light login-card-body">
        <p class="login-box-msg">Inicia sesión</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form']) ?>
        
        <!-- Mostrar alertas -->
        <div class="d-flex align-items-center mb-3">
            <?php
            foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
                echo Alert::widget([
                    'options' => ['class' => 'alert-' . ($type === 'error' ? 'danger' : $type)],
                    'body' => $message,
                ]);
            }
            ?>
        </div>
        
        <!-- Campo de usuario -->
        <?= $form->field($model, 'username', [
            'options' => ['class' => 'form-group has-feedback'],
            'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fa fa-user" style="color: #007bff;"></span></div></div>',
            'template' => '{beginWrapper}{input}{error}{endWrapper}',
            'wrapperOptions' => ['class' => 'input-group mb-3']
        ])->label(false)->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

        <!-- Campo de contraseña -->
        <?= $form->field($model, 'password', [
            'options' => ['class' => 'form-group has-feedback'],
            'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock" style="color: #007bff;"></span></div></div>',
            'template' => '{beginWrapper}{input}{error}{endWrapper}',
            'wrapperOptions' => ['class' => 'input-group mb-3']
        ])->label(false)->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

        <!-- Botones -->
        <div class="row">
            
            <div class="col-6">
                <?= Html::submitButton('Inicia sesión', ['class' => 'btn btn-primary btn-block']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-6">
                <!-- Ícono para abrir el modal -->
                <span class="fa fa-info-circle" style="cursor: pointer; color: #007bff;" data-toggle="modal" data-target="#infoModal"></span>
            </div>
</div>

<!-- Modal de Bootstrap -->
<?php Modal::begin([
    'id' => 'infoModal',
    'title' => 'Información',
    'footer' => Html::button('Cerrar', ['class' => 'btn btn-secondary', 'data-dismiss' => 'modal']),
]); ?>

<p>Sistema desarrollado por Sergio Eli Peña P.</p>

<?php Modal::end(); ?>
