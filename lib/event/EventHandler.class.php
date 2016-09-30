<?php

namespace jens1o\event;

class EventHandler {
	
	/**
	 * All registered listeners
	 * @var array[]
	 */
	private static $listeners = [];
	
	/**
	 * Registers all events in the given class
	 * @param Listener $this
	 */
	public static function registerEvents(Listener $listener) {
		// We need to go through each method...
		$reflection = new \ReflectionClass($listener);
		foreach($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
			/** @var $method \ReflectionMethod */
			if($method->isStatic()) continue; // Should not be static
			
			$docComment = $method->getDocComment();
			if($docComment === false) continue; // No Doc Comment exists, so no event handler annotation
			
			$docComment = (string) $docComment; // strange hosts :O
			
			// Check @EventHandler Annotation
			if(!(preg_match("/^[\t ]*\\* @EventHandler/m", $docComment) > 0)) continue; // There is no annotation
			
			// looks valid, now we're checking the parameters
			$parameters = $method->getParameters();
			
			if(count($parameters) !== 1) self::throwException($method, $listener, 'There are too less or too much parameters. One is allowed.');
			
			
			// Check type hinting, if some exists, then check if it is a valid event
			if(($event = $parameters[0]->getClass()) === null) self::throwException($method, $listener, 'Invalid type hinting. You must specified the EventName.');
			
			$eventName = $event->getName();
			
			if(!is_subclass_of($eventName, Event::class)) self::throwException($method, $listener, 'The event ' . $eventName . ' does not extend the '. Event::class . ' Class');

			self::$listeners[$eventName][] = [
					'listener' => $listener,
					'method' => $method->getName()
			]; // When changing this, also change the method fireEvent!

		}
	}
	
	/**
	 * Executes all registered handlers
	 * 
	 * @param 	Event 	$event
	 */
	public static function fireEvent(Event $event) {
		
		if(empty(self::$listeners[get_class($event)])) return; // no listeners

		foreach(self::$listeners[get_class($event)] as $listenerInfos) {
			$instance = $listenerInfos['listener'];
			$method = $listenerInfos['method'];
			
			$instance->$method($event); // actual call
		}
		
	}
	
	/**
	 * Private helper function to throw exception when a error while registering occured
	 * 
	 * @param \ReflectionMethod 	$method
	 * @param Listener				$listener
	 * @param string 				$msg
	 */
	private static function throwException(\ReflectionMethod $method, Listener $listener, $msg) {
		throw new \Exception('Cannot register method ' . $method->getName() . ' in class ' . get_class($listener) . '! Error: ' . $msg);
	}
	
}
