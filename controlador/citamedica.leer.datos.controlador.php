<?php

require_once '../negocio/Citamedica.clase.php';
require_once '../util/funciones/Funciones.clase.php';

if (! isset($_POST["p_codigoCitaMedica"]) ){
    Funciones::imprimeJSON(500, "Faltan parametros", "");
    exit();
}

try {
    $objCita = new Citamedica();
    $codigoCitaMedica = $_POST["p_codigoCitaMedica"];
    $resultado = $objCita->leerDatos($codigoCitaMedica);
    
    Funciones::imprimeJSON(200, "", $resultado);
} catch (Exception $exc) {
    Funciones::imprimeJSON(500 ,$exc->getMessage(),"");
}


