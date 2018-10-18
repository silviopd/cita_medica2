<?php

require_once '../datos/Conexion.clase.php';

class Especialidad extends Conexion {

    public function cargarListaDatos() {
        try {
            $sql = "select * from especialidad order by 2";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();

            $resultado = $sentencia->fetchall(PDO::FETCH_ASSOC);

            return $resultado;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

//    public function listar() {
//        try {
//            $sql = "select * from linea order by 2";
//            $sentencia = $this->dblink->prepare($sql);
//            $sentencia->execute();
//
//            $resultado = $sentencia->fetchall(PDO::FETCH_ASSOC);
//
//            return $resultado;
//        } catch (Exception $exc) {
//            throw $exc;
//        }
//    }

}
