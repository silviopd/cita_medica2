<?php

//    require_once 'sesion.validar.controlador.php';

    require_once '../negocio/Medico.clase.php';
    require_once '../util/funciones/Funciones.clase.php';

    try {
        $p_codigoMedico=$_POST["p_codigoEspecialidad"];
	$obj = new Medico();
        $resultado = $obj->cargarListaDatos($p_codigoMedico);
	Funciones::imprimeJSON(200, "", $resultado);
	
    } catch (Exception $exc) {
	Funciones::imprimeJSON(500, $exc->getMessage(), "");
	
    }
