<?php 

spl_autoload_register(function (string $className){
    $caminhoArquivo = str_replace('App\\', 'Apps\\', $className);
    $caminhoArquivo = str_replace('\\', DIRECTORY_SEPARATOR, $caminhoArquivo);
    $caminhoArquivo .= '.php';
 
    if(file_exists($caminhoArquivo)) {
        require_once $caminhoArquivo;
    }
});