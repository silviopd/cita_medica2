<?php

    require_once '../negocio/Citamedica.clase.php';
    require_once '../util/funciones/Funciones.clase.php';

    if (! isset($_POST["codigoCitaMedica"])){
	Funciones::imprimeJSON(500, "Faltan parametros", "");
	exit();
    }
    
    try {
        $objCita = new Citamedica();
        $codigoCitaMedica = $_POST["codigoCitaMedica"];
        $resultado = $objCita->eliminar($codigoCitaMedica);
        if ($resultado == true){
            //Eliminó correctamente
            Funciones::imprimeJSON(200, "El registro se ha eleiminado satisfactoriamente", "");
        }
    } catch (Exception $exc) {
        Funciones::imprimeJSON(500, $exc->getMessage(), "");
    }

    