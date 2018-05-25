<?php

// Autoload classes
function clients_web_service_autoloader($class_name) 
{
    if (false !== strpos($class_name, 'ClientsWebService')) 
    {
        $classes_dir = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;
        $class_file = str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
        require_once $classes_dir . $class_file;
    } 
}
spl_autoload_register( 'clients_web_service_autoloader' );
