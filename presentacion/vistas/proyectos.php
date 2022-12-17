<?php
// session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Ya hay una sesion activa, acceso no autorizado'); //sesiones activas al tiempo
else {
    $USUARIO = unserialize($_SESSION['usuario']);
}

$lista = '';
$identificacion = $USUARIO->getIdentificacion();

$datos = '[';

$resultado = Proyecto::getListaEnObjetos(null, null);
for ($i = 0; $i < count($resultado); $i++)
{
    $proyecto = $resultado[$i];
    $datos .=
        '{ id: "' . $proyecto->getIdProyecto()
        . '", nombre: "' . $proyecto->getNombre()
        . '", descripcion: "' . $proyecto->getDescripcion()
        . '", estado: "' . $proyecto->getEstado()
        . '", fechaInicio: "' . $proyecto->getFechaInicio()
        . '", fechaFinalizacion: "' . $proyecto->getFechaFinalizacion()
        . '"},';
}
$datos .= ']';

switch ($USUARIO->getTipoUsuario()) {
    case 'A': //muestra todos los proyectos y opciones porque es admin
        $resultado = Proyecto::getListaEnObjetos(null, null);
        for ($i = 0; $i < count($resultado); $i++) {

            $proyecto = $resultado[$i];
            $datos .=
                '{ id: "' . $proyecto->getIdProyecto()
                . '", nombre: "' . $proyecto->getNombre()
                . '", descripcion: "' . $proyecto->getDescripcion()
                . '", estado: "' . $proyecto->getEstado()
                . '", fechaInicio: "' . $proyecto->getFechaInicio()
                . '", fechaFinalizacion: "' . $proyecto->getFechaFinalizacion()
                . '"},';

            // $lista .= '<tr>';
            // $lista .= "<td>{$proyecto->getIdProyecto()}</td>";
            // $lista .= "<td>{$proyecto->getNombre()}</td>";
            // $lista .= "<td>{$proyecto->getDescripcion()}</td>";
            // $lista .= "<td>{$proyecto->getEstado()}</td>";
            // $lista .= "<td>{$proyecto->getFechaInicio()}</td>";
            // $lista .= "<td>{$proyecto->getFechaFinalizacion()}</td>";

            // if ($USUARIO->esAdmin($USUARIO->getIdentificacion())) { //esta misma validación se hace para todos, en caso de que sea trabajador se deja que postule o agregue estudios o habilidades
            //     $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoFormulario.php&accion=Modificar&idProyecto={$proyecto->getIdproyecto()}' title='modificar proyecto'> Modificar </a></td>";
            //     $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoCRUD.php&accion=Eliminar&idProyecto={$proyecto->getIdproyecto()}' onclick='eliminar({$proyecto->getIdproyecto()})' title='Eliminar proyecto'>Eliminar</a></td>";
            // }

            // $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoCRUD.php&accion=Postularse&idProyecto={$proyecto->getIdproyecto()}&idUsuario={$USUARIO->getIdentificacion()}' title='Postular a proyecto'>Postularse</a></td>";
            // $lista .= "<td></td>";
            // $lista .= "</tr>";
        }
        break;

    case 'D': //proyectos de los que es director

        break;

    default:
        //trabajador: proyectos en los que esta activo, o proyectos en los que puede postularse según sus habilidades
        break;
}
$datos .= ']';
?>

<h3>LISTA DE PROYECTOS</h3>
<table border="1">
    <thead>
        <tr>

            <!-- FUNCION EN JS PARA ELIMINAR FILAS DE LA TABLA DEPENDIENDO DE UN FILTRO -->
            <th> <input id="nombre" type="text" placeholder="Nombre del Proyecto"> </th>
            <th> <input id="descripcion" type="text" placeholder="Descripcion"></th>
            <th> <input id="estadp" type="text" placeholder="Estado del Proyecto"></button></th>
            <th> <input id="fecha_inicio" type="text" placeholder="Fecha de Inicio"></th>
            <th> <input id="fecha_fin" type="text" placeholder="Fecha de finalizacion"></th>
            <?php
                if (Usuario::esAdmin($identificacion) || Usuario::esDirector($identificacion)) {
                    // deja adicionar si el user es ADMIN o Director
                    echo  "<th><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoFormulario.php&accion=Adicionar'>Adicionar</a></th>";
                }
            ?>
        </tr>
    </thead>
    <tbody>
        <?= $lista ?>
        <?php
        if (count($resultado) == 0) {
            echo 'No se encontraron proyectos registrados'; //validar si se encontraron resultados
        }else{
            $datos;
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

    <?php echo 'const datos = ' . $datos . ';'; ?>
    console.log(datos);


    function busquedaColumna(id, arregloDatos, tipoDato) {
        const campoBusqueda = document.getElementById(id);
        let textoColumna = campoBusqueda.parentNode;
    }
</script>