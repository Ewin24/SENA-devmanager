<?php
// session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Ya hay una sesion activa, acceso no autorizado'); //sesiones activas al tiempo
else {
    $USUARIO = unserialize($_SESSION['usuario']);
}

// if (Usuario::esAdmin($USUARIO->getIdentificacion()) || Usuario::esDirector($USUARIO->getIdentificacion())) {
//     // deja adicionar si el user es ADMIN o Director
//     // echo  '<span><button type="button" class="btn btn-primary"><a href="principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoFormulario.php&accion=Adicionar"></a>Nuevo Proyecto</button></span> ';
//     // echo  '<button type="button" id="addRow" class="btn btn-primary">Nuevo Proyecto</button></span> ';
// }
$json_Perfiles = '[]';
$modoTabla = '';
echo $USUARIO->getTipoUsuario();

switch ($USUARIO->getTipoUsuario()) {
    case 'A': //Admin (Modo CRUD): muestra todos los perfiles y opciones porque es admin
        $json_Perfiles = Perfil::getListaEnJson(null, null);
        $modoTabla = "'CRUD'";

    case 'D': //Director (modo: Solo lectura): perfiles existentes
        $json_Perfiles = Perfil::getListaEnJson(null, null);
        // R solo lectura
        $modoTabla = "'R'";
        break;

    default: //trabajador (modo CRUD filtrado): solo su información de perfil activo
        $ident = $USUARIO->getIdentificacion();
        $filtroUsuario = "identificacion=$ident";
        $json_Perfiles = Perfil::getListaEnJson($filtroUsuario, null);
        $modoTabla = "'CRUD'";
        break;
}
?>

<h3 class="text-center">PERFIL DE USUARIOS</h3>

<fieldset class="form-group border p-3">
    <div class="container col-auto justify-content-center">
        <div class="row">
            <legend class="w-auto px-2">Perfiles</legend>
            <table id="tblPerfiles" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
            //< ?= $json_Perfiles ?>
        </div>
    </div>
</fieldset>

<fieldset class="form-group border p-3">
    <div class="container col-auto justify-content-center">
        <div class="row">
            <legend class="w-auto px-2">Estudios</legend>
            <table id="tblEstudios" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
            //< ?= $json_Perfiles ?>
        </div>
    </div>
</fieldset>

<fieldset class="form-group border p-3">
    <div class="container col-auto justify-content-center">
        <div class="row">
            <legend class="w-auto px-2">Habilidades</legend>
            <table id="tblHabilidades" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
            //< ?= $json_Perfiles ?>
        </div>
    </div>
</fieldset>


<script type="module">
    import {
        cargarPerfiles,
    } from './presentacion/vistas/js/perfiles.js';

    let lisPerfiles = [];
    var idUsuario = '';

    <?php echo 'const dPerf = ' . $json_Perfiles . ';'; ?>
    <?php echo 'const modoTabla = ' . $modoTabla . ';'; ?>

    // console.log(dProy);
    if (lisPerfiles.length == 0 || lisPerfiles == null) {
        lisPerfiles = [...dPerf];
    }

    $(document).ready(function() {
        // TODO: Ajustar según permisos del usuario
        console.log(modoTabla);
        cargarPerfiles('tblPerfiles', lisPerfiles, modoTabla);

        $(selectorTabla + ' tbody').on('click', 'tr', function() {
            // if($(this).hasClass('selected')) {
            // var celda = dataTable.cell(this);
            var rowindex = $(this).closest("tr").index();
            console.log(selectorTabla, rowindex);
            var data = $(selectorTabla).DataTable().row(rowindex).data();

            if (data.id != idUsuario) {
                $('tblEstudios').DataTable().clear().draw();
                $('tblHabilidades').DataTable().clear().draw();

                idUsuario = data.id;
                console.log(idUsuario);

                // peticion 
                fetch('http://localhost/SENA-devmanager/api/ProyectoControlador.php?id=' + idUsuario, {
                    method: 'GET',
                }).then((resp) => {
                    return resp.json();
                }).then((json) => {
                    const {
                        dEstudios,
                        dHabilidades
                    } = json;
                        // TODO: Ajustar según permisos del usuario
                        cargarEstudios('tblEstudios', dEstudios, modoTabla);
                        cargarHabilidades('tblHabilidades', dHabilidades, modoTabla);
                    });
                }

            // }
        });

        $('#addRowtblProyectos').click(function() {
            $('#tblEstudios').DataTable().clear().draw();
            $('#tblHabilidades').DataTable().clear().draw();
        });

    });
</script>