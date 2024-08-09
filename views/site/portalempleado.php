<?php
use yii\web\View;
use kartik\tabs\TabsX;
use hail812\adminlte\widgets\Alert;
use yii\widgets\DetailView;
use yii\helpers\Html;

$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);

$this->title = 'PAGINA DE INICIO - EMPLEADO';

$activeTab = Yii::$app->request->get('tab', 'info_p');
$currentDate = date('Y-m-d');
$antecedentesExistentes = [];
$observacionGeneral = '';
$descripcionAntecedentes = '';
$modelAntecedenteNoPatologico = new \app\models\AntecedenteNoPatologico();
$modelExploracionFisica = new \app\models\ExploracionFisica();
$editable = Yii::$app->user->can('editar-expediente-medico');


if ($antecedentes) {
    foreach ($antecedentes as $antecedente) {
        $antecedentesExistentes[$antecedente->cat_antecedente_hereditario_id][$antecedente->parentezco] = true;
        if (empty($observacionGeneral)) {
            $observacionGeneral = $antecedente->observacion;
        }
    }
}

// Si ya existe un antecedente patológico, obtenemos su descripción
$modelAntecedentePatologico = \app\models\AntecedentePatologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelAntecedentePatologico) {
    $modelAntecedentePatologico = new \app\models\AntecedentePatologico();
    $modelAntecedentePatologico->expediente_medico_id = $expedienteMedico->id;
}

// Obtener antecedentes no patológicos
$modelAntecedenteNoPatologico = \app\models\AntecedenteNoPatologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelAntecedenteNoPatologico) {
    $modelAntecedenteNoPatologico = new \app\models\AntecedenteNoPatologico();
    $modelAntecedenteNoPatologico->expediente_medico_id = $expedienteMedico->id;
}


$modelExploracionFisica = \app\models\ExploracionFisica::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelExploracionFisica) {
    $modelExploracionFisica = new \app\models\ExploracionFisica();
    $modelExploracionFisica->expediente_medico_id = $expedienteMedico->id;
}

$modelInterrogatorioMedico = \app\models\InterrogatorioMedico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelInterrogatorioMedico) {
    $modelInterrogatorioMedico = new \app\models\InterrogatorioMedico();
    $modelInterrogatorioMedico->expediente_medico_id = $expedienteMedico->id;
}
// Obtener antecedentes no patológicos
$modelAntecedentePerinatal = \app\models\AntecedentePerinatal::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelAntecedentePerinatal) {
    $modelAntecedentePerinatal = new \app\models\AntecedentePerinatal();
    $modelAntecedentePerinatal->expediente_medico_id = $expedienteMedico->id;
}


$modelAntecedenteGinecologico = \app\models\AntecedenteGinecologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelAntecedenteGinecologico) {
    $modelAntecedenteGinecologico = new \app\models\AntecedenteGinecologico();
    $modelAntecedenteGinecologico->expediente_medico_id = $expedienteMedico->id;
}

$modelAntecedenteObstrectico = \app\models\AntecedenteObstrectico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelAntecedenteObstrectico) {
    $modelAntecedenteObstrectico = new \app\models\AntecedenteObstrectico();
    $modelAntecedenteObstrectico->expediente_medico_id = $expedienteMedico->id;
}

$modelAlergia = \app\models\Alergia::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelAlergia) {
    $modelAlergia = new \app\models\Alergia();
    $modelAlergia->expediente_medico_id = $expedienteMedico->id;
}

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
 
              
             
  
  
                 
                      
                   
                      <?= $this->render('//empleado/view', [
                                'model' => $model,
                                'documentos' => $documentos,
                                'documentoModel' => $documentoModel,
                                'historial' => $historial,
                                'searchModelConsultas' => $searchModelConsultas,
                                'dataProviderConsultas' => $dataProviderConsultas,
                                'searchModel' => $searchModel,
                                'dataProvider' => $dataProvider,
                                'expedienteMedico' => $expedienteMedico,
                                'antecedentes' => $antecedentes,
                                'catAntecedentes' => $catAntecedentes,
                                'antecedenteNoPatologico' => $antecedenteNoPatologico, 
                                'ExploracionFisica' => $ExploracionFisica,
                                'InterrogatorioMedico' => $InterrogatorioMedico,
                                'AntecedentePerinatal' => $AntecedentePerinatal,
                                'AntecedenteGinecologico' => $AntecedenteGinecologico,
                                'AntecedenteObstrectico' => $AntecedenteObstrectico,
                                'Alergia' => $Alergia,
                                'antecedentePatologico' => $antecedentePatologico,
                                'documentoMedicoModel' => $documentoMedicoModel
                            ]) ?>

              
       
         
                  
       
  

</div>

<div class="col-md-4">
   <div class="card bg-light">
   <div class="card-header bg-info text-white">
<h4>Avisos</h4>
   </div>
             
  
  
                  <div class="card-body">
                     
                  <div style="max-height: 700px; overflow-y: auto;">

                             
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

</div>
