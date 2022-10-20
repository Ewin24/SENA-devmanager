<?php

class TipoUsuario
{

    private $codigo;

    public function __construct($codigo)
    { //video 8.1
        $this->codigo = $codigo;
    }

    public function getNombre()
    {
        $menu = "<nav class='border border-1'>";
        $menu .= "    <ul class='nav justify-content-center'>";

        switch ($this->codigo) {
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
    public function getMenu()
    {
        $menu = "<nav class='border border-1'>";
        $menu .= "<ul class='nav justify-content-center'>";
        // $menu .= "<li class='border nav-item me-4'><a class='nav-link active' aria-current='page' href='#'>Inicio</a></li>";
        $menu .= "<li class='border nav-item me-4'><a class='nav-link' href='principal.php?CONTENIDO=presentacion/vistas/proyectos.php&accion=''&id='''>Proyectos</a></li>";
        $menu .= "<li class='border nav-item me-4'><a class='nav-link' href='principal.php?CONTENIDO=presentacion/vistas/perfil.php&accion=''&id='''>Perfil</a></li>";
        $menu .= "<li class='border nav-item me-4'><a class='nav-link' href='principal.php?CONTENIDO=presentacion/vistas/estudio.php&accion=''&id='''>Estudios</a></li>";
        $menu .= "<li class='border nav-item me-4'><a class='nav-link' href='principal.php?CONTENIDO=presentacion/vistas/habilidad.php&accion=''&id='''>Habilidades</a></li>";

        switch ($this->codigo) {
            case 'A':
                $menu .= "<li class='border nav-item '><a class='nav-link' href='#'>Usuarios</a></li>";
                break;
            case 'T':

                break;
            default:

                break;
        }
        $menu .= "<li class='border nav-item '><a class='nav-link' href='index.php'>Salir</a></li>";
        return $menu;
    }
}
