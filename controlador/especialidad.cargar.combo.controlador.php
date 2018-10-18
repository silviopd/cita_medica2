<?php

//require_once 'sesion.validar.controlador.php';

require_once '../negocio/Especialidad.clase.php';
require_once '../util/funciones/Funciones.clase.php';

try {
    $obj = new Especialidad();
    $resultado = $obj->cargarListaDatos();
    Funciones::imprimeJSON(200, "", $resultado);
} catch (Exception $exc) {
    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}
