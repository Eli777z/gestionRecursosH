<?php
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var array $reporteData */
/** @var int $horasTotales */

$this->title = 'Reporte de Horas Extras Totales';
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="card-body bg-light">


                <div class="reporte-horas-extras">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID del reporte</th>
                <th>Tipo de Reporte</th>
                <th>Total de Horas</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reporteData as $data): ?>
                <tr>
                    <td><?= Html::encode($data['reporte_id']) ?></td>
                    <td><?= Html::encode($data['tipo']) ?></td>
                    <td><?= Html::encode($data['total_horas']) ?></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <strong>Actividades:</strong>
                        <ul class="list-unstyled">
                            <?php foreach ($data['actividades'] as $actividad): ?>
                                <?php
                                    setlocale(LC_TIME, "es_419.UTF-8");
                                    $fechaActividad = strtotime($actividad->fecha);
                                    $fechaFormateada = strftime('%A, %B %d, %Y', $fechaActividad);
                                    setlocale(LC_TIME, null);
                                ?>
                                <li>
                                    <?= Html::encode($actividad->actividad) ?> (<?= Html::encode($actividad->no_horas) ?> horas) -> <?= Html::encode($fechaFormateada) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-right">Total General de Horas:</th>
                <th><?= Html::encode($horasTotales) ?></th>
            </tr>
        </tfoot>
    </table>
</div>

</div>
                <!--.card-body-->
            </div>
            <!--.card-->
        </div>
        <!--.col-md-12-->
    </div>
    <!--.row-->
</div>