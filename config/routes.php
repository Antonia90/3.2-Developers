<?php 

/**
 * Used to define the routes in the system.
 * 
 * A route should be defined with a key matching the URL and an
 * controller#action-to-call method. E.g.:
 * 
 * '/' => 'index#index',
 * '/calendar' => 'calendar#index'
 */
$routes = array(
	'/'=>'tag#gestionar',
	'/tag/gestionar'=>'tag#gestionar'
	//'/test' => 'test#index', -> desbloquear para hacer testing
);
