<?php

$HabilidadesRegistradas = "";
$titulo = $_REQUEST['accion'];

if (isset($_REQUEST['idHabilidad'])) {
  $titulo = 'Modificar';
  $habilidad = new Habilidad('idHabilidad', $_REQUEST['idHabilidad']);
  $descripcion = $habilidad->getDescripcion(); //se hace desde aqui ya que en el html da error
} else {
  $habilidad = new Habilidad(null, null);
  $descripcion = "Descripcion";
}
$idHabilidad = $_REQUEST['idHabilidad'];
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="../../../librerias/bootstrap5/css/bootstrap.min.css" />
  <script src="../../../librerias/bootstrap5/js/bootstrap.min.js"></script>
  <title>Registro Habilidad</title>
</head>

<body>
  <div class="container position-relative">
    <div class="row justify-content-center pt-5">
      <div class="col-md-10">
        <div class="card">
          <div class="card-header">
            <h3 class="h1 text-center"><?= $titulo ?> Habilidad</h3>
          </div>
          <div class="card-body">
            <form action="principal.php?CONTENIDO=presentacion/configuracion/habilidad/habilidadCRUD.php&idHabilidad=<?= $idHabilidad ?>" method="post">
              <div class="form-group">
                <input type="text" class="form-control mt-2" id="nombre" name="nombre" placeholder="Nombre" title="Nombres" value="<?= $habilidad->getNombre() ?>" required />
              </div>
              <div class="form-group">

                <textarea class="form-control mt-2" id="descripcion" name="descripcion" placeholder="<?= $descripcion ?>" title="Descripcion" rows="3" required></textarea>
              </div>
              <div class="form-group">
                <input type="hidden" name="idHabilidad" value="<?= $idHabilidad ?>">
                <input type="submit" class="btn btn-primary btn-block mt-2" name="accion" value="<?= $titulo ?>" title="confirmar" />
                <a href="principal.php?CONTENIDO=presentacion/vistas/habilidad.php">
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