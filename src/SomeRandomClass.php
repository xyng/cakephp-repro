<?php

namespace App;

use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Event\EventManager;

class SomeRandomClass {
    public static function doStuff() {
        // this event allows the users application to hook in and provide eg. a factory for us
        // we do not have access to the DI in this function, so Events are an option.
        // since we're in a static function and need to get a factory from the user, we don't really have a subject yet
        // (our specific use-case is to prevent instantiating the default factory as that might not work in the users environment)
        $eventWithoutSubject = new Event('getStuff');

        EventManager::instance()->on('getStuff', function (EventInterface $event) {
            // returning is deprecated from 5.2 - but that should only yield a warning.
            return "stuff!";

            // this is the non-deprecated way which does work after 5.2
            // $event->setResult('stuff');
        });

        // dispatching the correctly constructed event
        // 5.1: works!
        // 5.2: expected: deprecation warning. got: CakeException because the deprecation warning uses `getSubject`
        $dispatch = EventManager::instance()->dispatch($eventWithoutSubject);

        // do stuff with result
        $result = $dispatch->getResult();

        return $result;
    }
}
