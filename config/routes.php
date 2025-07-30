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

	//'/'=>'tag#gestionar',
	'/tag/gestionar'=>'tag#gestionar'
	//'/test' => 'test#index', -> desbloquear para hacer testing
	'/test' => 'test#index',


	//tasks routes
	'/index'     => 'task#index',       // Mostrar todas las tareas
    '/view'      => 'task#taskView',        // Ver detalle de una tarea (requiere ?id=)
    '/create'    => 'task#create',      // Formulario + creación de tarea
    '/edit'      => 'task#edit',        // Formulario de edición (requiere ?id=)
    '/taskUpdate'    => 'task#update',      // Guardar edición (requiere ?id=)
    '/taskDelete'    => 'task#delete',      // Eliminar tarea (requiere ?id=)
	

	
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
