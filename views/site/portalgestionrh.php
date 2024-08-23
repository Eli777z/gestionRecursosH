<?php
//IMPORTACIONES
use yii\bootstrap5\Alert;
use yii\helpers\Html;

use yii\web\View;
//CARGAR ESTILOS CSS
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);

$this->title = 'PAGINA DE INICIO- ADMIN';

?>
<div class="container-fluid">
    <div class="row ">
        <?php


//ALERTA
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

        <div class="col-md-8">
            <div class="card">

                <div class="card-header gradient-yellow text-white">
                    <h2> Solicitudes Recientes</h2>
                </div>


                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">

                            <div style="max-height: 300px; overflow-y: auto;">
                                <?php setlocale(LC_TIME, "es_419.UTF-8"); ?>
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-dark">
                                    <tr>
            <th>Estatus</th> <!-- Nueva columna Estatus -->
            <th>Empleado</th>
            <th>Tipo de solicitud</th>
            <th>Fecha de Creación</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php // SE GENERA LA TABLA CON LAS SOLICITUDES MÁS RECIENTES REALIZADAS
        foreach ($solicitudesRecientes as $solicitud): ?>
            <tr>
                <td>
                    <?php
                    if ($solicitud->status === 'Nueva') {
                        echo '<i class="fa fa-fire" aria-hidden="true" style="color: #ea4242 "></i>';
                    } elseif ($solicitud->status === 'Visto') {
                        echo '<i class="fa fa-check-double" aria-hidden="true" style="color: #4678fc"></i>';
                    } else {
                        echo '<i class="fa fa-question" aria-hidden="true" style="color: #6c757d"></i>';
                    }
                    ?>
                </td>
                <td>
                    <?= $solicitud->empleado ? Html::encode($solicitud->empleado->nombre . ' ' . $solicitud->empleado->apellido) : 'N/A' ?>
                </td>
                <td>
                    <?= Html::encode($solicitud->nombre_formato) ?>
                </td>
                <td>
    <?= strftime('%A, %d de %B de %Y', strtotime($solicitud->created_at)) ?>
    a las <?= date('H:i', strtotime($solicitud->created_at)) ?> horas
</td>
                <td>
                    <?= Html::a('Ver', ['solicitud/view', 'id' => $solicitud->id], ['class' => 'btn btn-primary btn-sm']) ?>
                </td>
            </tr>
        <?php endforeach; ?> </tbody>
                                </table>

                            </div>


                        </div>
                    </div>


                </div>
            </div>


        </div>

        <div class="col-md-4">
            <div class="card bg-light">


                <div class="card-header bg-info text-white">
                    <h4>Avisos</h4>
                </div>


                <div class="card-body">



                    <?php setlocale(LC_TIME, "es_419.UTF-8"); ?>

                    <?php

                                             //SE RENDERIZA LA VISTA DE CARRUSEL-AVISOS DE AVISO, Y SE
                        //CARGA EL MODELO DE AVISO
                    echo $this->render('//aviso/carrusel-avisos', [
                        'avisos' => $avisos,

                    ]);
                    ?>


                </div>
            </div>
        </div>

    </div>

</div>