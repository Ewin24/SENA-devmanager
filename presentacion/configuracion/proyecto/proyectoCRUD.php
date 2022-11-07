<?php
//video 11.8
session_start();
if (!isset($_SESSION['usuario'])) {
    header('location: index.php?mensaje=Acceso no autorizado');
}

$proyecto = new Proyecto(null, null);

$idProyecto = $_REQUEST['idProyecto'];

switch ($_REQUEST['accion']) {
    case 'Adicionar':
        $proyecto->setNombre($_REQUEST['nombre']);
        $proyecto->setDescripcion($_REQUEST['descripcion']);
        $proyecto->setEstado($_REQUEST['estado']);
        $proyecto->setFechaInicio($_REQUEST['fechaInicio']);
        $proyecto->setFechaFinalizacion($_REQUEST['fechaFin']);
        $proyecto->setIdUsuario_FK($_REQUEST['idUsuario']); //llega desde proyectos.php y es el director de proyecto
        $proyecto->guardar();
        break;

    case 'Modificar':
        $habilidad->setIdHabilidad($idHabiliad);
        $habilidad->setNombre($_REQUEST['nombre']);
        $habilidad->setDescripcion($_REQUEST['descripcion']);
        $habilidad->modificar();
        break;

    case 'Eliminar':
        $habilidad->setIdHabilidad($idHabiliad);
        $habilidad->eliminar();
        break;

    case 'Postularse':

        break;
    default:
        # code...
        break;
}

header('location: principal.php?CONTENIDO=presentacion/vistas/estudio.php'); //regresa a la pagina para continuar las acciones