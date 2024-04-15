<?php
use hail812\adminlte3\yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\bootstrap5\Tabs;
use yii\grid\GridView;
use yii\bootstrap4\Modal;
use yii\web\YiiAsset;
use yii\widgets\Pjax;
use app\models\ExpedienteSearch;
use kartik\daterange\DateRangePicker;
/* @var $this yii\web\View */
/* @var $model app\models\Trabajador */


$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Trabajadors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                        <div class="d-flex align-items-center mb-3">
                    <?php
                    // Mostrar la foto del trabajador si está disponible
                    if ($model->foto) {
                        echo Html::img(['trabajador/foto-trabajador', 'id' => $model->id], ['class' => 'mr-3', 'style' => 'width: 100px; height: 100px;']);
                    } else {
                        // Si no hay foto, muestra un marcador de posición o un mensaje
                        echo Html::tag('div', 'No hay foto disponible', ['class' => 'mr-3']);
                    }
                    ?>
                    <h2 class="mb-0">Trabajador: <?= $model->nombre ?>  <?= $model->apellido ?></h2>
                </div>
                            <?php $this->beginBlock('datos');?>

                            <?= DetailView::widget([
                                'model' => $model,
                                'template' => '<tr><th style="width: 20%">{label}</th><td style="width: 30%">{value}</td></tr>', // Ajusta el ancho de las columnas
                                
                                'attributes' => [
                                    'id',
                                    'nombre',
                                    'apellido',
                                    'email:email',
                                    'fecha_nacimiento',
                                    'codigo_postal',
                                    'calle',
                                    'numero_casa',
                                    'telefono',
                                    'colonia',
                                  //  [
                                    //    'attribute' => 'foto',
                                      //  'value' => Url::to(['trabajador/foto-trabajador', 'id' => $model->id]),
                                       // 'format' => ['image',['width'=>'100','height'=>'100']], // Ajusta el tamaño según necesites
                                    //],
                                    'idusuario',
                                ],
                            ]) ?>
                            <?php $this->endBlock();?>
                            <?php $this->beginBlock('expediente');?>
                            <br>
                           
                            <?php

                            // Botón para abrir el modal
                            echo Html::button('Agregar Documento', [
                                'class' => 'btn btn-primary mb-3 ', // Agrega la clase mb-2 para margen inferior
                                'id' => 'btn-agregar-expediente',
                                'value' => Url::to(['expediente/create', 'idtrabajador' => $model->id]),
                            ]);

                            // Modal para cargar el formulario de creación de expediente
                            Modal::begin([
                                'title' => '<h4>Agregar Expediente</h4>',
                                'id' => 'modal-agregar-expediente',
                            ]);
                            echo '<div id="modalContent"></div>';
                            Modal::end();


                            ?>
                            <?php
                            // JavaScript para manejar el modal y la carga del formulario
                            $this->registerJs('
                            $(document).ready(function() {
                                $("#btn-agregar-expediente").click(function() {
                                    $("#modal-agregar-expediente").modal("show")
                                        .find("#modalContent")
                                        .load($(this).attr("value"));
                                });
                            });

                            // JavaScript para manejar el envío del formulario dentro del modal
                            $(document).on("beforeSubmit", "#expediente-form", function(event) {
                                event.preventDefault(); // Evitar envío de formulario por defecto

                                var form = $(this);

                                $.ajax({
                                    url: form.attr("action"),
                                    type: form.attr("method"),
                                    data: form.serialize(),
                                    dataType: "json",
                                    success: function(response) {
                                        if (response.success) {
                                            // Mostrar mensaje de éxito
                                            $("#modal-agregar-expediente").modal("hide"); // Opcional: cerrar modal
                                            alert("El expediente se ha creado correctamente.");
                                        } else {
                                            // Mostrar mensaje de error si es necesario
                                            alert("Hubo un error al crear el expediente.");
                                        }
                                    },
                                    error: function() {
                                        // Mostrar mensaje de error en caso de fallo en la solicitud AJAX
                                        alert("Error al enviar el formulario.");
                                    }
                                });
                            });
                            ');
                            ?>
                           <?php
$searchModel = new ExpedienteSearch();
$params = Yii::$app->request->queryParams;
$params[$searchModel->formName()]['idtrabajador'] = $model->id; // Agrega el filtro por idtrabajador
$dataProvider = $searchModel->search($params);
?>

<?php Pjax::begin(); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'options' => ['class' => 'grid-view', 'style' => 'width: 80%; margin: auto;'], // Ajusta el ancho del GridView y lo centra horizontalmente
    'tableOptions' => ['class' => 'table table-striped table-bordered table-condensed'], // Agrega clases de Bootstrap para un diseño más compacto
    
    
    'columns' => [
        [
            'attribute' => 'nombre',
            'value' => 'nombre',
            'options' => ['style' => 'width: 30%;'], // Ajusta el ancho de la columna
        ],
        [
            'attribute' => 'fechasubida',
            'filter' => false,
            'options' => ['style' => 'width: 30%;'], // Ajusta el ancho de la columna
        ],
        [
            'class' => ActionColumn::class,
            'template' => '{view} {delete} {download}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<i class="far fa-eye"></i>', ['expediente/open', 'id' => $model->id], [
                        'target' => '_blank',
                        'title' => 'Ver archivo',
                        'class' => 'btn btn-info btn-xs',
                        'data-pjax' => "0"
                    ]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<i class="fas fa-trash"></i>', ['expediente/delete', 'id' => $model->id, 'idtrabajador' => $model->idtrabajador], [
                        'title' => Yii::t('yii', 'Eliminar'),
                        'data-confirm' => Yii::t('yii', '¿Estás seguro de que deseas eliminar este elemento?'),
                        'data-method' => 'post',
                        'class' => 'btn btn-danger btn-xs',
                    ]);
                },
                'download' => function ($url, $model) {
                    return Html::a('<i class="fas fa-download"></i>', ['expediente/download', 'id' => $model->id], [
                        'title' => 'Descargar archivo',
                        'class' => 'btn btn-success btn-xs',
                        'data-pjax' => "0"
                    ]);
                },
            ],
            'options' => ['style' => 'width: 15%;'], // Ajusta el ancho de la columna
        ],
    ],
    'summaryOptions' => ['class' => 'summary mb-2'],
    'pager' => [
        'class' => 'yii\bootstrap4\LinkPager',
    ]
]); ?>
<?php Pjax::end(); ?>
<?php $this->endBlock();?>
<?= Tabs::widget([
        'items' => [
            [
                'label' => 'Información',
                'content' => $this->blocks['datos'],

                
               
            ],
            [
                'label' => 'Expediente',
                'content' => $this->blocks['expediente'],
                'active' => true,

            ],
           

        ],
    ]);

    ?>

                        </div>
                        <!--.col-md-12-->
                        
                    </div>
                    <!--.row-->
                    
                </div>
                <!--.card-body-->
                
            </div>
            <!--.card-->
         
        </div>
        <!--.col-md-10-->
    </div>
    <!--.row-->
</div>
<!--.container-fluid-->






