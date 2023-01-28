<?php

if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Ya hay una sesion activa, acceso no autorizado'); //sesiones activas al tiempo
else {
    $USUARIO = unserialize($_SESSION['usuario']);
    $id = $USUARIO->getId();
}

if (isset($_REQUEST['mensaje'])) {
    $mensaje = $_REQUEST['mensaje'];
    echo "<div id='alerta' class='alert alert-danger text-center m-2 ' role='alert'>$mensaje</div>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../../librerias/bootstrap5/css/bootstrap.min.css" />
    <script src="../../librerias/bootstrap5/js/bootstrap.min.js"></script>
    <title>Cambio de clave</title>
</head>

<body>
    <div class="container position-relative">
        <div class="row justify-content-center pt-5">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h3 class="h1 text-center">Cambio de clave</h3>
                    </div>
                    <div class="card-body">
                        <form action="principal.php?CONTENIDO=control/cambioClave.php" method="post">
                            <div class="form-group">
                                Ingrese la clave anterior
                                <input type="password" class="form-control mt-2" id="clave_anterior" name="clave_anterior" placeholder="Contraseña" title="contraseña" required />
                            </div>
                            <div class="form-group mt-5">
                                Ingrese la nueva clave
                                <input type="password" class="form-control mt-2" id="clave1" name="clave1" placeholder="Contraseña" title="contraseña" required />
                            </div>
                            <div class="form-group">
                                Confirme la nueva clave
                                <input type="password" class="form-control mt-2" id="clave2" name="clave2" placeholder="Repetir contraseña" title="repetir contraseña" required />
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <input type="submit" class="btn btn-primary btn-block mt-2" value="Confirmar cambio" title="registrar" name="registro" />
                                <a href="principal.php?CONTENIDO=presentacion/vistas/perfiles.php">
                                    <input type="button" class="btn btn-danger btn-block mt-2" value="Cancelar" title="cancelar" />
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>