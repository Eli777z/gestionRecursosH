<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\Alert;
use app\models\Empleado;
use app\models\JuntaGobierno;
/* @var $this yii\web\View */
/* @var $model app\models\ComisionEspecial */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Comision Especials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <p>
                        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>

<?php


// Obtener el ID del usuario que tiene la sesión iniciada
$usuarioId = Yii::$app->user->identity->id;

// Buscar el empleado relacionado con el usuario
$empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

if ($empleado) {
    $model->empleado_id = $empleado->id;

    // Buscar el registro en junta_gobierno que corresponde al empleado
    $juntaGobierno = JuntaGobierno::find()->where(['empleado_id' => $empleado->id])->one();

    if ($model->solicitud->status === 'Aprobado') {
        if ($juntaGobierno && ($juntaGobierno->nivel_jerarquico === 'Jefe de unidad' || $juntaGobierno->nivel_jerarquico === 'Director')){
            echo Html::a('Exportar Excel', ['export-segundo-caso', 'id' => $model->id], ['class' => 'btn btn-success']);
            echo Html::a('Exportar PDF', ['export-pdf', 'id' => $model->id], ['class' => 'btn btn-danger']);
        } else {
            echo Html::a('Exportar Excel', ['export', 'id' => $model->id], ['class' => 'btn btn-primary']);
            echo Html::a('Exportar PDF', ['export-pdf', 'id' => $model->id], ['class' => 'btn btn-danger']);
        }
    }
} else {
    Yii::$app->session->setFlash('error', 'No se pudo encontrar el empleado asociado al usuario actual.');
    return $this->redirect(['index']);
}
?>





                    </p>

                  
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'empleado_id',
                            'solicitud_id',
                            [
                                'label' => 'Motivo',
                                'value' => function ($model) {
                                    return $model->motivoFechaPermiso->motivo; // Reemplaza "nombre_del_atributo_del_motivo" con el nombre del atributo que deseas mostrar
                                },
                            ],
                            [
                                'label' => 'Fecha de Permiso',
                                'value' => function ($model) {
                                   
                                    setlocale(LC_TIME, "es_419");
                                    
                                    $fechaPermiso = strtotime($model->motivoFechaPermiso->fecha_permiso);
                                    
                                    $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                                    
                                    setlocale(LC_TIME, null);
                                    
                                    return $fechaFormateada;
                                },
                            ],
                            [
                                'label' => 'Status',
                                'value' => function ($model) {
                                   
                        
                                    
                                    $status = $model->solicitud->status;
                                    
                                
                                    return $status;
                                },
                            ],
                    
                    
                            [
                                'label' => 'Aprobó',
                                'value' => function ($model) {
                                   
                        
                                    
                                    $aprobante = $model->solicitud->nombre_aprobante;
                                    
                                
                                    return $aprobante;
                                },
                            ],
                    
                            [
                                'label' => 'Se aprobó',
                                'value' => function ($model) {
                                   
                        
                                    
                                    $aprobante = $model->solicitud->fecha_aprobacion;
                                    
                                
                                    return $aprobante;
                                },
                            ],
                    
                            [
                                'label' => 'Comentario',
                                'value' => function ($model) {
                                   
                        
                                    
                                    $aprobante = $model->solicitud->comentario;
                                    
                                
                                    return $aprobante;
                                },
                            ],
                        ],
                    ]) ?>
                </div>
                <!--.col-md-12-->
            </div>
            <!--.row-->
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>