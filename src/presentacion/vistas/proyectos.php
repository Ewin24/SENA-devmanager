<?php

require_once 'logica/clases/ProyectoAdm.php';
require_once 'logica/clasesGenericas/ddl_parametrizado.php';

// session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Ya hay una sesión activa, acceso no autorizado'); //sesiones activas al tiempo
else {
    $USUARIO = unserialize($_SESSION['usuario']);
}

$btnHabilidadQuitar = "";
$btnHabilidadAsign = "";
$btnTrabajadorQuitar = "";
$btnTrabajadorContr = "";
$btnPostularse = "";

$identificacion = $USUARIO->getIdentificacion();
$idUsuario = $USUARIO->getId();
$tipoUsuario = $USUARIO->getTipo_usuario();
// echo $idUsuario, $identificacion, $tipoUsuario;
switch ($tipoUsuario) {
    case 'A': //Admin (Modo CRUD): muestra todos los perfiles y opciones porque es admin
        $modoTabla = 'CRUD';
        $btnHabilidadQuitar = "<div class='col-lg-3'><input type='button' name='action' value='Quitar' class= 'btn btn-primary' onclick='quitarHabilidades()'></div>";
        $btnHabilidadAsign = "<div class='col-lg-3'><input type='button' name='action' value='Asignar' class='btn btn-primary' onclick='asignarHabilidades()'></div>";
        $btnTrabajadorQuitar = "<div class='col-lg-3'><input type='button' name='action' value='Anular Contrato' class='btn btn-primary' onclick='quitarTrabajadores()'></div>'";
        $btnTrabajadorContr = "<div class='col-lg-3'><input type='button' name='action' value='Contratar' class='btn btn-primary' onclick='asignarTrabajadores()'></div>";
        break;
    case 'D': //Director (modo CRUD filtrado): solo su información de perfil activo
        $modoTabla = 'CRUD';
        $btnHabilidadQuitar = "<div class='col-lg-3'><input type='button' name='action' value='Quitar' class= 'btn btn-primary' onclick='quitarHabilidades()'></div>";
        $btnHabilidadAsign = "<div class='col-lg-3'><input type='button' name='action' value='Asignar' class='btn btn-primary' onclick='asignarHabilidades()'></div>";
        $btnTrabajadorQuitar = "<div class='col-lg-3'><input type='button' name='action' value='Anular Contrato' class='btn btn-primary' onclick='quitarTrabajadores()'></div>'";
        $btnTrabajadorContr = "<div class='col-lg-3'><input type='button' name='action' value='Contratar' class='btn btn-primary' onclick='asignarTrabajadores()'></div>";
        break;
    default: //trabajador (modo: Solo lectura): perfiles existentes
        $modoTabla = 'R';
        $btnPostularse = "<div class='col-lg-3'><input type='button' name='action' value='Postularse' class='btn btn-primary' onclick='postularse()'></div>";
        break;
}
// // print_r($datosProyectos);
?>

<fieldset class="form-group p-3">
    <div class="container col-auto justify-content-center">
        <div class="row">
            <legend id="titulo" class="w-auto px-2">
                <h3>Proyectos Disponibles</h3>
            </legend>

            <table id="tblProyectos" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>

            <table id="new-Proyecto" style="display:none" class="col-auto">
                <tbody>
                    <tr>
                        <td></td>
                        <td>1</td>
                        <td>__nombre__</td>
                        <td>__descripcion__</td>
                        <td>__P__</td>
                        <td>01/01/1900</td>
                        <td>01/01/2099</td>
                        <td>__idDirector__</td>
                        <td>
                            <i class='bi ' +`${claseBotonEditarRow}` aria-hidden="true"></i>
                            <i class='bi ' +`${claseBotonEliminarRow}` aria-hidden="true"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?= $btnPostularse ?>
        </div>
    </div>

</fieldset>

<fieldset id='fsHabilidades' class="form-group border p-3">
    <legend id="titulo" class="w-auto px-2">
        <h3>Habilidades del proyecto</h3>
    </legend>
    <div class="row">
        <div class="col-lg-6">
            <h5 class="col text-center">Requeridas</h1>
                <table id="tblHab_Requeridas" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
                <table id="new-Hab_Requerida" style="display:none" class="col-auto">
                    <tbody>

                        <tr>
                            <td></td>
                            <td>1</td>
                            <td>__Proyecto__</td>
                            <td>__Habilidad__</td>
                            <td>
                                <i class='bi ' +`${claseBotonEditarRow}` aria-hidden="true"></i>
                                <i class='bi ' +`${claseBotonEliminarRow}` aria-hidden="true"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?= $btnHabilidadQuitar ?>
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
                <?= $btnHabilidadAsign ?>
        </div>
    </div>
</fieldset>

<fieldset id='fsTrabajadores' class="text-center form-group border p-3">
    <legend id="titulo" class="w-auto">
        <h3>Trabajadores del proyecto</h3>
    </legend>
    <div class="row">
        <div class="row col-lg-6">
            <h5 class="col text-center">Contratados</h5>
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
                <?= $btnTrabajadorQuitar ?>
            </table>
        </div>
        <div class="col-lg-6">
            <h5 id="testo" class="text-center">Postulados</h5>
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
                <?= $btnTrabajadorContr ?>
            </table>
        </div>
    </div>

</fieldset>

<script type="text/javascript">
    function mostrarAdvertencia(titulo, mensaje) {
        var html = `
        <div id="myModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-">
                        <h3 class="modal-title">${titulo}</h#>
                    </div>
                    <div class="modal-body">
                        <p>${mensaje}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        `
        $(document.body).append(html);
        $("#myModal").modal("show");
    }

    //////////////////////////////////////////////////////////////////////////////
    //FUNCIONALIDAD DE POSTULARSE A UN PROYECTO

    function postularse() {
        <?php echo 'const idUsuario = "' . $idUsuario . '";'; ?>

        $('#tblProyectos tbody tr').each(function(fila, elemento) {
            var checkb = $(elemento).find('input[type=checkbox]');
            if (checkb.is(':checked')) {
                var datosFila = $('#tblProyectos').DataTable().rows(fila).data()[0];

                var dataReq = {
                    datos: datosFila.id,
                    id_usuario: idUsuario,
                    action: 'Postularse'
                }

                $.ajax({
                    url: 'http://localhost/SENA-devmanager/src/api/ProyectoControlador.php',
                    method: "POST",
                    data: dataReq,
                    dataType: "json",
                    success: function(response) {
                        if (response['error']) mostrarAdvertencia('Ocurrido una excepción en la Base: ', response.error);
                        // alert("Status: "+response);
                        console.log(response);
                        $('#tbl_Proyectos').DataTable().ajax.reload();
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Status: " + textStatus);
                        alert("Error: " + errorThrown);
                    }
                });
            }
        });

    }

    //////////////////////////////////////////////////////////////////////////////
    //FUNCIONALIDAD DE HABILIDADES
    function asignarHabilidades() {
        var id_proyecto = $('#tblHab_Disponibles').attr('id_padre');

        $('#tblHab_Disponibles tbody tr').each(function(fila, elemento) {
            var checkb = $(elemento).find('input[type=checkbox]');
            if (checkb.is(':checked')) {
                var datosFila = $('#tblHab_Disponibles').DataTable().rows(fila).data()[0];

                var dataReq = {
                    datos: datosFila.id,
                    id_proyecto: id_proyecto,
                    action: 'Insertar_tblHab_Requeridas'
                }

                $.ajax({
                    url: 'http://localhost/SENA-devmanager/src/api/ProyectoControlador.php',
                    method: "POST",
                    data: dataReq,
                    dataType: "json",
                    success: function(response) {
                        // alert("Status: "+response);
                        // console.log('///////////' + response.json);
                        $('#tblHab_Disponibles').DataTable().ajax.reload();
                        $('#tblHab_Requeridas').DataTable().ajax.reload();
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Status: " + textStatus);
                        alert("Error: " + errorThrown);
                    }
                });
            }
        });
    }

    function quitarHabilidades() {
        var id_proyecto = $('#tblHab_Requeridas').attr('id_padre');

        $('#tblHab_Requeridas tbody tr').each(function(fila, elemento) {
            var checkb = $(elemento).find('input[type=checkbox]');
            if (checkb.is(':checked')) {
                var datosFila = $('#tblHab_Requeridas').DataTable().rows(fila).data()[0];

                var dataReq = {
                    datos: datosFila.id,
                    id_proyecto: id_proyecto,
                    action: 'Eliminar_tblHab_Requeridas'
                }

                $.ajax({
                    url: 'http://localhost/SENA-devmanager/src/api/ProyectoControlador.php',
                    method: "POST",
                    data: dataReq,
                    dataType: "json",
                    success: function(response) {
                        // alert("Status: "+response);
                        // console.log('///////////' + response.json);
                        $('#tblHab_Disponibles').DataTable().ajax.reload();
                        $('#tblHab_Requeridas').DataTable().ajax.reload();
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Status: " + textStatus);
                        alert("Error: " + errorThrown);
                    }
                });
            }
        });
    }

    //////////////////////////////////////////////////////////////////////////////
    //FUNCIONALIDAD DE TRABAJADORES
    function asignarTrabajadores() {
        var id_proyecto = $('#tblCandidatos').attr('id_padre');

        $('#tblCandidatos tbody tr').each(function(fila, elemento) {
            var checkb = $(elemento).find('input[type=checkbox]');
            if (checkb.is(':checked')) {
                var datosFila = $('#tblCandidatos').DataTable().rows(fila).data()[0];

                var dataReq = {
                    datos: datosFila.id_usuario,
                    id_proyecto: id_proyecto,
                    action: 'Insertar_tblContratados'
                }

                $.ajax({
                    url: 'http://localhost/SENA-devmanager/src/api/ProyectoControlador.php',
                    method: "POST",
                    data: dataReq,
                    dataType: "json",
                    success: function(response) {
                        // alert("Status: "+response);
                        // console.log('///////////' + response.json);
                        $('#tblCandidatos').DataTable().ajax.reload();
                        $('#tblContratados').DataTable().ajax.reload();
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Status: " + textStatus);
                        alert("Error: " + errorThrown);
                    }
                });
            }
        });
    }
    //quitar de trabajadores contratados y pasarlo a rechazado
    function quitarTrabajadores() {
        var id_proyecto = $('#tblContratados').attr('id_padre');

        $('#tblContratados tbody tr').each(function(fila, elemento) {
            var checkb = $(elemento).find('input[type=checkbox]');
            if (checkb.is(':checked')) {
                var datosFila = $('#tblContratados').DataTable().rows(fila).data()[0];

                var dataReq = {
                    datos: datosFila.id_usuario,
                    id_proyecto: id_proyecto,
                    action: 'Eliminar_tblContratados'
                }

                $.ajax({
                    url: 'http://localhost/SENA-devmanager/src/api/ProyectoControlador.php',
                    method: "POST",
                    data: dataReq,
                    dataType: "json",
                    success: function(response) {
                        // alert("Status: "+response);
                        // console.log('///////////' + response.json);
                        $('#tblCandidatos').DataTable().ajax.reload();
                        $('#tblContratados').DataTable().ajax.reload();
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Status: " + textStatus);
                        alert("Error: " + errorThrown);
                    }
                });
            }
        });
    }
</script>

<script type="module">
    import {
        cargarProyectos,
        cargarHabilidades,
        cargarTrabajadores
    } from './presentacion/vistas/js/proyectos.js'

    let lisProyectos = [];
    <?php echo 'const idUsuario = "' . $idUsuario . '";'; ?>
    <?php echo 'const tipoUsuario = "' . $tipoUsuario . '";'; ?>
    <?php echo 'const modoTabla = "' . $modoTabla . '";'; ?>
    <?php //echo 'const dProy = ' . $datosProyectos . ';'; 
    ?>
    $(document).ready(function() {

        $('input[value="Quitar"]').hide();
        $('input[value="Asignar"]').hide();
        $('input[value="Anular Contrato"]').hide();
        $('input[value="Contratar"]').hide();

        cargarProyectos('tblProyectos', idUsuario, modoTabla);
        var IdProySeleccionado = '';
        var selectorTabla = '#tblProyectos'

        $(selectorTabla + ' tbody').on('click', 'tr', function() {
            // var celda = dataTable.cell(this);
            var rowindex = $(this).closest("tr").index();
            // console.log(selectorTabla, rowindex);
            var data = $(selectorTabla).DataTable().row(rowindex).data();

            if (data.id != IdProySeleccionado) {

                IdProySeleccionado = data.id;
                cargarHabilidades('tblHab_Requeridas', IdProySeleccionado, tipoUsuario);
                cargarHabilidades('tblHab_Disponibles', IdProySeleccionado, tipoUsuario);
                cargarTrabajadores('tblContratados', IdProySeleccionado, tipoUsuario);
                cargarTrabajadores('tblCandidatos', IdProySeleccionado, tipoUsuario);

                if ($("#tblHab_Requeridas").length) $('input[value="Quitar"]').show();
                if ($("#tblHab_Disponibles").length) $('input[value="Asignar"]').show();
                if ($("#tblContratados").length) $('input[value="Anular Contrato"]').show();
                if ($("#tblCandidatos").length) $('input[value="Contratar"]').show();
            }

            if ($("#guardarCambiostblProyectos").css("display") === 'none') {
                if ($('#desctblProyectos').css("display") === 'block') {
                    // console.log("hijas");
                    $('#fsHabilidades').css("display", "block");
                    $('#fsTrabajadores').css("display", "block");
                } else {
                    // console.log("ocultar");
                    $('#fsHabilidades').css("display", "none");
                    $('#fsTrabajadores').css("display", "none");
                }
            } else {
                // console.log("todo oculto");
                $('#fsHabilidades').css("display", "none");
                $('#fsTrabajadores').css("display", "none");
            }
        });

        $('#addRowtblHab_Disponibles').click(function() {
            // if($('#tblHab_Requeridas').length) $('#tblHab_Requeridas').DataTable().clear().draw();
            // if($('#tblHab_Disponibles').length) $('#tblHab_Disponibles').DataTable().clear().draw();
            // if($('#tblContratados').length) $('#tblContratados').DataTable().clear().draw();
            // if($('#tblCandidatos').length) $('#tblCandidatos').DataTable().clear().draw();
            if ($("#tblHab_Requeridas").length) $('input[value="Quitar"]').hide();
            if ($("#tblHab_Disponibles").length) $('input[value="Asignar"]').hide();
            if ($("#tblContratados").length) $('input[value="Anular Contrato"]').hide();
            if ($("#tblCandidatos").length) $('input[value="Contratar"]').hide();
        });

    });
</script>