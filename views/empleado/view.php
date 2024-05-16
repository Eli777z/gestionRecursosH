<?php

use app\models\CatDepartamento;

use app\models\CatDireccion;
use app\models\CatDptoCargo;
use app\models\CatPuesto;
use app\models\CatTipoContrato;
use hail812\adminlte3\yii\grid\ActionColumn;
use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\file\FileInput;

use yii\helpers\Url;
use yii\bootstrap5\Tabs;
use yii\grid\GridView;
use yii\bootstrap4\Modal;
use yii\web\YiiAsset;
use yii\widgets\Pjax;
use yii\web\View;
use kartik\detail\DetailView;
use app\models\ExpedienteSearch;
//use yii\bootstrap4\ActiveForm;
use kartik\form\ActiveForm;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use app\models\Departamento;
use app\models\Documento;
use kartik\tabs\TabsX;
use app\models\DocumentoSearch;
use app\models\JuntaGobierno;
use yii\web\JsExpression;
use kartik\select2\Select2;
use app\models\CatTipoDocumento;
/* @var $this yii\web\View */
/* @var $model app\models\Empleado */

// Obtén el valor del parámetro de la URL (si está presente)
//$activeTab = Yii::$app->request->get('tab', 'info_p');

$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);

$this->title = 'Empleado ' . $model->numero_empleado;
$this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$activeTab = Yii::$app->request->get('tab', 'info_p');

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

                                <h2 class="mb-0"> Empleado: <?= $model->nombre ?> <?= $model->apellido ?></h2>
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
                                    'type' =>  DetailView::TYPE_PRIMARY

                                ],
                                'attributes' => [
                                    'numero_empleado',
                                    'nombre',
                                    'apellido',
                                    [
                                        'attribute' => 'fecha_nacimiento',
                                        'type' => DetailView::INPUT_DATE,
                                        'format' => ['date', 'php:Y-m-d'], // Formato de fecha
                                        'displayFormat' => 'php:Y-m-d',
                                        'widgetOptions' => [
                                            'pluginOptions' => [
                                                'format' => 'yyyy-mm-dd', // Formato deseado para la fecha
                                                'autoclose' => true,
                                            ]
                                        ],
                                    ],
                                    [
                                        'attribute' => 'edad',
                                        // 'type' => d, // Establece el campo como de solo lectura
                                        'displayOnly' => 'true',
                                    ],

                                    [
                                        'attribute' => 'sexo',
                                        'type' => DetailView::INPUT_SELECT2,
                                        'widgetOptions' => [
                                            'data' => ['Masculino' => 'Masculino', 'Femenino' => 'Femenino'],
                                            'options' => ['prompt' => 'Seleccionar Sexo'],
                                        ],
                                    ],

                                    [
                                        'attribute' => 'estado_civil',
                                        'type' => DetailView::INPUT_SELECT2,
                                        'widgetOptions' => [
                                            'data' => [
                                                'Masculino' => [
                                                    'Soltero' => 'Soltero',
                                                    'Casado' => 'Casado',
                                                    'Separado' => 'Separado',
                                                    'Viudo' => 'Viudo',
                                                ],
                                                'Femenino' => [
                                                    'Soltero' => 'Soltera',
                                                    'Casado' => 'Casada',
                                                    'Separado' => 'Separada',
                                                    'Viudo' => 'Viuda',
                                                ],
                                            ],
                                            'options' => ['prompt' => 'Seleccionar Estado Civil'],
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
                            <div class="row">
                                <div class="col-md-6">
                                    <?= DetailView::widget([
                                        'model' => $model,
                                        'condensed' => true,
                                        'hover' => true,
                                        'buttons1' => '{update}',
                                        'mode' => DetailView::MODE_VIEW,
                                        'panel' => [
                                            'heading' => 'Información de Contacto',
                                            'type' => DetailView::TYPE_PRIMARY,
                                            //'before' => '<h3>Información de contacto de emergencia</h3>',
                                        ],
                                        'attributes' => [
                                            'email:email',
                                            'telefono',
                                            'colonia',
                                            'calle',
                                            'numero_casa',
                                            'codigo_postal',


                                        ],
                                        'formOptions' => [
                                            'action' => ['actualizar-informacion-contacto', 'id' => $model->id],
                                        ],
                                    ]) ?>

                                </div>
                                <div class="col-md-6">
                                    <?= DetailView::widget([
                                        'model' => $model,
                                        'condensed' => true,
                                        'hover' => true,
                                        'buttons1' => '{update}',

                                        'mode' => DetailView::MODE_VIEW,
                                        'panel' => [
                                            'heading' => 'Información de Contacto de emergencia',
                                            'type' => DetailView::TYPE_PRIMARY,
                                            //'before' => '<h3>Información de contacto de emergencia</h3>',
                                        ],
                                        'attributes' => [

                                            'nombre_contacto_emergencia',

                                            [
                                                'attribute' =>  'relacion_contacto_emergencia',
                                                'type' => DetailView::INPUT_SELECT2,
                                                'widgetOptions' => [
                                                    'data' => [
                                                        'Padre' => 'Padre',
                                                        'Madre' => 'Madre',
                                                        'Esposo/a' => 'Esposo/a',
                                                        'Hijo/a' => 'Hijo/a',
                                                        'Hermano/a' => 'Hermano/a',
                                                        'Compañero/a de trabajo' =>  'Compañero/a de trabajo',
                                                        'Tio/a' => 'Tio/a'



                                                    ],
                                                    'options' => ['prompt' => 'Seleccionar parentesco'],
                                                ],
                                            ],
                                            'telefono_contacto_emergencia',
                                        ],
                                        'formOptions' => [
                                            'action' => ['actualizar-informacion-contacto', 'id' => $model->id],
                                            'enableAjaxValidation' => true,

                                        ],
                                    ]) ?>
                                </div>
                            </div>


                            <?php $this->endBlock(); ?>
                            <!-- INFOLABORAL-->
                            <?php $this->beginBlock('info_l'); ?>
                            <br>

                            <?php
                            $juntaDirectorDireccion = JuntaGobierno::find()
                                ->where(['nivel_jerarquico' => 'Director'])
                                ->andWhere(['cat_direccion_id' => $model->informacionLaboral->cat_direccion_id])
                                ->one();


                            // Definir los atributos
                            $attributes = [
                                
                                [
                                    'attribute' => 'cat_departamento_id',
                                    'label' => 'Departamento',
                                    'value' => isset($model->informacionLaboral->catDepartamento) ? $model->informacionLaboral->catDepartamento->nombre_departamento : null,
                                    'type' => DetailView::INPUT_SELECT2,
                                    'widgetOptions' => [
                                        'data' => ArrayHelper::map(CatDepartamento::find()->all(), 'id', 'nombre_departamento'), // Obtener los departamentos para el dropdown
                                        'options' => ['prompt' => 'Seleccionar Departamento'],
                                    ],
                                ],
                                [
                                    'attribute' => 'cat_dpto_cargo_id',
                                    'label' => 'DPTO',
                                    'value' => isset($model->informacionLaboral->catDptoCargo) ? $model->informacionLaboral->catDptoCargo->nombre_dpto : null,
                                    'type' => DetailView::INPUT_SELECT2,
                                    'widgetOptions' => [
                                        'data' => ArrayHelper::map(CatDptoCargo::find()->all(), 'id', 'nombre_dpto'), // Obtener los departamentos para el dropdown
                                        'options' => ['prompt' => 'Selecciona el DPTO'],
                                    ],
                                ],
                                [
                                    'attribute' => 'horario_laboral_inicio',
                                    'type' => DetailView::INPUT_TIME,
                                    'format' => ['time', 'php:H:i'], // Formato de hora (HH:mm)
                                    'widgetOptions' => [
                                        'pluginOptions' => [
                                            'showMeridian' => false, // No mostrar AM/PM
                                        ],
                                    ],
                                ],
                                // Agregar la fila "Director de dirección"
                                [
                                    'label' => 'Director de dirección',
                                    'value' => $juntaDirectorDireccion ? $juntaDirectorDireccion->profesion . ' '.$juntaDirectorDireccion->empleado->nombre . ' ' . $juntaDirectorDireccion->empleado->apellido : null,
                                ],

                                [
                                    'attribute' => 'horario_laboral_fin',
                                    'type' => DetailView::INPUT_TIME,
                                    'format' => ['time', 'php:H:i'], // Formato de hora (HH:mm)
                                    'widgetOptions' => [
                                        'pluginOptions' => [
                                            'showMeridian' => false, // No mostrar AM/PM

                                        ],
                                    ],
                                ],



                               
                                [
                                    'attribute' => 'cat_puesto_id',
                                    'label' => 'Puesto',
                                    'value' => isset($model->informacionLaboral->catPuesto) ? $model->informacionLaboral->catPuesto->nombre_puesto : null,
                                    'type' => DetailView::INPUT_SELECT2,
                                    'widgetOptions' => [
                                        'data' => ArrayHelper::map(CatPuesto::find()->all(), 'id', 'nombre_puesto'), // Obtener los departamentos para el dropdown
                                        'options' => ['prompt' => 'Seleccionar Puesto'],
                                    ],
                                ],
                                [
                                    'attribute' => 'cat_tipo_contrato_id',
                                    'label' => 'Tipo de contrato',
                                    'value' => isset($model->informacionLaboral->catTipoContrato) ? $model->informacionLaboral->catTipoContrato->nombre_tipo : null,
                                    'type' => DetailView::INPUT_SELECT2,
                                    'widgetOptions' => [
                                        'data' => ArrayHelper::map(CatTipoContrato::find()->all(), 'id', 'nombre_tipo'), // Obtener los departamentos para el dropdown
                                        'options' => ['prompt' => 'Seleccionar Tipo de Contrato'],
                                    ],
                                ],
                                [
                                    'attribute' => 'cat_direccion_id',
                                    'label' => 'Dirección',
                                    'value' => isset($model->informacionLaboral->catDireccion) ? $model->informacionLaboral->catDireccion->nombre_direccion : null,
                                    'type' => DetailView::INPUT_SELECT2,
                                    'widgetOptions' => [
                                        'data' => ArrayHelper::map(CatDireccion::find()->all(), 'id', 'nombre_direccion'), // Obtener los departamentos para el dropdown
                                        'options' => ['prompt' => 'Seleccionar Direccion'],
                                    ],
                                ],
                                [
                                    'attribute' => 'junta_gobierno_id',
                                    'label' => 'Jefe o director a cargo',
                                    'value' => isset($model->informacionLaboral->juntaGobierno->empleado) ? $model->informacionLaboral->juntaGobierno->profesion . ' ' .$model->informacionLaboral->juntaGobierno->empleado->nombre . ' ' . $model->informacionLaboral->juntaGobierno->empleado->apellido : null,
                                    'type' => DetailView::INPUT_SELECT2,
                                    'widgetOptions' => [
                                        'data' => ArrayHelper::map(
                                            JuntaGobierno::find()
                                                ->where(['nivel_jerarquico' => 'Jefe de unidad']) // Filtrar por rol 'Jefe de unidad'
                                                ->andWhere(['cat_direccion_id' => $model->informacionLaboral->cat_direccion_id]) // Filtrar por la misma dirección
                                                ->all(),
                                            'id',
                                            function ($model) {
                                                return $model->profesion . ' ' .$model->empleado->nombre . ' ' . $model->empleado->apellido;
                                            }
                                        ),
                                        'options' => ['prompt' => 'Seleccionar Jefe o director a cargo'],
                                        'pluginOptions' => [
                                            'templateResult' => new JsExpression('function(data) {
                                                if (!data.id) { return data.text; }
                                                return "Jefe de dirección ' . ($model->informacionLaboral->catDireccion ? $model->informacionLaboral->catDireccion->nombre_direccion : '') . '\\n" + data.text;
                                            }'),
                                            'templateSelection' => new JsExpression('function(data) {
                                                return data.text;
                                            }'),
                                        ],
                                    ],
                                ],
                                
                                
                                





                                [
                                    'attribute' => 'fecha_ingreso',
                                    'type' => DetailView::INPUT_DATE,
                                    'format' => ['date', 'php:Y-m-d'],
                                    'displayFormat' => 'php:Y-m-d',
                                    'widgetOptions' => [
                                        'pluginOptions' => [
                                            'format' => 'yyyy-mm-dd',
                                            'autoclose' => true,
                                            'viewMode' => 'months', // o 'years'
                                        ]
                                    ],
                                ],





                            ];

                            ?>




                            <?= DetailView::widget([
                                'model' => $model->informacionLaboral,
                                'condensed' => true,
                                'hover' => true,
                                'mode' => DetailView::MODE_VIEW,
                                'panel' => [
                                    'heading' => 'Información Laboral ',
                                    'type' =>  DetailView::TYPE_PRIMARY
                                ],
                                'buttons1' => '{update}',

                                'attributes' => $attributes,

                                'formOptions' => [
                                    'action' => ['actualizar-informacion-laboral', 'id' => $model->id],
                                ],
                            ]); ?>

                            <?php $this->endBlock(); ?>


                            <?php


                            echo TabsX::widget([
                                'options' => ['class' => 'nav-tabs-custom'],
                                'items' => [
                                    [
                                        'label' => 'Información personal',
                                        'content' => $this->blocks['info_p'],
                                        'active' => $activeTab === 'info_p', // Activa si es la pestaña actual
                                    ],
                                    [
                                        'label' => 'Información de contacto',
                                        'content' => $this->blocks['info_c'],
                                        'active' => $activeTab === 'info_c',
                                    ],
                                    [
                                        'label' => 'Información laboral',
                                        'content' => $this->blocks['info_l'],
                                        'active' => $activeTab === 'info_l',
                                    ],
                                ],
                                'position' => TabsX::POS_ABOVE,
                                'align' => TabsX::ALIGN_CENTER,
                                'encodeLabels' => false,
                            ]);
                            ?>


                            <?php $this->beginBlock('expediente'); ?>

                            <div class="documento-form">

                                <?php $form = ActiveForm::begin(['action' => ['documento/create', 'empleado_id' => $model->id], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'narrow-form']]); ?>

                                <?= $form->field($documentoModel, 'cat_tipo_documento_id')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(CatTipoDocumento::find()->all(), 'id', 'nombre_tipo'),
                                    'language' => 'es',
                                    'options' => ['placeholder' => 'Seleccione el tipo de documento', 'id' => 'tipo-documento'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label('Tipo de Documento') ?>


                                <?= $form->field($documentoModel, 'nombre')->textInput([
                                    'maxlength' => true,
                                    'id' => 'nombre-archivo',
                                    'style' => 'display:none',
                                    'placeholder' => 'Ingrese el nombre del documento'
                                ])->label(false) ?>

                                <?php
                                $this->registerJs("
                                        $('#tipo-documento').change(function(){
                                            var tipoDocumentoId = $(this).val();
                                            var nombreArchivoInput = $('#nombre-archivo');

                                            // Obtener el nombre del tipo de documento seleccionado
                                            var tipoDocumentoNombre = $('#tipo-documento option:selected').text();

                                            // Verificar si se seleccionó 'OTRO'
                                            if (tipoDocumentoNombre == 'OTRO') {
                                                // Mostrar el campo de nombre y limpiar su valor
                                                nombreArchivoInput.show().val('').focus();
                                            } else {
                                                // Ocultar el campo de nombre y asignar el nombre del tipo de documento seleccionado
                                                nombreArchivoInput.hide().val(tipoDocumentoNombre);
                                            }
                                        });
                                    ");
                                ?>

                                <?= $form->field($documentoModel, 'ruta')->widget(FileInput::classname(), [
                                    'options' => ['accept' => 'file/*'],
                                    'pluginEvents' => [
                                        'fileclear' => "function() {
                                        $('#nombre-archivo').val('');
                                        $('#tipo-archivo').val('');
                                    }",
                                    ],
                                    'pluginOptions' => [
                                        'showUpload' => false,
                                    ],
                                ])->label('Archivo') ?>

                                <div class="form-group">
                                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                                </div>

                                <?php ActiveForm::end(); ?>

                            </div>

                            <?php
                            $searchModel = new DocumentoSearch();
                            $params = Yii::$app->request->queryParams;
                            $params[$searchModel->formName()]['empleado_id'] = $model->id;
                            $dataProvider = $searchModel->search($params);
                            ?>

                            <?php Pjax::begin(); ?>
                            <div class="card-container">
                                <?= GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'filterModel' => $searchModel,
                                    'options' => ['class' => 'grid-view'],
                                    'tableOptions' => ['class' => 'table table-simple table-striped table-bordered table-condensed borderless'], // Aquí agregamos la clase "table-simple"
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        [
                                            'attribute' => 'nombre',
                                            'value' => 'nombre',
                                            'options' => ['style' => 'width: 30%;'],
                                        ],
                                        [
                                            'attribute' => 'fecha_subida',
                                            'filter' => false,
                                            'options' => ['style' => 'width: 30%;'],
                                        ],
                                        [
                                            'class' => 'hail812\adminlte3\yii\grid\ActionColumn',
                                            'template' => '{view} {delete} {download}',
                                            'buttons' => [
                                                'view' => function ($url, $model) {
                                                    return Html::a('<i class="far fa-eye"></i>', ['documento/open', 'id' => $model->id], [
                                                        'target' => '_blank',
                                                        'title' => 'Ver archivo',
                                                        'class' => 'btn btn-info btn-xs',
                                                        'data-pjax' => "0"
                                                    ]);
                                                },
                                                'delete' => function ($url, $model) {
                                                    return Html::a('<i class="fas fa-trash"></i>', ['documento/delete', 'id' => $model->id, 'empleado_id' => $model->empleado_id], [
                                                        'title' => Yii::t('yii', 'Eliminar'),
                                                        'data-confirm' => Yii::t('yii', '¿Estás seguro de que deseas eliminar este elemento?'),
                                                        'data-method' => 'post',
                                                        'class' => 'btn btn-danger btn-xs',
                                                    ]);
                                                },
                                                'download' => function ($url, $model) {
                                                    return Html::a('<i class="fas fa-download"></i>', ['documento/download', 'id' => $model->id], [
                                                        'title' => 'Descargar archivo',
                                                        'class' => 'btn btn-success btn-xs',
                                                        'data-pjax' => "0"
                                                    ]);
                                                },
                                            ],
                                            'options' => ['style' => 'width: 15%;'], //ancho de la columna
                                        ],
                                    ],
                                    'summaryOptions' => ['class' => 'summary mb-2'],
                                    'pager' => [
                                        'class' => 'yii\bootstrap4\LinkPager',
                                    ],
                                ]); ?>
                            </div>


                            <?php Pjax::end(); ?>
                            <?php $this->endBlock(); ?>



                            <?php $this->endBlock(); ?>






                        </div>
                        <!--.col-md-12-->

                    </div>
                    <!--.row-->


                </div>
                <!--.card-body-->
                <?php echo TabsX::widget([
                    'options' => ['class' => 'custom-tabs-2'],
                    'items' => [
                        [
                            'label' => 'Información',
                            'content' => $this->blocks['datos'],
                            'active' => true,


                        ],
                        [
                            'label' => 'Expediente',
                            'content' => $this->blocks['expediente'],


                        ],


                    ],
                    'position' => TabsX::POS_ABOVE,
                    'align' => TabsX::ALIGN_CENTER,
                    // 'bordered'=>true,
                    'encodeLabels' => false

                ]);

                ?>

            </div>
            <!--.card-->


        </div>
        <!--.col-md-10-->

    </div>
    <!--.row-->
</div>
<!--.container-fluid-->