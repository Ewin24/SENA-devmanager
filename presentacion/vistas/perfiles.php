<?php
// session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Ya hay una sesion activa, acceso no autorizado'); //sesiones activas al tiempo
else {
    $USUARIO = unserialize($_SESSION['usuario']);
}

//codigo en caso de que se generen mensajes desde la adicion, eliminacion o edicion de un usuario
$mensaje = '';
if (isset($_REQUEST['mensaje'])) {
    $mensaje = $_REQUEST['mensaje'];
    $sms = "<div id='alerta' class='alert alert-danger text-center m-2 ' role='alert'>$mensaje</div>";
}

$json_perfiles = '[';
$resultado = Perfil::getListaEnObjetos(null, null);
for ($i = 0; $i < count($resultado); $i++) {
    $perfil = $resultado[$i];
    $json_perfiles .= '{ id: "' . $perfil->getId()
        . '", identificacion: "' . $perfil->getIdentificacion()
        . '", nombres: "' . $perfil->getNombres()
        . '", apellidos: "' . $perfil->getApellidos()
        . '", correo: "' . $perfil->getCorreo()
        . '", tipo_identificacion: "' . $perfil->getTipoIdentificacion()
        . '", clave_hash: "' . $perfil->getClave()
        . '", direccion: "' . $perfil->getDireccion()
        . '", nombre_foto: "' . $perfil->getFoto()
        . '", telefono: "' . $perfil->getTelefono()
        . '", tipo_usuario: "' . $perfil->getTipoUsuario()
        . '", id_empresa: "' . $perfil->getIdEmpresa()
        . '"},';
}
$json_perfiles .= ']';

?>

<h3 class="text-center">PERFIL DE USUARIOS</h3>

<fieldset class="form-group border p-3">
    <div class="container col-auto justify-content-center">
        <div class="row">
            <legend class="w-auto px-2">Perfiles</legend>
            <table id="tblPerfiles" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
            //< ?=$json_Perfiles ?>
        </div>
    </div>
</fieldset>

<fieldset class="form-group border p-3">
    <div class="container col-auto justify-content-center">
        <div class="row">
            <legend class="w-auto px-2">Estudios</legend>
            <table id="tblEstudios" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
            //< ?=$json_Perfiles ?>
        </div>
    </div>
</fieldset>

<fieldset class="form-group border p-3">
    <div class="container col-auto justify-content-center">
        <div class="row">
            <legend class="w-auto px-2">Habilidades</legend>
            <table id="tblHabilidades" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
            //< ?=$json_Perfiles ?>
        </div>
    </div>
</fieldset>

<script type="module">
    import {
        cargarPerfiles,
        cargarEstudios,
        cargarHabilidades,
    } from './presentacion/vistas/js/perfiles.js';

    let lisPerfiles = [];
    <?php echo 'const dPerf = ' . $json_perfiles . ';'; ?>

    if (lisPerfiles.length == 0 || lisPerfiles == null) {
        lisPerfiles = [...dPerf];
    }

    $(document).ready(function() {
        let modoTabla = '';
        <?php
            echo "const tUsuario = '{$USUARIO->getTipo_Usuario()}' ;"; //traer tipo de usuario de sesion
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
        cargarPerfiles('tblPerfiles', lisPerfiles, modoTabla);
        var $idPerfilSeleccionado = '';
        var selectorTabla = '#tblPerfiles';

        $(selectorTabla + ' tbody').on('click', 'tr', function() {
            var rowindex = $(this).closest("tr").index();
            console.log(selectorTabla, rowindex);
            var data = $(selectorTabla).DataTable().row(rowindex).data();

            if (data.id != $idPerfilSeleccionado) {
                $('tblEstudios').DataTable().clear().draw();
                $('tblHabilidades').DataTable().clear().draw();

                $idPerfilSeleccionado = data.id;
                console.log($idPerfilSeleccionado);

                // peticion 
                fetch('http://localhost/SENA-devmanager/api/PerfilControlador.php?id=' + $idPerfilSeleccionado, {
                    method: 'GET',
                }).then((resp) => {
                    return resp.json();
                }).then((json) => {
                    const {
                        dEstudios,
                        dHabilidades
                    } = json;

                    console.log(dEstudios);

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
                    cargarEstudios('tblEstudios', dEstudios, modoTabla);
                    cargarHabilidades('tblHabilidades', dHabilidades, modoTabla);
                });
            }

            // }
        });

        $('#addRowtblPerfiles').click(function() {
            $('#tblEstudios').DataTable().clear().draw();
            $('#tblHabilidades').DataTable().clear().draw();
        });

    });
</script>