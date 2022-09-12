<?php
session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Ya hay una sesion activa, acceso no autorizado'); //sesiones activas al tiempo
else {
    $USUARIO = unserialize($_SESSION['usuario']);
}
$lista = '';

$resultado = Habilidad::getListaEnObjetos(null, null);

for ($i = 0; $i < count($resultado); $i++) {
    $proyecto = $resultado[$i];
    $lista .= '<tr>';
    $lista .= "<td>{$proyecto->getIdHabilidad()}</td>";
    $lista .= "<td>{$proyecto->getNombre()}</td>";
    $lista .= "<td>{$proyecto->getDescripcion()}</td>";
    $lista .= "<td>{$proyecto->getExperiencia()}</td>";
    $lista .= "<td>{$proyecto->getNivelDominio()}</td>";
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
            <th>Experiencia</th>
            <th>Dominio</th>
            <th><a href="">adicionar</a></th>
        </tr>
    </thead>
    <tbody>
        <?= $lista ?>
    </tbody>
</table>