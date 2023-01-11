<?php
//codigo para recibir los datos del formulario de registro y guardarlos en la base de datos

$usuario = new Usuario(null, null);

$mensaje = ''; //variable que contiene los mensajes de error o exito de las operaciones

switch ($_REQUEST['accion']) {
    case 'Adicionar':
        //validar que la identificacion no exista en la base de datos
        $identificacion = $_POST['identificacion'];
        $cadenaSQL = "select identificacion from usuario where identificacion = $identificacion";
        $identificaciones = ConectorBD::ejecutarQuery($cadenaSQL);
        if (count($identificaciones) > 0) {
            //header("Location: ./presentacion/vistas/usuario.php?mensaje= ya existe un usuario con esa identificacion en el sistema");
            $mensaje = 'ya existe un usuario con esa identificacion en el sistema';
            echo "<div id='alerta' class='alert alert-danger text-center m-2 ' role='alert'>$mensaje</div>";
            exit();
        } else {
            //en caso de que no exista un usuario con la misma identificacion, valida los demas campos y adiciona
            if (isset($_POST['registro']) && $_POST['clave1'] == $_POST['clave2']) {
                $identificacion = $_POST['identificacion'];
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $tipo_usuario = $_POST['tipo_usuario']; //por defecto es trabajador
                $clave = $_POST['clave2'];
                $nombreUsuario = $_POST['identificacion']; //por defecto el nombre de usuario es la identificacion
                $correo = $_POST['correo'];
                $telefono = $_POST['telefono'];
                $tipoIdentificacion = $_POST['tipo_identificacion'];
                $direccion = $_POST['direccion'];
                $nitEmpresa = $_POST['nit_empresa'];

                $usuario->setIdentificacion($identificacion);
                $usuario->setNombre($nombre);
                $usuario->setApellido($apellido);
                $usuario->setTipoUsuario($tipo_usuario);
                $usuario->setClave($clave);
                $usuario->setNombreUsuario($nombreUsuario);
                $usuario->setCorreo($correo);
                $usuario->setTelefono($telefono);
                $usuario->setTipoIdentificacion($tipoIdentificacion);
                $usuario->setDireccion($direccion);
                $usuario->setNitEmpresa($nitEmpresa);
                $usuario->guardar();

                $mensaje = 'Registro de usuario exitoso'; //mensaje
                echo "<div id='alerta' class='alert alert-success text-center m-2 ' role='alert'>$mensaje</div>";
            }
        }
        break;

    case 'Modificar':

        $usuario->setIdentificacion($_POST['identificacion']);
        $usuario->setNombre($_POST['nombre']);
        $usuario->setApellido($_POST['apellido']);
        $usuario->setTipoUsuario($_POST['tipo_usuario']);
        $usuario->setClave($_POST['clave2']);
        $usuario->setNombreUsuario($_POST['identificacion']);
        $usuario->setCorreo($_POST['correo']);
        $usuario->setTelefono($_POST['telefono']);
        $usuario->setTipoIdentificacion($_POST['tipo_identificacion']);
        $usuario->setDireccion($_POST['direccion']);
        $usuario->setNitEmpresa($_POST['nit_empresa']);
        $usuario->modificar($_REQUEST['identificacion_anterior']); //manda como parametro la identificacion anterior del usuario para actualizar

        $mensaje = 'Modificacion de datos de usuario exitosa'; //mensaje
        echo "<div id='alerta' class='alert alert-success text-center m-2 ' role='alert'>$mensaje</div>";

        break;

    case 'Eliminar':
        $usuario->setIdentificacion($_REQUEST['identificacion']);
        $usuario->eliminar();

        $mensaje = 'Eliminacion de usuario exitosa'; //mensaje
        echo "<div id='alerta' class='alert alert-success text-center m-2 ' role='alert'>$mensaje</div>";
        break;

    default:
        $mensaje = 'No se realizo ninguna accion'; //mensaje
        echo "<div id='alerta' class='alert alert-danger text-center m-2 ' role='alert'>$mensaje</div>";
        break;
}
header('location: principal.php?CONTENIDO=presentacion/vistas/usuario.php&mensaje=' . $mensaje); //regresa a la pagina para continuar las acciones
