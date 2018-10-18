<?php

session_name("SitemaComercial1");
    session_start();
    
    //validar usuario para que no ingrese por la ruta directamente
    if (!isset($_SESSION["s_codigo_usuario"])) {
        header("location:index.php");
        exit;
    }
    
    //capturando datos del usuario
    $nombreUsuario= ucwords(strtolower($_SESSION["s_nombre_usuario"]));
//    $cargoUsuario=$_SESSION["s_cargo_usuario"];
    $codigoUsuario=$_SESSION["s_codigo_usuario"];
    
    if (file_exists("../imagenes/".$codigoUsuario.".png")) {
        $fotoUsuario=$codigoUsuario.".png";
    }else{
        $fotoUsuario="sin-foto.jpg";
    }