<?php
session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Ya hay una sesion activa, acceso no autorizado'); //sesiones activas al tiempo
else {
    $USUARIO = unserialize($_SESSION['usuario']);
}
$lista = '';

$resultado = Estudio::getListaEnObjetos(null, null);

for ($i = 0; $i < count($resultado); $i++) {
    $estudio = $resultado[$i];
    echo $estudio;
    $lista .= '<tr>';
    $lista .= "<td>{$estudio->getIdEstudio()}</td>";
    $lista .= "<td>{$estudio->getNombreEstudio()}</td>";
    $lista .= "<td>{$estudio->getCertificado()}</td>";
    $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/estudio/estudioFormulario.php&accion=Modificar&idEstudio={$estudio->getIdEstudio()}' title='modificar Estudio'> Modificar </a></td>";
    $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/estudio/estudioCRUD.php&accion=Eliminar&idEstudio={$estudio->getIdEstudio()}' onclick='eliminar({$estudio->getIdEstudio()})' title='Eliminar Estudio'>Eliminar</a></td>";
    //$lista .= "<a href='presentacion/candidatos/propuestas/{$candidato->getPropuesta()}' title= 'ver propuesta' target= '-blank'><img src='presentacion/imagenes/pdf.png'></a>";
    
    $lista .= "<td></td>";
    $lista .= "</tr>";
}
?>

<h3>LISTA DE ESTUDIOS</h3>
<table border="1">
    <thead>
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Certificacion</th>
            <th><a href='principal.php?CONTENIDO=presentacion/configuracion/estudio/estudioFormulario.php&accion=Adicionar'>Adicionar</a></th>
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
            location = "principal.php?CONTENIDO=presentacion/configuracion/estudio/estudioCRUD.php&accion=Eliminar&idEstudio=" + id;
        }
    }
</script>