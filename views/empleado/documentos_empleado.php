<?php

use yii\helpers\Html;
use froala\froalaeditor\FroalaEditorWidget;

use kartik\file\FileInput;
use yii\grid\GridView;
use yii\widgets\Pjax;

use kartik\form\ActiveForm;
use yii\jui\DatePicker;

use yii\helpers\ArrayHelper;

use app\models\DocumentoSearch;

use kartik\select2\Select2;
use app\models\CatTipoDocumento;

?>
<div class="row">

<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-info text-white">
            <h3>Documentos del empleado</h3>
            <button type="button" id="toggle-expediente-button" class="btn btn-dark float-right">
                <i class="fa fa-upload"></i>&nbsp; &nbsp; Agregar nuevo archivo
            </button>

        </div>



        <script>
            document.getElementById('toggle-expediente-button').addEventListener('click', function() {
                var expedienteContent = document.getElementById('expediente-content');
                if (expedienteContent.style.display === 'none') {
                    expedienteContent.style.display = 'block';
                    this.innerHTML = '<i class="fa fa-ban"></i> &nbsp;  Cancelar';
                } else {
                    expedienteContent.style.display = 'none';
                    this.innerHTML = '<i class="fa fa-upload"></i> &nbsp; &nbsp; Agregrar nuevo archivo';
                }
            });
        </script>

        <div class="card-body" id="expediente-content" style="display: none;">

            <?php $form = ActiveForm::begin([
                'action' => ['documento/create', 'empleado_id' => $model->id],
                'options' => ['enctype' => 'multipart/form-data', 'class' => 'narrow-form']
            ]); ?>

            <div class="form-group">
                <?= $form->field($documentoModel, 'cat_tipo_documento_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(CatTipoDocumento::find()->all(), 'id', 'nombre_tipo'),
                    'language' => 'es',
                    'options' => ['placeholder' => 'Seleccione el tipo de documento', 'id' => 'tipo-documento'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'pluginEvents' => [
                        'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }",
                        'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '0px'); }",
                    ],
                ])->label('Tipo de Documento') ?>
            </div>

            <div class="form-group">
                <?= $form->field($documentoModel, 'nombre')->textInput([
                    'maxlength' => true,
                    'id' => 'nombre-archivo',
                    'style' => 'display:none',
                    'placeholder' => 'Ingrese el nombre del documento'
                ])->label(false) ?>
            </div>
            <div class="form-group">





                <?= $form->field($documentoModel, 'observacion')->widget(FroalaEditorWidget::className(), [

                    'clientOptions' => [
                        'toolbarInline' => false,
                        'theme' => 'royal', // optional: dark, red, gray, royal
                        'language' => 'es', // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                        'height' => 150,
                        'pluginsEnabled' => [
                            'align', 'charCounter', 'codeBeautifier', 'codeView', 'colors',
                            'draggable', 'entities', 'fontFamily',
                            'fontSize', 'fullscreen', 'inlineStyle',
                            'lineBreaker', 'link', 'lists', 'paragraphFormat', 'paragraphStyle',
                            'quickInsert', 'quote', 'save', 'table', 'url', 'wordPaste'
                        ]
                    ]
                ]) ?>


            </div>

            <div class="form-group">
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
                        'showCancel' => false,
                    ],
                ])->label('Archivo') ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Subir archivo <i class="fa fa-upload"></i>', ['class' => 'btn btn-warning float-right', 'id' => 'save-button-personal']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div class="col-md-12 mt-3">
    <div class="card">

        <div class="card-body">
            <?php
            $searchModel = new DocumentoSearch();
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

                        'options' => ['style' => 'width: 30%;'],
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
                        'options' => ['style' => 'width: 30%;'],

                    ],
                    [
                        'attribute' => 'observacion',
                        'format' => 'html',
                        'value' => function ($model) {
                            return \yii\helpers\Html::decode($model->observacion);
                        },
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

