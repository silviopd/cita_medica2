<?php

require_once '../datos/Conexion.clase.php';

class Medico extends Conexion {
    
    public function cargarListaDatos($p_codigoEspecialidad){
	try {
            $sql = " select * from medico where cod_especialidad=:p_codigoEspecialidad";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia -> bindParam(":p_codigoEspecialidad",$p_codigoEspecialidad);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $exc) {
            throw $exc;
        }
    }
    
}
