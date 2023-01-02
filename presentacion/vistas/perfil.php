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
echo "$USUARIO->getTipoUsuario()";

switch ($USUARIO->getTipoUsuario()) {
    case 'A': //muestra todos los perfiles y opciones porque es admin
        $json_Perfiles = Perfil::getListaEnJson(null, null);
        // modo CRUD;
        print_r("entr");
    case 'D': //proyectos de los que es director
        $json_Perfiles = Perfil::getListaEnJson(null, null);
        // solo lectura
        break;
        print_r("entrD");

    default:
        print_r($USUARIO->getTipoUsuario(), "entrDef");
        //trabajador: solo su información de perfil activo
        $ident = $USUARIO->getIdentificacion();
        $filtroUsuario = "identificacion=$ident";
        print_r($ident, $filtroUsuario);
        $json_Perfiles = Perfil::getListaEnJson($filtroUsuario, null);
        break;
}
?>

<h3 class="text-center">PERFIL DE USUARIOS</h3>

<fieldset class="form-group border p-3">
    <div class="container col-auto justify-content-center">
        <div class="row">
            <legend class="w-auto px-2">Perfiles</legend>

            <table id="tblPerfiles" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
            
            <?= $json_Perfiles ?>

        </div>
    </div>
</fieldset>


<script type="module">
    import {
        cargarPerfiles,
    } from './presentacion/vistas/js/perfiles.js';

    let lisPerfiles = [];
    <?php echo 'const dPerf = ' . $json_Perfiles . ';'; ?>

    // console.log(dProy);
    if (lisPerfiles.length == 0 || lisPerfiles == null) {
        lisPerfiles = [...dPerf];
    }

    $(document).ready(function() {
        // TODO: Ajustar según permisos del usuario
        var modoTabla = 'RUD'
        cargarPerfiles('tblPerfiles', lisPerfiles, modoTabla);
    });
</script>