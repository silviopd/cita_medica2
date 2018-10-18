<?php

require_once '../datos/Conexion.clase.php';

class Paciente extends Conexion {

    public function cargarListaDatos() {
        try {
            $sql = "SELECT 
nro_historia as codigo,
  (paciente.apellidos||', '|| paciente.nombres)::varchar paciente
FROM 
  public.paciente;
";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

}
