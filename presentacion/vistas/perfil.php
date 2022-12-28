<?php
session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Ya hay una sesion activa, acceso no autorizado'); //sesiones activas al tiempo
else {
    $USUARIO = unserialize($_SESSION['usuario']);
}
// $lista = '';

$json_Usuarios = Perfil::getListaEnJson(null, null);
$json_Perfiles = Perfil::getListaEnJson(null, null);


// for ($i = 0; $i < count($resultado); $i++) {
//     $perfil = $resultado[$i];
//     $lista .= '<tr>';
//     $lista .= "<td>{$perfil->getIdPerfil('nombre',$perfil->getNombre())[0][0]}</td>";
//     $lista .= "<td>{$perfil->getNombre()}</td>";
//     $lista .= "<td>{$perfil->getDescripcion()}</td>";
//     //$lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/habilidad/habilidadFormulario.php&accion=adicionar'>Adicionar</a></td>";
//     $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/perfil/perfilFormulario.php&accion=Modificar&idPerfil={$perfil->getIdPerfil('nombre',$perfil->getNombre())[0][0]}' title='modificar perfil'> Modificar </a></td>";
//     $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/perfil/perfilCRUD.php&accion=Eliminar&idPerfil={$perfil->getIdPerfil('nombre',$perfil->getNombre())[0][0]}' onclick='eliminar({$perfil->getIdPerfil('nombre',$perfil->getNombre())[0][0]})' title='Eliminar Perfil'>Eliminar</a></td>";
//     $lista .= "<td></td>";
//     $lista .= "</tr>";
// }
?>

<h3>LISTA DE PERFILES</h3>
<table border="1">
    <thead>
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Descripcion</th>
            <?php
                if (Usuario::esAdmin($USUARIO->getIdentificacion())) {
                    echo "<th><a href='principal.php?CONTENIDO=presentacion/configuracion/perfil/perfilFormulario.php&accion=Adicionar'>Adicionar</a></th>";
                }

                if (count($resultado) == 0) {
                    echo 'no se encontró perfil registrado';
                }
            ?>
        </tr>
    </thead>
    <tbody>
        <?= $json_Usuarios ?>
    </tbody>
</table>


<script type="text/javascript">
    function eliminar(id) {
        var respuesta = confirm("Esta seguro de eliminar este registro?");
        if (respuesta) {
            location = "principal.php?CONTENIDO=presentacion/configuracion/perfil/perfilCRUD.php&accion=Eliminar&idPerfil=" + id;
        }
    }
</script>