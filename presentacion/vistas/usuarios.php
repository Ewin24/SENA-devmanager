<?php
session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Ya hay una sesion activa, acceso no autorizado'); //sesiones activas al tiempo
else {
    $USUARIO = unserialize($_SESSION['usuario']);
}

//codigo en caso de que se generen mensajes desde la adicion, eliminacion o edicion de un usuario
$mensaje = '';
if (isset($_REQUEST['mensaje'])) {
    $mensaje = $_REQUEST['mensaje'];
    $sms = "<div id='alerta' class='alert alert-danger text-center m-2 ' role='alert'>$mensaje</div>";
}

$lista = '';

$json_usuarios = Usuario::getListaEnObjetos(null, null);

// for ($i = 0; $i < count($resultado); $i++) {
//     $usuario = $resultado[$i];
//     $lista .= '<tr>';
//     $lista .= "<td>{$usuario->getIdentificacion()}</td>";
//     $lista .= "<td>{$usuario->getNombre()}</td>";
//     $lista .= "<td>{$usuario->getApellido()}</td>";
//     $lista .= "<td>{$usuario->getTipoUsuario()}</td>";
//     $lista .= "<td>{$usuario->getNombreUsuario()}</td>";
//     $lista .= "<td>{$usuario->getCorreo()}</td>";
//     $lista .= "<td>{$usuario->getTelefono()}</td>";
//     $lista .= "<td>{$usuario->getTipoIdentificacion()}</td>";
//     $lista .= "<td>{$usuario->getFoto()}</td>";
//     $lista .= "<td>{$usuario->getDireccion()}</td>";
//     $lista .= "<td>{$usuario->getNitEmpresa()}</td>";

//     //$lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/habilidad/habilidadFormulario.php&accion=adicionar'>Adicionar</a></td>";
//     $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/usuario/usuarioFormulario.php&accion=Modificar&identificacion={$usuario->getIdentificacion()}' title='modificar usuario'> Modificar </a></td>";
//     $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/usuario/usuarioCRUD.php&accion=Eliminar&identificacion={$usuario->getIdentificacion()}' onclick='eliminar({$usuario->getIdentificacion()})' title='Eliminar usuario'>Eliminar</a></td>";
//     $lista .= "<td></td>";
//     $lista .= "</tr>";
// }
?>

<h3>LISTA DE USUARIOS</h3>
<table border="1">
    <thead>
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Tipo de usuario</th>
            <th>Nombre de usuario en el sistema</th>
            <th>Correo</th>
            <th>Telefono</th>
            <th>Tipo de identificacion del usuario</th>
            <th>Foto</th>
            <th>Direccion</th>
            <th>Nit de empresa donde trabaja</th>
            <th><a href='principal.php?CONTENIDO=presentacion/configuracion/usuario/usuarioFormulario.php&accion=Adicionar'>Adicionar</a></th>
        </tr>
    </thead>
    <tbody>
        <?= $json_usuarios ?>
    </tbody>
</table>


<script type="text/javascript">
    function eliminar(id) {
        var respuesta = confirm("Esta seguro de eliminar este registro?");
        if (respuesta) {
            location = "principal.php?CONTENIDO=presentacion/configuracion/usuario/usuarioCRUD.php&accion=Eliminar&identificacion=" + id;
        }
    }
</script>