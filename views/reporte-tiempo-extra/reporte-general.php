<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Empleado;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EmpleadoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vista General de Horas Extras';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="empleado-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'id',
            'label' => 'Empleado',
            'value' => function ($model) {
                return $model->apellido . ' ' . $model->nombre;
            },
            'filter' => Select2::widget([
                'model' => $searchModel,
                'attribute' => 'id',
                'data' => ArrayHelper::map(Empleado::find()->all(), 'id', function ($model) {
                    return $model->apellido . ' ' . $model->nombre;
                }),
                'options' => ['placeholder' => 'Selecciona un empleado'],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'theme' => Select2::THEME_KRAJEE_BS3,
            ]),
            'contentOptions' => ['class' => 'small-font'],
        ],
        [
            'attribute' => 'numero_empleado',
            'label' => 'Número de empleado',
            'value' => function ($model) {
                return $model->numero_empleado;
            },
            'filter' => Select2::widget([
                'model' => $searchModel,
                'attribute' => 'numero_empleado',
                'data' => ArrayHelper::map(
                    Empleado::find()->select(['numero_empleado'])->distinct()->all(),
                    'numero_empleado',
                    'numero_empleado'
                ),
                'options' => ['placeholder' => 'Número de Empleado'],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'theme' => Select2::THEME_KRAJEE_BS3,
            ]),
            'contentOptions' => ['class' => 'small-font'],
        ],
        [
            'attribute' => 'total_horas',
            'label' => 'Total de Horas Extras',
            'value' => function ($model) {
                return $model->getTotalHorasExtras();
            },
            'contentOptions' => ['class' => 'small-font'],
        ],
        [
            'label' => 'Actividades de Tiempo Extra',
            'format' => 'raw',
            'value' => function ($model) {
                $actividades = $model->getActividadesExtras();
                $resultado = '<ul>';
                foreach ($actividades as $actividad) {
                    $resultado .= '<li>' . ' - Actvidad: ' . Html::encode($actividad->actividad) . ' - Horas: ' . Html::encode($actividad->no_horas) . ' - Fecha: ' . Html::encode($actividad->fecha) . '</li>';
                }
                $resultado .= '</ul>';
                return $resultado;
            },
            'contentOptions' => ['class' => 'small-font'],
        ],
    ],
]); ?>

</div>
