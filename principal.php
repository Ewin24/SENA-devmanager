<?php
require_once 'logica/clasesGenericas/ConectorBD.php';
require_once 'logica/clases/Usuario.php';
require_once 'logica/clases/TipoUsuario.php';
require_once 'logica/clases/Proyecto.php';
require_once 'logica/clases/Habilidad.php';
require_once 'logica/clases/Estudio.php';
require_once 'logica/clases/Perfil.php';


date_default_timezone_set('America/Bogota'); //establecer zona horaria de colombia

session_start();
if (!isset($_SESSION['usuario'])) header('location: index.php?mensaje=Ya hay una sesion activa, acceso no autorizado'); //sesiones activas al tiempo
else {
    $USUARIO = unserialize($_SESSION['usuario']);
}
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="librerias/bootstrap5/css/bootstrap.min.css">
    <title>DevManager</title>
</head>

<body>
    <center>
        <div id="encabezado" class="m-4">
            <a href="./principal.php" target="_blank">
                <h1 class="display-1">DevManager</h1s>
            </a>
        </div>
    </center>

    <div id="menu" class="mt-5">
        <?= $USUARIO->getTipoEnObjeto()->getMenu() ?>
    </div>
    <div id="contenido">
        <?= include $_REQUEST['CONTENIDO'] ?>
    </div>

    <script src="librerias/bootstrap5/js/bootstrap.min.js"></script>
</body>

</html>