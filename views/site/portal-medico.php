<?php
//IMPORTACIONES
use yii\helpers\Html;
use hail812\adminlte\widgets\Alert;
use yii\web\View;
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);

$this->title = 'PAGINA DE INICIO- MEDICO';
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>
<div class="container-fluid">
<div id="dynamic-content">



</div>


<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-7">
            <div class="card">
              
            <div class="card-header gradient-yellow text-white">
    <h2> Solicitudes de Citas Medicas Recientes <i class="fa fa-heartbeat"></i></h2>
</div>


                <div class="card-body">
                    <div class="row">
                    <div class="col-sm-12">

                    <div style="max-height: 300px; overflow-y: auto;">
                           <?php setlocale(LC_TIME, "es_419.UTF-8");?>
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Estatus</th>
                            <th>Empleado</th>
                            
                            <th>Fecha de Creación</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                         // SE GENERA LA TABLA CON LA SOLICITUDES DE CITAS MEDICAS MÁS RECIENTES REALIZADAS
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
    <?= strftime('%A, %d de %B de %Y', strtotime($solicitud->created_at)) ?>
    a las <?= date('H:i', strtotime($solicitud->created_at)) ?> horas
</td>
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
