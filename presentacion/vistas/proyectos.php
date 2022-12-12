<?php
session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Ya hay una sesion activa, acceso no autorizado'); //sesiones activas al tiempo
else {
    $USUARIO = unserialize($_SESSION['usuario']);
}
$lista = '';
$identificacion = $USUARIO->getIdentificacion();

$resultado = Proyecto::getListaEnObjetos(null, null);


for ($i = 0; $i < count($resultado); $i++) {

    $proyecto = $resultado[$i];
    //echo $proyecto;
    $lista .= '<tr>';
    $lista .= "<td>{$proyecto->getIdProyecto()}</td>";
    $lista .= "<td>{$proyecto->getNombre()}</td>";
    $lista .= "<td>{$proyecto->getDescripcion()}</td>";
    $lista .= "<td>{$proyecto->getEstado()}</td>";
    $lista .= "<td>{$proyecto->getFechaInicio()}</td>";
    $lista .= "<td>{$proyecto->getFechaFinalizacion()}</td>";

    if ($USUARIO->esAdmin($USUARIO->getIdentificacion())) { //esta misma validacion se hace para todos, en caso de que sea trabajador se deja que postule o agregue estudios o habilidades
        $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoFormulario.php&accion=Modificar&idProyecto={$proyecto->getIdproyecto()}' title='modificar proyecto'> Modificar </a></td>";
        $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoCRUD.php&accion=Eliminar&idProyecto={$proyecto->getIdproyecto()}' onclick='eliminar({$proyecto->getIdproyecto()})' title='Eliminar proyecto'>Eliminar</a></td>";
    }

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
            <?php
            if (Usuario::esAdmin($identificacion)) {
                echo  "<th><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoFormulario.php&accion=Adicionar'>Adicionar</a></th>"; // deja adicionar si el user es admin
            }

            ?>
        </tr>
    </thead>
    <tbody>
        <?= $lista ?>
        <?php
        if (count($resultado) == 0) {
            echo 'No se encontraron proyectos registrados'; //validar si se encontraron resultados
        }
        ?>
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