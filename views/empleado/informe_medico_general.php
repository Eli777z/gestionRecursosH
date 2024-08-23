<?php
/* @var $this yii\web\View */
/* @var $model app\models\Empleado */
/* @var $expedienteMedico app\models\ExpedienteMedico */
/* @var $alergia app\models\Alergia */

use yii\helpers\Html;

$this->title = 'Expediente Médico de ' . $model->nombre;
?>
<div class="expediente-medico-view">
    
<div class="container">
<h1>Información Médica</h1>
<div class="container">
<h2>Datos del empleado</h2>
    <div class="section">

    <p><strong>Nombre:</strong> <?= Html::decode($model->nombre.' '.$model->apellido) ?></p>
    <p><strong>Edad:</strong> <?= Html::decode($model->edad) ?></p>
    <p><strong>Sexo:</strong> <?= Html::decode($model->sexo) ?></p>

    <p><strong>Número de seguro social:</strong> <?= Html::decode($model->nss) ?></p>

    </div>
    </div>
    
    
    <div class="container">
    <h2>Alergias:</h2>
    <div class="section">

    <p> <?= Html::decode($alergia->p_alergia) ?></p>
    </div>
    </div>
    
    
    <div class="container">
    <h2>Antecedentes:</h2>
    <div class="container">

    <h3>Patológicos:</h3>
    <p><?= Html::decode($antecedentePatologico->descripcion_antecedentes) ?></p>
    </div>
    <div class="container">
    <h3>Hereditarios:</h3>

    <?php
    // Agrupa los antecedentes por parentesco
    $antecedentesPorParentesco = [
        'Abuelos' => [],
        'Hermanos' => [],
        'Madre' => [],
        'Padre' => []
    ];

    foreach ($catAntecedentes as $catAntecedente) {
        foreach (['Abuelos', 'Hermanos', 'Madre', 'Padre'] as $parentezco) {
            if (isset($antecedentesExistentes[$catAntecedente->id][$parentezco])) {
                $antecedentesPorParentesco[$parentezco][] = Html::encode($catAntecedente->nombre);
            }
        }
    }
    ?>

    <?php foreach ($antecedentesPorParentesco as $parentezco => $antecedentes) : ?>
        <?php if (!empty($antecedentes)) : ?>
            <p><strong><?= $parentezco ?>:</strong> <?= implode(', ', $antecedentes) ?></p>
        <?php endif; ?>
    <?php endforeach; ?>


    <?php
// Función para convertir valores tinyint a texto
function convertTinyintToYesNo($value) {
    return $value ? 'Sí' : 'No';
}
?>
    </div>
<div class="container">
    <h3>Antecedentes No Patológicos:</h3>
    
    <div class="section">
        <h5>Actividad Física</h5>
        <p><strong>Realiza ejercicio:</strong> <?= convertTinyintToYesNo($antecedenteNoPatologico->p_ejercicio) ?>
            <?php if ($antecedenteNoPatologico->p_ejercicio != 0) { ?>
                <strong> Minutos al día: </strong> <?= $antecedenteNoPatologico->p_minutos_x_dia_ejercicio ?>
            <?php } ?>
        </p>
        <p><strong>Realiza deporte:</strong> <?= convertTinyintToYesNo($antecedenteNoPatologico->p_deporte) ?>
            <?php if ($antecedenteNoPatologico->p_deporte != 0) { ?>
                <strong> Deporte: </strong> <?= $antecedenteNoPatologico->p_a_deporte ?>
                <strong> Frecuencia: </strong> <?= $antecedenteNoPatologico->p_frecuencia_deporte ?>
            <?php } ?>
        </p>
        <p><strong>Horas que duerme durante el día:</strong> <?= Html::decode($antecedenteNoPatologico->p_horas_sueño) ?></p>
        <p><strong>Duerme durante el día:</strong> <?= convertTinyintToYesNo($antecedenteNoPatologico->p_dormir_dia) ?></p>
        <p><strong>Observaciones:</strong> <?= Html::decode($antecedenteNoPatologico->observacion_actividad_fisica) ?></p>
    </div>
    
    <div class="section">
        <h5>Hábitos Alimenticios</h5>
        <p><strong>Desayuna:</strong> <?= convertTinyintToYesNo($antecedenteNoPatologico->p_desayuno) ?>
            <strong> Comidas al día: </strong> <?= $antecedenteNoPatologico->p_comidas_x_dia ?>
        </p>
        <p><strong>Toma café:</strong> <?= convertTinyintToYesNo($antecedenteNoPatologico->p_cafe) ?>
            <?php if ($antecedenteNoPatologico->p_cafe != 0) { ?>
                <strong>Tazas al día: </strong> <?= $antecedenteNoPatologico->p_tazas_x_dia ?>
            <?php } ?>
        </p>
        <p><strong>Sigue alguna dieta:</strong> <?= convertTinyintToYesNo($antecedenteNoPatologico->p_dieta) ?>
            <?php if ($antecedenteNoPatologico->p_dieta != 0) { ?>
                <strong>Información: </strong> <?= $antecedenteNoPatologico->p_info_dieta ?>
            <?php } ?>
        </p>
        <p><strong>Observaciones:</strong> <?= Html::decode($antecedenteNoPatologico->observacion_comida) ?></p>
    </div>
    
    <div class="section">
        <h5>Alcoholismo</h5>
        <p><strong>Consume alcohol:</strong> <?= convertTinyintToYesNo($antecedenteNoPatologico->p_alcohol) ?>
            <?php if ($antecedenteNoPatologico->p_alcohol != 0) { ?>
                <strong> Frecuencia: </strong> <?= $antecedenteNoPatologico->p_frecuencia_alcohol ?>
                <strong> Edad en la que inició: </strong> <?= $antecedenteNoPatologico->p_edad_alcoholismo ?>
                <strong> Copas al día: </strong> <?= $antecedenteNoPatologico->p_copas_x_dia ?>
                <strong> Cervezas al día: </strong> <?= $antecedenteNoPatologico->p_cervezas_x_dia ?>
            <?php } ?>
        </p>
        <p><strong>Ex-alcohólico:</strong> <?= convertTinyintToYesNo($antecedenteNoPatologico->p_ex_alcoholico) ?>
            <?php if ($antecedenteNoPatologico->p_ex_alcoholico != 0) { ?>
                <strong> Edad en la que dejó de beber: </strong> <?= $antecedenteNoPatologico->p_edad_fin_alcoholismo ?>
            <?php } ?>
        </p>
        <p><strong>Observaciones:</strong> <?= Html::decode($antecedenteNoPatologico->observacion_alcoholismo) ?></p>
    </div>
    
    <div class="section">
        <h5>Tabaquismo</h5>
        <p><strong>Fuma:</strong> <?= convertTinyintToYesNo($antecedenteNoPatologico->p_fuma) ?>
            <?php if ($antecedenteNoPatologico->p_fuma != 0) { ?>
                <strong> Frecuencia: </strong> <?= $antecedenteNoPatologico->p_frecuencia_tabaquismo ?>
                <strong> Edad en la que inició: </strong> <?= $antecedenteNoPatologico->p_edad_tabaquismo ?>
                <strong> Cigarros por día: </strong> <?= $antecedenteNoPatologico->p_no_cigarros_x_dia ?>
            <?php } ?>
        </p>
        <p><strong>Ex-fumador:</strong> <?= convertTinyintToYesNo($antecedenteNoPatologico->p_ex_fumador) ?>
            <?php if ($antecedenteNoPatologico->p_ex_fumador != 0) { ?>
                <strong> Edad en la que dejó de fumar: </strong> <?= $antecedenteNoPatologico->p_edad_fin_tabaquismo ?>
            <?php } ?>
        </p>
        <p><strong>Fumador pasivo:</strong> <?= convertTinyintToYesNo($antecedenteNoPatologico->p_fumador_pasivo) ?></p>
        <p><strong>Observaciones:</strong> <?= Html::decode($antecedenteNoPatologico->observacion_tabaquismo) ?></p>
    </div>
    
    <div class="section">
        <h5>Otros</h5>
        <p><strong>Religión:</strong> <?= Html::decode($antecedenteNoPatologico->religion) ?></p>
        <p><strong>Actividades en días libres:</strong> <?= Html::decode($antecedenteNoPatologico->p_act_dias_libres) ?></p>
        <?php if ($antecedenteNoPatologico->p_situaciones != 'Ninguna') { ?>
            <p><strong>Situaciones personales:</strong> <?= Html::decode($antecedenteNoPatologico->p_situaciones) ?></p>
        <?php } ?>
        <p><strong>Tipo de sangre:</strong> <?= Html::decode($antecedenteNoPatologico->tipo_sangre) ?></p>
        <p><strong>Descripción vivienda:</strong> <?= Html::decode($antecedenteNoPatologico->datos_vivienda) ?></p>
    </div>
    
    <div class="section">
        <h5>Consumo de Drogas</h5>
        <p><strong>Consume algún tipo de droga:</strong> <?= Html::decode($antecedenteNoPatologico->p_drogas) ?>
            <?php if ($antecedenteNoPatologico->p_drogas != 0) { ?>
                <strong> Frecuencia: </strong> <?= $antecedenteNoPatologico->p_frecuencia_droga ?>
                <strong> Edad en la que inició: </strong> <?= $antecedenteNoPatologico->p_edad_droga ?>
                <strong> Usa droga intravenosa: </strong> <?= convertTinyintToYesNo($antecedenteNoPatologico->p_droga_intravenosa) ?>
            <?php } ?>
        </p>
        <p><strong>Ex-adicto:</strong> <?= convertTinyintToYesNo($antecedenteNoPatologico->p_ex_adicto) ?>
            <?php if ($antecedenteNoPatologico->p_ex_adicto != 0) { ?>
                <strong> Edad en la que dejó de consumir: </strong> <?= $antecedenteNoPatologico->p_edad_fin_droga ?>
            <?php } ?>
        </p>
        <p><strong>Observaciones:</strong> <?= Html::decode($antecedenteNoPatologico->observacion_droga) ?></p>
    </div>

    <div class="section">
        <h5>Observación General / Otras Observaciones</h5>
        <p><?= Html::decode($antecedenteNoPatologico->observacion_general) ?></p>
    </div>
</div>

<style>
    .section {
        margin-bottom: 20px;
    }
    h3, h5 {
        color: #3c8dbc;
    }
    p {
        font-size: 14px;
        line-height: 1.6;
    }
</style>


<div class="container">
<div class="section">
    <h3>Antecedentes Perinatales:</h3>
    <p><strong>Hora de nacimiento:</strong> <?= Html::decode($antecedentePerinatal->p_hora_nacimiento) ?></p>
    <p><strong>Número de gestación:</strong> <?= Html::decode($antecedentePerinatal->p_no_gestacion) ?></p>
    <p><strong>Edad gestacional:</strong> <?= Html::decode($antecedentePerinatal->p_edad_gestacional) ?></p>

    <?php if($antecedentePerinatal->p_parto === 1 ){ ?>
    <p><strong>Parto:</strong> <?= convertTinyintToYesNo($antecedentePerinatal->p_parto) ?>  </p> 
    <?php }?>
    <?php if($antecedentePerinatal->p_cesarea === 1 ){ ?>
    <p><strong>Cesarea:</strong> <?= convertTinyintToYesNo($antecedentePerinatal->p_cesarea) ?> </p> 
    <?php }?>

    <p><strong>Sitio del parto:</strong> <?= Html::decode($antecedentePerinatal->p_sitio_parto) ?></p>

    <p><strong>Peso al nacer:</strong> <?= Html::decode($antecedentePerinatal->p_peso_nacer) ?> <strong>Talla:</strong> <?= Html::decode($antecedentePerinatal->p_talla) ?></p>
</div>
<div class="section">

    <h5>Perimetros (cm)</h5>
        <p><strong>Céfalico:</strong> <?= Html::decode($antecedentePerinatal->p_cefalico) ?>
        <strong> Toracico:</strong> <?= Html::decode($antecedentePerinatal->p_toracico) ?>
        <strong> Abdominal:</strong> <?= Html::decode($antecedentePerinatal->p_abdominal) ?>
    </p>
</div>
<div class="section">


    <?php if($antecedentePerinatal->p_apnea_neonatal === 1 ){ ?>
    <p><strong>Apnea neonatal:</strong> <?= convertTinyintToYesNo($antecedentePerinatal->p_apnea_neonatal) ?>  </p> 
    <?php }?>

    <?php if($antecedentePerinatal->p_cianosis === 1 ){ ?>
    <p><strong>Cianosis:</strong> <?= convertTinyintToYesNo($antecedentePerinatal->p_cianosis) ?>  </p> 
    <?php }?>

    <?php if($antecedentePerinatal->p_hemorragias === 1 ){ ?>
    <p><strong>Hemorragias:</strong> <?= convertTinyintToYesNo($antecedentePerinatal->p_hemorragias) ?>  </p> 
    <?php }?>

    <?php if($antecedentePerinatal->p_ictericia === 1 ){ ?>
    <p><strong>Ictericia:</strong> <?= convertTinyintToYesNo($antecedentePerinatal->p_ictericia) ?>  </p> 
    <?php }?>

    <?php if($antecedentePerinatal->p_convulsiones === 1 ){ ?>
    <p><strong>Convulsiones:</strong> <?= convertTinyintToYesNo($antecedentePerinatal->p_convulsiones) ?>  </p> 
    <?php }?>
</div>
<div class="section">

    <h5>OBSERVACION GENERAL / OTRAS OBSERVACIONES</h5>
<p><?= Html::decode($antecedentePerinatal->observacion) ?></p>
</div>
</div>
<?php if($model->sexo === "Femenino" ){ ?>
<div class="container">

<h3>Ginecologicos:</h3>
<div class="section">

<p><strong>Menarca:</strong> <?= Html::decode($antecedenteGinecologico->p_menarca) ?></p>

<p><strong>Menopausia:</strong> <?= Html::decode($antecedenteGinecologico->p_menopausia) ?></p>

<p><strong>Fecha ultima menstración:</strong> <?= Html::decode($antecedenteGinecologico->p_f_u_m) ?></p>

<p><strong>Inicio vida sexual:</strong> <?= Html::decode($antecedenteGinecologico->p_inicio_vida_s) ?></p>

<p><strong>Número de parejas:</strong> <?= Html::decode($antecedenteGinecologico->p_no_parejas) ?></p>

<p><strong>Padece:</strong> 

<?php if($antecedenteGinecologico->p_vaginits === 1 ){ ?>
   Vaginitis, 
<?php }?>

<?php if($antecedenteGinecologico->p_cervicitis_mucopurulenta === 1 ){ ?>
   Cervicitis Mucopurulenta,
<?php }?>

<?php if($antecedenteGinecologico->p_chancroide === 1 ){ ?>
    Chancroide,
<?php }?>

<?php if($antecedenteGinecologico->p_clamidia === 1 ){ ?>
    Clamidia,

    <?php }?>

<?php if($antecedenteGinecologico->p_eip === 1 ){ ?>
    Enfermedad Inflamatoria Pélvica (E.I.P.),

    <?php }?>

<?php if($antecedenteGinecologico->p_gonorrea === 1 ){ ?>
    Gonorrea,

    <?php }?>

<?php if($antecedenteGinecologico->p_hepatitis === 1 ){ ?>
    Hepatitis,

    <?php }?>

<?php if($antecedenteGinecologico->p_herpes === 1 ){ ?>
    Herpes,

    <?php }?>

<?php if($antecedenteGinecologico->p_lgv === 1 ){ ?>
    Linfogranuloma venéreo (LGV),

    <?php }?>

<?php if($antecedenteGinecologico->p_molusco_cont === 1 ){ ?>
    Molusco Contagioso,

    <?php }?>

<?php if($antecedenteGinecologico->p_ladillas === 1 ){ ?>
    Piojos "ladillas" pubicos,

    <?php }?>

<?php if($antecedenteGinecologico->p_sarna === 1 ){ ?>
    Sarna,

    <?php }?>

<?php if($antecedenteGinecologico->p_sifilis === 1 ){ ?>
    Sifilis,

    <?php }?>

<?php if($antecedenteGinecologico->p_tricomoniasis === 1 ){ ?>
    Tricomoniasis,

    <?php }?>

<?php if($antecedenteGinecologico->p_vb === 1 ){ ?>
    Vaginosis Bacteriana,

    <?php }?>

<?php if($antecedenteGinecologico->p_vih === 1 ){ ?>
    VIH,

    <?php }?>

<?php if($antecedenteGinecologico->p_vph === 1 ){ ?>
    Virus del papiloma humano (VPH),

    <?php }?>

</p>
</div>
<div class="section">

<h5>Anticoncepción</h5>
<p><strong>Tipo:</strong> <?= Html::decode($antecedenteGinecologico->p_tipo_anticoncepcion) ?></p>
<p><strong>Inicio:</strong> <?= Html::decode($antecedenteGinecologico->p_inicio_anticoncepcion) ?></p>
<p><strong>Suspensión:</strong> <?= Html::decode($antecedenteGinecologico->p_suspension_anticoncepcion) ?></p>
</div>
<div class="section">

<h5>OBSERVACION GENERAL / OTRAS OBSERVACIONES</h5>
<p><?= Html::decode($antecedenteGinecologico->observacion) ?></p>
</div>
</div>
<?php }?>


<?php if($model->sexo === "Femenino" ){ ?>

<div class="container">

<h3>Obstrecticos</h3>
<div class="section">

<?php if($antecedenteObstrectico->p_gestacion != null ){ ?>

<p><strong>Gestación: </strong> <?= Html::decode($antecedenteObstrectico->p_gestacion) ?></p>
<?php }?>
<?php if($antecedenteObstrectico->p_aborto != null ){ ?>
<p><strong>Aborto: </strong> <?= Html::decode($antecedenteObstrectico->p_aborto) ?></p>
<?php }?>


<?php if($antecedenteObstrectico->p_parto != null ){ ?>
<p><strong>Parto: </strong> <?= Html::decode($antecedenteObstrectico->p_parto) ?></p>
<?php }?>

<?php if($antecedenteObstrectico->p_cesarea != null ){ ?>
<p><strong>Cesarea: </strong> <?= Html::decode($antecedenteObstrectico->p_cesarea) ?></p>
<?php }?>

<?php if($antecedenteObstrectico->p_nacidos_vivo != null ){ ?>
<p><strong>Nacidos vivos: </strong> <?= Html::decode($antecedenteObstrectico->p_nacidos_vivo) ?></p>
<?php }?>

<?php if($antecedenteObstrectico->p_viven != null ){ ?>
<p><strong>Viven: </strong> <?= Html::decode($antecedenteObstrectico->p_viven) ?></p>
<?php }?>

<?php if($antecedenteObstrectico->p_nacidos_muerto != null ){ ?>
<p><strong>Nacidos muertos: </strong> <?= Html::decode($antecedenteObstrectico->p_nacidos_muerto) ?></p>
<?php }?>

<?php if($antecedenteObstrectico->p_medicacion_gestacional != null ){ ?>
<p><strong>Medicación gestacional: </strong> <?= Html::decode($antecedenteObstrectico->p_medicacion_gestacional) ?></p>
<?php }?>
</div>
<div class="section">

<h5>OBSERVACION GENERAL / OTRAS OBSERVACIONES</h5>
<p><?= Html::decode($antecedenteObstrectico->observacion) ?></p>
</div>
</div>

<?php }?>

    </div>



    <div class="container">
    <h2>Exploración fisica:</h2>
    <div class="section">
    <h3>Habitus Exterior:</h3>
    <p><?= Html::decode($exploracionFisica->desc_habitus_exterior) ?></p>
    </div>

    <div class="section">
    <h3>Cabeza:</h3>
    <p><?= Html::decode($exploracionFisica->desc_cabeza) ?></p>
    </div>


    <div class="section">
    <h3>Cuello:</h3>
    <p><?= Html::decode($exploracionFisica->desc_cuello) ?></p>
    </div>


    <div class="section">
    <h3>Torax:</h3>
    <p><?= Html::decode($exploracionFisica->desc_torax) ?></p>
    </div>

    <div class="section">
    <h3>Abdomen:</h3>
    <p><?= Html::decode($exploracionFisica->desc_abdomen) ?></p>
    </div>
    <?php if($model->sexo === "Femenino" ){ ?>

    <div class="section">
    <h3>Exploración ginecologica:</h3>
    <p><?= Html::decode($exploracionFisica->desc_exploración_ginecologica) ?></p>
    </div>
    <?php } ?>
    <div class="section">
    <h3>Exploración de genitales:</h3>
    <p><?= Html::decode($exploracionFisica->desc_genitales) ?></p>
    </div>

    <div class="section">
    <h3>Columna vertebral:</h3>
    <p><?= Html::decode($exploracionFisica->desc_columna_vertebral) ?></p>
    </div>

    <div class="section">
    <h3>Extremidades:</h3>
    <p><?= Html::decode($exploracionFisica->desc_extremidades) ?></p>
    </div>

    <div class="section">
    <h3>Exploración neurologica:</h3>
    <p><?= Html::decode($exploracionFisica->desc_exploracion_neurologica) ?></p>
    </div>


    </div>

    <div class="container">
    <h2>Interrogatorio médico:</h2>

    <div class="section">
    <h3>Cardiovascular:</h3>
    <p><?= Html::decode($interrogatorioMedico->desc_cardiovascular) ?></p>
    </div>


    <div class="section">
    <h3>Digestivo:</h3>
    <p><?= Html::decode($interrogatorioMedico->desc_digestivo) ?></p>
    </div>

    <div class="section">
    <h3>Endocrino:</h3>
    <p><?= Html::decode($interrogatorioMedico->desc_endocrino) ?></p>
    </div>

    <?php if($model->sexo === "Femenino" ){ ?>
    <div class="section">
    <h3>Mamas:</h3>
    <p><?= Html::decode($interrogatorioMedico->desc_mamas) ?></p>
    </div>
    <?php } ?>

    <div class="section">
    <h3>Piel y anexos:</h3>
    <p><?= Html::decode($interrogatorioMedico->desc_piel_anexos) ?></p>
    </div>

    <div class="section">
    <h3>Reproductor:</h3>
    <p><?= Html::decode($interrogatorioMedico->desc_reproductor) ?></p>
    </div>

    <div class="section">
    <h3>Respiratorio:</h3>
    <p><?= Html::decode($interrogatorioMedico->desc_respiratorio) ?></p>
    </div>

    <div class="section">
    <h3>Sistema nervioso:</h3>
    <p><?= Html::decode($interrogatorioMedico->desc_sistema_nervioso) ?></p>
    </div>

    <div class="section">
    <h3>Sistemas generales:</h3>
    <p><?= Html::decode($interrogatorioMedico->desc_sistemas_generales) ?></p>
    </div>


    <div class="section">
    <h3>Urinario:</h3>
    <p><?= Html::decode($interrogatorioMedico->desc_urinario) ?></p>
    </div>







    </div>
    
<style>
    .floating-btn {
        position: fixed;
        bottom: 45%; /* Ajusta la distancia desde la parte inferior */
        right: 30%; /* Ajusta la distancia desde la parte derecha */
        z-index: 1000; /* Asegúrate de que el botón esté por encima de otros elementos */
        padding: 10px 20px; /* Ajusta el relleno del botón */
        border-radius: 50px; /* Haz que el botón sea redondeado */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Añade una sombra para darle un efecto flotante */
    }
</style>

    <!--.card-->


</div>
<?= Html::a('Imprimir', '#', ['class' => 'btn btn-primary no-print floating-btn', 'onclick' => 'window.print(); return false;']) 
//PERMITE ABRIR LA VENTANA DE IMPRESIÓN DEL NAVEGADOR PARA IMPRIMIR EL CONTENIDO HTML, ES DECIR, EL FORMATO GENERADO A FORMA DE HTML
?>
</div>
</div>


<style>
    .section {
        margin-bottom: 20px;
    }

    h3, h5 {
        color: #3c8dbc;
    }

    p {
        font-size: 14px;
        line-height: 1.6;
    }

    /* Ocultar el botón de imprimir al imprimir la página */
    @media print {
        .no-print {
            display: none;
        }
    }
</style>