
<?php
//IMPORTACIONES
use yii\helpers\Html;
use froala\froalaeditor\FroalaEditorWidget;
use kartik\file\FileInput;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\form\ActiveForm;
use yii\jui\DatePicker;

use app\models\DocumentoMedicoSearch;

?>
<div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-info text-white">
                                            <h3>Documentos médicos del empleado</h3>
                                            <?php
                                            //PERMISO PARA PODER REALIZAR ACCIONES DE LOS REGISTROS DE LOS DOCUMENTOS MEDICOS
                                            if (Yii::$app->user->can('acciones-documentos-medicos')) { ?>

                                                <button type="button" id="toggle-expediente-medico-button" class="btn btn-dark float-right">
                                                    <i class="fa fa-upload"></i>&nbsp; &nbsp; Agregar nuevo archivo
                                                </button>

                                            <?php } ?>
                                        </div>

                                        <script>
                                            document.getElementById('toggle-expediente-medico-button').addEventListener('click', function() {
                                                var expedienteMedicoContent = document.getElementById('expediente-medico-content');
                                                if (expedienteMedicoContent.style.display === 'none') {
                                                    expedienteMedicoContent.style.display = 'block';
                                                    this.innerHTML = '<i class="fa fa-ban"></i> &nbsp; Cancelar';
                                                } else {
                                                    expedienteMedicoContent.style.display = 'none';
                                                    this.innerHTML = '<i class="fa fa-upload"></i> &nbsp; &nbsp; Agregar nuevo archivo';
                                                }
                                            });
                                        </script>

                                        <div class="card-body" id="expediente-medico-content" style="display: none;">
                                            <?php 
                                            //FORMULARIO PARA SUBIR EL ARCHIVO MEDICO Y CREAR UN NUEVO REGISTRO
                                            $form = ActiveForm::begin([
                                                'action' => ['documento-medico/create', 'empleado_id' => $model->id],
                                                'options' => ['enctype' => 'multipart/form-data', 'class' => 'narrow-form']
                                            ]); ?>

                                            <div class="form-group">
                                                <?= $form->field($documentoMedicoModel, 'nombre')->textInput([
                                                    'maxlength' => true,
                                                    'placeholder' => 'Ingrese el nombre del documento'
                                                ])->label('Nombre del documento') ?>
                                            </div>

                                            <div class="form-group">
                                                <?= $form->field($documentoMedicoModel, 'observacion')->widget(FroalaEditorWidget::className(), [
                                                    'clientOptions' => [
                                                        'toolbarInline' => false,
                                                        'theme' => 'royal',
                                                        'language' => 'es',
                                                        'height' => 150,
                                                        'pluginsEnabled' => [
                                                            'align', 'charCounter', 'codeBeautifier', 'codeView', 'colors',
                                                            'draggable', 'entities', 'fontFamily',
                                                            'fontSize', 'fullscreen', 'inlineStyle',
                                                            'lineBreaker', 'link', 'lists', 'paragraphFormat', 'paragraphStyle',
                                                            'quickInsert', 'quote', 'save', 'table', 'url', 'wordPaste'
                                                        ]
                                                    ]
                                                ]) //EXTENSION QUE PERMITE QUE EL CAMPO DE TEXTO PUEDA MOSTRARSE COMO UN EDITOR DE TEXTO FUNCIONAL ?>
                                            </div>

                                            <div class="form-group">
                                                <?= $form->field($documentoMedicoModel, 'ruta')->widget(FileInput::classname(), [
                                                    //'options' => ['accept' => 'pdf'],
                                                    'pluginOptions' => [
                                                        'showUpload' => false,
                                                        'showCancel' => false,
                                                        'allowedFileExtensions' => ['pdf', 'jpg', 'xlsx'],

                                                    ],
                                                ])->label('Archivo') ?>
                                            </div>

                                            <div class="form-group">
                                                <?= Html::submitButton('Subir archivo <i class="fa fa-upload"></i>', ['class' => 'btn btn-warning float-right']) ?>
                                            </div>

                                            <?php ActiveForm::end(); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">

                                    <div class="col-md-12 mt-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <?php
                                                $searchModel = new DocumentoMedicoSearch();
                                                $params = Yii::$app->request->queryParams;
                                                $params[$searchModel->formName()]['empleado_id'] = $model->id;
                                                $dataProvider = $searchModel->search($params);
                                                ?>

                                                <?php Pjax::begin(); ?>

                                                <?= GridView::widget([
                                                    'dataProvider' => $dataProvider,
                                                    'filterModel' => $searchModel,
                                                    'columns' => [
                                                        ['class' => 'yii\grid\SerialColumn'],
                                                        [
                                                            'attribute' => 'nombre',
                                                            'value' => 'nombre',
                                                            'filter' => false,
                                                            'options' => ['style' => 'width: 20%;'],
                                                        ],

                                                        [
                                                            'attribute' => 'fecha_subida',
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                setlocale(LC_TIME, "es_419.UTF-8");
                                                                return strftime('%A, %d de %B de %Y', strtotime($model->fecha_subida));
                                                            },
                                                            'filter' => DatePicker::widget([
                                                                'model' => $searchModel,
                                                                'attribute' => 'fecha_subida',
                                                                'language' => 'es',
                                                                'dateFormat' => 'php:Y-m-d',
                                                                'options' => [
                                                                    'class' => 'form-control',
                                                                    'autocomplete' => 'off',
                                                                ],
                                                                'clientOptions' => [
                                                                    'changeYear' => true,
                                                                    'changeMonth' => true,
                                                                    'yearRange' => '-100:+0',
                                                                ],
                                                            ]),
                                                        ],
                                                        [
                                                            'attribute' => 'observacion',
                                                            'format' => 'html',
                                                            'value' => function ($model) {
                                                                return \yii\helpers\Html::decode($model->observacion);
                                                            },
                                                            'filter' => false,
                                                            'options' => ['style' => 'width: 51%;'],
                                                        ],
                                                        [
                                                            //ACCIONES QUE SE PUEDEN REALIZAR CON LOS REGISTROS DE LOS DOCUMENTOS MEDICOS
                                                            // SE VERIFICA LOS TIPOS DE ARCHIVO QUE PUEDE SER VISIBLES DESDE EL NAVEGADOR Y LOS 
                                                            //QUE NO, SOLO SE PERMITE QUE SEAN DESCARGADOS
                                                            'class' => 'hail812\adminlte3\yii\grid\ActionColumn',
                                                            //'template' => '{view} {delete} {download}',
                                                            'template' => Yii::$app->user->can('acciones-documentos-medicos') ? '{view} {delete} {download}' : '{view} {download}',

                                                            'buttons' => [
                                                                'view' => function ($url, $model) {
                                                                    // Verificar si el archivo es PDF o imagen
                                                                    $extensionsToShowEye = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
                                                                    $fileExtension = pathinfo($model->ruta, PATHINFO_EXTENSION);

                                                                    if (in_array(strtolower($fileExtension), $extensionsToShowEye)) {
                                                                        return Html::a('<i class="far fa-eye"></i>', ['documento-medico/open', 'id' => $model->id], [
                                                                            'target' => '_blank',
                                                                            'title' => 'Ver archivo',
                                                                            'class' => 'btn btn-info btn-xs',
                                                                            'data-pjax' => "0"
                                                                        ]);
                                                                    } else {
                                                                        return '';
                                                                    }
                                                                },
                                                                'delete' => function ($url, $model) {
                                                                    return Html::a('<i class="fas fa-trash"></i>', ['documento-medico/delete', 'id' => $model->id, 'empleado_id' => $model->empleado_id], [
                                                                        'title' => Yii::t('yii', 'Eliminar'),
                                                                        'data-confirm' => Yii::t('yii', '¿Estás seguro de que deseas eliminar este elemento?'),
                                                                        'data-method' => 'post',
                                                                        'class' => 'btn btn-danger btn-xs',
                                                                    ]);
                                                                },
                                                                'download' => function ($url, $model) {
                                                                    return Html::a('<i class="fas fa-download"></i>', ['documento-medico/download', 'id' => $model->id], [
                                                                        'title' => 'Descargar archivo',
                                                                        'class' => 'btn btn-success btn-xs',
                                                                        'data-pjax' => "0"
                                                                    ]);
                                                                },
                                                            ],

                                                        ],
                                                    ],
                                                    'summaryOptions' => ['class' => 'summary mb-2'],
                                                    'pager' => [
                                                        'class' => 'yii\bootstrap4\LinkPager',
                                                    ],
                                                    'tableOptions' => ['class' => 'no-style-gridview'],
                                                    'rowOptions' => function ($model, $key, $index, $grid) {
                                                        return ['class' => 'no-style-gridview'];
                                                    },
                                                ]); ?>

                                                <?php Pjax::end(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           