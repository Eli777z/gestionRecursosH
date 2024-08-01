
<?php


use yii\helpers\Html;
use froala\froalaeditor\FroalaEditorWidget;
use yii\helpers\HtmlPurifier;

use kartik\form\ActiveForm;


$editable = Yii::$app->user->can('editar-expediente-medico');
$modelAntecedenteGinecologico = \app\models\AntecedenteGinecologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelAntecedenteGinecologico) {
    $modelAntecedenteGinecologico = new \app\models\AntecedenteGinecologico();
    $modelAntecedenteGinecologico->expediente_medico_id = $expedienteMedico->id;
}
?>
<?php $form = ActiveForm::begin(['action' => ['antecedente-ginecologico', 'id' => $model->id]]); ?>
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="card">
                                        <div class="card-header gradient-blue text-white text-center">
                                            <h2>Antecedente Ginecologico</h2>
                                            <i class="fa fa-medkit fa-2x"></i>
                                            <?php if (Yii::$app->user->can('editar-expediente-medico')) { ?>

                                                <div class="float-submit-btn">
                                                    <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
                                                </div>
                                            <?php } ?>

                                        </div>
                                        <div class="card-body bg-light">
                                            <div class="container">


                                                <div class="row">
                                                    <div class="col-6 col-sm-2 ">
                                                        <?= Html::label('Menarca', 'p_menarca') ?>
                                                        <?= Html::input('number', 'AntecedenteGinecologico[p_menarca]', $AntecedenteGinecologico->p_menarca, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="col-6 col-sm-2">
                                                        <?= Html::label('Menopausia', 'p_menopausia') ?>
                                                        <?= Html::input('number', 'AntecedenteGinecologico[p_menopausia]', $AntecedenteGinecologico->p_menopausia, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="col-6 col-sm-2">
                                                        <?= Html::label('F.U.M', 'p_f_u_m') ?>
                                                        <?= Html::input('date', 'AntecedenteGinecologico[p_f_u_m]', $AntecedenteGinecologico->p_f_u_m, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="col-6 col-sm-2">
                                                        <?= Html::label('F.U. Citología', 'p_f_u_m') ?>
                                                        <?= Html::input('date', 'AntecedenteGinecologico[p_f_u_citologia]', $AntecedenteGinecologico->p_f_u_citologia, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                    </div>





                                                    <div class="w-100"></div>

                                                    <br>
                                                    <hr class="solid">
                                                    <div class="col-6 col-sm-4">
                                                        <h4>Alteraciones de la menstruación</h4>
                                                    </div>
                                                    <div class="w-100"></div>

                                                    <div class="col-6 col-sm-3">

                                                        <div class="form-group">
                                                            <?= Html::label('de Frecuencia', 'p_alteracion_frecuencia') ?>
                                                            <?= Html::dropDownList('AntecedenteGinecologico[p_alteracion_frecuencia]', $AntecedenteGinecologico->p_alteracion_frecuencia, [

                                                                'Amenorrea' => 'Amenorrea',
                                                                'Polimenorrea' => 'Polimenorrea',
                                                                'Oligomenorrea' => 'Oligomenorrea',
                                                            ], [
                                                                'class' => 'form-control',         'prompt' => 'Seleccione si tiene alguna', 'disabled' => !$editable
                                                            ]) ?>
                                                        </div>

                                                    </div>
                                                    <div class="col-6 col-sm-3">

                                                        <div class="form-group">
                                                            <?= Html::label('de Duración', 'p_alteracion_duracion') ?>
                                                            <?= Html::dropDownList('AntecedenteGinecologico[p_alteracion_duracion]', $AntecedenteGinecologico->p_alteracion_duracion, [

                                                                'Menometrorragia' => 'Menometrorragia',

                                                            ], [
                                                                'class' => 'form-control',         'prompt' => 'Seleccione si tiene alguna', 'disabled' => !$editable
                                                            ]) ?>
                                                        </div>

                                                    </div>

                                                    <div class="col-6 col-sm-3">

                                                        <div class="form-group">
                                                            <?= Html::label('de Cantidad', 'p_alteracion_cantidad') ?>
                                                            <?= Html::dropDownList('AntecedenteGinecologico[p_alteracion_cantidad]', $AntecedenteGinecologico->p_alteracion_cantidad, [

                                                                'Hipermenorrea' => 'Hipermenorrea',
                                                                'Hipomenorrea' => 'Hipomenorrea',
                                                            ], [
                                                                'class' => 'form-control',         'prompt' => 'Seleccione si tiene alguna', 'disabled' => !$editable
                                                            ]) ?>
                                                        </div>

                                                    </div>

                                                    <div class="w-100"></div>
                                                    <br>
                                                    <hr class="solid">

                                                    <div class="col-6 col-sm-2 ">
                                                        <?= Html::label('Inicio vida sexual', 'p_inicio_vida_s') ?>
                                                        <?= Html::input('number', 'AntecedenteGinecologico[p_inicio_vida_s]', $AntecedenteGinecologico->p_inicio_vida_s, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                    </div>
                                                    <div class="col-6 col-sm-2 ">
                                                        <?= Html::label('Número de parejas', 'p_no_parejas') ?>
                                                        <?= Html::input('number', 'AntecedenteGinecologico[p_no_parejas]', $AntecedenteGinecologico->p_no_parejas, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="w-100"></div>
                                                    <br>

                                                    <div class="col-6 bg-white rounded p-4">
                                                        <div class="list-container" style="max-height: 150px; overflow-y: auto;">
                                                            <ul class="list-unstyled">
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_vaginits]', $AntecedenteGinecologico->p_vaginits, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_vaginits',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Vaginitis', 'p_vaginits', ['class' => 'custom-control-label',]) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_cervicitis_mucopurulenta]', $AntecedenteGinecologico->p_cervicitis_mucopurulenta, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_cervicitis_mucopurulenta',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Cervicitis Mucopurulenta', 'p_cervicitis_mucopurulenta', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_chancroide]', $AntecedenteGinecologico->p_chancroide, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_chancroide',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Chancroide', 'p_chancroide', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_clamidia]', $AntecedenteGinecologico->p_clamidia, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_clamidia',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Clamidia', 'p_clamidia', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_eip]', $AntecedenteGinecologico->p_eip, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_eip',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Enfermedad Inflamatoria Pélvica (E.I.P.)', 'p_eip', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_gonorrea]', $AntecedenteGinecologico->p_gonorrea, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_gonorrea',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Gonorrea', 'p_gonorrea', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_hepatitis]', $AntecedenteGinecologico->p_hepatitis, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_hepatitis',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Hepatitis', 'p_hepatitis', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_herpes]', $AntecedenteGinecologico->p_herpes, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_herpes',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Herpes', 'p_herpes', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_lgv]', $AntecedenteGinecologico->p_lgv, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_lgv',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Linfogranuloma venéreo (LGV)', 'p_lgv', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_molusco_cont]', $AntecedenteGinecologico->p_molusco_cont, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_molusco_cont',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Molusco Contagioso', 'p_molusco_cont', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_ladillas]', $AntecedenteGinecologico->p_ladillas, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_ladillas',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Piojos "ladillas" pubicos', 'p_ladillas', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_sarna]', $AntecedenteGinecologico->p_sarna, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_sarna',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Sarna', 'p_sarna', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>

                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_sifilis]', $AntecedenteGinecologico->p_sifilis, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_sifilis',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Sifilis', 'p_sifilis', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>

                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_tricomoniasis]', $AntecedenteGinecologico->p_tricomoniasis, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_tricomoniasis',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Tricomoniasis', 'p_tricomoniasis', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_vb]', $AntecedenteGinecologico->p_vb, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_vb',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Vaginosis Bacteriana', 'p_vb', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>

                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_vih]', $AntecedenteGinecologico->p_vih, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_vih',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('VIH', 'p_vih', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_vph]', $AntecedenteGinecologico->p_vph, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_vph',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Virus del papiloma humano (VPH)', 'p_vph', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                    <div class="w-100"></div>
                                                    <br>
                                                    <div class="col-6 col-sm-4">
                                                        <h4>Anticoncepción</h4>
                                                    </div>
                                                    <div class="w-100"></div>
                                                    <div class="col-6 col-sm-6">
                                                        <?= Html::label('Tipo', 'p_tipo_anticoncepcion') ?>
                                                        <?= Html::textInput('AntecedenteGinecologico[p_tipo_anticoncepcion]', $modelAntecedenteGinecologico->p_tipo_anticoncepcion, ['class' => 'form-control', 'id' => 'p_tipo_anticoncepcion', 'disabled' => !$editable]) ?>
                                                    </div>
                                                    <div class="w-100"></div>

                                                    <div class="col-6 col-sm-3">
                                                        <?= Html::label('Inicio', 'p_inicio_anticoncepcion') ?>
                                                        <?= Html::input('date', 'AntecedenteGinecologico[p_inicio_anticoncepcion]', $AntecedenteGinecologico->p_inicio_anticoncepcion, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                    </div>
                                                    <div class="col-6 col-sm-3">
                                                        <?= Html::label('Suspensión', 'p_suspension_anticoncepcion') ?>
                                                        <?= Html::input('date', 'AntecedenteGinecologico[p_suspension_anticoncepcion]', $AntecedenteGinecologico->p_suspension_anticoncepcion, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                    </div>




                                                </div>
                                                <!-- Columna derecha con el textarea -->
                                                <br>
                                                <hr class="solid">





                                                <div class="form-group">
                                                    <?= Html::label('Observación General') ?>


                                                    <?php

                                                    if ($editable) {
                                                        echo $form->field($AntecedenteGinecologico, 'observacion')->widget(FroalaEditorWidget::className(), [
                                                            'clientOptions' => [
                                                                'toolbarInline' => false,
                                                                'theme' => 'royal', // optional: dark, red, gray, royal
                                                                'language' => 'es', // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                                                                'height' => 300,
                                                                'pluginsEnabled' => [
                                                                    'align', 'charCounter', 'codeBeautifier', 'codeView', 'colors',
                                                                    'draggable', 'emoticons', 'entities', 'fontFamily',
                                                                    'fontSize', 'fullscreen', 'inlineStyle',
                                                                    'lineBreaker', 'link', 'lists', 'paragraphFormat', 'paragraphStyle',
                                                                    'quickInsert', 'quote', 'save', 'table', 'url', 'wordPaste'
                                                                ]
                                                            ]
                                                        ])->label(false);
                                                    } else {
                                                        // Si no tiene permiso, mostrar el texto plano
                                                        $htmlcont = Html::decode($AntecedenteGinecologico->observacion);
                                                        echo HtmlPurifier::process($htmlcont);
                                                    }
                                                    ?>
                                                </div>

                                                <?php if (Yii::$app->user->can('editar-expediente-medico')) { ?>


                                                    <div class="form-group">
                                                        <?= Html::submitButton('Guardar Todos los Cambios &nbsp; &nbsp; <i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
                                                    </div>
                                                <?php } ?>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php ActiveForm::end(); ?>
                           