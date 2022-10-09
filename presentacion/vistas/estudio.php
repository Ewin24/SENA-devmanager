<?php
session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Ya hay una sesion activa, acceso no autorizado'); //sesiones activas al tiempo
else {
    $USUARIO = unserialize($_SESSION['usuario']);
}
$lista = '';

$resultado = Habilidad::getListaEnObjetos(null, null);

for ($i = 0; $i < count($resultado); $i++) {
    $habilidad = $resultado[$i];
    $lista .= '<tr>';
    $lista .= "<td>{$habilidad->getIdHabilidad('nombre',$habilidad->getNombre())[0][0]}</td>";
    $lista .= "<td>{$habilidad->getNombre()}</td>";
    $lista .= "<td>{$habilidad->getDescripcion()}</td>";
    //$lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/habilidad/habilidadFormulario.php&accion=adicionar'>Adicionar</a></td>";
    $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/habilidad/habilidadFormulario.php&accion=Modificar&idHabilidad={$habilidad->getIdHabilidad('nombre',$habilidad->getNombre())[0][0]}' title='modificar habilidad'> Modificar </a></td>";
    $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/habilidad/habilidadCRUD.php&accion=Eliminar&idHabilidad={$habilidad->getIdHabilidad('nombre',$habilidad->getNombre())[0][0]}' onclick='eliminar({$habilidad->getIdHabilidad('nombre',$habilidad->getNombre())[0][0]})' title='Eliminar Habilidad'>Eliminar</a></td>";
    $lista .= "<td></td>";
    $lista .= "</tr>";

}
?>

<h3>LISTA DE HABILIDADES</h3>
<table border="1">
    <thead>
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Descripcion</th>
            <th><a href='principal.php?CONTENIDO=presentacion/configuracion/habilidad/habilidadFormulario.php&accion=Adicionar'>Adicionar</a></th>
        </tr>
    </thead>
    <tbody>
        <?= $lista ?>
    </tbody>
</table>


<script type="text/javascript">
    function eliminar(id) {
        var respuesta = confirm("Esta seguro de eliminar este registro?");
        if (respuesta) {
            location = "principal.php?CONTENIDO=presentacion/configuracion/habilidad/habilidadCRUD.php&accion=Eliminar&idHabilidad=" + id;
        }
    }
</script>