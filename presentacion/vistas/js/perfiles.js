import { cargarTablaGenerica } from "../../../librerias/tablaGenerica.js";

function cargarPerfiles(nombreTabla, arreglo, modoTabla='CRUD') {
    var colsPerfiles = [
        {
            data: null,
            render: function () {
              return "<input type='checkbox'/>";
            },
            visible: true,
          },
          { title: "Id", data: "id", name: 'id', visible: false },
          { title: "Identificacion", data: "identificacion", name: 'identificacion', visible: true },
          {title: "Tipo Ident.",data: "tipo_identificacion", name:'tipo_identificacion', className: "ddl" },
          { title: "Nombres", data: "nombres",name: "nombres", visible: true },
          { title: "Apellidos", data: "apellidos",name: "apellidos", visible: true },
          { title: "Correo", data: "correo", name: "correo", visible: true },
          { title: "Clave", data: "clave_hash", name: "clave_hash", visible: false },
          { title: "Dirección", data: "direccion",name: "direccion", visible: true },
          { title: "Foto", data: "nombre_foto", name: "nombre_foto", visible: true },
          { title: "Telefono", data: "telefono", name: "telefono", visible: true },
          { title: "Tipo de Usuario", data: "tipo_usuario", name: "tipo_usuario",  visible: true },
          { title: "nit de Empresa", data: "id_mpresa", name: "id_mpresa", visible: false }
    ];
    console.log("Perf", arreglo);
    cargarTablaGenerica(nombreTabla, arreglo, colsPerfiles, modoTabla);
}

function cargarEstudios(nombreTabla, arreglo, modoTabla='CRUD'){
    var colsEstudios = [
        { data:null, render:function(){return "<input type='checkbox'/>";}, visible: true },
        { title: 'id', data: 'id', visible: true },
        { title: 'Nombre Certificado', data: 'nombre_certificado'},
        { title: 'Fecha Certificado', data: 'fecha_certificado'},
        { title: 'Id usuario', data: 'id_usuario', visible: false},
        { title: 'Id estudio', data: 'id_estudio', visible: false}
    ];
    // console.log("hab", arreglo);
    cargarTablaGenerica(nombreTabla, arreglo, colsEstudios, modoTabla);
}

function cargarHabilidades(nombreTabla, arreglo, modoTabla='CRUD'){
    var colsHabilidades = [
        { data:null, render:function(){return "<input type='checkbox'/>";}, visible: true },
        { title: 'id', data: 'id', visible: true },
        { title: 'Experiencia', data: 'experiencia'},
        { title: 'Id usuario', data: 'id_usuario', visible: false},
        { title: 'Id habilidad', data: 'id_habilidad', visible: false}
    ];
    // console.log("hab", arreglo);
    cargarTablaGenerica(nombreTabla, arreglo, colsHabilidades, modoTabla);
}

export { cargarPerfiles, cargarEstudios, cargarHabilidades }