<?php

use app\models\CatDepartamento;
use hail812\adminlte3\yii\grid\ActionColumn;
use yii\helpers\Html;
//use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\bootstrap5\Tabs;
use yii\grid\GridView;
use yii\bootstrap4\Modal;
use yii\web\YiiAsset;
use yii\widgets\Pjax;
use yii\web\View;
use kartik\detail\DetailView;
use app\models\ExpedienteSearch;
use yii\bootstrap4\ActiveForm;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use app\models\Departamento;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\Empleado */



$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center mb-3">
                                <?php
                                // Registro de script
                                $this->registerJs("
                               // Función para abrir el cuadro de diálogo de carga de archivos cuando se hace clic en la imagen
                               $('#foto').click(function() {
                                   $('#foto-input').trigger('click');
                               });
                               
                               // Función para enviar el formulario cuando se selecciona una nueva imagen
                               $('#foto-input').change(function() {
                                   $('#foto-form').submit();
                               });
                               ", View::POS_READY);
                                ?>

                                <div id="foto" title="Cambiar foto de perfil" style="position: relative;">
                                    <?php if ($model->foto) : ?>
                                        <?= Html::img(['empleado/foto-empleado', 'id' => $model->id], ['class' => 'mr-3', 'style' => 'width: 100px; height: 100px;']) ?>
                                    <?php else : ?>
                                        <?= Html::tag('div', 'No hay foto disponible', ['class' => 'mr-3']) ?>
                                    <?php endif; ?>
                                    <i class="fas fa-edit" style="position: absolute; bottom: 5px; right: 5px; cursor: pointer;"></i>
                                </div>



                                <?php $form = ActiveForm::begin(['id' => 'foto-form', 'options' => ['enctype' => 'multipart/form-data', 'action' => ['cambio', 'id' => $model->id]]]); ?>

                                <?= $form->field($model, 'foto')->fileInput(['id' => 'foto-input', 'style' => 'display:none'])->label(false) ?>


                                <?php ActiveForm::end(); ?>

                                <h2 class="mb-0">Empleado: <?= $model->nombre ?> <?= $model->apellido ?></h2>
                            </div>
                            <?php $this->beginBlock('datos'); ?>

                            <?php $this->beginBlock('info_p'); ?>
                            <br>
                            <?php $this->registerJs("
    $('#pjax-update-info').on('pjax:success', function(event, data, status, xhr) {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
            alert(response.message); // Muestra un mensaje de éxito
            $.pjax.reload({container: '#pjax-update-info'}); // Recarga el contenido del contenedor Pjax
        } else {
            alert(response.message); // Muestra un mensaje de error
        }
    });
    "); ?>
                            <?php Pjax::begin([
                                'id' => 'pjax-update-info', // Un ID único para el widget Pjax
                                'options' => ['pushState' => false], // Deshabilita el uso de la API pushState para navegadores que la soportan
                            ]); ?>

                            <?= DetailView::widget([
                                'model' => $model,
                                'condensed' => true,
                                'hover' => true,
                                'buttons1' => '{update}',
                                'mode' => DetailView::MODE_VIEW,
                                'panel' => [
                                    'heading' => 'Información Personal',
                                    'type' => DetailView::TYPE_INFO,
                                ],
                                'attributes' => [
                                    'nombre',
                                    'apellido',
                                    [
                                        'attribute' => 'fecha_nacimiento',
                                        'type' => DetailView::INPUT_DATE,
                                        'format' => ['date', 'php:Y-m-d'], // Formato de fecha
                                        'displayFormat' => 'php:Y-m-d', // Formato de visualización
                                        'widgetOptions' => [
                                            'pluginOptions' => [
                                                'format' => 'yyyy-mm-dd', // Formato deseado para la fecha
                                                'autoclose' => true,
                                            ]
                                        ],
                                    ],

                                 ///   'codigo_postal',
                                  //  'calle',
                                    //'numero_casa',
                                    //'colonia',
                                ],
                                'formOptions' => [
                                    'action' => ['actualizar-informacion', 'id' => $model->id],
                                ],
                            ]); ?>
                            <?php Pjax::end(); ?>
                            <?php $this->endBlock(); ?>


                            <?php $this->beginBlock('info_c'); ?>
                            <br>
                            <?= DetailView::widget([
                                'model' => $model,
                                'condensed' => true,
                                'hover' => true,
                                'buttons1' => '{update}',
                                'mode' => DetailView::MODE_VIEW,
                                'panel' => [
                                    'heading' => 'Información de Contacto ',
                                    'type' => DetailView::TYPE_INFO,
                                ],

                                'attributes' => [
                                    'email:email',
                                    'telefono',

                                ],
                                'formOptions' => [
                                    'action' => ['actualizar-informacion-contacto', 'id' => $model->id],
                                ],
                            ]) ?>
                            <?php $this->endBlock(); ?>
<!-- INFOLABORAL-->
                            <?php $this->beginBlock('info_l'); ?>
                            <br>
                            <?= DetailView::widget([
                                'model' => $model->informacionLaboral,
                                'condensed' => true,
                                'hover' => true,
                                'mode' => DetailView::MODE_VIEW,
                                'panel' => [
                                    'heading' => 'Información Laboral ',
                                    'type' => DetailView::TYPE_INFO,
                                ],
                                'buttons1' => '{update}',
                               
                                'attributes' => [
                                    //'numero_trabajador',
                                    [
                                        'attribute' => 'cat_departamento_id',
                                        'label' => 'Departamento',
                                        'value' => isset($model->informacionLaboral->catdepartamento) ? $model->informacionLaboral->catdepartamento->nombre_departamento : null,
                                        'type' => DetailView::INPUT_SELECT2,
                                        'widgetOptions' => [
                                            'data' => ArrayHelper::map(CatDepartamento::find()->all(), 'id', 'nombre'), // Obtener los departamentos para el dropdown
                                            'options' => ['prompt' => 'Seleccionar Departamento'], // Opción por defecto
                                        ],
                                    ],
                                    
                                    [
                                        'attribute' => 'fecha_ingreso',
                                        'type' => DetailView::INPUT_DATE,
                                        'format' => ['date', 'php:Y-m-d'], // Formato de fecha
                                        'displayFormat' => 'php:Y-m-d', // Formato de visualización
                                        'widgetOptions' => [
                                            'pluginOptions' => [
                                                'format' => 'yyyy-mm-dd', // Formato deseado para la fecha
                                                'autoclose' => true,
                                            ]
                                        ],
                                    ],

                                ],
                                'formOptions' => [
                                    'action' => ['actualizar-informacion-laboral', 'id' => $model->id],
                                ],
                            ]); ?>

                            <?php $this->endBlock(); ?>



                            <?= Tabs::widget([
                                'options' => ['class' => 'custom-tabs'],
                                'items' => [
                                    [
                                        'label' => 'Información personal',
                                        'content' => $this->blocks['info_p'],
                                        'active' => true,
                                    ],
                                    [
                                        'label' => 'Información de contacto',
                                        'content' => $this->blocks['info_c'],
                                        //'active' => true,
                                    ],
                                    [
                                        'label' => 'Información laboral',
                                        'content' => $this->blocks['info_l'],
                                        //'active' => true,
                                    ],
                                ],
                            ]); ?>


                            <?php $this->endBlock(); ?>
                            <?= Tabs::widget([
                                'options' => ['class' => 'custom-tabs-2'],
                                'items' => [
                                    [
                                        'label' => 'Información',
                                        'content' => $this->blocks['datos'],
                                        'active' => true,


                                    ],
                                  //  [
                                //        'label' => 'Expediente',
                                    //    'content' => $this->blocks['expediente'],


                                    //],


                                ],

                            ]);

                            ?>

                        </div>
                        <!--.col-md-12-->

                    </div>
                    <!--.row-->

                </div>
                <!--.card-body-->

            </div>
            <!--.card-->

        </div>
        <!--.col-md-10-->
    </div>
    <!--.row-->
</div>
<!--.container-fluid-->