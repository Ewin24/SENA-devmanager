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
        { title: "Id", data: "id", visible: false },
        { title: "Identificacion", data: "identificacion", visible: true },
        {
            title: "Tipo Ident.",
            data: "tipo_identificacion",
            className: "ddl",
        },
        { title: "Nombres", data: "nombres", visible: true },
        { title: "Apellidos", data: "apellidos", visible: true },
        { title: "Correo", data: "correo", visible: true },
        { title: "Clave", data: "clave_hash", visible: true, type: "password" },
        { title: "Dirección", data: "direccion", visible: true },
        { title: "Foto", data: "nombre_foto", visible: true },
        { title: "Telefono", data: "telefono", visible: true },
        { title: "Tipo de Usuario", data: "tipo_usuario", visible: true },
        { title: "nitEmpresa", data: "id_empresa", visible: false },
    ];
    console.log("Perf", arreglo);
    cargarTablaGenerica(nombreTabla, arreglo, colsPerfiles, modoTabla);
}

// TODO: Crear metodos cargarEstudios, cargarHabilidades

export { cargarPerfiles, cargarEstudios, cargarHabilidades }