<?php
//video 11.8
session_start();
if (!isset($_SESSION['usuario'])) {
    header('location: index.php?mensaje=Acceso no autorizado');
}

$estudio = new Estudio(null, null);

$idEstudio = $_REQUEST['idEstudio'];

switch ($_REQUEST['accion']) {
    case 'Adicionar':
        $estudio->setNombreEstudio($_REQUEST['nombreEstudio']);
        if (isset($_REQUEST['certificado'])) {
            $estudio->setCertificado($_REQUEST['certificado']);
        }
        $estudio->setCertificado(""); //si no se envio el certificado al adicionar, ponerlo vacio
        $estudio->setFechaCertificacion($_REQUEST['fechaCertificado']);
        $estudio->guardar();
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

    default:
        # code...
        break;
}

header('location: principal.php?CONTENIDO=presentacion/vistas/estudio.php'); //regresa a la pagina para continuar las acciones