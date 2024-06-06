<?php
use yii\helpers\Html;

/* @var $filePath string */

$this->title = 'Imprimir Permiso Económico';
$this->params['breadcrumbs'][] = $this->title;

$encodedPath = Yii::$app->security->maskToken(basename($filePath));
?>

<div class="permiso-economico-print">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Descargar', ['download', 'filename' => $encodedPath], ['class' => 'btn btn-primary']) ?>
        <button onclick="window.print();" class="btn btn-warning">Imprimir</button>
    </p>
    <iframe src="<?= Yii::getAlias('@web') . '/runtime/archivos_temporales/' . basename($filePath) ?>" style="width:100%; height:600px;"></iframe>
</div>
