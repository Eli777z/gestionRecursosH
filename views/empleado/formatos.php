<?php

use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\grid\GridView;
use kartik\select2\Select2;

$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);

$this->title = 'Plantillas de los Formatos';
$this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$activeTab = Yii::$app->request->get('tab', 'info_p');

?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
            <div class="card-header bg-primary text-white"> <!-- Agregando las clases bg-primary y text-white -->
                    <h3><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                           
                            <div class="empleado-form">



                                <?php $form = ActiveForm::begin([
                                    'options' => ['enctype' => 'multipart/form-data']
                                ]); ?>


                                <?= $form->field($model, 'selectedName')->widget(Select2::classname(), [
                                    'data' => [
                                        'permiso_fuera_trabajo' => 'Permiso Fuera del Trabajo',
                                        'comision' => 'Comisión Especial',
                                        'permiso_economico' => 'Permiso Económico',
                                        'permiso_sin_goce' => 'Permiso Sin Goce de Sueldo',
                                        'cambio_dia_laboral' => 'Cambio de Día Laboral',
                                        'cambio_horario' => 'Cambio de Horario de Trabajo',
                                        'cambio_vacacional' => 'Cambio de Período Vacacional',
                                        'reporte_tiempo_extra' => 'Reporte de Tiempo Extra',
                                        'reporte_general_tiempo_extra' => 'Reporte General de Tiempo Extra',
                                    ],
                                    'options' => [
                                        'placeholder' => 'Selecciona el tipo de formato',
                                        'multiple' => false
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ])->label('Selecciona el nombre para el archivo'); ?>

<?= $form->field($model, 'file')->widget(FileInput::classname(), [
    'options' => ['accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'],
    'pluginOptions' => [
        'showPreview' => true,
        'showUpload' => false,
        'allowedFileExtensions' => ['xls', 'xlsx'],
        'showCancel' => false, // Agregar esta opción para ocultar el botón de cancelar
    ]
])->label('Sube la plantilla del formato'); ?>


                                <div class="form-group">
                                    <?= Html::submitButton('Subir', ['class' => 'btn btn-primary', 'name' => 'upload-button']) ?>
                                </div>

                                <?php ActiveForm::end(); ?>

                                <?php if (Yii::$app->session->hasFlash('uploadSuccess')) : ?>
                                    <div class="alert alert-success">
                                        <?= Yii::$app->session->getFlash('uploadSuccess') ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (Yii::$app->session->hasFlash('deleteSuccess')) : ?>
                                    <div class="alert alert-success">
                                        <?= Yii::$app->session->getFlash('deleteSuccess') ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (Yii::$app->session->hasFlash('deleteError')) : ?>
                                    <div class="alert alert-danger">
                                        <?= Yii::$app->session->getFlash('deleteError') ?>
                                    </div>
                                <?php endif; ?>
                                <div class="card-header bg-primary text-white"> <!-- Agregando las clases bg-primary y text-white -->
                                <h4>Plantillas</h4>
                </div>
                                

                                <?= GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        'filename',
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'template' => '{delete} {download}',
                                            'buttons' => [
                                                'delete' => function ($url, $model, $key) {
                                                    return Html::beginForm(['delete-formato'], 'post', ['style' => 'display: inline;'])
                                                        . Html::hiddenInput('filename', $model['filename'])
                                                        . Html::submitButton('<i class="fas fa-trash"></i> ', [
                                                            'class' => 'btn btn-danger btn-xs',
                                                            'data-confirm' => '¿Estás seguro de que quieres eliminar este archivo?',
                                                        ])
                                                        . Html::endForm();
                                                },
                                                'download' => function ($url, $model, $key) {
                                                    return Html::a('<i class="fas fa-download"></i> ', ['download-formato', 'filename' => $model['filename']], [
                                                        'class' => 'btn btn-success btn-xs',
                                                        'title' => 'Descargar archivo',
                                                        'data-pjax' => '0',
                                                    ]);
                                                },
                                            ],
                                        ],
                                    ],
                                ]); ?>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>