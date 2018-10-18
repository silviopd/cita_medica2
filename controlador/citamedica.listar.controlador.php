<?php

require_once '../negocio/Citamedica.clase.php';
require_once '../util/funciones/Funciones.clase.php';

try {    
    
    $objCita = new Citamedica();
    $resultado = $objCita->listar();
    
    Funciones::imprimeJSON(200, "", $resultado);
    
    
} catch (Exception $exc) {
    //Funciones::mensaje($exc->getMessage(), "e");
    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}

