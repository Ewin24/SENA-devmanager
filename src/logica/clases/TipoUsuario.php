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

        $menu = "<div class='col-sm'><div class='card'><div class='card-body'><h5 class='card-title'>Proyectos</h5><a href='principal.php?CONTENIDO=presentacion/vistas/proyectos.php&accion=''&id=''' class='btn btn-primary'>Acceder</a></div></div></div>";
        $menu .= "<div class='col-sm'><div class='card'><div class='card-body'><h5 class='card-title'>Perfil</h5><a href='principal.php?CONTENIDO=presentacion/vistas/perfiles.php&accion=''&id=''' class='btn btn-primary'>Acceder</a></div></div></div>";

        switch ($codigo) {
            case 'A':
                $menu .= "<div class='col-sm'><div class='card'><div class='card-body'><h5 class='card-title'>Administracion</h5><a href='principal.php?CONTENIDO=presentacion/vistas/empresas.php&accion=''&id=''' class='btn btn-primary'>Acceder</a></div></div></div>";
                // $menu .= "<li class='border nav-item me-4'><a class='nav-link' href='principal.php?CONTENIDO=presentacion/vistas/empresas.php&accion=''&id='''>Administración</a></li>";
                break;
            case 'T':
                break;
            case 'D':
                break;
            default:
                break;
        }
        $menu .= "<div class='col-sm'><div class='card'><div class='card-body'><h5 class='card-title'>----------></h5><a href='index.php' class='btn btn-primary'>Salir</a> </div></div></div>";
        // $menu .= "<li class='border nav-item '><a class='nav-link' href='index.php'>Salir</a></li>";
        return $menu;
    }
}
