<?php

$titulo = $_REQUEST['accion'];

if (isset($_REQUEST['idPerfil'])) {
    $titulo = 'Modificar';
    $perfil = new Perfil('idPerfil', $_REQUEST['idPerfil']);
    $descripcion = $perfil->getDescripcion(); //se hace desde aqui ya que en el html da error
} else {
    $perfil = new Perfil(null, null);
    $descripcion = "Descripcion";
}
$idPerfil = $_REQUEST['idPerfil'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../../../librerias/bootstrap5/css/bootstrap.min.css" />
    <script src="../../../librerias/bootstrap5/js/bootstrap.min.js"></script>
    <title>Registro Perfil</title>
</head>

<body>
    <div class="container position-relative">
        <div class="row justify-content-center pt-5">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h3 class="h1 text-center"><?= $titulo ?> Perfil</h3>
                    </div>
                    <div class="card-body">
                        <form action="principal.php?CONTENIDO=presentacion/configuracion/perfil/perfilCRUD.php&idHabilidad=<?= $idPerfil ?>" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control mt-2" id="nombre" name="nombre" placeholder="Nombre" title="Nombres" value="<?= $perfil->getNombre() ?>" required />
                            </div>
                            <div class="form-group">
                                <textarea class="form-control mt-2" id="descripcion" name="descripcion" placeholder="<?= $descripcion ?>" title="Descripcion" rows="3" required><?= $descripcion ?> </textarea>
                            </div>
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