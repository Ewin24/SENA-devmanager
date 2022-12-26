<?php
// session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Ya hay una sesión activa, acceso no autorizado'); //sesiones activas al tiempo
else {
    $USUARIO = unserialize($_SESSION['usuario']);
}

$identificacion = $USUARIO->getIdentificacion();

$datosProyectos = '[';

$resultado = Proyecto::getListaEnObjetos(null, null);
for ($i = 0; $i < count($resultado); $i++)
{
    $proyecto = $resultado[$i];
    $datosProyectos .=
        '{ id: "' . $proyecto->getIdProyecto()
        . '", nombre: "' . $proyecto->getNombre()
        . '", descripcion: "' . $proyecto->getDescripcion()
        . '", estado: "' . $proyecto->getEstado()
        . '", fecha_inicio: "' . $proyecto->getFechaInicio()
        . '", fecha_fin: "' . $proyecto->getFechaFinalizacion()
        . '"},';
}
$datosProyectos .= ']';

switch ($USUARIO->getTipoUsuario()) {
    case 'A': //muestra todos los proyectos y opciones porque es admin
        //$resultado = Proyecto::getListaEnObjetos(null, null);
        // for ($i = 0; $i < count($resultado); $i++) {

        //     $proyecto = $resultado[$i];
        //     echo $proyecto;
        //     $datos .=
        //         '{ id: "' . $proyecto->getIdProyecto()
        //         . '", nombre: "' . $proyecto->getNombre()
        //         . '", descripcion: "' . $proyecto->getDescripcion()
        //         . '", estado: "' . $proyecto->getEstado()
        //         . '", fechaInicio: "' . $proyecto->getFechaInicio()
        //         . '", fechaFinalizacion: "' . $proyecto->getFechaFinalizacion()
        //         . '"},';

        //     $lista .= '<tr>';
        //     $lista .= "<td>{$proyecto->getIdProyecto()}</td>";
        //     $lista .= "<td>{$proyecto->getNombre()}</td>";
        //     $lista .= "<td>{$proyecto->getDescripcion()}</td>";
        //     $lista .= "<td>{$proyecto->getEstado()}</td>";
        //     $lista .= "<td>{$proyecto->getFechaInicio()}</td>";
        //     $lista .= "<td>{$proyecto->getFechaFinalizacion()}</td>";

        //     if ($USUARIO->esAdmin($USUARIO->getIdentificacion())) { //esta misma validación se hace para todos, en caso de que sea trabajador se deja que postule o agregue estudios o habilidades
        //         $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoFormulario.php&accion=Modificar&idProyecto={$proyecto->getIdproyecto()}' title='modificar proyecto'> Modificar </a></td>";
        //         $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoCRUD.php&accion=Eliminar&idProyecto={$proyecto->getIdproyecto()}' onclick='eliminar({$proyecto->getIdproyecto()})' title='Eliminar proyecto'>Eliminar</a></td>";
        //     }

        //     $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoCRUD.php&accion=Postularse&idProyecto={$proyecto->getIdproyecto()}&idUsuario={$USUARIO->getIdentificacion()}' title='Postular a proyecto'>Postularse</a></td>";
        //     $lista .= "<td></td>";
        //     $lista .= "</tr>";
        // }
        break;

    case 'D': //proyectos de los que es director

        break;

    default:
        //trabajador: proyectos en los que esta activo, o proyectos en los que puede postularse según sus habilidades
        break;
}
?>

<h3 class="text-center">LISTA DE PROYECTOS</h3>
<?php
    if (Usuario::esAdmin($identificacion) || Usuario::esDirector($identificacion)) {
        // deja adicionar si el user es ADMIN o Director
        // echo  '<span><button type="button" class="btn btn-primary"><a href="principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoFormulario.php&accion=Adicionar"></a>Nuevo Proyecto</button></span> ';
        // echo  '<button type="button" id="addRow" class="btn btn-primary">Nuevo Proyecto</button></span> ';
    }
?>


<fieldset class="form-group border p-3">
    <div class="container col-auto justify-content-center">
        <div class="row">
            <legend class="w-auto px-2">Proyectos Disponibles</legend>

            <table id="tblProyectos" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
            <!-- <div class="col align-self-center">
                <textarea id="campoDescripcion" type="text" class="form-control" style="min-width: 100%" rows="5" disabled="disabled"></textarea>
            </div> -->

            <table id="new-row-template" style="display:none" class="col-auto">
                <tbody>
                    <tr>
                        <td></td>
                        <td>__id__</td>
                        <td>__nombre__</td>
                        <td>__descripcion__</td>
                        <td>__estado__</td>
                        <td>01/01/1900</td>
                        <td>01/01/2099</td>
                        <td>
                            <i class='bi '+`${claseBotonEditarRow}` aria-hidden="true"></i>
                            <i class='bi '+`${claseBotonEliminarRow}` aria-hidden="true"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- <br> 
        <div class="row">
            <div class="col align-self-start"></div>
            <div class="col align-self-center"></div>
            <div id="botonesGuardarCambios" class="col align-self-end" disabled="disabled">
                <button type="button" id="btn-cancel" class="btn btn-secondary" data-dismiss="modal">Revertir Cambios</button>
                <button type="button" id="btn-save" class="btn btn-primary" data-dismiss="modal">Guardar Cambios</button>
            </div>
        </div> -->
    </div>

</fieldset>

<fieldset class="form-group border p-3">
    <legend class="w-auto px-2">Definir Perfiles</legend>
    <div class="row">
        <div class="col-lg-6">
            <h5 class="col text-center">Requeridos</h1>
            <table id="tblPerfiles" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
            
        </div>
        <div class="col-lg-6">
            <h5 class="text-center">Disponibles</h1>
            <table id="tblPerfilesDisp" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
        </div>
    </div>
</fieldset>

<fieldset class="form-group border p-3">
    <legend class="w-auto px-2">Asignar Trabajadores</legend>
    <div class="row">
        <div class="row col-lg-6">
            <h5 class="col text-center">Asignados</h5>
            <table id="tblTrabajadores" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>

        </div>
        <div class="col-lg-6">
            <h5 class="text-center">Postulados/Disponibles</h5>
            <table id="tblTrabajadoresDisp" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
        </div>
    </div>
    
</fieldset>

<!-- <script type="text/javascript" src="assets/barraBusqueda.js"></script> -->
<script type="module"> 

    import { cargarProyectos } from './presentacion/vistas/js/proyectos.js'

    let lisProyectos = [];
    <?php echo 'const dProy = ' . $datosProyectos . ';'; ?>
    console.log(dProy);
    if (lisProyectos.length == 0 || lisProyectos == null){
        lisProyectos = [...dProy];
    }
    //genera_tabla(arreglo);    

    $(document).ready(function() {
        cargarProyectos('tblProyectos', lisProyectos);
    });

</script>

