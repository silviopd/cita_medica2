<?php

require '../datos/Conexion.clase.php';

class Sesion extends Conexion {

    private $email;
    private $clave;
    private $recordarUsuario;

    function getEmail() {
        return $this->email;
    }

    function getClave() {
        return $this->clave;
    }

    function getRecordarUsuario() {
        return $this->recordarUsuario;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setClave($clave) {
        $this->clave = $clave;
    }

    function setRecordarUsuario($recordarUsuario) {
        $this->recordarUsuario = $recordarUsuario;
    }

    public function iniciarSesion() {
        try {
            $sql = "select * from usuario where email=:p_email";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_email", $this->getEmail());
            $sentencia->execute();
            $resultado = $sentencia->fetch();

            if ($resultado["clave"] == md5($this->getClave())) {
                if ($resultado["estado"] == "I") {
                    return 0;
                } else {
                    session_name("SitemaComercial1");
                    session_start();

                    $_SESSION["s_nombre_usuario"] =$resultado["nombre"];
//                    $_SESSION["s_cargo_usuario"] = $resultado["cargo"];
                    $_SESSION["s_codigo_usuario"] = $resultado["codigo_usuario"];

                    if ($this->getRecordarUsuario() == "S") {
                        setcookie("loginusuario", $this->getEmail(), 0, "/");
                    } else {
                        setcookie("loginusuario", "", 0, "/");
                    }
                    return 1;
                }
            } else {
                return 2;
            }
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    function captcha() {        
        $v1 = rand(0, 9);
        $v2 = rand(0, 9);
        $v3 = rand(0, 9);
        $v4 = rand(0, 9);
        $cadena = $v1.$v2.$v3.$v4;
        setcookie("codigocaptcha", $cadena, time() + 315360000, "/");
        return $cadena;
    }
    
     public function numeroAleatorio() {
        $array[0] = 11;
        do {
            $numero = rand(0, 9);
            if (in_array($numero, $array)) {
//                echo 'Numero repetido<br>';
            } else {
                $array[] = $numero;
            }
        } while (count($array) <= 10);
        return $array;
    }

}
