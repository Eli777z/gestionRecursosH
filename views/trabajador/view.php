<?php
use hail812\adminlte3\yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\bootstrap5\Tabs;
use yii\grid\GridView;
use yii\bootstrap4\Modal;
use yii\web\YiiAsset;
/* @var $this yii\web\View */
/* @var $model app\models\Trabajador */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Trabajadors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <p>
                        <?= Html::a('Update', ['update', 'id' => $model->id, 'idusuario' => $model->idusuario], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Delete', ['delete', 'id' => $model->id, 'idusuario' => $model->idusuario], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </p>
                    <?php $this->beginBlock('datos');?>
                    
                    <?= DetailView::widget([
                        'model' => $model,
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
                            [
                                'attribute' => 'foto',
                                'value' => Url::to(['trabajador/foto-trabajador', 'id' => $model->id]),
                                'format' => ['image',['width'=>'100','height'=>'100']], // Ajusta el tamaño según necesites
                            ],
                            
                            
                            'idusuario',
                        ],
                    ]) ?>
<?php $this->endBlock();?>
<?php $this->beginBlock('expediente');?>
<br>
<h2>Expediente de <?=$model->nombre?>
</h2>
<?php



// Botón para abrir el modal
echo Html::button('Agregar Expediente', [
    'class' => 'btn btn-success',
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


<?= GridView::widget([
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getExpedientes(), // Obtener los expedientes del trabajador
        'pagination' => [
            'pageSize' => 50,
        ],
    ]),
    'columns' => [
        'id',
       'nombre',
        'fechasubida',
        [
            'class' => ActionColumn::class,
            'template' => '{view} {delete} {download}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<i class="far fa-eye"></i>', ['expediente/open', 'id' => $model->id], [
                        'target' => '_blank',
                        'title' => 'Ver archivo',
                        'class' => 'btn btn-info btn-xs', // Clase de Bootstrap para un botón de información pequeño
                    ]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<i class="fas fa-trash"></i>', ['expediente/delete', 'id' => $model->id, 'idtrabajador' => $model->idtrabajador], [
                        'title' => Yii::t('yii', 'Eliminar'),
                        'data-confirm' => Yii::t('yii', '¿Estás seguro de que deseas eliminar este elemento?'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                        'class' => 'btn btn-danger btn-xs', // Clase de Bootstrap para un botón de peligro pequeño
                    ]);
                },
                'download' => function ($url, $model) {
                    return Html::a('<i class="fas fa-download"></i>', ['expediente/download', 'id' => $model->id], [
                        'title' => 'Descargar archivo',
                        'class' => 'btn btn-success btn-xs', // Clase de Bootstrap para un botón de éxito pequeño
                    ]);
                },
            ],
        ],
        
        
    ],
    'summaryOptions' => ['class' => 'summary mb-2'],
    'pager' => [
        'class' => 'yii\bootstrap4\LinkPager',
    ]
]); ?>
<?php $this->endBlock();?>
                </div>
                <!--.col-md-12-->
            </div>
            <!--.row-->
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>

<?= Tabs::widget([
        'items' => [
            [
                'label' => 'Información',
                'content' => $this->blocks['datos'],

                'active' => true,
               
            ],
            [
                'label' => 'Expediente',
                'content' => $this->blocks['expediente'],


            ],
           

        ],
    ]);

    ?>





