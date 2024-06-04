<?php
use yii\web\View;
use kartik\tabs\TabsX;
use hail812\adminlte\widgets\Alert;
use yii\widgets\DetailView;
use yii\helpers\Html;
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);

$this->title = 'PAGINA DE INICIO- EMPLEADO';
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <div class="card bg-light"> 
               

        <div class="card-header gradient-yellow text-white">
                    <h2>INFORMACIÓN DE EMPLEADO</h2>
                   
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center mb-3">
                                <?php
                                foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
                                    if ($type === 'error') {
                                        echo Alert::widget([
                                            'options' => ['class' => 'alert-danger'],
                                            'body' => $message,
                                        ]);
                                    } else {
                                        echo Alert::widget([
                                            'options' => ['class' => 'alert-' . $type],
                                            'body' => $message,
                                        ]);
                                    }
                                }
                                ?>
                            </div>



</div>


<?php $this->beginBlock('informacion_personal'); ?>

<div class="card">
            <div class="card-header bg-info text-white">
                <h3>Información personal</h3>
            </div>
            <div class="card-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'foto',
                'format' => 'html',
                'filter' => false,
                'value' => function ($model) {
                    if ($model->foto) {
                        $urlImagen = Yii::$app->urlManager->createUrl(['empleado/foto-empleado', 'id' => $model->id]);
                        return Html::img($urlImagen, ['width' => '80px', 'height' => '80px']);
                    }
                    return null;
                },
            ],
            'numero_empleado',
            'nombre',
            'apellido',
            [
                'label' => 'Fecha de nacimiento',

                'attribute' => 'fecha_nacimiento',
                'format' => 'raw',
                'value' => function ($model) {
                    setlocale(LC_TIME, "es_419.UTF-8");
                    return strftime('%A, %d de %B de %Y', strtotime($model->fecha_nacimiento));
                },
            ],
            'edad',
          'sexo',
            [
                'attribute' => 'estado_civil',
                'value' => function($model) {
                    return ucfirst($model->estado_civil);
                },
            ],
        ],
    ]) ?>

</div>
        </div>
<?php $this->endBlock(); ?>




<?php $this->beginBlock('informacion_contacto'); ?>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h3>Información de contacto</h3>
            </div>
            <div class="card-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'email:email',
                        'telefono',
                        'colonia',
                        'calle',
                        'numero_casa',
                        'codigo_postal',
                    ],
                ]) ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h3>Información de contacto de emergencia</h3>
            </div>
            <div class="card-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'nombre_contacto_emergencia',
                        [
                            'attribute' => 'relacion_contacto_emergencia',
                            'value' => function($model) {
                                return ucfirst($model->relacion_contacto_emergencia);
                            },
                        ],
                        'telefono_contacto_emergencia',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>

<?php $this->endBlock(); ?>


<?php

$this->beginBlock('informacion_laboral'); ?>



<div class="card mt-4">
    <div class="card-header bg-info text-white">
        <h3>Información Laboral</h3>
    </div>
    <div class="card-body">
        <?= DetailView::widget([
            'model' => $model->informacionLaboral,
            'attributes' => [
           

                [
                    'label' => 'Tipo de contrato',
                    'attribute' => 'cat_tipo_contrato_id',
                    'value' => function($model) {
                        return $model->catTipoContrato->nombre_tipo; 
                    },
                ],
                [
                    'label' => 'Fecha en que se incorporo a la empresa',

                    'attribute' => 'fecha_ingreso',
                    'format' => 'raw',
                    'value' => function ($model) {
                        setlocale(LC_TIME, "es_419.UTF-8");
                        return strftime('%A, %d de %B de %Y', strtotime($model->fecha_ingreso));
                    },
                ],
                [
                    'label' => 'Departamento',
                    'attribute' => 'cat_departamento_id',
                    'value' => function($model) {
                        return $model->catDepartamento->nombre_departamento; 
                    },
                ],
                [
                    'label' => 'DPTO',
                    'attribute' => 'cat_dpto_cargo_id',
                    'value' => function($model) {
                        return $model->catDptoCargo->nombre_dpto;
                    },
                ],
                [
                    'label' => 'Hora de entrada',
                    'attribute' => 'horario_laboral_inicio:time',
                    'value' => function($model) {
                        return $model->horario_laboral_inicio;
                    },
                ],
                
                [
                    'label' => 'Hora de salida',
                    'attribute' => 'horario_laboral_fin:time',
                    'value' => function($model) {
                        return $model->horario_laboral_fin;
                    },
                ],
                [
                    'label' => 'Puesto',

                    'attribute' => 'cat_puesto_id',
                    'value' => function($model) {
                        return $model->catPuesto->nombre_puesto; 
                    },
                ],
                [
                    'label' => 'Dirección',

                    'attribute' => 'cat_direccion_id',
                    'value' => function($model) {
                        return $model->catDireccion->nombre_direccion; 
                    },
                ],
            //    [
              //      'label' => 'Jefe',
//
  //                  'attribute' => 'junta_gobierno_id',
    //               'value' => function($model) use ($jefesDirectores) {
     //                   return $jefesDirectores[$model->junta_gobierno_id]; 
       //           },
        //        ],
                //[
          //   //       'label' => 'Director de dirección',
            ///        'value' => function($model) use ($juntaDirectorDireccion) {
                      //  return $juntaDirectorDireccion ? $juntaDirectorDireccion->profesion . ' ' . $juntaDirectorDireccion->empleado->nombre . ' ' . $juntaDirectorDireccion->empleado->apellido : 'No Asignado';
                   /// },
               // ],
            ],
        ]) ?>
    </div>
</div>

<?php $this->endBlock(); ?>

<?php $this->beginBlock('informacion_vacaciones'); ?>



<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h3>Primer periodo vacacional</h3>
            </div>
            <div class="card-body">
                <?= DetailView::widget([
                    'model' => $model->informacionLaboral->vacaciones,
                    'attributes' => [
                        [
                            'label' => 'Año de periodo',

                            'attribute' => 'año',
                            'value' => function($model) {
                                return $model->periodoVacacional->año; 
                            },
                        ],
                        [
                            'label' => 'Fecha inicial del periodo',

                            'attribute' => 'fecha_inicio',
                            'format' => 'raw',
                            'value' => function ($model) {
                                setlocale(LC_TIME, "es_419.UTF-8");
                                return strftime('%A, %d de %B de %Y', strtotime($model->periodoVacacional->fecha_inicio));
                            },
                        ],
                        [
                            'label' => 'Fecha final del periodo',
                            'attribute' => 'fecha_final',
                            'format' => 'raw',
                            'value' => function ($model) {
                                setlocale(LC_TIME, "es_419.UTF-8");
                                return strftime('%A, %d de %B de %Y', strtotime($model->periodoVacacional->fecha_final));
                            },
                        ],
                        [
                            'label' => 'Periodo original',
                            'attribute' => 'original',
                            'value' => function($model) {
                                return $model->periodoVacacional->original; 
                            },
                        ],
                        [
                            'label' => 'Dias de vacaciones totales del periodo',
                            'attribute' => 'dias_vacaciones_periodo',
                            'value' => function($model) {
                                return $model->periodoVacacional->dias_vacaciones_periodo; 
                            },
                        ],

                        [
                            'label' => 'Dias pendientes de disfrutar',
                            'attribute' => 'dias_disponibles',
                            'value' => function($model) {
                                return $model->periodoVacacional->dias_disponibles; 
                            },
                        ],
                    
                    ],
                ]) ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h3>Segundo periodo vacacional</h3>
            </div>
            <div class="card-body">
            <?= DetailView::widget([
                    'model' => $model->informacionLaboral->vacaciones,
                    'attributes' => [
                        [
                            'label' => 'Año de periodo',
                            'attribute' => 'año',
                            'value' => function($model) {
                                return $model->segundoPeriodoVacacional->año; 
                            },
                        ],
                        [
                            'label' => 'Fecha inicial del periodo',
                            'attribute' => 'fecha_inicio',
                            'format' => 'raw',
                            'value' => function ($model) {
                                setlocale(LC_TIME, "es_419.UTF-8");
                                return strftime('%A, %d de %B de %Y', strtotime($model->segundoPeriodoVacacional->fecha_inicio));
                            },
                        ],
                        [
                            'label' => 'Fecha inicial del periodo',

                            'attribute' => 'fecha_final',
                            'format' => 'raw',
                            'value' => function ($model) {
                                setlocale(LC_TIME, "es_419.UTF-8");
                                return strftime('%A, %d de %B de %Y', strtotime($model->segundoPeriodoVacacional->fecha_final));
                            },
                        ],
                        [
                            'label' => 'Periodo original',

                            'attribute' => 'original',
                            'value' => function($model) {
                                return $model->segundoPeriodoVacacional->original; 
                            },
                        ],
                        [
                            'label' => 'Dias de vacaciones totales del periodo',

                            'attribute' => 'dias_vacaciones_periodo',
                            'value' => function($model) {
                                return $model->segundoPeriodoVacacional->dias_vacaciones_periodo; 
                            },
                        ],


                        [
                            'label' => 'Dias pendientes de disfrutar',

                            'attribute' => 'dias_disponibles',
                            'value' => function($model) {
                                return $model->segundoPeriodoVacacional->dias_disponibles; 
                            },
                        ],
                    
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>

<?php $this->endBlock(); ?>

<?php 

echo TabsX::widget([
    'enableStickyTabs' => true,

    'items' => [

        [
            'label' => 'Información Personal',
            'content' => $this->blocks['informacion_personal'],
            'active' => true
        ],
        [
            'label' => 'Información de Contacto',
            'content' => $this->blocks['informacion_contacto'],
        ],
        //[
          //  'label' => 'Información Laboral',
            //'content' => $this->blocks['informacion_laboral'],
        //],
        [
            'label' => 'Vacaciones',
            'content' => $this->blocks['informacion_vacaciones'],
        ],
    ],
    'position' => TabsX::POS_ABOVE,
    'align' => TabsX::ALIGN_CENTER,
    'bordered' => true,
    'encodeLabels' => false
]);
?>








</div>
                    </div>
                </div>

                
            </div>
        </div>
    </div>






















   











   
    </div>
</div>