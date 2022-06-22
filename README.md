# laravel-caldav

Пакет Laravel - клиент CalDav.


## Установка

composer.json

```json

 "require": {

        "plsatin/caldav": "dev-master"
    },

    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/plsatin/laravel-caldav"
        }
    ]


```

## Пример использования


```php
<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Plsatin\Caldav\SimpleCalDAVClient;


class CalDavEventController extends Controller
{

    public function newEvent(Request $request)
    {
       
            $summaryEvent = $request->input('summary');
            $startEvent = date( 'Ymd\THis', strtotime( $request->input('start')) );
            $endEvent = date( 'Ymd\THis', strtotime( $request->input('end') ) );
            $descriptionEvent = $request->input('description');
            $locationEvent = $request->input('location');
        
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

            $client = new SimpleCalDAVClient();
        
            try {
                
                $client->connect(env('CALDAV_URL'), env('CALDAV_USER'), env('CALDAV_PASSWORD'));
                $arrayOfCalendars = $client->findCalendars();
                $client->setCalendar($arrayOfCalendars[env('CALDAV_CALID')]);
                
                $NewEventOnServer = $client->create($NewEvent);
        
                echo $NewEventOnServer->getData();
        
            }

        catch (Exception $e) {
            echo $e->__toString();
        }
  
    }


}



```
