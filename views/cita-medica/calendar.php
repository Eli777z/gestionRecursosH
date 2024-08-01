<?php
use yii\helpers\Url;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Calendario de Citas Médicas</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.js"></script>
</head>
<body>

<div id="calendar"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: '<?= Url::to(['cita-medica/calendar']) ?>', // Llama a la acción del controlador que devuelve eventos en formato JSON
    });

    calendar.render();
});
</script>

</body>
</html>
