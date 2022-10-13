<?php

class Estudio{
	private $idEstudio;
	private $idCertificacion;
	private $nombreEstudio;
	private $fechaCertificacion;
	private $certificado;

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "SELECT idEstudio,idCertificacion,nombreEstudio,fechaCertificacion,certificado FROM estudio WHERE $campo = $valor;";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
                print_r($campo);
            }
            //asignacion de los datos
            $this->idEstudio = $campo['idEstudio'];
            $this->idCertificacion = $campo['idCertificacion'];
            $this->nombreEstudio = $campo['nombreEstudio'];
            $this->fechaCertificacion = $campo['fechaCertificacion'];
            $this->certificado = $campo['certificado'];
        }
    }

    public function getIdEstudio()
    {
        return $this->idEstudio;
    }
    
    public function getIdCertificacion()
    {
        return $this->idCertificacion;
    }

    public function getNombreEstudio()
    {
        return $this->nombreEstudio;
    }

    public function getFechaCertificacion()
    {
        return $this->fechaCertificacion;
    }
    
    public function getCertificado()
    {
        return $this->certificado;
    }

    public function setIdEstudio($idEstudio)
    {
        $this->idEstudio = $idEstudio;
    }

    public function setIdCertificacion($idCertificacion)
    {
        $this->idCertificacion = $idCertificacion;
    }

    public function setNombreEstudio($nombreEstudio)
    {
        $this->nombreEstudio = $nombreEstudio;
    }

    public function setFechaCertificacion($fechaCertificacion)
    {
        $this->fechaCertificacion = $fechaCertificacion;
    }

    public function setCertificado($certificado)
    {
        $this->certificado = $certificado;
    }

    public function guardar()
    {
        //echo $this->nombre, $this->descripcion;
        $cadenaSQL = "INSERT INTO  estudio (idCertificacion,nombreEstudio,fechaCertificacion, certificado ) VALUES ( '$this->idCertificacion' , '$this->nombreEstudio' , '$this->fechaCertificacion' , '$this->certificado' )";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function modificar()
    {
        $cadenaSQL = "update estudio set idEstudio='{$this->idEstudio}', idCertificacion='{$this->idCertificacion}', fechaCertificacion='{$this->fechaCertificacion}', certificado='{$this->certificado}' where idEstudio = {$this->idEstudio}";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public function eliminar()
    {
        $cadenaSQL = "DELETE FROM estudio WHERE idEstudio = $this->idEstudio;";
        ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '')
            $filtro = '';
        else
            $filtro = "where $filtro";
        if ($orden == null || $orden == '')
            $orden = '';
        else
            $orden = "order by $orden";

        $cadenaSQL = "SELECT idEstudio, idCertificacion, nombreEstudio, fechaCertificacion, certificado FROM estudio $filtro $orden";
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getListaEnObjetos($filtro, $orden)
    {
        $resultado = Estudio::getLista($filtro, $orden);
        $lista = array();
        for ($i = 0; $i < count($resultado); $i++) {
            $estudio = new Estudio($resultado[$i], null);
            $lista[$i] = $estudio;
        }
        return $lista;
    }
}
