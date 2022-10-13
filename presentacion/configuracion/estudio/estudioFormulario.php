<?php

$titulo = $_REQUEST['accion'];

if (isset($_REQUEST['idEstudio'])) {
  $titulo = 'Modificar';
  $estudio = new Estudio('idEstudio', $_REQUEST['idEstdio']);
  $nombreEstudio = $estudio->getNombreEstudio(); //se hace desde aqui ya que en el html da error
} else {
  $estudio = new Estudio(null, null);
  $nombreEstudio = "Descripcion";
}
$idHabilidad = $_REQUEST['idEstudio'];
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="../../../librerias/bootstrap5/css/bootstrap.min.css" />
  <script src="../../../librerias/bootstrap5/js/bootstrap.min.js"></script>
  <title><?= $titulo ?> Estudio</title>
</head>

<body>
  <div class="container position-relative">
    <div class="row justify-content-center pt-5">
      <div class="col-md-10">
        <div class="card">
          <div class="card-header">
            <h3 class="h1 text-center"><?= $titulo ?> Estudio</h3>
          </div>
          <div class="card-body">
            <form action="principal.php?CONTENIDO=presentacion/configuracion/estudio/estudioCRUD.php&idEstudio=<?= $idEstudio ?>" method="post">
              <div class="form-group">
                <input type="text" class="form-control mt-2" id="nombreEstudio" name="nombreEstudio" placeholder="Nombre estudio" title="Nombre estudio" value="<?= $estudio->getNombreEstudio() ?>" required />
              </div>


              <!-- Inicio de caja para fechas-->
              Fehca del Certificado <input class="mt-5" type="date" id="fechaCertificado" name="fechaCertificado" title="fechaCertificado" value="<?= $estudio->getFechaCertificacion() ?>">

              <!-- fin de caja para fechas -->

              <!-- caja para subir archivo -->
              <div class="mt-5 ">
                Certificado en formato PDF: <input class="form-control form-control-lg mt-1" type="file" name="certificado" value="" accept=".pdf">
              </div>
              <!-- fin caja para subir archivo -->

              <div class="form-group">
                <input type="hidden" name="idEstudio" value="<?= $idEstudio ?>">
                <input type="submit" class="btn btn-primary btn-block mt-2" name="accion" value="<?= $titulo ?>" title="confirmar" />
                <a href="principal.php?CONTENIDO=presentacion/vistas/estudio.php">
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