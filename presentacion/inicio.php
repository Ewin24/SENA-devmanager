<?php
//manejo de session
@session_start();
if (!isset($_SESSION['usuario'])) {
    header("location: index.php?mensaje=Acceso no autorizado");
} else $USUARIO = unserialize($_SESSION['usuario']);

echo 'acceso aprobado, pagina de inicio';

?>
