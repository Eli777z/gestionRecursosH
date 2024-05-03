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
use kartik\tabs\TabsX;
use yii\web\JsExpression;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\Empleado */

// Obtén el valor del parámetro de la URL (si está presente)
//$activeTab = Yii::$app->request->get('tab', 'info_p');

$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);

$this->title = $model->id;
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
                                    'type' =>  DetailView::TYPE_PRIMARY

                                ],
                                'attributes' => [
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
                                    'enableAjaxValidation'=>true,

                                ],
                            ]) ?>
                             </div>
</div>


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
                                    'type' =>  DetailView::TYPE_PRIMARY
                                ],
                                'buttons1' => '{update}',

                                'attributes' => [
                                    //'numero_trabajador',
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
                                        'attribute' => 'fecha_ingreso',
                                        'type' => DetailView::INPUT_DATE,
                                        'format' => ['date', 'php:Y-m-d'], 
                                        'displayFormat' => 'php:Y-m-d',  'widgetOptions' => [
                                            'pluginOptions' => [
                                                'format' => 'yyyy-mm-dd',                                                 'autoclose' => true,
                                            ]
                                        ],
                                    ],

                                ],
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




                            <?php $this->endBlock(); ?>
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
                                    //    'content' => $this->blocks['expediente'],


                                    ],


                                ],
                                'position'=>TabsX::POS_ABOVE,
                                'align'=>TabsX::ALIGN_CENTER,
                               // 'bordered'=>true,
                                'encodeLabels'=>false

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