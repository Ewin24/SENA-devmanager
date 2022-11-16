<?php
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
    <link rel="stylesheet" href="../../../librerias/bootstrap5/css/bootstrap.min.css" />
    <script src="../../../librerias/bootstrap5/js/bootstrap.min.js"></script>
    <title><?= $titulo ?> Proyecto</title>
</head>

<body>
    <div class="container position-relative">
        <div class="row justify-content-center pt-5">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h3 class="h1 text-center"><?= $titulo ?> Proyecto</h3>
                    </div>
                    <div class="card-body">
                        <form action="principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoCRUD.php&idProyecto=<?= $_REQUEST['idProyecto'] ?>" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control mt-2" id="nombre" name="nombre" placeholder="Nombre" title="Nombres" value="<?= $proyecto->getNombre() ?>" required />
                            </div>
                            <div class="form-group">
                                <textarea class="form-control mt-2" id="descripcion" name="descripcion" placeholder="<?= $descripcion ?>" title="Descripcion" rows="3" required><?= $proyecto->getDescripcion() ?></textarea>
                            </div>

                            <!-- inicio de peticion de fechas -->
                            <div class="form-group">
                                Fecha Inicio (aaaa-mm-dd HH:mm:ss) <input class="mt-4" type="datetime" id="fechaInicio" name="fechaInicio" title="fecha de inicio" value="<?= $proyecto->getFechaInicio() ?>">
                            </div>
                            <div class="form-group">
                                Fehca Fin (aaaa-mm-dd HH:mm:ss) <input class="mt-4" type="datetime" id="fechaFin" name="fechaFin" title="fecha de finalizacion" value="<?= $proyecto->getFechaFinalizacion() ?>">
                            </div>
                            <!-- fin peticion de fechas -->

                            <!-- estado del proyecto T= terminado, E= ejecucion, I= por iniciar  -->
                            <div class="form-group mt-4">
                                <select class="form-control mt-2" id="estado" name="estado" title="estadado del proyecto">
                                    <option value="T">Terminado</option>
                                    <option value="E">En ejecucion</option>
                                    <option value="I">Por iniciar</option>
                                </select>
                            </div>
                            <!-- estado del proyecto -->

                            <!-- director de proyecto  -->
                            <div class="form-group mt-4">
                                <select class="form-control mt-2" id="directores" name="idUsuario" title="Directores de proyecto del sistema">
                                    <option value="">Directores de proyecto</option>
                                    <?= $listaUsuarios ?>
                                </select>
                            </div>
                            <!-- director de proyecto -->

                            <div class="form-group ">
                                <input type="hidden" name="idPerfil" value="<?= $idPerfil ?>">
                                <input type="submit" class="btn btn-primary btn-block mt-2" name="accion" value="<?= $titulo ?>" title="confirmar" />
                                <a href="principal.php?CONTENIDO=presentacion/vistas/proyectos.php">
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