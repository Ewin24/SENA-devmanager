<?php
//codigo que consulta las empresas registradas en la base de datos y las agrega al menu de seleccion del formulario de registro de empresas
$cadenaSQL = "select nit,nombre from empresa";
$empresas = ConectorBD::ejecutarQuery($cadenaSQL);
$empresasRegistradas = "";

for ($i = 0; $i < count($empresas); $i++) {
    $empresasRegistradas .= "<option value='" . $empresas[$i]['nit'] . "'>" . $empresas[$i]['nombre'] . "</option>";
}


$titulo = $_REQUEST['accion'];

if (isset($_REQUEST['idProyecto'])) {
    $titulo = 'Modificar';
    $proyecto = new Proyecto('idProyecto', $_REQUEST['idProyecto']);
    $estado = $proyecto->getEstado();
    //    print_r( getdate()['0']). "<br>" ; otra opcion para obtener fecha actual en un formato de arreglo
    //    echo strtotime(date('Y-m-d'));  metodo que estoy usando para poder mandar fecha directamente del html

    //director de royecto que estaba seleccionado
    $directorProyecto = $proyecto->getIdUsuario_FK();
    $cadenaSQL = "select identificacion, nombre, apellido from usuario where identificacion = '$directorProyecto' "; //hacer la validacion de que el tipo de usuario sea 'D'
    $usuarios = ConectorBD::ejecutarQuery($cadenaSQL);
    $listaUsuarios = "";
    for ($i = 0; $i < count($usuarios); $i++) {
        $listaUsuarios .= "<option value='" . $usuarios[$i]['identificacion'] . "'>" . $usuarios[$i]['nombre'] . $usuarios[$i]['apellido'] . "</option>";
    }
} else {
    $proyecto = new Proyecto(null, null);
    $descripcion = "Descripcion";
}
//conteo para seleccionar el director de proyecto
$cadenaSQL = "select identificacion, nombre, apellido from usuario where tipoUsuario = 'D' "; //hacer la validacion de que el tipo de usuario sea 'D'
$usuarios = ConectorBD::ejecutarQuery($cadenaSQL);
$listaUsuarios = "";
for ($i = 0; $i < count($usuarios); $i++) {
    $listaUsuarios .= "<option value='" . $usuarios[$i]['identificacion'] . "'>" . $usuarios[$i]['nombre'] . $usuarios[$i]['apellido'] . "</option>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../../librerias/bootstrap5/css/bootstrap.min.css" />
    <script src="../../librerias/bootstrap5/js/bootstrap.min.js"></script>
    <title>Registro</title>
</head>

<body>
    <div class="container position-relative">
        <div class="row justify-content-center pt-5">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h3 class="h1 text-center">Registro de usuarios</h3>
                    </div>
                    <div class="card-body">
                        <form action="principal.php?CONTENIDO=presentacion/configuracion/usuario/usuarioCRUD.php&identificacion = <?= $_REQUEST['identificacion'] ?>" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" title="Por defecto tu usuario sera tu identificacion" disabled />
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control mt-2" id="nombre" name="nombre" placeholder="Nombres" title="Nombres" required />
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control mt-2" id="apellido" name="apellido" placeholder="Apellidos" title="apellidos" required />
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control mt-2" id="correo" name="correo" placeholder="Correo Electronico" title="correo electronico" required />
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control mt-2" id="telefono" name="telefono" placeholder="Telefono" title="Telefono" />
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control mt-2" id="direccion" name="direccion" placeholder="Direccion" title="direccion" />
                            </div>
                            <div class="form-group">
                                <select class="form-control mt-2" id="tipo_identificacion" name="tipo_identificacion" title="tipo de identificacion">
                                    <option value="C">Cedula de ciudadania</option>
                                    <option value="T">Tarjeta de identidad</option>
                                    <option value="R">Registro civil</option>
                                    <option value="P">Pasaporte</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control mt-2" id="identificacion" name="identificacion" placeholder="Identificacion" title="identificacion" />
                            </div>
                            <div class="form-group">
                                <select class="form-control mt-2" id="empresa" name="nit_empresa" title="empresa para la que trabaja">
                                    <option value="">Empresas</option>
                                    <?= $empresasRegistradas ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control mt-2" id="clave1" name="clave1" placeholder="Contraseña" title="contraseña" required />
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control mt-2" id="clave2" name="clave2" placeholder="Repetir contraseña" title="repetir contraseña" required />
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary btn-block mt-2" value="Registrar" title="registrar" name="registro" />
                                <a href="../../index.php">
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

<script>
    function campoUsuario() {
        document.getElementById("identificacion").addEventListener("keyup", function(event) {
            document.getElementById("usuario").value = document.getElementById("identificacion").value;
        });
    }
    campoUsuario();
</script>

</html>