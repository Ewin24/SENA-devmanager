<?php

class TipoUsuario
{
    public function __construct()
    { //video 8.1

    }

    public static function getNombre($codigo)
    {
        $menu = "<nav class='border border-1'>";
        $menu .= "<ul class='nav justify-content-center'>";

        switch ($codigo) {
            case 'A':
                $nombre = 'Administrador';
                break;
            case 'T':
                $nombre = 'Trabajador';
                break;
            default:
                $nombre = 'Desconocido';
                break;
        }
        return $nombre;
    }
    public static function getMenu($codigo)
    {
        // $menu = "<nav class='border border-1'>";
        // $menu .= "<ul class='nav justify-content-center'>";
        // $menu .= "<li class='border nav-item me-4'><a class='nav-link' href='principal.php?CONTENIDO=presentacion/vistas/proyectos.php&accion=''&id='''>Proyectos</a></li>";
        // $menu .= "<li class='border nav-item me-4'><a class='nav-link' href='principal.php?CONTENIDO=presentacion/vistas/perfiles.php&accion=''&id='''>Perfil</a></li>";

        $menu = "<li class='nav-item m-2'><a class='nav-link active' href='principal.php?CONTENIDO=presentacion/vistas/proyectos.php&accion=''&id='''>Proyectos</a></li>";
        $menu .= "<li class='nav-item m-2'><a class='nav-link active' href='principal.php?CONTENIDO=presentacion/vistas/perfiles.php&accion=''&id='''>Perfiles</a></li>";

        switch ($codigo) {
            case 'A':
                // $menu .= "<li class='border nav-item me-4'><a class='nav-link' href='principal.php?CONTENIDO=presentacion/vistas/empresas.php&accion=''&id='''>Administración</a></li>";
                $menu .= "<li class='nav-item m-2'><a class='nav-link active' href='principal.php?CONTENIDO=presentacion/vistas/empresas.php&accion=''&id='''>Administracion</a></li>";
                break;
            case 'T':
                break;
            case 'D':
                break;
            default:
                break;
        }
        // $menu .= "<li class='border nav-item '><a class='nav-link' href='index.php'>Salir</a></li>";
        $menu .= "<li class='nav-item m-2'><a class='nav-link active' href='index.php'>Salir</a></li>";

        return $menu;
    }
}
