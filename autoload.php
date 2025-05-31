<?php
    // Autoload para cargar las clases de los controladores
    function controllers_autoload($classname){
        $classname = str_replace("\\", "/", $classname); // Convertir namespace a ruta
        $file = __DIR__ . '/' . $classname . '.php'; // Agregar la barra "/"
        if (file_exists($file)) require_once $file;
    }

    spl_autoload_register('controllers_autoload');

?>