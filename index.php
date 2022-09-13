<!DOCTYPE html>
<?php
session_start();
session_unset();
session_destroy();


$mensaje = '';
if (isset($_REQUEST['mensaje'])) {
    $mensaje = $_REQUEST['mensaje'];
    $sms = "<div id='alerta' class='alert alert-danger text-center m-2 ' role='alert'>$mensaje</div>";
}
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="librerias/bootstrap5/css/bootstrap.min.css">
    <script src="librerias/bootstrap5/js/bootstrap.min.js"></script>
    <title>devManager</title>
</head>

<body>
    <?= @$sms ?>
    <div class="container position-relative ">
        <div class="row justify-content-center pt-5">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h3 class="h1 text-center">Inicio de sesion</h3>
                    </div>
                    <div class="card-body">
                        <form action="control/validar.php" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control mt-2" id="clave" name="clave" placeholder="Contraseña">
                            </div>

                            <button type="submit" class="btn btn-primary mt-2">Iniciar sesion</button>
                            <a href="presentacion/configuracion/registro.php">
                                <button type="button" class="btn btn-primary mt-2 ms-2 ">Registrarse</button>
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>