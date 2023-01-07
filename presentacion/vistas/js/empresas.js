import { cargarTablaGenerica } from "../../../librerias/tablaGenerica.js";


function cargarEmpresas(nombreTabla, idUsuario, modoTabla = 'R') {

    var dataUrl = 'http://localhost/SENA-devmanager/api/EmpresaControlador.php';//hay que configurar algun tipo de variable para que la url sirva si el proyecto cambia de nombre o de server
    var ddl_estado_ops = [
    { value : 'X', key : '' },
    { value : 'P', key : 'Pendiente' },
    { value : 'E', key : 'Ejecución' },
    { value : 'T', key : 'Terminado' }
    ];

    var colsEmpresas = [
        {   data:null, render:function(){return "<input type='checkbox'/>";}, visible: true },
        {   title: 'id', data: 'id', name: 'id',  visible: false },
        {   title: 'Nit', data: 'nit',name: 'nit' },
        {   title: 'Nombre', data: 'nombre',name: 'nombre' ,visible: false },
        {   title: 'Direccion', data: 'direccion',name: 'direccion',visible: true},
        {   title: 'Correo', data: 'correo',name:'correo', visible: true},
        {   title: 'Telefono', data: 'telefono',name: 'telefono', visible: true},
        {   title: 'Nombre Representante', data: 'nombre_representante',name:'nombre_representante', visible: true},
        {   title: 'Correo Representante', data: 'correo_representante',name: 'correo_representante',visible: true}
    ];

    // configuración de carga inicial
    // $('#campoDescripcion').hide();
    $('#botonesGuardarCambios').hide();
    $('#botonesGuardarCambios').attr("disabled", "disabled");

    //TODO: preguntar a willi como funciona esta var
    var payloadEmpresas = {
        datos : idUsuario,
        action : 'cargar_'+nombreTabla,
        html_table : nombreTabla
    }

    cargarTablaGenerica(nombreTabla, colsEmpresas, modoTabla, dataUrl, payloadEmpresas, ddl_estado_ops, true);

    //TODO: preguntar el funcionamiento de este codigo
    $('#btn-cancel-'+nombreTabla).click(function() {
        $('#fsUsuarios').prop("disabled", false);
    });
}

function cargarTrabajadores(nombreTabla, idEmpresaSeleccionada, modoTabla ='R') {
    // var dataUrl = "";
    var colsTrabajadores = [
      {data: null,render: function () {return "<input type='checkbox'/>";},visible: true,},
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

    var payloadTrabajadores = {
        datos : JSON.stringify( idEmpresaSeleccionada ),
        action : 'cargar_'+nombreTabla,
        html_table : nombreTabla
    }

    if($('#'+nombreTabla).lenght) $('#'+nombreTabla).DataTable().clear().draw();
    cargarTablaGenerica(nombreTabla, colsTrabajadores, modoTabla, dataUrl, payloadTrabajadores);
}

export { cargarEmpresas, cargarTrabajadores }

// https://datatables.net/forums/discussion/1723/editable-with-datepicker-inside-datatables
// select everything when editing field in focus
// https://stackoverflow.com/questions/14643617/create-table-using-javascript
// https://linuxhint.com/create-table-from-array-objects-javascript/
