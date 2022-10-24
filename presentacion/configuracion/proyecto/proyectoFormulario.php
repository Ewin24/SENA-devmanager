<?php
$titulo = $_REQUEST['accion'];
$idProyecto;

if (isset($_REQUEST['idProyecto'])) {
    $titulo = 'Modificar';
    $proyecto = new Proyecto('idProyecto', $_REQUEST['idProyecto']);
    $descripcion = $proyecto->getDescripcion(); //se hace desde aqui ya que en el html da error
} else {
    $proyecto = new Proyecto(null, null);
    $descripcion = "Descripcion";
    $idProyecto = "";
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../../../librerias/bootstrap5/css/bootstrap.min.css" />
    <script src="../../../librerias/bootstrap5/js/bootstrap.min.js"></script>
    <title>Registro Proyecto</title>
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
                        <form action="principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoCRUD.php&idProyecto=<?= $idProyecto ?>" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control mt-2" id="nombre" name="nombre" placeholder="Nombre" title="Nombres" value="<?= $proyecto->getNombre() ?>" required />
                            </div>
                            <div class="form-group">
                                <textarea class="form-control mt-2" id="descripcion" name="descripcion" placeholder="<?= $descripcion ?>" title="Descripcion" rows="3" required><?= $descripcion ?> </textarea>
                            </div>

                            


                            <!-- inicio de peticion de fechas -->
                            <div class="form-group">
                                Fecha Inicio (aaaa-mm-dd HH:mm:ss) <input class="mt-5" type="datetime" id="fechaInicio" name="fechaInicio" title="fecha de inicio" value="<?= $proyecto->getFechaInicio() ?>">
                            </div>
                            <div class="form-group">
                                Fehca Fin (aaaa-mm-dd HH:mm:ss) <input class="mt-5" type="datetime" id="fechaFin" name="fechaFin" title="fecha de finalizacion" value="<?= $proyecto->getFechaFinalizacion() ?>">
                            </div>
                            <!-- fin peticion de fechas -->

                            <div class="form-group">
                                <input type="hidden" name="idPerfil" value="<?= $idPerfil ?>">
                                <input type="submit" class="btn btn-primary btn-block mt-2" name="accion" value="<?= $titulo ?>" title="confirmar" />
                                <a href="principal.php?CONTENIDO=presentacion/vistas/perfil.php">
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