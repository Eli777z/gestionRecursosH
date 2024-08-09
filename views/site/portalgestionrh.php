<?php

use yii\bootstrap5\Alert;
use yii\helpers\Html;

use yii\web\View;
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);

$this->title = 'PAGINA DE INICIO- ADMIN';

?>
<div class="container-fluid">
<div class="row ">
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
   
   <div class="col-md-8">
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
                              <th>Empleado</th>
                           
                              
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

<div class="col-md-4">
   <div class="card bg-light">
              
             
   <div class="card-header bg-info text-white">
<h4>Avisos</h4>
   </div>
           
  
                  <div class="card-body">
                     

                             
                             <?php setlocale(LC_TIME, "es_419.UTF-8");?>
                
                             <?php
                
                // Otros contenidos de la vista principal

                            // Incluir el contenido del nuevo archivo de vista
                            
                            echo $this->render('//aviso/carrusel-avisos', [
                                'avisos' => $avisos,

                            ]);
                            ?>
              
                
  
  
  
              
       
                  
              </div>
          </div>
  

</div>


                        
</div>

</div>