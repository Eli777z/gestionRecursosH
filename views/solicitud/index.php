<?php

use yii\helpers\Html;

use yii\widgets\Pjax;
use kartik\select2\Select2;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\web\View;
use kartik\daterange\DateRangePicker;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SolicitudSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Historial de solicitudes de incidencias';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header gradient-info text-white">
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
                'theme' => Select2::THEME_KRAJEE_BS3,
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
        
        
        
     //   [
       ///     'attribute' => 'status',
          //  'format' => 'raw',
        //    'label' => 'Estatus',
          //  'value' => function ($model) {
            //    $status = '';
          //      switch ($model->status) {
            //        case 'Aprobado':
              //          $status = '<span class="badge badge-success">' . $model->status . '</span>';
   //                    break;
 // /                  case 'En Proceso':
     //                   $status = '<span class="badge badge-warning">' . $model->status . '</span>';
       //                 break;
         ///           case 'Rechazado':
            //            $status = '<span class="badge badge-danger">' . $model->status . '</span>';
   //                     break;
  //                default:
    //                    $status = '<span class="badge badge-secondary">' . $model->status . '</span>';
      //                  break;
        //        }
          ///      return $status;
          //  },
 //           'filter' => Html::activeDropDownList($searchModel, 'status', ['Aprobado' => 'Aprobado', 'En Proceso' => 'En Proceso', 'Rechazado' => 'Rechazado'], ['class' => 'form-control', 'prompt' => 'Todos']),
   //     ],
     //   [
       //     'attribute' => 'comentario',
         //   'format' => 'ntext',
       //     'filter' => false,
      //  ],
       

        [
            'attribute' => 'nombre_formato',
            'label' => 'Tipo de solicitud',
            'value' => function ($model) {
                return $model->nombre_formato;
            },
            'filter' => Select2::widget([
                'model' => $searchModel,
                'attribute' => 'nombre_formato',
                'data' => \yii\helpers\ArrayHelper::map(\app\models\Solicitud::find()->select(['nombre_formato'])->distinct()->all(), 'nombre_formato', 'nombre_formato'), 
                'options' => ['placeholder' => 'Seleccione un tipo de solicitud'],
                'pluginOptions' => [
                    'allowClear' => true

                    
                ],
                'theme' => Select2::THEME_KRAJEE_BS3,
            ]),
        ],

        ['class' => 'hail812\adminlte3\yii\grid\ActionColumn'],
    ],
    'summaryOptions' => ['class' => 'summary mb-2'],
    'pager' => [
        'class' => 'yii\bootstrap4\LinkPager',
    ],
   
    
]); 
Pjax::end();

$script = <<< JS
    setInterval(function(){
        $.pjax.reload({container:'#pjax-container'});
    }, 20000);
JS;

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
