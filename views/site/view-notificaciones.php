<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $notificaciones app\models\Notificacion[] */

$this->title = 'Notificaciones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notificacion-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="list-group">
        <?php foreach ($notificaciones as $notificacion): ?>
            <a href="#" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1"><?= Html::encode($notificacion->mensaje) ?></h5>
                    <small><?= Yii::$app->formatter->asRelativeTime($notificacion->created_at) ?></small>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>
