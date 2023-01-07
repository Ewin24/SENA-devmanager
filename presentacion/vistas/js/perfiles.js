import { cargarTablaGenerica } from "../../../librerias/tablaGenerica.js";

dataUrl = "http://localhost/SENA-devmanager/api/PerfilControlador.php";

function cargarPerfiles(nombreTabla, idUsuario, arreglo, modoTabla='CRUD') {
    var colsPerfiles = [
          { data: null,render: function () {return "<input type='checkbox'/>";},visible: true,},
          { title: "Id", data: "id", name: 'id', visible: false },
          { title: "Identificacion", data: "identificacion", name: 'identificacion', visible: true },
          { title: "Tipo Ident.",data: "tipo_identificacion", name:'tipo_identificacion', className: "ddl" },
          { title: "Nombres", data: "nombres",name: "nombres", visible: true },
          { title: "Apellidos", data: "apellidos",name: "apellidos", visible: true },
          { title: "Correo", data: "correo", name: "correo", visible: true },
          { title: "Clave", data: "clave_hash", name: "clave_hash", visible: false },
          { title: "Dirección", data: "direccion",name: "direccion", visible: true },
          { title: "Foto", data: "nombre_foto", name: "nombre_foto", visible: true },
          { title: "Telefono", data: "telefono", name: "telefono", visible: true },
          { title: "Tipo de Usuario", data: "tipo_usuario", name: "tipo_usuario",  visible: true },
          { title: "nit de Empresa", data: "id_empresa", name: "id_mpresa", visible: false }
    ];

    
    // configuración de carga inicial
    // $('#campoDescripcion').hide();
    $('#botonesGuardarCambios').hide();
    $('#botonesGuardarCambios').attr("disabled", "disabled");

    var payloadPerfil = {
        datos : idUsuario,
        action : 'cargar_'+nombreTabla,
        html_table : nombreTabla
    }

    console.log("Perf", arreglo);
    cargarTablaGenerica(nombreTabla, colsPerfiles, modoTabla, dataUrl, payloadPerfil, ddl_ops, true);

    $('#btn-cancel-'+nombreTabla).click(function() {
        $('#fsEstudios').prop("disabled", false);
        $('#fsHabilidades').prop("disabled", false);
    });
}

function cargarEstudios(nombreTabla, IdPerfilSeleccionado, modoTabla='CRUD'){
    var colsEstudios = [
        { data:null, render:function(){return "<input type='checkbox'/>";}, visible: true },
        { title: 'id', data: 'id', visible: true },
        { title: 'Nombre Certificado', data: 'nombre_certificado'},
        { title: 'Fecha Certificado', data: 'fecha_certificado'},
        { title: 'Id usuario', data: 'id_usuario', visible: false},
        { title: 'Id estudio', data: 'id_estudio', visible: false}
    ];
    // console.log("hab", arreglo);

    var payloadEstudios = {
        datos : JSON.stringify( IdPerfilSeleccionado ),
        action : 'cargar_'+nombreTabla,
        html_table : nombreTabla
    }
    if($('#'+nombreTabla).lenght) $('#'+nombreTabla).DataTable().clear().draw();
    cargarTablaGenerica(nombreTabla, colsEstudios, modoTabla, dataUrl, payloadEstudios);
}

function cargarHabilidades(nombreTabla, IdPerfilSeleccionado, modoTabla='CRUD'){
    var colsHabilidades = [
        { data:null, render:function(){return "<input type='checkbox'/>";}, visible: true },
        { title: 'id', data: 'id', visible: true },
        { title: 'Experiencia', data: 'experiencia'},
        { title: 'Id usuario', data: 'id_usuario', visible: false},
        { title: 'Id habilidad', data: 'id_habilidad', visible: false}
    ];

    var payloadHabilidades = {
        datos : JSON.stringify( IdPerfilSeleccionado),
        action : 'cargar_'+nombreTabla,
        html_table : nombreTabla
    }

    if($('#'+nombreTabla).lenght) $('#'+nombreTabla).DataTable().clear().draw();
    cargarTablaGenerica(nombreTabla, colsHabilidades, modoTabla, dataUrl, payloadHabilidades);
}

export { cargarPerfiles, cargarEstudios, cargarHabilidades }