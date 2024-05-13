<?php
use yii\web\View;


$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);

$this->title = 'PAGINA DE INICIO- EMPLEADO';
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>
<div class="container-fluid">
<div id="dynamic-content">



</div>

    
<div class="row">
    <div class="col-md-4"> <!-- Cambiado de col-md-6 a col-md-4 -->
        <div class="card">
            <div class="card-header bg-primary text-white">
            <h5 class="card-title text-white">Formatos de Incidencias</h5> <!-- Cambio del color del texto del título a blanco -->
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <a href="#" class="dropdown-item">PERMISO FUERA DEL TRABAJO</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#" class="dropdown-item">COMISION ESPECIAL</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#" class="dropdown-item">PERMISO ECONÓMICO</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#" class="dropdown-item">PERMISO SIN GOCE DE SUELDO</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#" class="dropdown-item">CAMBIO DE DÍA LABORAL</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#" class="dropdown-item">CAMBIO DE HORARIO DE TRABAJO</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#" class="dropdown-item">CAMBIO DE PERIODO VACACIONAL</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#" class="dropdown-item">REPORTE DE TIEMPO EXTRA</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#" class="dropdown-item">REPORTE GENERAL DE TIEMPO EXTRA</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="formulario-container"> <!-- Div para mostrar el formulario -->
        <!-- Aquí se insertará dinámicamente el formulario -->
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.dropdown-item').click(function(e) {
        e.preventDefault(); // Evita el comportamiento predeterminado del enlace
        
        // Obtener el texto del enlace clicado
        var motivo = $(this).text();
        
        // Realizar una solicitud AJAX para obtener el formulario correspondiente
        $.ajax({
            url: 'obtener-formulario', // Usando la URL amigable// Reemplazar 'url_del_controlador' por la URL de tu controlador
            type: 'GET',
            data: { motivo: motivo }, // Pasar el motivo como parámetro
            success: function(response) {
                // Mostrar el formulario obtenido dentro del contenedor
                $('#formulario-container').html(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});
</script>




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