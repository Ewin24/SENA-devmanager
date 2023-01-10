<?php

require_once '../../logica/clasesGenericas/ConectorBD.php';
//codigo que consulta las empresas registradas en la base de datos y las agrega al menu de seleccion del formulario de registro de empresas
$cadenaSQL = "select id,nombre from empresas";
$empresas = ConectorBD::ejecutarQuery($cadenaSQL);
$empresasRegistradas = "";

for ($i = 0; $i < count($empresas); $i++) {
  $empresasRegistradas .= "<option value='" . $empresas[$i]['id'] . "'>" . $empresas[$i]['nombre'] . "</option>";
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
            <form action="../../control/registro.php" method="post">
              <div class="form-group">
                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" title="Por defecto tu usuario sera tu identificacion" disabled />
              </div>
              <div class="form-group">
                <input type="text" class="form-control mt-2" id="nombre" name="nombres" placeholder="Nombres" title="Nombres" required />
              </div>
              <div class="form-group">
                <input type="text" class="form-control mt-2" id="apellido" name="apellidos" placeholder="Apellidos" title="apellidos" required />
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
                <select class="form-control mt-2" id="empresa" name="id_empresa" title="empresa para la que trabaja">
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  function campoUsuario() {
    document.getElementById("identificacion").addEventListener("input", function(event) {
      document.getElementById("usuario").value = document.getElementById("identificacion").value;
    });
  }
  campoUsuario();
</script>

</html>