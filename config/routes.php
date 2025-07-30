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

	//tasks routes
	'/index'     => 'task#index',       // Mostrar todas las tareas
    '/view'      => 'task#taskView',        // Ver detalle de una tarea (requiere ?id=)
    '/create'    => 'task#create',      // Formulario + creación de tarea
    '/edit'      => 'task#edit',        // Formulario de edición (requiere ?id=)
    '/taskUpdate'    => 'task#update',      // Guardar edición (requiere ?id=)
    '/taskDelete'    => 'task#delete',      // Eliminar tarea (requiere ?id=)
	
);
