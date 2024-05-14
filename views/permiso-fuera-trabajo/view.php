<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PermisoFueraTrabajo */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Permiso Fuera Trabajos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <p>
                        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </p>
                    <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'empleado_id',
        'solicitud_id',
        [
            'label' => 'Motivo',
            'value' => function ($model) {
                return $model->motivoFechaPermiso->motivo; // Reemplaza "nombre_del_atributo_del_motivo" con el nombre del atributo que deseas mostrar
            },
        ],
        [
            'attribute' => 'fecha_salida',
            'value' => function ($model) {
                setlocale(LC_TIME, "es_419");
                $fechaSalida = strtotime($model->fecha_salida);
                $fechaFormateada = strftime('%A, %B %d, %Y', $fechaSalida);
                setlocale(LC_TIME, null);
                return $fechaFormateada;
            },
        ],
        [
            'attribute' => 'fecha_regreso',
            'value' => function ($model) {
                setlocale(LC_TIME, "es_419");
                $fechaRegreso = strtotime($model->fecha_regreso);
                $fechaFormateada = strftime('%A, %B %d, %Y', $fechaRegreso);
                setlocale(LC_TIME, null);
                return $fechaFormateada;
            },
        ],
        
       // 'fecha_regreso',
        //'fecha_a_reponer',
        [
            'attribute' => 'fecha_a_reponer',
            'value' => function ($model) {
                setlocale(LC_TIME, "es_419");
                $fechaAreponer = strtotime($model->fecha_a_reponer);
                $fechaFormateada = strftime('%A, %B %d, %Y', $fechaAreponer);
                setlocale(LC_TIME, null);
                return $fechaFormateada;
            },
        ],
        [
            'attribute' => 'horario_fecha_a_reponer',
            'value' => function ($model) {
                // Obtenemos la hora en formato AM/PM con minutos
                $hora = date("g:i A", strtotime($model->horario_fecha_a_reponer));
                return $hora;
            },
        ],
        'nota:ntext',
        [
            'label' => 'Fecha de Permiso',
            'value' => function ($model) {
               
                setlocale(LC_TIME, "es_419");
                
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