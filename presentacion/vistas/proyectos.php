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
    $lista .= '<tr>';
    $lista .= "<td>{$proyecto->getNombre()}</td>";
    $lista .= "<td>{$proyecto->getDescripcion()}</td>";
    $lista .= "<td>{$proyecto->getEstado()}</td>";
    $lista .= "<td>{$proyecto->getFechaInicio()}</td>";
    $lista .= "<td>{$proyecto->getFechaFin()}</td>";
    $lista .= "</tr>";
}
?>

<h3>LISTA DE PROYECTOS</h3>
<table border="1">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Descripcion</th>
            <th>Estado</th>
            <th>Fecha Inicio</th>
            <th>Fecha Fin</th>
            <th><a href="">adicionar</a></th>
        </tr>
    </thead>
    <tbody>
        <?= $lista ?>
    </tbody>
</table>