<?php

class ConectorBD {

    //put your code here
    private $servidor;
    private $puerto;
    private $baseDatos;
    private $controlador;
    private $usuario;
    private $clave;
    private $conexion;

    public function __construct() {
        $ruta = dirname((__FILE__)) . '/../../configuracion.ini'; //se hacen 3 retrocesos, puesto que tambien esta la carpeta Sorurce files en el camino
        if (!file_exists($ruta)) {
            echo 'Error: No existe el archivo de configuracion de la base de datos. Nombre del archivo: ' . $ruta;
            //return false;
            //die(); // se detiene el procesamiento de codigo de este archivo
        } else {//se tiene certeza de que el archivo existe y se encontro
            $parametros = parse_ini_file($ruta); //lee el archivo de configuracion y los datos los introduce a parametros como una matriz    
            if (!$parametros) {
                echo 'Error: no se puede procesar el archivo de configuaracion. Nombre del archivo: ' . $ruta;
                //return false;
            } else {
                //print_r($parametros);
                $this->servidor = $parametros['servidor']; //this.servidor = $this-> servidor     --- es una matriz asociativa, por ello se usa el nombre
                $this->puerto = $parametros['puerto'];
                $this->baseDatos = $parametros['baseDatos'];
                $this->controlador = $parametros['controlador'];
                $this->usuario = $parametros['usuario'];
                $this->clave = $parametros['clave'];
                //return true;
            }
        }
    }

    public function conectar() {
        try {
            $this->conexion = new PDO("$this->controlador: host=$this->servidor;port=$this->puerto;dbname=$this->baseDatos", $this->usuario, $this->clave);
            //$this->conexion = new PDO($dsn, $username, $passwd)
            //echo 'Conectado a la base de datos ';
            return true;
            //print_r(PDO::getAvailableDrivers()); //ver controladores que se encuentran disponibles en la base de datos
        } catch (Exception $e) {
            echo 'No se pudo conectar a la base de datos. ' . $e->getMessage();
            return false;
        }
    }

    public function desconectar() {
        $this->conexion = null; //hay otras manera de hacer desconexion con pdo, pero en este caso solo se hace la conexion null
    }

    public static function ejecutarQuery($cadenaSQL) {
        $conectorBD = new ConectorBD();
        //$conectorBD->conectar();
        if ($conectorBD->conectar()) {
            $sentencia = $conectorBD->conexion->prepare($cadenaSQL);

            if (!$sentencia->execute()) {
                $consulta = false;
            } else {
                $consulta = $sentencia->fetchAll(); //si se hace un select... devuelve los datos como una matriz asociativa
                $sentencia->closeCursor();
            }
        } else {
            echo 'No se pudo ejecutar la cadena SQL';
        }
        $conectorBD->desconectar();
        return $consulta;
    }

}
