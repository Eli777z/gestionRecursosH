<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\tabs\TabsX;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CountriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Countries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= Html::a('Create Countries', ['create'], ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                    <?php $this->beginBlock('block-crear-junta-gobierno');?>

                    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <?php $this->endBlock();?>

<?php $this->beginBlock('block-view-junta-gobierno');?>

<?php Pjax::begin(['id' => 'countries']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end() ?>
<?php $this->endBlock();?>


                </div>
                <!--.card-body-->
            </div>
            <!--.card-->
        </div>
        <!--.col-md-12-->
    </div>
    <!--.row-->
</div>
<?php echo TabsX::widget([
                    'options' => ['class' => 'custom-tabs-2'],
                    'items' => [
                       
                        [
                            'label' => 'Junta De Gobierno',
                           'content' => $this->blocks['block-view-junta-gobierno'],
                           'active' => true,

                        ],
                        [
                            'label' => 'Añadir',
                            'content' => $this->blocks['block-crear-junta-gobierno'],
                           


                        ],


                    ],
                    'position' => TabsX::POS_ABOVE,
                    'align' => TabsX::ALIGN_CENTER,
                    // 'bordered'=>true,
                    'encodeLabels' => false

                ]);

                ?>