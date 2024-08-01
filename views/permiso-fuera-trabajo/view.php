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
<div class="row justify-content-center">
<div class="col-md-10">
    <div class="card">
    <div class="card-header bg-info text-white">
                    <h2>PERMISO FUERA TRABAJO</h2>
                    <?php
// Obtener el ID del usuario actual
$usuarioActual = Yii::$app->user->identity;
$empleadoActual = $usuarioActual->empleado;
$juntaGobierno = JuntaGobierno::find()->where(['empleado_id' => $model->empleado_id])->one();

// Comparar el ID del empleado actual con el ID del empleado para el cual se está creando o viendo el registro
if ($empleadoActual->id === $empleado->id) {
    // El empleado está viendo su propio registro
    echo Html::a('<i class="fa fa-home"></i> Inicio', ['site/portalempleado'], [
        'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
        'encode' => false,
    ]);

    // Verificar el nivel jerárquico del empleado para sus propios registros
    if ($juntaGobierno && ($juntaGobierno->nivel_jerarquico === 'Jefe de unidad' || $juntaGobierno->nivel_jerarquico === 'Director')) {
        echo Html::a('<i class="fa fa-print" aria-hidden="true"></i> Vista Previa de Impresión', ['export-html-segundo-caso', 'id' => $model->id], ['class' => 'btn btn-success', 'target' => '_blank']);
    } else {
        echo Html::a('<i class="fa fa-print" aria-hidden="true"></i> Vista Previa de Impresión', ['export-html', 'id' => $model->id], ['class' => 'btn btn-dark', 'target' => '_blank']);
    }
} else {
    // El empleado está creando o viendo un registro para otro empleado
    if (Yii::$app->user->can('crear-formatos-incidencias-empleados')) {
        echo Html::a('<i class="fa fa-chevron-left"></i> Volver', ['empleado/index'], [
            'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
            'encode' => false,
        ]);
    } else {
        echo Html::a('<i class="fa fa-home"></i> Inicio', ['site/portalempleado'], [
            'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
            'encode' => false,
        ]);
    }

    // Verificar el nivel jerárquico del empleado para el cual se está creando el registro
    if ($juntaGobierno && ($juntaGobierno->nivel_jerarquico === 'Jefe de unidad' || $juntaGobierno->nivel_jerarquico === 'Director')) {
        echo Html::a('<i class="fa fa-print" aria-hidden="true"></i> Vista Previa de Impresión', ['export-html-segundo-caso', 'id' => $model->id], ['class' => 'btn btn-success', 'target' => '_blank']);
    } else {
        echo Html::a('<i class="fa fa-print" aria-hidden="true"></i> Vista Previa de Impresión', ['export-html', 'id' => $model->id], ['class' => 'btn btn-dark', 'target' => '_blank']);
    }
}

// Obtener el ID del usuario actual
$usuarioId = Yii::$app->user->identity->id;

// Obtener el empleado actual asociado al usuario
$empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

if ($empleado) {
    // Asignar el ID del empleado al modelo
    $model->empleado_id = $empleado->id;

    // Obtener la Junta de Gobierno del empleado para el cual se está creando el registro

    // Verificar el nivel jerárquico del empleado para el cual se está creando el registro
    if ($juntaGobierno && ($juntaGobierno->nivel_jerarquico === 'Jefe de unidad' || $juntaGobierno->nivel_jerarquico === 'Director')) {
      //  echo Html::a('<i class="fa fa-print" aria-hidden="true"></i> Vista Previa de Impresión', ['export-html-segundo-caso', 'id' => $model->id], ['class' => 'btn btn-success ', 'target' => '_blank']);
    } else {
        //echo Html::a('<i class="fa fa-print" aria-hidden="true"></i> Vista Previa de Impresión', ['export-html', 'id' => $model->id], ['class' => 'btn btn-dark ', 'target' => '_blank']);
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
    'attribute' => 'motivo',
    'format' => 'html',
    'value' => function ($model) {
        return \yii\helpers\Html::decode($model->motivoFechaPermiso->motivo);
    },
    'filter' => false,
    'options' => ['style' => 'width: 65%;'],
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
</div>
</div>