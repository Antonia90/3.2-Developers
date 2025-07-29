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
	'/test' => 'test#index',
	
	//Rutas de user
	
	'/' => 'user#index',
	'/signup' => 'user#signup',
	'/login' => 'user#login',
	'/userView' => 'user#userView',
	'/logout' => 'user#logout',
	'/update' => 'user#update',
	'/logoutSuccess' => 'user#logoutSuccess',
	'/delete' => 'user#delete'




);
