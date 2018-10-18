<?php

require_once '../negocio/sesion.clase.php';
require_once '../util/funciones/Funciones.clase.php';

try {
    $email = $_POST["txtusuario"];
    $clave = $_POST["campo"];

    $_COOKIE["codigocaptcha"]; //captcha generado
//    $captchatotal = $_COOKIE["codigocaptcha1"] + $_COOKIE["codigocaptcha2"] + $_COOKIE["codigocaptcha3"];
    $captcha = $_POST["txtcaptcha"]; //capturando captcha del input


    if (isset($_POST["chkrecordar"])) {
        $recordarUsuario = $_POST["chkrecordar"];
    } else {
        $recordarUsuario = "";
    }

    $objSesion = new Sesion();
    $objSesion->setEmail($email);
    $objSesion->setClave($clave);
    $objSesion->setRecordarUsuario($recordarUsuario);

    $resultado = $objSesion->iniciarSesion();

//    if ($_COOKIE["codigocaptcha"] == $captcha) {
       
        switch ($resultado) {
            case 0:
                Funciones::mensaje("El usuario esta inactivo", "a", "../vista/index.php", 1.7);
                break;

            case 1:
                //Funciones::mensaje("El usuario activo","a","../vista/principal.vista.php",5);                               
                unset($_COOKIE["codigocaptcha"]);
                header("location:../vista/principal.vista.php"); 
                break;

            case 2:
                Funciones::mensaje("Contraseña incorrecta", "e", "../vista/index.php", 1.7);
                break;

            default:
                break;
        }
//    }else{
//         Funciones::mensaje("captcha incorrecto", "a", "../vista/index.php", 1.7);
//    }
} catch (Exception $exc) {
    Funciones::mensaje($exc->getMessage(), "e");
}



