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
                            "SELECT JSON_OBJECT(tabla, arr)
                            FROM (
                                SELECT tabla, JSON_ARRAYAGG(JSON_OBJECT(campo, arr)) AS arr
                                FROM (
                                    SELECT tabla, campo, JSON_ARRAYAGG(JSON_OBJECT(valor, texto)) AS arr
                                    FROM ddl_parametrizado p
                                    where tabla = 'tblProyectos'
                                    AND campo IN ('correo_director', 'estado')
                                    AND valor IS NOT NULL
                                    GROUP BY tabla, campo
                                ) AS a
                                GROUP BY tabla
                            ) AS b";

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
        
        $query = "SELECT JSON_OBJECT(tabla, arr) as JSON
                    FROM (
                        SELECT tabla, JSON_ARRAYAGG(JSON_OBJECT(campo, arr)) AS arr
                        FROM (
                            SELECT tabla, campo, JSON_ARRAYAGG(JSON_OBJECT('key',valor, 'value',texto)) AS arr
                            FROM ddl_parametrizado p
                            where $filtro
                            AND valor IS NOT NULL
                            GROUP BY tabla, campo
                        ) AS a
                        GROUP BY tabla
                    ) AS b";

        $ddl = new Ddl_Parametrizado(null,null);
        // $sql = "$ddl->cadenaSQLjson $filtro $orden";
        $sql = $query;
        // echo $sql;
        return ConectorBD::ejecutarQuery($sql)[0];
    }
}