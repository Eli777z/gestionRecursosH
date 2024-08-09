<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $avisos app\models\Aviso[] */

$this->title = 'Avisos';

setlocale(LC_TIME, 'es_419.UTF-8'); 

?>


    <div class="row ">
        <!-- Contenedor que rodea las tarjetas -->
        <div class="col-md-12">
            <div class="avisos-container">
                
                <?php if (Yii::$app->user->can('manejo-avisos')) { ?>

              <p>  <?= Html::a('<i class="fas fa-sticky-note"></i> Crear Nuevo Aviso', ['aviso/create'], [
                                  'class' => 'btn btn-dark',  
                                   ]) ?>
                                 </p>
                                 <?php } ?>


                                 <?php foreach ($avisos as $aviso): ?>
    <div class="mb-4">
        <div class="card" style="border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;">
            <img src="<?= Yii::$app->urlManager->createUrl(['aviso/ver-imagen', 'nombre' => $aviso->imagen]) ?>" class="card-img-top" alt="<?= Html::encode($aviso->titulo) ?>">
            <div class="card-body">
            <p class="card-text float-right" style="font-size: 0.8rem; color: #6c757d;">
                    <?php
                    // Formatea la fecha y hora
                    $timestamp = strtotime($aviso->created_at);
                    echo strftime('%d de %B del %Y, %I:%M %p', $timestamp);
                    ?>
                </p>
                <h1 class="card-title"><?= Html::encode($aviso->titulo) ?></h1>
                
                <p class="card-text"><?= Html::decode($aviso->mensaje) ?></p>
                <!-- Botón de eliminación -->
                <?php if (Yii::$app->user->can('manejo-avisos')) { ?>

                <?= Html::a('<i class="far fa-edit"></i> Editar', ['aviso/update', 'id' => $aviso->id], [
                    'class' => 'btn btn-warning',
                    
                ]) ?>
                <?= Html::a('<i class="fas fa-times-circle"></i> Quitar', ['aviso/delete', 'id' => $aviso->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => '¿Estás seguro de que deseas eliminar este aviso?',
                        'method' => 'post',
                    ],
                ]) ?>
                  <?php }  ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>


            </div>
        </div>
    </div>



