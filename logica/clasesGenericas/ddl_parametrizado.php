<?php
//clase para el manejo de controles ddl parametrizados en la base
class Ddl_Parametrizado
{
    public $tabla;
	public $campo;
	public $valor;
	public $texto;

    protected $cadenaSQL = "SELECT  tabla, campo, valor, texto 
                            FROM    ddl_parametrizado";
    protected $cadenaSQLjson =
                            "SELECT json_object(tabla, 
                                    json_object(
                                        campo, json_array( group_concat(
                                                            json_object('valor', valor, 'texto', texto)) )
                                    )
                            )		
                            FROM 	ddl_parametrizado";

    public function __construct($campo, $valor)
    {
        if ($campo != null) {
            if (!is_array($campo)) {
                $cadenaSQL = "$this->cadenaSQL $campo = $valor";
                $campo = ConectorBD::ejecutarQuery($cadenaSQL)[0];
                // print_r($campo);
            }
            //asignacion de los datos
            $this->tabla = $campo['tabla'];
            $this->campo = $campo['campo'];
            $this->valor = $campo['valor'];
            $this->texto = $campo['texto'];
        }
    }

    protected function getLista($filtro, $orden)
    {
        if ($filtro == null || $filtro == '')
            $filtro = '';
        else
            $filtro = "WHERE $filtro";
        if ($orden == null || $orden == '')
            $orden = '';
        else
            $orden = "ORDER BY $orden";

        $cadenaSQL = "$this->cadenaSQL $filtro $orden";
        // echo $cadenaSQL;
        return ConectorBD::ejecutarQuery($cadenaSQL);
    }

    public static function getddlOps($filtro, $orden){
        if ($filtro == null || $filtro == '')
            $filtro = '';
        else
            $filtro = "WHERE $filtro";
        if ($orden == null || $orden == '')
            $orden = '';
        else
            $orden = "ORDER BY $orden";

        $ddl = new Ddl_Parametrizado(null,null);
        $sql = "$ddl->cadenaSQLjson $filtro $orden";
        // echo $sql;
        return json_encode(ConectorBD::ejecutarQuery($sql));
    }
}