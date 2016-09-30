# php-listener
The simple to use and extensible Listener in php.

## How to use
Simple download the files and include the autoload.php like this in your source:
```php
<?php

require_once 'path/to/autoload.php';
```
(in this example,  we'll create a nice person who will greet us)

then you can start creating your first event, it is really simple. You need to extend the class ```jens1o\event\Event``` like this:

```php
<?php

namespace example\events;

use jens1o\event\Event;

class GreetEvent extends Event {

    /**
     * The greeting
     * @var string
     */
    private $greet;

    /**
     * Creates a new GreetEvent
     *
     * @param   $greet  string  The greeting
     */
    public function __construct($greet = 'People') {
        $this->greet = $greet;
    }

    /**
     * Returns the greeting
     */
    public function getGreeting() {
        return $this->greet;
    }

}
```

after that, we'll create the listener, where you need to implement the ```jens1o\event\Listener``` Interface and write some methods:
```php
<?php

use example\events\GreetEvent;
use jens1o\event\EventHandler;
use jens1o\event\Listener;

class GreetListener implements Listener {

    /**
     * The GreetEvent Executor
     *
     * This annotation IS really important. Methods also must be public, and not be static!
     * @EventHandler
     *
     * @param   $event  GreetEvent
     */
    public function whenTheyComeDoThe(GreetEvent $event) {
        echo 'Hello ' . $event->getGreeting();
        // outputs 'Hello People'
    }

}


// last but not least, register the listener
EventHandler::registerEvents(new GreetListener());
```

After all, we need to fire that:
```php
<?php

use example\events\GreetEvent;
use jens1o\event\EventHandler;

EventHandler::fireEvent(new GreetEvent());
// or
EventHandler::fireEvent(new GreetEvent('Jens'));
// would output 'Hello Jens'

```

done. It's simple, isn't it?

## Requirements:
- PHP 5.4 should work(and above)

## Todo:
- Publishing in Composer (when anything is done)
- Event Priority
- Set cancel/ignore cancel
- Correct my grammar mistakes...

##### License: MIT
