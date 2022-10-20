<?php
session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Ya hay una sesion activa, acceso no autorizado'); //sesiones activas al tiempo
else {
    $USUARIO = unserialize($_SESSION['usuario']);
}
$lista = '';

$resultado = Proyecto::getListaEnObjetos(null, null);

for ($i = 0; $i < count($resultado); $i++) {
    $proyecto = $resultado[$i];
    //echo $proyecto;
    $lista .= '<tr>';
    $lista .= "<td>{$proyecto->getIdProyecto()}</td>";
    $lista .= "<td>{$proyecto->getNombre()}</td>";
    $lista .= "<td>{$proyecto->getDescipcion()}</td>";
    $lista .= "<td>{$proyecto->getEstado()}</td>";
    $lista .= "<td>{$proyecto->getFechaInicio()}</td>";
    $lista .= "<td>{$proyecto->getFechaFinalizacion()}</td>";

    if ($evento->getEstado() == 'Por ejecutar') { //si el evento esta por ejecutar, se puede editar o eliminar
        $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoFormulario.php&accion=Modificar&idProyecto={$proyecto->getIdproyecto()}' title='modificar proyecto'> Modificar </a></td>";
    
    }

    $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoCRUD.php&accion=Eliminar&idProyecto={$proyecto->getIdproyecto()}' onclick='eliminar({$proyecto->getIdproyecto()})' title='Eliminar proyecto'>Eliminar</a></td>";
    $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoCRUD.php&accion=Postularse&idProyecto={$proyecto->getIdproyecto()}&idUsuario={$USUARIO->getIdentificacion()}' title='Postular a proyecto'>Postularse</a></td>";
    $lista .= "<td></td>";
    $lista .= "</tr>";
}
?>

<h3>LISTA DE PROYECTOS</h3>
<table border="1">
    <thead>
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Descripcion</th>
            <th>Estado</th>
            <th>Fecha de Inicio</th>
            <th>Fecha de finalizacion</th>
            <th><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoFormulario.php&accion=Adicionar'>Adicionar</a></th>
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
            location = "principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoCRUD.php&accion=Eliminar&idEstudio=" + id;
        }
    }
</script>