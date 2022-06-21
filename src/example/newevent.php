<?php

require_once('../SimpleCalDAVClient.php');

if($_POST == null) {
    echo '
<form action="#" method="post">
    <p>Введите данные о событии</p>
    <p>Наименование события:<br><input name="summary" type="text"></p>
    <p>Начало:<br><input name="start" type="datetime-local"></p>
    <p>Конец:<br><input name="end" type="datetime-local"></p>
    <p>Описание:<br><input name="description" type="text"></p>
    <p>Место:<br><input name="location" type="text"></p>
    <input type="submit" value="Создать событие">
</form>';

} else {

    $summaryEvent = $_POST['summary'];
    $startEvent = date( 'Ymd\THis', strtotime( $_POST['start'] ) );
    $endEvent = date( 'Ymd\THis', strtotime( $_POST['end'] ) );
    $descriptionEvent = $_POST['description'];
    $locationEvent = $_POST['location'];

    $NewUUID = uniqid();



    $NewEvent = 'BEGIN:VCALENDAR
PRODID:-//REZHCABLE//CalDAV//RU
VERSION:2.0
BEGIN:VTIMEZONE
TZID:Asia/Yekaterinburg
X-LIC-LOCATION:Asia/Yekaterinburg
END:VTIMEZONE
BEGIN:VEVENT
UID:'.$NewUUID.'
SUMMARY:'.$summaryEvent.'
DTSTAMP:'.$startEvent.'
DTSTART;TZID=Asia/Yekaterinburg:'.$startEvent.'
DTEND;TZID=Asia/Yekaterinburg:'.$endEvent.'
LOCATION:'.$locationEvent.'
DESCRIPTION:'.$descriptionEvent.'
END:VEVENT
END:VCALENDAR';


    // echo $NewEvent;

    $client = new SimpleCalDAVClient();

    try {
        
        $client->connect('https://caldav.yandex.ru/calendars/user/calendar_id/', 'user', 'password');
        $arrayOfCalendars = $client->findCalendars();
        $client->setCalendar($arrayOfCalendars["calendar_id"]);
        
        $NewEventOnServer = $client->create($NewEvent);

        echo $NewEventOnServer->getData();

    }

    catch (Exception $e) {
        echo $e->__toString();
    }

}






?>