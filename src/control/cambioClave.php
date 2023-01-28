<?php
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Ya hay una sesion activa, acceso no autorizado'); //sesiones activas al tiempo
else {
    $USUARIO = unserialize($_SESSION['usuario']);
    $id = $USUARIO->getId();
}

$mensaje = '';
$claveAnterior = $_REQUEST['clave_anterior'];
$clave1 = $_REQUEST['clave1'];
$clave2 = $_REQUEST['clave2'];

$validacion = Usuario::verify($claveAnterior, $USUARIO->getClave_hash());

if ($validacion = 1) {
    if ($clave1 == $clave2) {
        if (strlen($clave1) >= 8 && strlen($clave2) >= 8) {
            Usuario::cambioClave($clave2, $id);
            $mensaje = 'la actualizacion de clave tuvo exito, salga e inicie sesion de nuevo';
        }
    } else {
        $mensaje = 'las claves no coinciden o no tienen 8 caracteres';
    }
}
print "<script>window.setTimeout(function() { window.location = 'principal.php?CONTENIDO=presentacion/configuracion/cambioClaveFormulario.php&mensaje=" . $mensaje . "'" . '}, 3000);</script>';
