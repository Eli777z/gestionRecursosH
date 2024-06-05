<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\Alert;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\models\Solicitud */
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);

$this->title = $model->id;


$this->params['breadcrumbs'][] = ['label' => 'Solicitudes', 'url' => ['solicitud/index']];

if ($empleadoId !== null) {
   
    $this->params['breadcrumbs'][] = ['label' => 'Empleado ' . $empleadoId, 'url' => ['empleado/view', 'id' => $empleadoId]];
}
$this->params['breadcrumbs'][] = ['label' => 'Solicitud ' . $model->id];

\yii\web\YiiAsset::register($this);
?>
<div class="container-fluid">
    <div class="row justify-content-center"> 
        <div class="col-md-8">
            <div class="card">
            <div class="card-header gradient-info text-white">
                    <div class="d-flex justify-content-between"> 
                        <h1> <?= $model->nombre_formato?></h1>
                    <?php /* ?>
<div class="form-group mr-2">
    <?= Html::beginForm(['aprobar-solicitud', 'id' => $model->id], 'post', ['class' => 'form-inline']) ?>
        <?= Html::label('Añadir Comentarios:', null, ['class' => 'control-label']) ?>
        <?= Html::textInput('comentario', $model->comentario, ['class' => 'form-control']) ?>
    <?= Html::endForm() ?>
</div>

<div class="form-group mr-2 mb-2">
    <?php if ($model->nombre_formato == 'CAMBIO PERIODO VACACIONAL'): ?>
        <?= Html::beginForm(['aprobar-cambio-periodo-vacacional', 'id' => $model->id], 'post', ['class' => 'form-inline']) ?>
        <?= Html::submitButton(Html::tag('i', '  Aprobar', ['class' => 'fas fa-check']), ['name' => 'status', 'value' => 'Aprobado', 'class' => 'btn btn-primary', 'title' => 'Aceptar']) ?>
        <?= Html::endForm() ?>
    <?php else: ?>
        <?= Html::beginForm(['aprobar-solicitud', 'id' => $model->id], 'post', ['class' => 'form-inline']) ?>
        <?= Html::submitButton(Html::tag('i', '  Aprobar', ['class' => 'fas fa-check']), ['name' => 'status', 'value' => 'Aprobado', 'class' => 'btn btn-success', 'title' => 'Aceptar']) ?>
        <?= Html::endForm() ?>
    <?php endif; ?>
</div>
<?php 




                        <div class="form-group mr-2 mb-2">
                            <?= Html::submitButton(Html::tag('i', '  Rechazar', ['class' => 'fas fa-times']), ['name' => 'status', 'value' => 'Rechazado', 'class' => 'btn btn-danger', 'title' => 'Rechazar']) ?>
                        </div>
                        
                        <?= Html::endForm() ?>
                    </div>

                    */ ?>
                </div>
            </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
                                echo Alert::widget([
                                    'options' => ['class' => 'alert-' . $type],
                                    'body' => $message,
                                ]);
                            } ?>
                          

<?php

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'label' => 'Número de empleado',
            'value' => function ($model) {
                return $model->empleado->numero_empleado;
            },
        ],
        [
            'label' => 'Tipo de formato',
            'value' => function ($model) {
                return $model->nombre_formato;
            },
        ],
        [
            'label' => 'Nombre',
            'value' => function ($model) {
                return $model->empleado ? $model->empleado->nombre . ' ' . $model->empleado->apellido : 'N/A';
            },
        ],
        [
            'attribute' => 'fecha_creacion',
            'format' => 'raw',
            'value' => function ($model) {
                setlocale(LC_TIME, "es_419.UTF-8");
                return strftime('%A, %d de %B de %Y', strtotime($model->fecha_creacion));
            },
        ],
        // Otros atributos de la solicitud...
    ],
]);

if ($formato) {
    $formatoAttributes = [];
    
    switch ($model->nombre_formato) {
        case 'PERMISO FUERA DEL TRABAJO':
            $formatoAttributes = [
                //[
                  //  'label' => 'Motivo Fecha Permiso',
                    //'value' => $formato->motivo_fecha_permiso ? $formato->motivo_fecha_permiso->descripcion : 'N/A',
                //],
                [
                    'attribute' => 'hora_salida',
                    'value' => function ($formato) {
                        $hora = date("g:i A", strtotime($formato->hora_salida));
                        return $hora;
                    },
                ],
                [
                    'attribute' => 'hora_regreso',
                    'value' => function ($formato) {
                        $hora = date("g:i A", strtotime($formato->hora_regreso));
                        return $hora;
                    },
                ],
                [
                    'label' => 'Motivo',
                    'value' => function ($formato) {
                        return $formato->motivoFechaPermiso->motivo; 
                    },
                ],

                [
                    'attribute' => 'horario_fecha_a_reponer',
                    'value' => function ($formato) {
                        $hora = date("g:i A", strtotime($formato->horario_fecha_a_reponer));
                        return $hora;
                    },
                ],
               // 'nota:ntext',
                [
                    'label' => 'Fecha de Permiso',
                    'value' => function ($formato) {
                       
                        setlocale(LC_TIME, "es_419.UTF-8");
                        
                        $fechaPermiso = strtotime($formato->motivoFechaPermiso->fecha_permiso);
                        
                        $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                        
                        setlocale(LC_TIME, null);
                        
                        return $fechaFormateada;
                    },
                ],
                // Otros atributos específicos de PermisoFueraTrabajo
            ];
            break;

        case 'COMISION ESPECIAL':
            $formatoAttributes = [
                
                            [
                                'label' => 'Motivo',
                                'value' => function ($formato) {
                                    return $formato->motivoFechaPermiso->motivo; // Reemplaza "nombre_del_atributo_del_motivo" con el nombre del atributo que deseas mostrar
                                },
                            ],
                            [
                                'label' => 'Fecha de Permiso',
                                'value' => function ($formato) {
                                   
                                    setlocale(LC_TIME, "es_419.UTF-8");
                                    
                                    $fechaPermiso = strtotime($formato->motivoFechaPermiso->fecha_permiso);
                                    
                                    $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                                    
                                    setlocale(LC_TIME, null);
                                    
                                    return $fechaFormateada;
                                },
                            ],
                // Otros atributos específicos de ComisionEspecial
            ];
            break;

        case 'PERMISO ECONOMICO':
            $formatoAttributes = [
                [
                    'label' => 'Motivo',
                    'value' => function ($formato) {
                        return $formato->motivoFechaPermiso->motivo; 
                    },
                ],
                [
                    'label' => 'Fecha de Permiso',
                    'value' => function ($formato) {
                       
                        setlocale(LC_TIME, "es_419.UTF-8");
                        
                        $fechaPermiso = strtotime($formato->motivoFechaPermiso->fecha_permiso);
                        
                        $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                        
                        setlocale(LC_TIME, null);
                        
                        return $fechaFormateada;
                    },
                ],
                // Otros atributos específicos de PermisoEconomico
            ];
            break;

        // Agrega aquí más casos para otros formatos si es necesario...
    }

    echo DetailView::widget([
        'model' => $formato,
        'attributes' => $formatoAttributes,
    ]);
} else {
    echo '<p>No se encontró información del formato asociado.</p>';
}

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
        <!--.col-md-6-->
    </div>
    <!--.row justify-content-center-->
</div>
<!--.container-fluid-->
