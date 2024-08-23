<?php
//IMPORTACIONES
use kartik\tabs\TabsX;

use yii\helpers\Html;

use yii\grid\GridView;
use yii\widgets\Pjax;

use yii\jui\DatePicker;

?>

<div class="container-fluid">

<?php
//BLOQUES PARA AÑADIRLOS AL TABS
$this->beginBlock('block-parametro-formato'); ?>
        <?php
        // REDENRIZAR VISTA Y CARGAR MODELO DE PARAMETRO_FORMATO
        echo $this->render('//parametro-formato/index', [
            'searchModel' => $parametroFormatoSearchModel,
            'dataProvider' => $parametroFormatoDataProvider,
        ]);
        ?>

<?php $this->endBlock();?>

<?php $this->beginBlock('block-departamentos'); ?>
<?php
          // REDENRIZAR VISTA Y CARGAR MODELO DE CAT_DEPARTAMENTO
        echo $this->render('//cat-departamento/index', [
            'searchModel' => $catDepartamentoSearchModel,
            'dataProvider' => $catDepartamentoDataProvider
        ]);
        ?>
  <?php $this->endBlock();?>      
  
  <?php $this->beginBlock('block-puestos'); ?>
        <?php
          // REDENRIZAR VISTA Y CARGAR MODELO DE CAT_PUESTO
        echo $this->render('//cat-puesto/index', [
            'searchModel' => $catPuestoSearchModel,
            'dataProvider' => $catPuestoDataProvider,
        ]);
        ?>
    <?php $this->endBlock();?> 

<?php
//ORGANIZAR PESTAÑAS
$tabs = [
    [
        'label' => 'Formatos',
        'content' => $this->blocks['block-parametro-formato'],
        'active' => true,
        'options' => [
            'id' => 'conf-formatos',
        ],
    ],
    [
        'label' => 'Nombramientos',
        'content' => $this->blocks['block-puestos'],
       // 'active' => true,
        'options' => [
            'id' => 'conf-puestos',
        ],
    ],
    [
        'label' => 'Departamentos',
        'content' => $this->blocks['block-departamentos'],
      //  'active' => true,
        'options' => [
            'id' => 'conf-departamentos',
        ],
    ],
];


//MOSTRAR LOS BLOQUES EN LOS TABS
echo TabsX::widget([
    'enableStickyTabs' => true,
    'options' => ['class' => 'custom-tabs-2'],
    'items' => $tabs,
    'position' => TabsX::POS_ABOVE,
    'align' => TabsX::ALIGN_CENTER,
    'encodeLabels' => false,
]);
?>

</div>
