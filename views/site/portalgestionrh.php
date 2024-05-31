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
                                // Mostrar los flash messages
                                foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
                                    if ($type === 'error') {
                                        // Muestra los mensajes de error en rojo
                                        echo Alert::widget([
                                            'options' => ['class' => 'alert-danger'],
                                            'body' => $message,
                                        ]);
                                    } else {
                                        // Muestra los demás mensajes de flash con estilos predeterminados
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



    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'CPU Traffic',
                'number' => '10 <small>%</small>',
                'icon' => 'fas fa-cog',
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Messages',
                'number' => '1,410',
                'icon' => 'far fa-envelope',
            ]) ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Bookmarks',
                'number' => '410',
                 'theme' => 'success',
                'icon' => 'far fa-flag',
            ]) ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Uploads',
                'number' => '13,648',
                'theme' => 'gradient-warning',
                'icon' => 'far fa-copy',
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Bookmarks',
                'number' => '41,410',
                'icon' => 'far fa-bookmark',
                'progress' => [
                    'width' => '70%',
                    'description' => '70% Increase in 30 Days'
                ]
            ]) ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?php $infoBox = \hail812\adminlte\widgets\InfoBox::begin([
                'text' => 'Likes',
                'number' => '41,410',
                'theme' => 'success',
                'icon' => 'far fa-thumbs-up',
                'progress' => [
                    'width' => '70%',
                    'description' => '70% Increase in 30 Days'
                ]
            ]) ?>
            <?= \hail812\adminlte\widgets\Ribbon::widget([
                'id' => $infoBox->id.'-ribbon',
                'text' => 'Ribbon',
            ]) ?>
            <?php \hail812\adminlte\widgets\InfoBox::end() ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Events',
                'number' => '41,410',
                'theme' => 'gradient-warning',
                'icon' => 'far fa-calendar-alt',
                'progress' => [
                    'width' => '70%',
                    'description' => '70% Increase in 30 Days'
                ],
                'loadingStyle' => true
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => '150',
                'text' => 'New Orders',
                'icon' => 'fas fa-shopping-cart',
            ]) ?>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?php $smallBox = \hail812\adminlte\widgets\SmallBox::begin([
                'title' => '150',
                'text' => 'New Orders',
                'icon' => 'fas fa-shopping-cart',
                'theme' => 'success'
            ]) ?>
            <?= \hail812\adminlte\widgets\Ribbon::widget([
                'id' => $smallBox->id.'-ribbon',
                'text' => 'Ribbon',
                'theme' => 'warning',
                'size' => 'lg',
                'textSize' => 'lg'
            ]) ?>
            <?php \hail812\adminlte\widgets\SmallBox::end() ?>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => '44',
                'text' => 'User Registrations',
                'icon' => 'fas fa-user-plus',
                'theme' => 'gradient-success',
                'loadingStyle' => true
            ]) ?>
        </div>
    </div>
</div>