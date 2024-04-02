<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>
<div class="error-page">
    <div class="error-content" style="margin-left: auto;">
        <h3><i class="fas fa-exclamation-triangle text-danger"></i> <?= Html::encode($name) ?></h3>

        <p>
            <?= nl2br(Html::encode($message)) ?>
        </p>

        <p>
        El error anterior ocurrió mientras el servidor web estaba procesando su solicitud. Comuníquese con nosotros si cree que se trata de un error del servidor. Gracias. Mientras tanto, puedes<a href="javascript:history.back(-1);">
            regresar.
        </p>

     
    </div>
</div>

