<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ConsultaMedicaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Consulta Medicas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= Html::a('Create Consulta Medica', ['create'], ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>


                    <?php Pjax::begin(); ?>
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'id',
                            'cita_medica_id',
                            'motivo:ntext',
                            'sintomas:ntext',
                            'diagnostico:ntext',
                            //'tratamiento:ntext',
                            //'presion_arterial_minimo',
                            //'presion_arterial_maximo',
                            //'temperatura_corporal',
                            //'aspecto_fisico:ntext',
                            //'nivel_glucosa',
                            //'oxigeno_sangre',
                            //'medico_atendio',
                            //'frecuencia_cardiaca',
                            //'frecuencia_respiratoria',
                            //'estatura',
                            //'peso',
                            //'imc',
                            //'expediente_medico_id',

                            ['class' => 'hail812\adminlte3\yii\grid\ActionColumn'],
                        ],
                        'summaryOptions' => ['class' => 'summary mb-2'],
                        'pager' => [
                            'class' => 'yii\bootstrap4\LinkPager',
                        ]
                    ]); ?>

                    <?php Pjax::end(); ?>

                </div>
                <!--.card-body-->
            </div>
            <!--.card-->
        </div>
        <!--.col-md-12-->
    </div>
    <!--.row-->
</div>
