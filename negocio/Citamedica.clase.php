<?php

require_once '../datos/Conexion.clase.php';

class Citamedica extends Conexion {

  private $nro_cita_medica;
  private $nro_historia;
  private $cod_medico;
  private $fecha_cita;
  private $estado;
  private $codigo_usuario;
  private $turno;
  
  function getNro_cita_medica() {
      return $this->nro_cita_medica;
  }

  function getNro_historia() {
      return $this->nro_historia;
  }

  function getCod_medico() {
      return $this->cod_medico;
  }

  function getFecha_cita() {
      return $this->fecha_cita;
  }

  function getEstado() {
      return $this->estado;
  }

  function getCodigo_usuario() {
      return $this->codigo_usuario;
  }

  function getTurno() {
      return $this->turno;
  }

  function setNro_cita_medica($nro_cita_medica) {
      $this->nro_cita_medica = $nro_cita_medica;
  }

  function setNro_historia($nro_historia) {
      $this->nro_historia = $nro_historia;
  }

  function setCod_medico($cod_medico) {
      $this->cod_medico = $cod_medico;
  }

  function setFecha_cita($fecha_cita) {
      $this->fecha_cita = $fecha_cita;
  }

  function setEstado($estado) {
      $this->estado = $estado;
  }

  function setCodigo_usuario($codigo_usuario) {
      $this->codigo_usuario = $codigo_usuario;
  }

  function setTurno($turno) {
      $this->turno = $turno;
  }

            
    public function listar() {
        try {
            $sql = "SELECT 
  cita_medica.nro_cita_medica as codigo, 
  cita_medica.fecha_cita, 
  (case when cita_medica.estado='1' then 'Activo' else 'Inactivo' end)::varchar estado,    
  (paciente.apellidos ||', '||  paciente.nombres)::varchar paciente,
  medico.nombre as medico,
  especialidad.descripcion as especialidad,
  (case when cita_medica.turno='M' then 'MAÑANA' else (case when  cita_medica.turno='T' then 'TARDE' else 'NOCHE' end) end)::varchar as turno,  
  usuario.nombre as usuario
FROM 
  public.cita_medica, 
  public.especialidad, 
  public.medico, 
  public.paciente, 
  public.usuario
WHERE 
  cita_medica.codigo_usuario = usuario.codigo_usuario AND
  especialidad.cod_especialidad = medico.cod_especialidad AND
  medico.cod_medico = cita_medica.cod_medico AND
  paciente.nro_historia = cita_medica.nro_historia";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();

            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function eliminar($p_codigoCitaMedica) {
        $this->dblink->beginTransaction();
        try {
            $sql = "delete from cita_medica where nro_cita_medica = :p_codigoCitaMedica";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_codigoCitaMedica", $p_codigoCitaMedica);
            $sentencia->execute();

            $this->dblink->commit();

            return true;
        } catch (Exception $exc) {
            $this->dblink->rollBack();
            throw $exc;
        }

        return false;
    }

    public function agregar() {
        $this->dblink->beginTransaction();

        try {
            $sql = "select * from f_generar_correlativo('cita') as nc";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->execute();
            $resultado = $sentencia->fetch();

            if ($sentencia->rowCount()) {
                $nuevoCodigoCita = $resultado["nc"];
                $this->setNro_cita_medica($nuevoCodigoCita);

                $sql = "
                        INSERT INTO cita_medica(
            nro_cita_medica, nro_historia, cod_medico, fecha_cita, 
            codigo_usuario, turno)
    VALUES (:p_nro_cita_medica, :p_nro_historia, :p_cod_medico, :p_fecha_cita, 
            :p_codigo_usuario, :p_turno);
                    ";

                //Preparar la sentencia
                $sentencia = $this->dblink->prepare($sql);

                //Asignar un valor a cada parametro
                $sentencia->bindParam(":p_nro_cita_medica", $this->getNro_cita_medica());
                $sentencia->bindParam(":p_nro_historia", $this->getNro_historia());
                $sentencia->bindParam(":p_cod_medico", $this->getCod_medico());
                $sentencia->bindParam(":p_fecha_cita", $this->getFecha_cita());
                
                session_name("SitemaComercial1");
                    session_start();
                    
                $sentencia->bindParam(":p_codigo_usuario", $_SESSION["s_codigo_usuario"]);
                $sentencia->bindParam(":p_turno", $this->getTurno());

                //Ejecutar la sentencia preparada
                $sentencia->execute();


                //Actualizar el correlativo en +1
                $sql = "update correlativo set numero = numero + 1 where tabla = 'cita'";
                $sentencia = $this->dblink->prepare($sql);
                $sentencia->execute();

                $this->dblink->commit();

                return true; //significa que todo se ha ejecutado correctamente
            } else {
                throw new Exception("No se ha configurado el correlativo para la tabla artículo");
            }
        } catch (Exception $exc) {
            $this->dblink->rollBack(); //Extornar toda la transacción
            throw $exc;
        }

        return false;
    }

    public function leerDatos($p_codigoCitaMedica) {
        try {
            $sql = "SELECT 
  cita_medica.nro_cita_medica, 
  cita_medica.nro_historia, 
  cita_medica.cod_medico, 
  cita_medica.fecha_cita, 
  cita_medica.estado, 
  cita_medica.turno, 
  cita_medica.codigo_usuario, 
  especialidad.cod_especialidad
FROM 
  public.cita_medica, 
  public.paciente, 
  public.medico, 
  public.especialidad, 
  public.usuario
WHERE 
  paciente.nro_historia = cita_medica.nro_historia AND
  medico.cod_especialidad = especialidad.cod_especialidad AND
  medico.cod_medico = cita_medica.cod_medico AND
  usuario.codigo_usuario = cita_medica.codigo_usuario AND 
  cita_medica.nro_cita_medica=:p_codigoCitaMedica;
";
            $sentencia = $this->dblink->prepare($sql);
            $sentencia->bindParam(":p_codigoCitaMedica", $p_codigoCitaMedica);
            $sentencia->execute();

            $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function editar() {
        $this->dblink->beginTransaction();

        try {
            $sql = "UPDATE cita_medica
   SET  nro_historia=:p_nro_historia, cod_medico=:p_cod_medico, fecha_cita=:p_fecha_cita, 
       turno=:p_turno
  WHERE nro_cita_medica=:p_nro_cita_medica;";
            $sentencia = $this->dblink->prepare($sql);
            //Asignar un valor a cada parametro
            $sentencia->bindParam(":p_nro_historia", $this->getNro_historia());
            $sentencia->bindParam(":p_cod_medico", $this->getCod_medico());
            $sentencia->bindParam(":p_fecha_cita", $this->getFecha_cita());
            $sentencia->bindParam(":p_turno", $this->getTurno());
            $sentencia->bindParam(":p_nro_cita_medica", $this->getNro_cita_medica());

            //Ejecutar la sentencia preparada
            $sentencia->execute();

            $this->dblink->commit();
            return true;
        } catch (Exception $exc) {
            $this->dblink->rollBack();
            echo $exc->getTraceAsString();
        }
        return false;
        
        #1 C:\xampp\htdocs\cita_medica\controlador\citamedica.agregar.editar.controlador.php(41): Citamedica->editar()


    }

}
