import { cargarTablaGenerica } from "../../../librerias/tablaGenerica.js";

var dataUrl = 'http://localhost/SENA-devmanager/src/api/PerfilControlador.php';

function cargarPerfiles(nombreTabla, idUsuario, modoTabla='R') {
    var ddl_estado_ops = [
        { value : 'X', key : '' },
        { value : 'P', key : 'Pendiente' },
        { value : 'E', key : 'Ejecución' },
        { value : 'T', key : 'Terminado' }
    ];

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

    //console.log("Perf", arreglo);
    cargarTablaGenerica(nombreTabla, colsPerfiles, modoTabla, dataUrl, payloadPerfil);

    $('#btn-cancel-'+nombreTabla).click(function() {
        $('#fsEstudios').prop("disabled", false);
        $('#fsHabilidades').prop("disabled", false);
    });
}

function cargarEstudios(nombreTabla, IdPerfilSeleccionado, modoTabla='CRUD'){
    var colsEstudios = [
        { data:null, render:function(){return "<input type='checkbox'/>";}, visible: true },
        { title: "Id", data: "id", name: 'id', visible: false },
        { title: 'Nombre', data: 'nombre' ,name: 'nombre', visible: true },
        { title: 'Nombre Certificado', data: 'nombre_certificado', name: 'nombre_certificado', visible: true },
        { title: 'Nombre Archivo', data: 'nombre_archivo', name:'nombre_archivo', className: 'fUpload',
                    // render: function ( data, type, row, meta ) {
                    // var idDat = "cert_" + data.id; //meta.row;
                    // var idDatFN = "datFileName" + meta.row;
                    // var ctrol = `
                    //             <div class="input-group">
                    //             <span class="input-group-btn">
                    //                 <input id=${idDat} class="btn btn-primary file-upload" type="file" name="file" disabled="disabled"/>
                    //             </span> 
                    //             </div>
                    //             `
                    // return ctrol;},
                    visible: true },
        { title: 'Fecha Certificado', data: 'fecha_certificado', name: 'fecha_certificado', visible: true },
        { title: 'Id usuario', data: 'id_usuario', name: 'id_usuario', visible: false},
        { title: 'Id estudio', data: 'id_estudio', name: 'id_estudio', visible: false}
    ];
    // console.log("hab", arreglo);

    var payloadEstudios = {
        datos : IdPerfilSeleccionado ,
        action : 'cargar_'+nombreTabla,
        html_table : nombreTabla
    }
    if($('#'+nombreTabla).lenght) $('#'+nombreTabla).DataTable().clear().draw();
    cargarTablaGenerica(nombreTabla, colsEstudios, modoTabla, dataUrl, payloadEstudios);
}

function cargarHabilidades(nombreTabla, IdPerfilSeleccionado, modoTabla='CRUD'){
    var colsHabilidades = [
        { data:null, render:function(){return "<input type='checkbox'/>";}, visible: true },
        { title: 'Nombre Habilidad', data: 'nombre', name: 'nombre', visible: true },
        { title: 'Descripcion', data: 'descripcion', name: 'descripcion', visible: true },
        { title: 'Experiencia', data: 'experiencia', name: 'experiencia', visible: true },
        { title: 'Id usuario', data: 'id_usuario', name: 'id_usuario',visible: false},
        { title: 'Id habilidad', data: 'id_habilidad', name: 'id_habilidad' ,visible: false}
    ];

    var payloadHabilidades = {
        datos : IdPerfilSeleccionado,
        action : 'cargar_'+nombreTabla,
        html_table : nombreTabla
    }

    if($('#'+nombreTabla).lenght) $('#'+nombreTabla).DataTable().clear().draw();
    cargarTablaGenerica(nombreTabla, colsHabilidades, modoTabla, dataUrl, payloadHabilidades);
}

export { cargarPerfiles, cargarEstudios, cargarHabilidades }