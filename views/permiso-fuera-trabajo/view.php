<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\Alert;
use app\models\Empleado;
use app\models\JuntaGobierno;
/* @var $this yii\web\View */
/* @var $model app\models\PermisoFueraTrabajo */

$this->title = $model->id;
//$this->params['breadcrumbs'][] = ['label' => 'Permiso Fuera Trabajos', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="container-fluid">
    <div class="card">
    <div class="card-header bg-info text-white">
                    <h2>PERMISO FUERA TRABAJO</h2>
                    <?php if (Yii::$app->user->can('crear-formatos-incidencias-empleados')) { ?>

<?= Html::a('<i class="fa fa-chevron-left"></i> Volver', ['empleado/index'], [
'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',

'encode' => false, // Para que el HTML dentro del enlace no se escape
]) ?>

<?php }


else{ ?>
<?= Html::a('<i class="fa fa-chevron-left"></i> Volver', ['site/portalempleado'], [
'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',

'encode' => false, // Para que el HTML dentro del enlace no se escape
]) ?>

<?php }?>

                       
                      
                          <?php


$usuarioId = Yii::$app->user->identity->id;

$empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

if ($empleado) {
    $model->empleado_id = $empleado->id;

    $juntaGobierno = JuntaGobierno::find()->where(['empleado_id' => $empleado->id])->one();


        if ($juntaGobierno && ($juntaGobierno->nivel_jerarquico === 'Jefe de unidad' || $juntaGobierno->nivel_jerarquico === 'Director')){
            echo Html::a('<i class="fa fa-file-excel" aria-hidden="true"></i> Exportar Excel', ['export-segundo-caso', 'id' => $model->id], ['class' => 'btn btn-success ']);
            echo Html::a('<i class="fa fa-file-pdf"></i> Exportar PDF', ['export-pdf', 'id' => $model->id], ['class' => 'btn btn-danger ml-3']);
        } else {
            echo Html::a('<i class="fa fa-file-excel" aria-hidden="true"></i> Exportar Excel', ['export', 'id' => $model->id], ['class' => 'btn btn-success']);
            echo Html::a('<i class="fa fa-file-pdf"></i> Exportar PDF', ['export-pdf', 'id' => $model->id], ['class' => 'btn btn-danger ml-3']);
        }
        
} else {
    Yii::$app->session->setFlash('error', 'No se pudo encontrar el empleado asociado al usuario actual.');
    return $this->redirect(['index']);
}
?>



                          
                
                </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                   

                    <?php 
    foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
        echo Alert::widget([
            'options' => ['class' => 'alert-' . $type],
            'body' => $message,
        ]);
    }
    ?>
                  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
       // 'empleado_id',
        'solicitud_id',
       // [
         //   'label' => 'Número de Empleado',
//            'value' => function ($model) {
  //              return $model->empleado->numero_empleado; 
    //        },
      //  ],
 //       [
   //         'label' => 'Nombre',
     //       'value' => function ($model) {
       //         return $model->empleado->nombre; 
//            },
  //      ],
        [
            'label' => 'Motivo',
            'value' => function ($model) {
                return $model->motivoFechaPermiso->motivo; 
            },
        ],
        [
            'label' => 'Hora de Salida',
            'attribute' => 'hora_salida',
            'value' => function ($model) {
                $hora = date("g:i A", strtotime($model->hora_salida));
                return $hora;
            },
        ],
        [
            'label' => 'Hora de Regreso',
            'attribute' => 'hora_regreso',
            'value' => function ($model) {
                $hora = date("g:i A", strtotime($model->hora_regreso));
                return $hora;
            },
        ],
        [
            'label' => 'Fecha que Repondrá',
            'attribute' => 'fecha_a_reponer',
            'value' => function ($model) {
                setlocale(LC_TIME, "es_419.UTF-8");
                $fechaAreponer = strtotime($model->fecha_a_reponer);
                $fechaFormateada = strftime('%A, %B %d, %Y', $fechaAreponer);
                setlocale(LC_TIME, null);
                return $fechaFormateada;
            },
        ],
        [
            'label' => 'Horario de fecha que Repondrá',
            'attribute' => 'horario_fecha_a_reponer',
            'value' => function ($model) {
                $hora = date("g:i A", strtotime($model->horario_fecha_a_reponer));
                return $hora;
            },
        ],
        [
            'label' => 'Fecha de Permiso',
            'value' => function ($model) {
                setlocale(LC_TIME, "es_419.UTF-8");
                $fechaPermiso = strtotime($model->motivoFechaPermiso->fecha_permiso);
                $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                setlocale(LC_TIME, null);
                return $fechaFormateada;
            },
        ],
    ],
]) ?>

                </div>
                <!--.col-md-12-->
            </div>
            <!--.row-->
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>