<?php
session_start();
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
$json_usuarios = '[';
$resultado = Usuario::getListaEnObjetos(null, null);
for ($i = 0; $i < count($resultado); $i++) {
    $usuario = $resultado[$i];
    $json_usuarios .= '{ identificacion: "' . $usuario->getIdentificacion()
        . '", nombre: "' . $usuario->getNombre()
        . '", apellido: "' . $usuario->getApellido()
        . '", tipoUsuario: "' . $usuario->getTipoUsuario()
        . '", nombreUsuario: "' . $usuario->getNombreUsuario()
        . '", correo: "' . $usuario->getCorreo()
        . '", telefono: "' . $usuario->getTelefono()
        . '", tipoIdentificacion: "' . $usuario->getTipoIdentificacion()
        . '", foto: "' . $usuario->getFoto()
        . '", direccion: "' . $usuario->getDireccion()
        . '", nitEmpresa: "' . $usuario->getNitEmpresa()
        . '"},';
}
$json_usuarios .= ']';

print_r($json_usuarios);
?>

<h3 class="text-center">GESTION DE USUSARIOS</h3>
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
            <legend class="w-auto px-2">Usuarios Registrados</legend>

            <table id="tblUsuarios" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
            <!-- <div class="col align-self-center">
                <textarea id="campoDescripcion" type="text" class="form-control" style="min-width: 100%" rows="5" disabled="disabled"></textarea>
            </div> -->

            <table id="new-row-template" style="display:none" class="col-auto">
                <tbody>
                    <tr>
                        <td></td>
                        <td>__identificacion__</td>
                        <td>__nombre__</td>
                        <td>__apellido__</td>
                        <td>__tipoUsuario__</td>
                        <td>__nombre Usuario__</td>
                        <td>__correo__</td>
                        <td>__tipo identificacion__</td>
                        <td><input type="file" name="foto" id=""></td>
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
        cargarUsuarios
    } from './presentacion/vistas/js/usuarios.js'

    let lisUsuarios = [];
    <?php echo 'const dProy = ' . $json_usuarios . ';'; ?>
    console.log(dProy);
    if (lisUsuarios.length == 0 || lisUsuarios == null) {
        lisUsuarios = [...dProy];
    }
    //genera_tabla(arreglo);    

    $(document).ready(function() {
        cargarUsuarios('tblUsuarios', lisUsuarios);
    });
</script>