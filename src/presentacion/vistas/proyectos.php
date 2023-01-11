<?php

require_once 'logica/clases/ProyectoAdm.php';
require_once 'logica/clasesGenericas/ddl_parametrizado.php';

// session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Ya hay una sesión activa, acceso no autorizado'); //sesiones activas al tiempo
else {
    $USUARIO = unserialize($_SESSION['usuario']);
}

$identificacion = $USUARIO->getIdentificacion();
$idUsuario = $USUARIO->getId();
$tipoUsuario = $USUARIO->getTipo_usuario();
echo $idUsuario, $identificacion, $tipoUsuario;
switch ($tipoUsuario) {
    case 'A': //Admin (Modo CRUD): muestra todos los perfiles y opciones porque es admin
        // $datosProyectos = Proyecto::getListaEnJson(null, null);
        $modoTabla = 'CRUD';
        echo "Usuario A";
        break;

    case 'D': //Director (modo CRUD filtrado): solo su información de perfil activo
        // $idUsuario = $USUARIO->getId();
        // $filtroUsuario = "id_usuario='$idUsuario'";
        // $datosProyectos = Proyecto::getListaEnJson($filtroUsuario, null);
        // echo "Usuario D";
        // R solo lectura
        $modoTabla = 'CRUD';
        break;

    default: //trabajador (modo: Solo lectura): perfiles existentes
        // $datosProyectos = $USUARIO->getProyectosUsuario($USUARIO->getId());
        $modoTabla = 'R';
        // echo "Usuario T";
        break;
}
// // print_r($datosProyectos);
?>

<h3 class="text-center">LISTA DE PROYECTOS</h3>

<fieldset class="form-group border p-3">
    <div class="container col-auto justify-content-center">
        <div class="row">
            <legend class="w-auto px-2">Proyectos Disponibles</legend>

            <table id="tblProyectos" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
            <!-- <div class="col align-self-center">
                <textarea id="campoDescripcion" type="text" class="form-control" style="min-width: 100%" rows="5" disabled="disabled"></textarea>
            </div> -->

            <table id="new-Proyecto" style="display:none" class="col-auto">
                <tbody>
                    <tr>
                        <td></td>
                        <td>__id__</td>
                        <td>__nombre__</td>
                        <td>__descripcion__</td>
                        <td>__P__</td>
                        <td>01/01/1900</td>
                        <td>01/01/2099</td>
                        <td>__idDirector__</td>
                        <td>__Correo@Director__</td>
                        <td>
                            <i class='bi ' +`${claseBotonEditarRow}` aria-hidden="true"></i>
                            <i class='bi ' +`${claseBotonEliminarRow}` aria-hidden="true"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</fieldset>

<fieldset id='fsHabilidades' class="form-group border p-3">
    <legend class="w-auto px-2">Habilidades del proyecto</legend>
    <div class="row">
        <div class="col-lg-6">
            <h5 class="col text-center">Requeridas</h1>
                <table id="tblHab_Requeridas" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
                <table id="new-Hab_Requerida" style="display:none" class="col-auto">
                    <tbody>
                        <tr>
                            <td></td>
                            <td>__id__</td>
                            <td>__Proyecto__</td>
                            <td>__Habilidad__</td>
                            <td>
                                <i class='bi ' +`${claseBotonEditarRow}` aria-hidden="true"></i>
                                <i class='bi ' +`${claseBotonEliminarRow}` aria-hidden="true"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
        </div>
        <div class="col-lg-6">
            <h5 class="text-center">Disponibles</h1>
                <table id="tblHab_Disponibles" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
                <table id="new-Hab_Disponible" style="display:none" class="col-auto">
                    <tbody>
                        <tr>
                            <td></td>
                            <td>__id__</td>
                            <td>__Proyecto__</td>
                            <td>__Habilidad__</td>
                            <td>
                                <i class='bi ' +`${claseBotonEditarRow}` aria-hidden="true"></i>
                                <i class='bi ' +`${claseBotonEliminarRow}` aria-hidden="true"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
        </div>
    </div>
</fieldset>

<fieldset id='fsTrabajadores' class="form-group border p-3">
    <legend class="w-auto px-2">Trabajadores del proyecto</legend>
    <div class="row">
        <div class="row col-lg-6">
            <h5 class="col text-center">Asignados</h5>
            <table id="tblContratados" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
            <table id="new-Contratado" style="display:none" class="col-auto">
                <tbody>
                    <tr>
                        <td></td>
                        <td>__id__</td>
                        <td>__id_usuario__</td>
                        <td>__fecha_solicitud__</td>
                        <td>__estado__</td>
                        <td>
                            <i class='bi ' +`${claseBotonEditarRow}` aria-hidden="true"></i>
                            <i class='bi ' +`${claseBotonEliminarRow}` aria-hidden="true"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-lg-6">
            <h5 id="testo" class="text-center">Postulados/Disponibles</h5>
            <table id="tblCandidatos" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
            <table id="new-Candidato" style="display:none" class="col-auto">
                <tbody>
                    <tr>
                        <td></td>
                        <td>__id__</td>
                        <td>__id_usuario__</td>
                        <td>__fecha_solicitud__</td>
                        <td>__estado__</td>
                        <td>
                            <i class='bi ' +`${claseBotonEditarRow}` aria-hidden="true"></i>
                            <i class='bi ' +`${claseBotonEliminarRow}` aria-hidden="true"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</fieldset>

<script type="module">
    import {
        cargarProyectos,
        cargarHabilidades,
        cargarTrabajadores
    } from './presentacion/vistas/js/proyectos.js'

    let lisProyectos = [];
    <?php echo 'const idUsuario = "' . $idUsuario . '";'; ?>
    <?php echo 'const modoTabla = "' . $modoTabla . '";'; ?>
    <?php //echo 'const dProy = ' . $datosProyectos . ';'; ?>

    // if (lisProyectos.length == 0 || lisProyectos == null) {
    //     lisProyectos = [...dProy];
    // }
    // console.log(lisProyectos);

    // //genera_tabla(arreglo);    


    $(document).ready(function() {
        cargarProyectos('tblProyectos', idUsuario, modoTabla);
        var IdProySeleccionado = '';
        var selectorTabla = '#tblProyectos'

        $(selectorTabla + ' tbody').on('click', 'tr', function() {
            // if($(this).hasClass('selected')) {
            // var celda = dataTable.cell(this);
            var rowindex = $(this).closest("tr").index();
            // console.log(selectorTabla, rowindex);
            var data = $(selectorTabla).DataTable().row(rowindex).data();

            if (data.id != IdProySeleccionado) {

                IdProySeleccionado = data.id;
                // console.log(IdProySeleccionado);
                
                console.clear();
                cargarHabilidades('tblHab_Requeridas', IdProySeleccionado, modoTabla);
                cargarHabilidades('tblHab_Disponibles',IdProySeleccionado, modoTabla);
                cargarTrabajadores('tblContratados', IdProySeleccionado, modoTabla);
                cargarTrabajadores('tblCandidatos', IdProySeleccionado, modoTabla);
                // //// peticion - https://coderszine.com/live-datatables-crud-with-ajax-php-mysql/
                // var dataReq = {
                //     datos : IdProySeleccionado, 
                //     action : 'cargarTablasHijas'
                // };
                // $.ajax({
                //     url:"http://localhost/SENA-devmanager/api/ProyectoControlador.php",
                //     method:"POST",
                //     data: dataReq,
                //     dataType:"json",
                //     success:function(datos){
                //         var { dHabReq, dHabDisp, dTrabReq, dTrabDisp } = datos.data;
                //         console.log(datos);
                //         cargarHabilidades('tblHab_Requeridas', dHabReq, modoTabla);
                //         cargarHabilidades('tblHab_Disponibles', dHabDisp, modoTabla);
                //         cargarTrabajadores('tblContratados', dTrabReq, modoTabla);
                //         cargarTrabajadores('tblCandidatos', dTrabDisp, modoTabla);
                //     }
                // });
                
                // fetch('http://localhost/SENA-devmanager/api/ProyectoControlador.php?id=' + IdProySeleccionado, {
                //     method: 'GET',
                // }).then((resp) => {
                //     return resp.json();
                // }).then((json) => {
                //     const {
                //         dHabReq,
                //         dHabDisp,
                //         dTrabReq,
                //         dTrabDisp
                //     } = json;
                //         cargarHabilidades('tblHab_Requeridas', dHabReq, modoTabla);
                //         cargarHabilidades('tblHab_Disponibles', dHabDisp, modoTabla);
                //         cargarTrabajadores('tblContratados', dTrabReq, modoTabla);
                //         cargarTrabajadores('tblCandidatos', dTrabDisp, modoTabla);
                // });
            }
        });

        $('#addRowtblProyectos').click(function() {
            $('#tblHab_Requeridas').DataTable().clear().draw();
            $('#tblHab_Disponibles').DataTable().clear().draw();
            $('#tblContratados').DataTable().clear().draw();
            $('#tblCandidatos').DataTable().clear().draw();
        });

    });
</script>