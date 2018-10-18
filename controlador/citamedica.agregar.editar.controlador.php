<?php

require_once '../negocio/Citamedica.clase.php';
require_once '../util/funciones/Funciones.clase.php';

if (! isset($_POST["p_datosFormulario"]) ){
    Funciones::imprimeJSON(500, "Faltan parametros", "");
    exit();
}

$datosFormulario = $_POST["p_datosFormulario"];

//Convertir todos los datos que llegan concatenados a un array
parse_str($datosFormulario, $datosFormularioArray);



//quitar
//print_r($datosFormularioArray);
//exit();

try {
    $objCita = new Citamedica();
    $objCita->setFecha_cita( $datosFormularioArray["txtfecha"] );
    $objCita->setTurno( $datosFormularioArray["cboturnomodal"] );
    $objCita->setNro_historia( $datosFormularioArray["cbopacientemodal"] );
    $objCita->setCod_medico( $datosFormularioArray["cbomedicomodal"] );
    
    if ($datosFormularioArray["txttipooperacion"]=="agregar"){
        echo ("hola");
        exit();
        $resultado = $objCita->agregar();
        if ($resultado==true){
            Funciones::imprimeJSON(200, "Grabado correctamente", "");
        }
    }
    else{
//        echo ("chau");
//        exit();
        $objCita->setNro_cita_medica($datosFormularioArray["txtcodigo"]);
        $resultado = $objCita->editar();
        if ($resultado==true){
            Funciones::imprimeJSON(200, "Grabado correctamente", "");
        }
    }
    
} catch (Exception $exc) {
    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}
