import { cargarTablaGenerica } from "../../../librerias/tablaGenerica.js";


function cargarEmpresas(nombreTabla, arreglo,modeloTabla) {

    var dataUrl = 'principal.php?CONTENIDO=presentacion/configuracion/empresa/empresaCRUD.php&accion=Modificar&idEstudio=';//no existe el crud de empresas
    var ddl_estado_ops = [
    { value : 'X', key : '' },
    { value : 'P', key : 'Pendiente' },
    { value : 'E', key : 'Ejecución' },
    { value : 'T', key : 'Terminado' }
    ];

    var colsEmpresas = [
        {   data:null, render:function(){return "<input type='checkbox'/>";}, visible: true },
        {   title: 'id', data: 'id', visible: false },
        {   title: 'Nit', data: 'nit' },
        {   title: 'Nombre', data: 'nombre', visible: false },
        {   title: 'Direccion', data: 'direccion', visible: true},
        {   title: 'Correo', data: 'correo', visible: true},
        {   title: 'Telefono', data: 'telefono', visible: true},
        {   title: 'Nombre Representante', data: 'nombre_representante', visible: true},
        {   title: 'Correo Representante', data: 'correo_representante', visible: true},
    ];

    // configuración de carga inicial
    // $('#campoDescripcion').hide();
    $('#botonesGuardarCambios').hide();
    $('#botonesGuardarCambios').attr("disabled", "disabled");

    var modoTabla = 'CRUD'; // definir que acciones está disponibles para la tabla
    cargarTablaGenerica(nombreTabla, arreglo, colsEmpresas, modoTabla, ddl_estado_ops);
    // getProyectoSeleccionado(nombreTabla);
}

function getEmpresaSeleccionada(nombreTabla){
    var IdEmpreSeleccionada = '';
    var selectorTabla = '#'+nombreTabla;
    $(selectorTabla+' tbody').on('click', 'tr', function () {
        if($(this).hasClass('selected')) {
            // var celda = dataTable.cell(this);
            var rowindex = $(this).closest("tr").index();
            // console.log(selectorTabla, rowindex);
            var data = $(selectorTabla).DataTable().row( rowindex ).data();
            IdEmpreSeleccionada = data.id;
            console.log(IdEmpreSeleccionada);
            // cargarTrabajadores(IdProySeleccionado);
        }
    });  
}

function cargarUsuarios(nombreTabla, arreglo,modeloTabla) {
    var dataUrl =
      "principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoCRUD.php&accion=Modificar&idEstudio=";
    var ddl_estado_ops = [
      { value: "X", key: "" },
      { value: "P", key: "Pendiente" },
      { value: "E", key: "Ejecución" },
      { value: "T", key: "Terminado" },
    ];
  
    var colsTrabajadores = [
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
      { title: "Clave", data: "clave_hash", visible: false },
      { title: "Dirección", data: "direccion", visible: true },
      { title: "Foto", data: "nombre_foto", visible: true },
      { title: "Telefono", data: "telefono", visible: true },
      { title: "Tipo de Usuario", data: "tipo_usuario", visible: true },
      { title: "nitEmpresa", data: "id_empresa", visible: false },
    ];

    console.log("Trab:", arreglo);
    cargarTablaGenerica(nombreTabla, arreglo, colsTrabajadores,modeloTabla);

    // if (idProyecto == null || idProyecto == '')
    // {
    //     $datPerfRequeridos = '[]';
    //     $datPerfDisponibles = '[]';
    //     $datTrabAsignados = '[]';
    //     $datTrabDisponibles = '[]';
    // }
    // // else
    // // {
    // //     // <?php echo 'const result = ' . Proyecto::getTrabajadoresAsignados($idProySeleccionado) . d';'; ?>
    // //     // <?php echo ' . cargarTrabajadores( .' $idProySeleccionado .'); ' ?>;

        
    // //     // <?php echo 'const datTrabAsignados = ' . cargarTrabajadores($idProySeleccionado) . ';'; ?>
    // //     // console.log(datTrabAsignados);
    // //     //Definiendo la lógica de negocio dentro de la clase
    // //     // $datTrabAsignados = 
    // //     // $datTrabDisponibles = Proyecto::getTrabajadoresDisponibles($idProySeleccionado);
    // // }
}

export { cargarEmpresas, cargarUsuarios }

// const url = "principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoCRUD.php&accion=Modificar&idEstudio=";
// const updateField = (_id, data, callback) => {
//     $.ajax({
//         url: `${url}${_id}`,
//         data,
//         type: 'POST',
//         success: (data) => {
//             callback(null);
//         },
//         error: (err) => {
//             callback(err);
//         }
//     });
// };
    
/*

$(document).ready(function () {
    tabla = $('#example').DataTable({
        // select:{style:'single'},
        searching: true,
        ordering: true,
        data: arreglo,
        createdRow:function(row){
            $(".datecell", row).datepicker();
        },
        // fields: [
        //     {   label: "id:",   name: "id" },
        //     {   label: "Nombre:",   name: "nombre" },
        //     {   label: "Descripcion:",  name: "descripcion" },
        //     {   label: "Estado:",  name: "estado" },
        //     {
        //         label: "Fecha inicio:",
        //         name: "fecha_inicio",
        //         type:  'datetime',
        //         def:   function () { return new Date(); },
        //         format: 'D-M-Y',
        //     },
        //     {
        //         label: 'Fecha finalización:',
        //         name: 'fecha_fin',
        //         type:  'datetime',
        //         def:   function () { return new Date(); },
        //         format: 'D-M-Y',
        //     },
        // ],
        columns: [
            // {
            //     data: null,
            //     defaultContent: '',
            //     className: 'select-checkbox',
            //     orderable: false
            // },
            { data:null, render:function(){return "<input type='checkbox'/>";}, visible: true },
            { title: 'id', data: 'id', visible: false },
            { title: 'Nombre', data: 'nombre' },
            { title: 'Descripcion', data: 'descripcion' },
            { title: 'Estado', data: 'estado' },
            { 
                title: 'Fecha inicio' , 
                data: 'fecha_inicio', 
                type: 'datetime',
                className: 'datecell',
                // defaultContent: '<input type="text" class="datepicker-input" placeholder="Fecha de Inicio">',
                // render: function (date){
                //     return "<input type='text' class='datecell' value='" + date + "'/>"
                // },
            },
            { title: 'Fecha finalización', data: 'fecha_fin', type: 'datetime', className: 'ui-datepicker-inline' },
            // {
            //     data: null,
            //     defaultContent: '<button class="bi bi-pencil-square" data-action="edit" onclick="editar(this)"></button>', //'<span class="bi bi-pencil-square tool" onclick="editar()">',
            //     className: 'row-edit dt-center',
            //     orderable: false
            // },
            // {
            //     data: null,
            //     defaultContent: '<button class="bi bi-trash-fill" data-action="delete" onclick="eliminar(this.id)"></button>',
            //     className: 'row-remove dt-center',
            //     orderable: false
            // },
        ],
        // buttons: [ {
        //     extend: "createInline",
        //     editor: this,
        //     formOptions: {
        //         submitTrigger: -2,
        //         submitHtml: '<span class="bi bi-trash-fill"></span>'
        //     }
        // } ],
        columnDefs: [ 
            { 
                target: 0,
                visible: false,
                searcheable: false,
            },
            // {
            //     className: 'ui-datepicker-inline', "targets":[4,5]
            // }
        ],
    //     columnDefs: [
    //             {
    //                 targets: -1,
    //                 data: null,
    //                 defaultContent: '<span class="bi bi-pencil-square"></span><span class="bi bi-trash-fill"></span>',
    //             },
    //         ],
    });
});


// $("#example").on('click', 'tbody tr', function() {
//     var table = $('#example').DataTable({
//         //turn ordering off, just to demonstrate the rows actually are inserted the right place
//         ordering: false
//     })

//     var currentPage = table.page();
    
//     //insert a test row
//     // http://jsfiddle.net/55rfa8zb/1/
//     //https://datatables.net/forums/discussion/28186/how-to-add-a-row-in-an-editable-table-and-keep-all-the-html-attributes
//     //https://codepen.io/quanghuy1294/pen/OgNELB
//     table.row.add({
//         "id" : "sasdas",
//         "nombre" : "sasdas",
//         "descripcion" : "work_code",
//         "estado" : "P",
//         "fecha_inicio" : "1/1/2022",
//         "fecha_fin" : "1/10/2022",
//     }).draw();
    
//     //move added row to desired index (here the row we clicked on)
//     var index = table.row(this).index(),
//         rowCount = table.data().length-1,
//         insertedRow = table.row(rowCount).data(),
//         tempRow;
//         console.log(index);

//     for (var i=rowCount;i>index;i--) {
//         tempRow = table.row(i-1).data();
//         table.row(i).data(tempRow);
//         table.row(i-1).data(insertedRow);
//     }     
//     //refresh the page
//     table.page(currentPage).draw(false);
// });  



// https://github.com/FilipeMazzon/Datatable-inline-Edit-Free
$(document).ready(function () {
    var table = $('#example').DataTable();
    $("#example tbody").on('click', 'td', function () {
        var celda = table.cell(this);
        var celda_data = celda.context[0].aoData[0];//._aData[0];
        console.log(this, celda, celda_data);
        var columna = celda["0"][0].column;
        var campoId = celda.context[0].aoData[0].anCells[1].textContent;
        var campo = celda.context[0].aoColumns[columna].sTitle;
        var className = celda.context[0].aoColumns[columna].className
        var data = celda.data();
        if (data.search('<input') === -1) {
            if(className==="datecell"){
                celda.data('<input type="text" class="datecell" id=' + campoId + ' value="' + data + '"/>');
                $(".datecell", celda).datepicker();    
            }else{
                celda.data('<input type="text" id=' + campoId + ' value="' + data + '"/>');
            }
            var input = document.getElementById(campoId);
            input.addEventListener("keyup", function (event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    var newData = {};
                    newData[campo] = input.value;
                    updateCampo(row_data, newData, (err) => {
                        if (err) alert(err);
                        else {
                            celda.data(input.value);
                        }
                    });
                }
            })
        }
    })
});


// // // agregar selección de fila https://datatables.net/examples/api/select_single_row.html
// // $('#example tbody').on('click', 'tr', function () {
// //     if ($(this).hasClass('selected')) {
// //         $(this).removeClass('selected');
// //     } else {
// //         tabla.$('tr.selected').removeClass('selected');
// //         $(this).addClass('selected');
// //     }
// // });

// // $('.datepicker-input').datepicker({
// //     dateFormat: 'mm-dd-yy',
// //     onClose: function(dateText, inst) {
// //         $(this).parent().find('.date').focus().html(dateText).blur();
// //     }
// // });

// // // Shows the datepicker when clicking on the content editable div
// // $('.date').click(function() {
// //     $(this).parent().find('.datepicker-input').datepicker("show");
// // });

*/



// https://datatables.net/forums/discussion/1723/editable-with-datepicker-inside-datatables
// // select everything when editing field in focus



// // https://stackoverflow.com/questions/14643617/create-table-using-javascript
// // // https://linuxhint.com/create-table-from-array-objects-javascript/