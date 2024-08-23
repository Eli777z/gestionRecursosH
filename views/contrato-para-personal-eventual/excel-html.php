<?php
use yii\helpers\Html;

$this->title = 'Solicitud_contrato_personal_eventual_'.$model->empleado->nombre.' '.$model->empleado->apellido;
//$this->params['breadcrumbs'][] = $this->title;
?>
<head>
    <!-- bootstrap 5.x or 4.x is supported. You can also use the bootstrap css 3.3.x versions -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
 
 <!-- default icons used in the plugin are from Bootstrap 5.x icon library (which can be enabled by loading CSS below) -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
  
 <!-- alternatively you can use the font awesome icon library if using with `fas` theme (or Bootstrap 4.x) by uncommenting below. -->
 <!-- link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" crossorigin="anonymous" -->
  
 <!-- the fileinput plugin styling CSS file -->
 <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
  
 <!-- if using RTL (Right-To-Left) orientation, load the RTL CSS file after fileinput.css by uncommenting below -->
 <!-- link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/css/fileinput-rtl.min.css" media="all" rel="stylesheet" type="text/css" /-->
  
 <!-- the jQuery Library -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  
 <!-- buffer.min.js and filetype.min.js are necessary in the order listed for advanced mime type parsing and more correct
      preview. This is a feature available since v5.5.0 and is needed if you want to ensure file mime type is parsed 
      correctly even if the local file's extension is named incorrectly. This will ensure more correct preview of the
      selected file (note: this will involve a small processing overhead in scanning of file contents locally). If you 
      do not load these scripts then the mime type parsing will largely be derived using the extension in the filename
      and some basic file content parsing signatures. -->
 <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/buffer.min.js" type="text/javascript"></script>
 <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/filetype.min.js" type="text/javascript"></script>
  
 <!-- piexif.min.js is needed for auto orienting image files OR when restoring exif data in resized images and when you
     wish to resize images before upload. This must be loaded before fileinput.min.js -->
 <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/piexif.min.js" type="text/javascript"></script>
  
 <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. 
     This must be loaded before fileinput.min.js -->
 <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/plugins/sortable.min.js" type="text/javascript"></script>
  
 <!-- bootstrap.bundle.min.js below is needed if you wish to zoom and preview file content in a detail modal
     dialog. bootstrap 5.x or 4.x is supported. You can also use the bootstrap js 3.3.x versions. -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  
 <!-- the main fileinput plugin script JS file -->
 <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/fileinput.min.js"></script>
  
 <!-- following theme script is needed to use the Font Awesome 5.x theme (`fa5`). Uncomment if needed. -->
 <!-- script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/themes/fa5/theme.min.js"></script -->
  
 <!-- optionally if you need translation for your language then include the locale file as mentioned below (replace LANG.js with your language locale) -->
 <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.0/js/locales/LANG.js"></script>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<style>
    @media print {
        .container-fluid, body * {
            visibility: hidden; /* Ocultar todos los elementos */
        }
        .excel-content, .excel-content * {
            visibility: visible; /* Hacer visible solo el área de impresión */
        }
        .print-area {
            position: absolute;
            top: 5%;
            
        left: 4%;
            width: 200%;
            max-height: 100%;
            transform: scale(0.9); /* Escalar el contenido al 75% */
            transform-origin: top left; /* Ajustar el origen de la transformación */
        }
        /* Márgenes de la página */
        @page {
            size: auto;
            margin: 0;
        }
        /* Ajustar el contenido al área imprimible */
        body {
            margin: 0;
            padding: 0;
        }
    }
</style>
<div class="row justify-content-center">

           
            
<div class="excel-html-preview">
    <div class="print-area">
        <div class="excel-content">
            <?= $htmlContent ?>
        </div>
    </div>
</div>



   

            <!--.row-->
       
        <!--.card-body-->


<style>
    .floating-btn {
        position: fixed;
        bottom: 45%; /* Ajusta la distancia desde la parte inferior */
        right: 15%; /* Ajusta la distancia desde la parte derecha */
        z-index: 1000; /* Asegúrate de que el botón esté por encima de otros elementos */
        padding: 10px 20px; /* Ajusta el relleno del botón */
        border-radius: 50px; /* Haz que el botón sea redondeado */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Añade una sombra para darle un efecto flotante */
    }
</style>

    <!--.card-->


</div>
<?= Html::a('Imprimir', '#', ['class' => 'btn btn-primary no-print floating-btn', 'onclick' => 'window.print(); return false;']) ?>

