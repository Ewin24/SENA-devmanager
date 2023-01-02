<?php
//session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Ya hay una sesion activa, acceso no autorizado'); //sesiones activas al tiempo
else {
    $USUARIO = unserialize($_SESSION['usuario']);
    $identificacion = $USUARIO->getIdentificacion();
}

//codigo en caso de que se generen mensajes desde la adicion, eliminacion o edicion de un usuario
$mensaje = '';
if (isset($_REQUEST['mensaje'])) {
    $mensaje = $_REQUEST['mensaje'];
    $sms = "<div id='alerta' class='alert alert-danger text-center m-2 ' role='alert'>$mensaje</div>";
}

//$json_usuarios = Usuario::getListaEnObjetos(null, null);
$json_empresa = '[';
$resultado = Empresa::getListaEnObjetos(null, null);
for ($i = 0; $i < count($resultado); $i++) {
    $empresa = $resultado[$i];
    $json_empresa .= '{ id: "' . $empresa->getId()
        . '", nit: "' . $empresa->getNit()
        . '", nombre: "' . $empresa->getNombre()
        . '", direccion: "' . $empresa->getDireccion()
        . '", correo: "' . $empresa->getCorreo()
        . '", telefono: "' . $empresa->getTelefono()
        . '", nombre_representante: "' . $empresa->getNombreRepresentante()
        . '", correo_representante: "' . $empresa->getCorreoRepresentante()
        . '"},';
}
$json_empresa .= ']';

//print_r($json_empresa);
//$idEmpresaSeleccionada = '20a9d4e8-63a8-48f0-910f-c7339d8fd7ec';
//EmpresaAdm::cargarTablasHijas($idEmpresaSeleccionada);
?>

<h3 class="text-center">ADMINISTRACION</h3>
<?php
if (Usuario::esAdmin($identificacion)) {
    // deja manipular usuarios si el user de sesion es ADMIN 
    // echo  '<span><button type="button" class="btn btn-primary"><a href="principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoFormulario.php&accion=Adicionar"></a>Nuevo Proyecto</button></span> ';
    // echo  '<button type="button" id="addRow" class="btn btn-primary">Nuevo Proyecto</button></span> ';
}
?>

<fieldset class="form-group border p-3">
    <div class="container col-auto justify-content-center">
        <div class="row">
            <legend class="w-auto px-2">EMPRESAS REGISTRADAS</legend>

            <table id="tblEmpresas" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
            <!-- <div class="col align-self-center">
                <textarea id="campoDescripcion" type="text" class="form-control" style="min-width: 100%" rows="5" disabled="disabled"></textarea>
            </div> -->

            <table id="new-Empresa" style="display:none" class="col-auto">
                <tbody>
                    <tr>
                        <td></td>
                        <td>__nit__</td>
                        <td>__direccion__</td>
                        <td>__correo__</td>
                        <td>__telefono__</td>
                        <td>__nombre representante__</td>
                        <td>__correo representante__</td>
                        <td>
                            <i class='bi ' +`${claseBotonEditarRow}` aria-hidden="true"></i>
                            <i class='bi ' +`${claseBotonEliminarRow}` aria-hidden="true"></i>
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
    <div class="container col-auto justify-content-center">
        <div class="row">
            <legend class="w-auto px-2">USUARIOS EMPRESA</legend>

            <table id="tblUsuarios" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
            <!-- <div class="col align-self-center">
                <textarea id="campoDescripcion" type="text" class="form-control" style="min-width: 100%" rows="5" disabled="disabled"></textarea>
            </div> -->

            <table id="new-Usuario" style="display:none" class="col-auto">
                <tbody>
                    <tr>
                        <td>__Nit__</td>
                        <td>__apellido__</td>
                        <td>__tipoUsuario__</td>
                        <td>__nombre Usuario__</td>
                        <td>__correo__</td>
                        <td>__tipo identificacion__</td>
                        <td>__telefono__</td>
                        <td>__direccion__</td>
                        <td>__Empresa__</td>
                        <td>
                            <i class='bi ' +`${claseBotonEditarRow}` aria-hidden="true"></i>
                            <i class='bi ' +`${claseBotonEliminarRow}` aria-hidden="true"></i>
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

<!-- <script type="text/javascript" src="assets/barraBusqueda.js"></script> -->
<script type="module">
    import {
        cargarEmpresas,
        cargarUsuarios
    } from './presentacion/vistas/js/empresas.js'

    let lisEmpresas = [];
    <?php echo 'const dEmpr = ' . $json_empresa . ';'; ?>

    // console.log(dProy);
    if (lisEmpresas.length == 0 || lisEmpresas == null) {
        lisEmpresas = [...dEmpr];
    }

    $(document).ready(function() {
        //  opciones de crud, dependiendo del usuario de sesion
        let modoTabla = ''
        <?php
        echo "const tUsuario = '{$USUARIO->getTipoUsuario()}' ;"; //traer tipo de usuario de sesion
        ?>
        switch (tUsuario) {
            case 'A':
                modoTabla = 'CRUD';
                break;
            case 'D':
                modoTabla = 'R';
                break;
            case 'T':
                modoTabla = 'R';
                break;
            default:
                break;
        }
        cargarEmpresas('tblEmpresas', lisEmpresas, modoTabla);
        var $idEmpresaSeleccionada = '';
        var selectorTabla = '#tblEmpresas'
        //console.log(lisEmpresas);

        $(selectorTabla + ' tbody').on('click', 'tr', function() {
            // if($(this).hasClass('selected')) {
            // var celda = dataTable.cell(this);
            var rowindex = $(this).closest("tr").index();
            console.log(selectorTabla, rowindex);
            var data = $(selectorTabla).DataTable().row(rowindex).data();

            if (data.id != $idEmpresaSeleccionada) {
                $('tblUsuarios').DataTable().clear().draw();
                $idEmpresaSeleccionada = data.id;
                // console.log($idEmpresaSeleccionada);

                // peticion 
                fetch('http://localhost/SENA-devmanager/api/EmpresaControlador.php?nit=' + $idEmpresaSeleccionada, {
                    method: 'GET',
                }).then((resp) => {
                    return resp.json();
                }).then((json) => {
                    const {
                        trabajadores
                    } = json;

                    //  opciones de crud, dependiendo del usuario de sesion
                    let modoTabla = '';
                    switch (tUsuario) {
                        case 'A':
                            modoTabla = 'CRUD';
                            break;
                        case 'D':
                            modoTabla = 'CRU';
                            break;
                        case 'T':
                            modoTabla = 'R';
                            break;
                        default:
                            break;
                    }
                    cargarUsuarios('tblUsuarios', trabajadores, modoTabla);
                });
            }
            // }
        });

        //limpia tablas hijas cuando se hace addRow
        $('#addRowtblEmpresa').click(function() {
            $('#tblUsuarios').DataTable().clear().draw();
        });
    });
</script>