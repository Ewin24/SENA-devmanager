import {    cargarTablaGenerica, claseBotonEditarRow, claseBotonEliminarRow     } from "../../../librerias/tablaGenerica.js";

var dataUrl = "http://localhost/SENA-devmanager/api/ProyectoControlador.php";

function cargarProyectos(nombreTabla, idUsuario, arreglo=[], modoTabla='CRUD') {

    var ddl_estado_ops = [
    { value : 'X', key : '' },
    { value : 'P', key : 'Pendiente' },
    { value : 'E', key : 'Ejecución' },
    { value : 'T', key : 'Terminado' }
    ];

    var colProy = [
        {   data:null, render:function(){return "<input type='checkbox'/>";}, visible: true },
        {   title: 'id', data: 'id', name:'id', visible: false },
        {   title: 'Nombre', data: 'nombre', name:'nombre' },
        {   title: 'Descripcion', data: 'descripcion', name:'descripcion', visible: false },
        {   title: 'Estado', data: 'estado', name:'estado', type: "select", className: 'ddl', 
            // render: function() {'<option value='+ ddl_estado_ops['key'] +'>' + ddl_estado_ops['value']  + '</option>' }
            //     render: function (data, type, row) {
            //         var $select = $('<select class="select-basic" disabled="disabled" ></select>',
            //         {
            //             id: row.id,
            //             value: data
            //         });
            //     $.each(ddl_estado_ops, function (k, v) {
            //         // if (1 == 1) {   //changed this, not sure why the original code has it
            //         var $option = $("<option></option>",
            //         {
            //             text: v.key,
            //             value: v.value
            //         });
            //         //if selected_id = id then this is the selected value
            //         if (row.estado == v.value) {  //use == instead of ===
            //             $option.attr("selected", "selected");
            //         }
            //         $select.append($option);
            //         // }
            //     });
            //     return $select.prop("outerHTML");
            //   }
        },
        {   title: 'Fecha inicio', data: 'fecha_inicio', name: 'fecha_inicio', className: 'datepicker', type: 'date',  format:    'DD-MM-YYYY' },
        {   title: 'Fecha finalización',  data: 'fecha_fin',  name: 'fecha_fin', className: 'datepicker', type: 'date',  format:    'DD-MM-YYYY' },
        {   title: 'id Director', data: 'id_director', name: 'id_director', type: "select", className: 'ddl', visible: true },
        // {   title: 'Correo del Director', data: 'correo_director', name: 'correo_director', type: "select", className: 'ddl', visible: true },
    ];

    // configuración de carga inicial
    // $('#campoDescripcion').hide();
    $('#botonesGuardarCambios').hide();
    $('#botonesGuardarCambios').attr("disabled", "disabled");

    var payloadProyecto = {
        datos : idUsuario,
        action : 'cargar_'+nombreTabla,
        html_tabla : nombreTabla
    }

    cargarTablaGenerica(nombreTabla, colProy, modoTabla, dataUrl, payloadProyecto, ddl_estado_ops, true);

    // const claseBotonEditarRow = 'bi-pencil-square';
    // const claseBotonEliminarRow = 'bi-trash-fill';
    // const claseBotonConfirmarRow = 'bi-check-circle';
    // const claseBotonCancelarRow = 'bi-x-circle';

    // $('#addRow'+nombreTabla).click(function() {
    //     $('#fsHabilidades').prop("disabled", true);
    //     $('#fsTrabajadores').prop("disabled", true);
    // });

    // $('#btn-cancel-'+nombreTabla).click(function() {
    //     $('#fsHabilidades').prop("disabled", false);
    //     $('#fsTrabajadores').prop("disabled", false);
    // });
}

function cargarHabilidades(nombreTabla, IdProySeleccionado, modoTabla='CRUD'){
    var colsHabilidades = [
        { data:null, render:function(){return "<input type='checkbox'/>";}, visible: true },
        { title: 'id', data: 'id', visible: false },
        { title: 'id_proyecto', data: 'id_proyecto'},
        { title: 'id_habilidad', data: 'id_habilidad'}
    ];
    // console.log("hab", arreglo);
    
    var payloadHabilidades = {
        datos : JSON.stringify( IdProySeleccionado ),
        action : 'cargar_'+nombreTabla,
        html_tabla : nombreTabla
    }
    if($('#'+nombreTabla).lenght) $('#'+nombreTabla).DataTable().clear().draw();
    cargarTablaGenerica(nombreTabla, colsHabilidades, modoTabla, dataUrl, payloadHabilidades);
}

function cargarTrabajadores(nombreTabla, IdProySeleccionado, modoTabla='CRUD'){
    var colsTrabajadores = [
        { data:null, render:function(){return "<input type='checkbox'/>";}, visible: true },
        { title: 'id', data: 'id', visible: false },
        { title: 'id_usuario', data: 'id_usuario' },
        { title: 'fecha_solicitud', data: 'fecha_solicitud' },
        { title: 'estado', data: 'estado' },
    ];

    // console.log("Trab:", arreglo);
    var payloadTrabajadores = {
        datos : JSON.stringify( IdProySeleccionado ),
        action : 'cargar_'+nombreTabla,
        html_tabla : nombreTabla
    }

    if($('#'+nombreTabla).lenght) $('#'+nombreTabla).DataTable().clear().draw();
    cargarTablaGenerica(nombreTabla, colsTrabajadores, modoTabla, dataUrl, payloadTrabajadores);
}

export { cargarProyectos, cargarHabilidades, cargarTrabajadores }

