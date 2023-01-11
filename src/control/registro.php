<?php
//codigo para recibir los datos del formulario de registro y guardarlos en la base de datos
require_once '../logica/clases/usuario.php';
require_once '../logica/clasesGenericas/ConectorBD.php';

//validar que la identificacion no exista en la base de datos
$identificacion = $_POST['identificacion'];
$cadenaSQL = "select identificacion from usuarios where identificacion = $identificacion";
$identificaciones = ConectorBD::ejecutarQuery($cadenaSQL);
if (count($identificaciones) > 0) {
    header("Location: ../index.php ?mensaje= ya existe un usuario con esa identificacion en el sistema");
    echo "<div id='alerta' class='alert alert-danger text-center m-2 ' role='alert'>$mensaje</div>";
    exit();
}

if (isset($_POST['registro']) && $_POST['clave1'] == $_POST['clave2']) {
    $identificacion = $_POST['identificacion'];
    $nombre = $_POST['nombres'];
    $apellido = $_POST['apellidos'];
    $tipo_usuario = 'T'; //por defecto es trabajador
    $clave = $_POST['clave2'];
    //$nombreUsuario = $_POST['identificacion']; ya no se necesit
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $tipoIdentificacion = $_POST['tipo_identificacion'];
    $direccion = $_POST['direccion'];
    $nitEmpresa = $_POST['id_empresa'];

    $usuario = new Usuario(null, null);
    $usuario->setIdentificacion($identificacion);
    $usuario->setNombres($nombre);
    $usuario->setApellidos($apellido);
    $usuario->setTipo_usuario($tipo_usuario);
    $usuario->setClave_hash($clave);
    //$usuario->setNombreUsuario($nombreUsuario); no se necesita
    $usuario->setCorreo($correo);
    $usuario->setTelefono($telefono);
    $usuario->setTipo_identificacion($tipoIdentificacion);
    $usuario->setDireccion($direccion);
    $usuario->setId_empresa($nitEmpresa);
    $usuario->guardar();

    header('location: ../index.php?mensaje=Registro de usuario exitoso'); //hace el registro y manda el mensaje de exito
}


//campos opcionales a futuro
 //    $fecha_registro = date("Y-m-d"); //fecha de registro del usuario opcional
 //    $imagen = $_FILES['imagen']['name'];
 //    $ruta = $_FILES['imagen']['tmp_name'];
 //    $destino = "../img/usuarios/" . $imagen;
 //    move_uploaded_file($ruta, $destino);
