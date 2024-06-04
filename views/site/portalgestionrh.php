<?php

use yii\helpers\Html;
use hail812\adminlte\widgets\Alert;
use yii\web\View;
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);

$this->title = 'PAGINA DE INICIO- ADMIN';
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>
<div class="container-fluid">
<div id="dynamic-content">



</div>


<div class="container-fluid">
    <div class="row justify-content-left">
        <div class="col-sm-7">
            <div class="card">
              
            <div class="card-header gradient-yellow text-white">
    <h2> Solicitudes Recientes <i class="fas fa-envelope"></i></h2>
</div>


                <div class="card-body">
                    <div class="row">
                    <div class="col-sm-12">
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
                            <div style="max-height: 300px; overflow-y: auto;">
                           <?php setlocale(LC_TIME, "es_419.UTF-8");?>
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Empleado</th>
                            <th>Tipo de Solicitud</th>
                            <th>Status</th>
                            <th>Fecha de Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitudesRecientes as $solicitud): ?>
                            <tr>
                                <td>
                                    <?= $solicitud->empleado ? Html::encode($solicitud->empleado->nombre . ' ' . $solicitud->empleado->apellido) : 'N/A' ?>
                                </td>
                                <td><?= Html::encode($solicitud->nombre_formato) ?></td>
                                <td>
                                    <?php
                                    $statusClass = '';
                                    switch ($solicitud->status) {
                                        case 'Aprobado':
                                            $statusClass = 'badge badge-success';
                                            break;
                                        case 'Rechazado':
                                            $statusClass = 'badge badge-danger';
                                            break;
                                        case 'En Proceso':
                                            $statusClass = 'badge badge-warning';
                                            break;
                                        default:
                                            $statusClass = 'badge badge-secondary';
                                            break;
                                    }
                                    ?>
                                    <span class="<?= $statusClass ?>"><?= Html::encode($solicitud->status) ?></span>
                                </td>

                                <td><?= strftime('%A, %d de %B de %Y', strtotime($solicitud->fecha_creacion)) ?></td>
                                <td>
                                    <?= Html::a('Ver', ['solicitud/view', 'id' => $solicitud->id], ['class' => 'btn btn-primary btn-sm']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
     
        </div>
                </div>

                
            </div>
        </div>
    </div>
</div>
</div>



 
    </div>
