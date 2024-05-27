<?php

use yii\helpers\Html;

use yii\widgets\Pjax;
use kartik\select2\Select2;
use yii\grid\GridView;
use yii\jui\DatePicker;
use kartik\daterange\DateRangePicker;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SolicitudSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Solicitudes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header bg-primary text-white"> <!-- Agregando las clases bg-primary y text-white -->
                    <h3><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="card-body">
         
                    <?php

                    Pjax::begin(['id' => 'pjax-container']);
                    echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'empleado_id',
            'label' => 'Empleado',
            'value' => function ($model) {
                return $model->empleado ? $model->empleado->nombre . ' ' . $model->empleado->apellido : 'N/A';
            },
            'filter' => Select2::widget([
                'model' => $searchModel,
                'attribute' => 'empleado_id',
                'data' => \yii\helpers\ArrayHelper::map(\app\models\Empleado::find()->all(), 'id', function($model) {
                    return $model->nombre . ' ' . $model->apellido;
                }), 
                'options' => ['placeholder' => 'Seleccione un empleado'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'theme' => Select2::THEME_BOOTSTRAP, // Esto aplicará el estilo de Bootstrap al Select2
                'pluginEvents' => [
                    'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }", // Aquí se personaliza el icono de borrar
                ],
                'pluginEvents' => [
                    'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '2px'); }", // Agregar un margen izquierdo
                ],
            ]),
        ],
        [
            'attribute' => 'fecha_creacion',
            'format' => 'raw',
            'value' => function ($model) {
                setlocale(LC_TIME, "es_419.UTF-8");
                return strftime('%A, %d de %B de %Y', strtotime($model->fecha_creacion));
            },
            'filter' => DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'fecha_creacion',
                'language' => 'es',
    'dateFormat' => 'php:Y-m-d',
    'options' => ['class' => 'form-control',
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
            'attribute' => 'status',
            'format' => 'raw',
            'label' => 'Estatus',
            'value' => function ($model) {
                $status = '';
                switch ($model->status) {
                    case 'Aprobado':
                        $status = '<span class="badge badge-success">' . $model->status . '</span>';
                        break;
                    case 'En Proceso':
                        $status = '<span class="badge badge-warning">' . $model->status . '</span>';
                        break;
                    case 'Rechazado':
                        $status = '<span class="badge badge-danger">' . $model->status . '</span>';
                        break;
                    default:
                        $status = '<span class="badge badge-secondary">' . $model->status . '</span>';
                        break;
                }
                return $status;
            },
            'filter' => Html::activeDropDownList($searchModel, 'status', ['Aprobado' => 'Aprobado', 'En Proceso' => 'En Proceso', 'Rechazado' => 'Rechazado'], ['class' => 'form-control', 'prompt' => 'Todos']),
        ],
        [
            'attribute' => 'comentario',
            'format' => 'ntext',
            // Aquí se deshabilita el filtro
            'filter' => false,
        ],
       

        [
            'attribute' => 'nombre_formato',
            'label' => 'Tipo de solicitud',
            'value' => function ($model) {
                return $model->nombre_formato;
            },
            'filter' => Select2::widget([
                'model' => $searchModel,
                'attribute' => 'nombre_formato',
                'data' => \yii\helpers\ArrayHelper::map(\app\models\Solicitud::find()->select(['nombre_formato'])->distinct()->all(), 'nombre_formato', 'nombre_formato'), // Asegúrate de que 'nombre_formato' sea el nombre correcto del campo en tu tabla Solicitud
                'options' => ['placeholder' => 'Seleccione un tipo de solicitud'],
                'pluginOptions' => [
                    'allowClear' => true

                    
                ],
                'theme' => Select2::THEME_BOOTSTRAP, // Esto aplicará el estilo de Bootstrap al Select2
                'pluginEvents' => [
                    'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }", // Aquí se personaliza el icono de borrar
                ],
                'pluginEvents' => [
                    'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '2px'); }", // Agregar un margen izquierdo
                ],
            ]),
        ],

        ['class' => 'hail812\adminlte3\yii\grid\ActionColumn'],
    ],
    'summaryOptions' => ['class' => 'summary mb-2'],
    'pager' => [
        'class' => 'yii\bootstrap4\LinkPager',
    ],
    // Agregar el botón de reinicio fuera del GridView
   
    
]); 
Pjax::end();

// Script para actualizar el contenedor Pjax cada 30 segundos
$script = <<< JS
    setInterval(function(){
        $.pjax.reload({container:'#pjax-container'});
    }, 20000);
JS;

// Registrar el script en la vista
$this->registerJs($script);
 ?>


                </div>
                <!--.card-body-->
            </div>
            <!--.card-->
        </div>
        <!--.col-md-12-->
    </div>
    <!--.row-->
</div>
