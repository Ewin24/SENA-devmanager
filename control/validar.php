<?php
require_once '../logica/clasesGenericas/ConectorBD.php';
require_once '../logica/clases/Usuario.php';
//require_once '../logica/clases/TipoUsuario.php';

$usuario = $_REQUEST['usuario'];
$clave = $_REQUEST['clave'];
$usuario = Usuario::validar($usuario, $clave);
if ($usuario == null) header('location: ../index.php?mensaje=Usuario o contraseña no valida'); //hacemos de de nuevo una redireccion a index
else {
    session_start();
    $_SESSION['usuario'] = serialize($usuario);
    header('location: ../principal.php?CONTENIDO=presentacion/inicio.php');
}
