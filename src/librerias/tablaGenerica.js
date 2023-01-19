// http://jsfiddle.net/awbq0p4e/
const claseBotonEditarRow = 'bi-pencil-square';
const claseBotonEliminarRow = 'bi-trash-fill';
const claseBotonConfirmarRow = 'bi-check-circle';
const claseBotonCancelarRow = 'bi-x-circle';

// const ultimaColumna = "<td><div><button id='edit_row' class='bi "+claseBotonEditarRow +"' aria-hidden='true'></button><button id='delete_row' class='bi "+claseBotonEliminarRow +"' aria-hidden='true'></button><div></td>";  
const botonEditar   = "<i class='bi "+claseBotonEditarRow +"' aria-hidden='true'></i>";  
const botonEliminar = "<i class='bi "+claseBotonEliminarRow +"' aria-hidden='true'></i>";  

var existenCambiosPendientes = false;
var insertandoNuevoRegistro = false;
var dataTable = null;
var ddl_ops = null;
var dataUrl = null;
var base_url = 'http://localhost/SENA-devmanager/src/api/';

var urlControlador = '';

function cargarTablaGenerica(nombreTabla, cols, modoTabla='CRUD', urlControlador='', payloadInicial = {}, ddl_ops = [], campo_desc = false, arreglo={}, idPadre = '')
{
    urlControlador = urlControlador;
    var filaEnEdicion = '';
    var selectorTabla = '#'+nombreTabla
    cols.push({    
                data: null,
                render:function(){
                    var modoelegido = modoTabla.toUpperCase();
                    var ultimaColumna = '<div id="botones"><td>';
                    switch(modoelegido){
                        case 'CRUD':
                        case 'RUD':
                        case 'UD':
                            ultimaColumna += botonEditar+botonEliminar; //+"</td>";
                            break;
                        case 'RU':
                        case 'U':
                            ultimaColumna += botonEditar; //+"</td>";
                            break;
                        case 'RD':
                        case 'D':
                            ultimaColumna+= botonEliminar; //+"</td>";
                            break;
                        default:
                            break;
                    }
                    return ultimaColumna+'</td></div>';
                },
                // className: 'row-edit dt-center',
                orderable: false
            });

    dataTable = null;
    var primerRegistro = $.fn.dataTable.absoluteOrder( '1' );

    //// definir id para cada fila: 
    //// https://datatables.net/reference/option/rowId
    //// https://editor.datatables.net/examples/advanced/jsonId.html
    dataTable = $(selectorTabla).DataTable({
        ajax: {
            url: urlControlador,
            method:"POST",
            data: payloadInicial,
            dataType:"json",
            // dataSrc: 'datos',
            dataSrc: function ( json ) {
                //Make your callback here.
                if(json.accion == "Acción no definida") alert(json.accion);
                // console.log(json);

                if( json.ddl_ops!=null && json.ddl_ops[0].length != 0){
                    var opciones = JSON.parse(json.ddl_ops[0])[nombreTabla];
                    var obj = {};
                    for(const op of opciones){
                        var key = Object.keys(op)[0];
                        obj[key] = op[key];
                        // console.log(key, op);
                    }
                    ddl_ops = obj;
                }
                // ddl_ops = JSON.parse(json.ddl_ops[0])[nombreTabla];
                return json.data;
            }, 
            // success:function(response){
            //     // alert("Status: "+response);
            //     console.log(response);
            //     existenCambiosPendientes = false;
            //     insertandoNuevoRegistro = false;
            //     return response.data;
            // }, 
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                // alert("Status: " + textStatus); 
                // alert("Error: " + errorThrown); 
                var titulo = "<b>Error al cargar : </b>"+ nombreTabla.substring(3,nombreTabla.length-1);
                var mensaje = "<b>Error en la invocación de : </b> "+payloadInicial['action'] + "<br><b>Detalle del Error : </b>"+ errorThrown['message'];
                if(errorThrown['message'] === undefined){
                    return;
                }
                mostrarAdvertencia(titulo, mensaje);
            }
        },
        // data: arreglo,
        columns: cols,
        rowReorder: {
            dataSrc: 'order',
            selector: 'tr'
        },
        lengthMenu: [
            [ 5, 10, 20, -1 ],
            ['5', '10', '20', 'Todos los' ]
        ],
        // scrollY: 400,
        // scrollX: true,
        destroy: true,
        processing: true,
        select:{ style:'single' }, //toggleable: true },
        // fnInitComplete: function(oSettings, json) {
        //         // Seleccionar primera fila automáticamente;
        //         $(selectorTabla+' tbody tr:eq(0)').click();
        //         alert( 'DataTables has finished its initialisation.' );
        // },

        //// http://live.datatables.net/bobeluza/16/edit
        // rowId: function (row) {
        //     return row[1] + '-' + row[2];
        // },
        createdRow:function(row, data, dataIndex){
            $(".datepicker", row).datepicker();
            $(row).attr('id', data.id);
        },
        "columnDefs": [ 
            { targets: 1, type: primerRegistro },
            {
                targets: '_all',
                "createdCell": function (td, cellData, rowData, row, col) {
                    var t = nombreTabla;
                    if(col != 0 && col != cols.length ){
                        var campo = cols[col].name;

                        if(idPadre != ''){
                            $(selectorTabla).attr('id_padre', idPadre);
                        }
                        // asociar el id del padre
                        if(campo == 'id'){
                            for(const key of Object.keys(rowData)){
                                if(payloadInicial.hasOwnProperty(key)) {
                                    $(selectorTabla).attr('id_padre', payloadInicial[key]);
                                }
                            }
                        }

                        $(td).attr('id', campo);

                        if(cols[col].className == 'ddl' && ddl_ops.length != 0){
                            for(const op of ddl_ops[campo]){
                                var key = Object.keys(op)[0];
                                var value = Object.keys(op)[1];
                                if(op[key] === rowData[campo]) { 
                                    $(td).empty().append(op[value]); }
                                // objec[key] = op[key];
                                // console.log(key, op, commit);
                            }
                        }
                        // TODO: revisar esto del hipervinculo para abrir
                        if(cols[col].className == 'up_doc'){
                            // var hiperlink = '<a href='+rowData[campo]+' target="_blank">'+cellData.split('/').pop()+'</a>'; 
                            var valor = cellData != "" ? 'Archivo.pdf':'';
                            var hiperlink = '<a href='+rowData[campo]+' target="_blank">'+ valor +'</a>'; 
                            $(td).empty().append(hiperlink);
                        }
                        if (cols[col].className == 'up_img') {
                            $(td).empty().append('<img src="'+ cellData +'" style="{border-radius: 70px;}"/> ');
                        }
                    }
                }
            }
        ],
        // "columnDefs": [ {
        //         targets: '_all',
        //         render: function ( data, type, row, name ) {
        //             return '<a id="'+data+'">'+name+'</a>';
        //     }
        // }],
        dom: '<"row"<"col-sm-2"><"col-sm-5 text-center"f><"col-sm-5">>t<"row"<"col-sm-3"l><"col-sm-5 text-center"p><"col-sm-4"i>>',
        "language":	{
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },

    });

    var idDivBotonesGuardar = "guardarCambios"+nombreTabla;
    var idBotonGuardar = "btn-save-"+nombreTabla;
    var idBotonRevertir = "btn-cancel-"+nombreTabla;
    var selectorBotonesGuardar = '#'+idDivBotonesGuardar;
    
    var ctrlBotonesGuardar = `<br> 
    <div class="row">
        <div class="col align-self-start"></div>
        <div class="col align-self-center"></div>
        <div id=${idDivBotonesGuardar} class="col align-self-end" disabled="disabled">
            <button type="button" id=${idBotonRevertir} class="btn btn-secondary" data-dismiss="modal">Revertir Cambios</button>
            <button type="button" id=${idBotonGuardar} class="btn btn-primary" form="myform" data-dismiss="modal">Guardar Cambios</button>
        </div>
    </div>`;
    $(selectorTabla).after(ctrlBotonesGuardar);
    // $(selectorBotonesGuardar).children().attr("disabled","disabled");
    $( selectorBotonesGuardar ).hide();

    if(campo_desc){
        var idCtrlDescripcion = 'desc'+nombreTabla;
        var selectorCtrlDescripcion = '#desc'+nombreTabla;
        var ctrlDescripcion = '<br><div class="w-auto p-3 align-self-center"><textarea id='+idCtrlDescripcion+' type="text" class="w-auto p-3 form-control" style="min-width: 100%" rows="5" disabled="disabled"></textarea></div>';
        $(selectorTabla).after(ctrlDescripcion);
        $( selectorCtrlDescripcion ).hide();
    }

    $(selectorTabla+' tbody').on('click', 'tr', function () {
        if(!existenCambiosPendientes){
            if ($(this).hasClass('selected')) {
                if ( $( selectorCtrlDescripcion ).length ) {
                    if ( existenCambiosPendientes) {
                        $( selectorCtrlDescripcion ).show();
                    }
                    else{
                        $( selectorCtrlDescripcion ).hide();
                    }
                }
            }
            else {
                // $(this).addClass('selected');
                // $(this).removeClass('selected');

                if ( $( selectorCtrlDescripcion ).length ) {
                    if ( existenCambiosPendientes) {
                        $( selectorCtrlDescripcion ).removeAttr("disabled");
                    }
                    $( selectorCtrlDescripcion ).show();

                    var tr = $(this).closest("tr");
                    var rowindex = tr.index();
                    var data = $(selectorTabla).DataTable().row( rowindex ).data();
                    $( selectorCtrlDescripcion ).val(data.descripcion);
                }
            }
        }
    });

    //     // // eventos de selección de fila
    // $(selectorTabla+' tbody').on('click', 'tr', function () {
    //     if ($(this).hasClass('selected')) {
    //         $(this).removeClass('selected');
    //     }
    //     else {
    //         $(this).addClass('selected');
    //     }
    // });

    // // // eventos de selección de fila
    // $(selectorTabla+' tbody').on('click', 'tr', function () {
        
    //     if ($(this).hasClass('selected')) {
    //         $(this).removeClass('selected');
    //         if ( $( selectorCtrlDescripcion ).length ) {
    //             if ( existenCambiosPendientes) {
    //                 $( selectorCtrlDescripcion ).removeAttr("disabled");
    //             }
    //             $(this).addClass('selected');
    //             $( selectorCtrlDescripcion ).show();
    //         }
    //         else{
    //             $( selectorCtrlDescripcion ).hide();
    //         }
    //    } 
    //     else {
    //         // dataTable.$('tr.selected').removeClass('selected');
    //         $(this).addClass('selected');
    //         // var celda = dataTable.cell(this);
    //         // var celda_data = celda.context[0].aoData[0];//._aData[0];
    //         // var columna = celda["0"][0].column; // columna descripcion
    //         var tr = $(this).closest("tr");
    //         var rowindex = tr.index();
    //         var data = $(selectorTabla).DataTable().row( rowindex ).data();

    //         if ( $( selectorCtrlDescripcion ).length ){
    //             if ( existenCambiosPendientes) {
    //                 $( selectorCtrlDescripcion ).removeAttr("disabled");
    //             }
    //             $( selectorCtrlDescripcion ).val(data.descripcion);
    //             $( selectorCtrlDescripcion ).show();
    //         }
    //     }
    // });

    
    // editar
    $(selectorTabla).on('mousedown.edit', 'i.bi.'+`${claseBotonEditarRow}`, function(e) {
        enableRowEdit($(this));
        existenCambiosPendientes = true;

        filaEnEdicion = $(this).closest('tr').index();

        $(selectorTabla+ ' tbody tr #botones').each(function(rowindex, divBotones) {
            if (rowindex != filaEnEdicion){
                $(divBotones).attr('style', 'display:none');
            }
        });

        if ( $( selectorCtrlDescripcion ).length ) {
            if ( existenCambiosPendientes) {
                $( selectorCtrlDescripcion ).removeAttr("disabled");
            }
            $( selectorCtrlDescripcion ).show();
            var data = $(selectorTabla).DataTable().row( filaEnEdicion ).data();
            $( selectorCtrlDescripcion ).val(data.descripcion);
        }

        activar_upload(urlControlador);
        $(this).closest('tr').click();
        $(selectorTabla).DataTable().row($(this)).select().draw();
        // var nonSelected = $(selectorTabla).DataTable().rows( { selected: false } ).nodes().each(function(row){
        //     var but = $(selectorTabla).DataTable().row(row).node().attr('disabled', 'disabled'); //find("td:last-child i.bi."+claseBotonEliminarRow);
        //     but.hide();//.attr('disabled', 'disabled');
        // }); 
        
        // e.stopPropagation();
        // e.preventDefault();
    });

    // TODO: metodo para bloquear botones cuando edición esté activa
    // $(selectorTabla+' tbody').on('click', 'tr', function () {

    //     // https://datatables.net/forums/discussion/25833/is-there-any-way-to-programmatically-select-rows
    //     var tabla = $(selectorTabla).DataTable();
    //     tabla.rows().indexes().each(
    //         function( idx ){
    //             var $node = tabla.row( idx ).node();
    //             if ( tabla.row( idx ).node().hasClass('selected')){
    //                 oTT.fnSelect( $('#'+table+' tbody tr')[idx] );
    //             }
    //             else
    //             {
    //                 tabla.row( idx ).node().addClass('ignoreme');
    //             }
    //             // if ( tabla.row( idx ).data().dept === dept ){
    //             //     oTT.fnSelect( $('#'+table+' tbody tr')[idx] );
    //             // }
    //         }
    //     );

    //     // https://www.grepper.com/tpc/datatables+get+all+rows
    //     $(selectorTabla).DataTable().rows().map((rowId) => {
    //         // each row is an array where each column is an element in the array
    //         // as a string
    //         var row = $(selectorTabla).DataTable().rows(rowId);
    //         if(!row.hasClass('selected')){
    //             console.log(index);
    //         }
    //         else
    //         {
    //             $(selectorTabla).DataTable().row(rowId).addClass('ignoreme');
    //         }
    //         var d = this.data();
    //     });
    // });

    function mostrarAdvertencia(titulo, mensaje) {
        var html = `
        <div id="myModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-">
                        <h3 class="modal-title">${titulo}</h#>
                    </div>
                    <div class="modal-body">
                        <p>${mensaje}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        `
        $(document.body).append(html);
        $("#myModal").modal("show");
    }
    
    // boton confirmar
    $(selectorTabla).on('mousedown.save', "i.bi."+claseBotonConfirmarRow, function(e) {
        updateRow($(this), true); // Pass save button to function.
    });
    
    $(selectorTabla).on('mousedown', 'td .bi.'+`${claseBotonEliminarRow}`, function(e) {
        
        var rowdata = $(selectorTabla).DataTable().row($(this).closest("tr")).data();

        var accionCRUD = 'Eliminar';

        var dataReq = {
            datos : rowdata.id, 
            action : accionCRUD+'_'+nombreTabla,
        };
        $.ajax({
            url: urlControlador,
            method:"POST",
            data: dataReq,
            dataType:"json",
            success:function(response){
                if(response['error']){
                    var mensaje = response['error'].split(']:').pop().split(' (')[0];
                    mostrarAdvertencia(response['accion'].replace('_tbl', ' ') , mensaje);
                    return;
                }
                // alert("Status: "+response);
                // console.log(rowdata);
                existenCambiosPendientes = false;
                insertandoNuevoRegistro = false;
                $(selectorTabla).DataTable().row($(this).closest("tr")).remove().draw();
                $(selectorTabla).DataTable().ajax.reload();
            }, 
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                mostrarAdvertencia('ajax Status', textStatus);
                mostrarAdvertencia('Error', errorThrown);
            }
        });
    });

    $(selectorTabla).on('mousedown', 'input', function(e) {
        e.stopPropagation();
    });
    $(selectorTabla).on('mousedown', '.select-basic', function(e) {
        e.stopPropagation();
    });


//     // $(selectorTabla+" tbody tr td #form-demo").on('submit',(function(e) {
//     function sendForm(form){
//         // e.preventDefault();

//         var form = $(form).closest('tr'); //.find('#fupForm');  
//         var file_data = form.find('td #form-demo input[name=pdf]')[0].value;
//         var form_data = form.find('td #form-demo');
//         // form_data.submit();
//         // form_data.append('pdf', file_data);
//         // form_data.append('action', 'cargarArchivo_'+nombreTabla);

//         $.ajax({
//             url: form_data.attr('action'),
//             method: "POST",
//             dataType: 'json',
//             data: new FormData(form_data[0]),
//             processData: false,
//             contentType: false,
//             success: function(result){
//                 console.log(result);
//             },
//             error: function(er){}
//         });
       
//         // $.ajax({
//         //     url: urlControlador,
//         //     method: 'POST',
//         //     type: 'POST', // For jQuery < 1.9
//         //     data:  form_data,
//         //     cache: false,
//         //     contentType: false,
//         //     processData: false,
//         //     beforeSend : function()
//         //     {
//         //     //$("#preview").fadeOut();
//         //     $("#err").fadeOut();
//         //     },
//         //     success: function(data)
//         //     {
//         //     if(data=='invalid')
//         //     {
//         //     // invalid file format.
//         //     $("#err").html("Invalid File !").fadeIn();
//         //     }
//         //     else
//         //     {
//         //     // view uploaded file.
//         //     $("#preview").html(data).fadeIn();
//         //     $("#form")[0].reset(); 
//         //     }
//         //     },
//         //     error: function(e) 
//         //     {
//         //     $("#err").html(e).fadeIn();
//         //     }          
//         //     });
//             return false; 
//     }
// // });

    $(selectorTabla).on('submit', '#', function(event) {

        event.preventDefault();

        // retrieve form element
        var form = this.closest('form');
        // prepare data
        var dReq = {
            'pdf': form[0].files,
            'action': 'cargarArchivo_tblEstudios'
        }
        // get url
        var url = form.action;
        url = urlControlador;
        
        // send request
        $.ajax({
            type: 'POST',
            method: 'POST',
            url: url,
            data: dReq,
            cache: false,
            contentType: false,
            processData: false,
            success:function(response){
                if(response['error']){
                    var mensaje = response['error'].split(']:').pop().split(' (')[0];
                    mostrarAdvertencia(response['accion'].replace('_tbl', ' ') , mensaje);
                    return;
                }
                // console.log(rowdata);
            }, 
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                mostrarAdvertencia('ajax Status', textStatus);
                mostrarAdvertencia('Error', errorThrown);
            }

        });
    });

    // // File input field trigger when the HTML element is clicked
    // $(selectorTabla+ "tbody tr td #dropBox").click(function(){
    //     $(selectorTabla+ "tbody tr td form input[type=file]").click();
    // });

    // // Prevent browsers from opening the file when its dragged and dropped
    // $(document).on('drop dragover', function (e) {
    //     e.preventDefault();
    // });

    // // Call a function to handle file upload on select file
    // $(selectorTabla+ 'tbody tr td form').on('change', '.submitBtn', fileUpload);

    // Guardar Cambios
    $('#btn-save-'+nombreTabla).on('click', async function() {
        updateRows(true); // Update all edited rows
        
        var fila = $(selectorTabla+' tbody tr:eq('+filaEnEdicion+')');
        var cells = fila.find("td").not(':first').not(':last');
        var rowdata = $(selectorTabla).DataTable().row(filaEnEdicion).data();

        var clase;
        var accionCRUD = '';
        var img, doc, ruta_doc, ruta_imagen;

        if(insertandoNuevoRegistro){

            cells.each(function(i, elemento) {

                clase = elemento.className.toUpperCase().trim();
                rowdata[elemento.id] = elemento.value;

                if(clase.indexOf('UP_DOC') == 0) {
                    // es una carga de documento
                    doc = fila.find('td #uploadDoc')[0];
                    ruta_doc = fila.find('td #uploadDoc').attr('value');
                    rowdata[elemento.id] = base_url + ruta_doc;
                }
                else {
                    if(clase.indexOf('UP_IMG') == 0) {
                        img = fila.find('td #myUploadedImg')[0];
                        ruta_imagen = fila.find('td #myUploadedImg').attr('src');
                        rowdata[elemento.id] = base_url+ruta_imagen;
                    } 
                }
            });
            // encontrando id de referencia a la tabla Padre 
            // TODO: verificar
            for(const key of Object.keys(rowdata)){
                if(payloadInicial.hasOwnProperty(key)) {
                    rowdata[key] = payloadInicial[key];
                }
            }
            accionCRUD = 'Insertar';
        }
        else {   

            cells.each(function(i, elemento) {
                                
                clase = elemento.className.toUpperCase().trim();
                rowdata[elemento.id] = elemento.value;

                if(clase.indexOf('UP_DOC') == 0) {
                    // es una carga de documento
                    doc = fila.find('td #uploadDoc')[0];
                    ruta_doc = fila.find('td #uploadDoc').attr('value');
                    rowdata[elemento.id] = base_url + ruta_doc;
                }
                else {
                    if(clase.indexOf('UP_IMG') == 0) {
                        img = fila.find('td #myUploadedImg')[0];
                        ruta_imagen = fila.find('td #myUploadedImg').attr('src');
                        rowdata[elemento.id] = base_url+ruta_imagen;
                    } 
                }
                
                // if(elemento.className.toUpperCase().indexOf('FILE')<0){
                //     rowdata[elemento.id] = elemento.value;
                // }
                // else
                // {
                //     var ruta_imagen = fila.find('td #myUploadedImg').attr('src');
                //     rowdata[elemento.id] = base_url+ruta_imagen;
                // }
            });

            accionCRUD = 'Modificar';
        }
        // Si existe campo descripción, hay que llenar los datos con la nueva información
        if ( $( selectorCtrlDescripcion ).length ) rowdata.descripcion = $(selectorCtrlDescripcion).val();

        //// peticion - https://coderszine.com/live-datatables-crud-with-ajax-php-mysql/
        //// https://pastebin.com/raw/tuwVTa4D
        //// https://www.geeksforgeeks.org/how-to-pass-multiple-json-objects-as-data-using-jquerys-ajax/
        //// https://gabrieleromanato.name/jquery-sending-json-data-to-php-with-ajax
        var dataReq = {
            datos : JSON.stringify( rowdata ), 
            // files: archivos,
            action : accionCRUD+'_'+nombreTabla,
        };
        $.ajax({
            url: urlControlador,
            method:"POST",
            data: dataReq,
            dataType:"json",
            success:function(response){
                if(response['error']){
                    var mensaje = response['error'].split(']:').pop().split(' (')[0];
                    mostrarAdvertencia(response['accion'].replace('_tbl', ' ') , mensaje);
                    // esta logica activa campo descripción dentro de la tabla
                    if ( $( selectorCtrlDescripcion ).length ) {
                        $( selectorCtrlDescripcion ).removeAttr("disabled");
                        $( selectorCtrlDescripcion ).val('agregue una descripción');
                        $( selectorCtrlDescripcion ).show();
                    }
                    if ( $( selectorBotonesGuardar).length ) {
                        $( selectorBotonesGuardar ).show();
                    }
                    return;
                }
                // console.log(rowdata);
                filaEnEdicion = ''
                $(selectorTabla).DataTable().ajax.reload();
                existenCambiosPendientes = false;
                insertandoNuevoRegistro = false;
            }, 
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                mostrarAdvertencia('ajax Status', textStatus);
                mostrarAdvertencia('Error', errorThrown);
            }
        });
    });

    // Cancelar Cambios
    $('#btn-cancel-'+nombreTabla).on('click', function() {
        updateRows(false); // Revert all edited rows
        existenCambiosPendientes = false;

        if ( insertandoNuevoRegistro ){
            insertandoNuevoRegistro = false;
            $(selectorTabla).DataTable().row(0).remove().draw();
        }
        filaEnEdicion = '';
        $(selectorTabla).DataTable().ajax.reload();
    });
    
    // Botón nuevo proyecto
    $(selectorTabla).css('border-top', 'none')
        .before($('<div>').addClass('addRow')
        .append($('<button class="btn btn-primary" type="button">')
        .attr('id', 'addRow'+nombreTabla)
        .text('Nuevo '+nombreTabla.substring(3,nombreTabla.length-1))));

    // Add row
    $('#addRow'+nombreTabla).click(function() {
        existenCambiosPendientes = true;
        insertandoNuevoRegistro = true;

        var PlantillaNuevoRegistro = nombreTabla.substring(3,nombreTabla.length-1);

        var $NewRow = $("#new-"+PlantillaNuevoRegistro).find('tr').clone();
        $(selectorTabla).DataTable().row.add($NewRow).draw();

        //move added row to desired index (here the row we clicked on)
        var currentPage = $(selectorTabla).DataTable().page();
        var rowElement = $(selectorTabla).find("tbody tr:last-child");
        var index = $(selectorTabla).DataTable().row(rowElement).index(),
            rowCount = $(selectorTabla).DataTable().data().length-1,
            insertedRow = $(selectorTabla).DataTable().row(rowCount).data(),
            tempRow;
        // console.log(index-1, rowCount, insertedRow );
        filaEnEdicion = rowCount;


        // for (var i=rowCount; i>0; i--) {
        //     tempRow = $(selectorTabla).DataTable().row(i-1).data();
        //     $(selectorTabla).DataTable().row(i).data(tempRow);
        //     $(selectorTabla).DataTable().row(i-1).data(insertedRow);
        // }
        
        //refresh the page
        $(selectorTabla).DataTable().row(rowCount).select();
        $(selectorTabla).DataTable().page(currentPage).draw(false);
        moveToPageWithSelectedItem();
        	
        // https://datatables.net/beta/1.8/examples/api/add_row.html
        // https://stackoverflow.com/questions/52792749/how-to-get-datatables-header-name
        // var headerNames = dataTable.columns().header().map(d => d.textContent).toArray()
        // var headerNames = dataTable.columns().header().map(d => "<td>dato "+d.textContent+"</td>").toArray()
        // //$(selectorTabla).dataTable().fnAddData(headerNames);
        // var t = dataTable.row.add(headerNames).draw(true);

        // Toggle edit mode upon creation.
        // enableRowEdit($(selectorTabla).find("tbody tr:first-child td i.bi."+claseBotonEditarRow));
        // enableRowEdit($(selectorTabla).find("tbody tr:first-child td i.bi"));
        var selector = `tbody tr:eq(${filaEnEdicion}) td i.bi`;
        // console.log(selector);
        enableRowEdit($(selectorTabla).find(selector));
        bloquearAccionesPantalla(nombreTabla, filaEnEdicion);
        activar_upload(urlControlador);
        // TODO: Posterior a esta acción, en la tabla proyectos se causa una excepción
    });

    function moveToPageWithSelectedItem() {
       
        var numberOfRows = $(selectorTabla).DataTable().data().length;
        var rowsOnOnePage = $(selectorTabla).DataTable().page.len();
        if(numberOfRows/rowsOnOnePage > 1){
            filaEnEdicion = (numberOfRows % rowsOnOnePage) - 1;
        }

        if (rowsOnOnePage < numberOfRows) {
            var selectedNode = $(selectorTabla).DataTable().row(".selected").node();
            var nodePosition = $(selectorTabla).DataTable().rows({order: 'current'}).nodes().indexOf(selectedNode);
            var pageNumber = Math.floor(nodePosition / rowsOnOnePage);
            $(selectorTabla).DataTable().page(pageNumber).draw(false); //move to page with the element
        }
    }

    // habilitar edición
    function enableRowEdit($editButton) {
        
        // esta logica activa campo descripción dentro de la tabla
        if ( $( selectorCtrlDescripcion ).length ) {
            $( selectorCtrlDescripcion ).removeAttr("disabled");
            $( selectorCtrlDescripcion ).val('agregue una descripción');
            $( selectorCtrlDescripcion ).show();
        }
        if ( $( selectorBotonesGuardar).length ) {
            $( selectorBotonesGuardar ).show();
        }

        $editButton.removeClass().addClass("bi "+claseBotonConfirmarRow);
        $editButton.attr("aria-hidden", "true");
        $editButton.hide();

        var $row = $editButton.closest("tr").off("mousedown");

        $row.find('td.ddl').each(function(i, el) {
            enableddlEdit($(this))
        });

        $row.find('td.up_img').each(function(i, el) {
            enableImgPicker($(this))
        });

        $row.find('td.up_doc').each(function(i, el) {
            enableDocUploadEdit($(this))
        });

        $row.find("td").not('.ddl').not('.up_img').not('.up_doc')//.not('.datepicker')
                       .not(':first').not(':last')
                       .each(function(i, el) {
            enableEditText($(this))
        });

        $row.find('td.datepicker').each(function(i, el) {
            enableDatePicker($(this))
        });
        

        var $cancelButton = $editButton.closest('tr').find("td:last-child i.bi."+claseBotonEliminarRow);
        $cancelButton.removeClass().addClass("bi "+claseBotonCancelarRow);
        $cancelButton.attr("aria-hidden", "true");
        $cancelButton.hide();
    }


    function enableEditText($cell) {
        var txt = $cell.text();
        $cell.empty().append($('<input>', {
            type : 'text',
            value : txt
        }).data('original-text', txt));
    }

    function enableddlEdit($cell) {
        var campo = $cell.context.id;
        var options = ddl_ops[campo];
        var valor = $(selectorTabla).DataTable().row($cell.closest('tr').index()).data()[campo];
        var commit = false;

        for(const op of options){
            var key = Object.keys(op)[0];
            var value = Object.keys(op)[1];
            if(op[key] === valor) {commit = true; valor = op[key]; break;}
            // objec[key] = op[key];
            // console.log(key, op, commit);
        }

        var select = $('<select>').prop('id', campo);
        $(options).each(function() {
            select.append($("<option>")
                    .prop('value', this.key)
                    .text(this.value));
        });
        select.val(commit ? valor : $cell.data('original-text'));
        $cell.empty().html(select);
    }
    
    function enableDatePicker($cell) {
        var txt = $cell.context.childNodes[0].value;
        $cell.empty().append($('<input>', {
            class: 'datepicker',
            type : 'date',
            value : txt
        }).data('original-text', txt));
    }
    
    function enableImgPicker($cell) {
        var campo = $cell.context.id;
        var valor = $(selectorTabla).DataTable().row($cell.closest('tr').index()).data()[campo];

        var html = `
                    <input type="file" id="uploadImage" class="up_img"/>
                    <img id="myUploadedImg" alt="Fotografía" style="width:180px;display: none;" />
                    `
        $cell.empty().append(html);
        $cell.find('#uploadImage').attr('value', valor);
    }

    var _URL = window.URL || window.webkitURL;
    $("td.up_img").change(function (e) {
        e.preventDefault();
        e.stopPropagation();
        var archivo, tipo_archivo;
        nombreTabla = e.target.parentElement.parentElement.parentElement.parentElement.id;
        filaEnEdicion = $(e.target.parentElement).closest('tr') .index();
        var input = $("#uploadImage")[0];

        if (input.id == 'uploadImage') {

            var img, file;
            archivo = input.files[0];
            tipo_archivo = input.files[0].type;
            var encode_permitidos = ['application/pdf', 'application/msword', 'application/vnd.ms-office', 'image/jpeg', 'image/png', 'image/jpg'];
            
            if((tipo_archivo == encode_permitidos[0]) || (tipo_archivo == encode_permitidos[1]) || (tipo_archivo == encode_permitidos[2])){
                file = new FileReader();
                file.onload = function () {
                    sendImg(urlControlador);
                };
                file.onerror = function () {
                    mostrarAdvertencia('Archivo no permitido', "Este tipo de archivo no es válido o permitido en el sistema:" + tipo_archivo);
                };
                file.src = _URL.createObjectURL(archivo);
            }

            if((tipo_archivo == encode_permitidos[3]) || (tipo_archivo == encode_permitidos[4]) || (tipo_archivo == encode_permitidos[5])){
                img = new Image();
                img.onload = function () {
                    sendImg(urlControlador);
                };
                img.onerror = function () {
                    // alert("Este tipo de archivo no es válido o permitido en el sistema:" + archivo.type);
                    mostrarAdvertencia('Archivo no permitido', "Este tipo de archivo no es válido o permitido en el sistema:" + tipo_archivo);
                };
                img.src = _URL.createObjectURL(archivo);
            }else{
                // alert('Solo se permite la carga de archivos, PDF, DOC, JPG, JPEG, & PNG en el sistema.');
                mostrarAdvertencia('Archivo no permitido', 'Solo se permite la carga de archivos, PDF, DOC, JPG, JPEG, & PNG en el sistema.');
                return false;
            }
            
        }
    });

    function sendImg(urlSend) {
        var formData = new FormData();
        formData.append('file', $('#uploadImage')[0].files[0]);
        formData.append('action', "cargarArchivo_"+nombreTabla)
        $.ajax({
            type: 'post',
            url: urlSend,
            data: formData,
            success: function (response) {
                var res = JSON.parse(response);
                var ctrol = $("#myUploadedImg")[0];
                if (res.status === 1) {
                    // var my_path = "pdfs/" + status;
                    $("#myUploadedImg").attr("src", res.data);
                    // filename = res.data;
                }
            },
            processData: false,
            contentType: false,
            error: function () {
                mostrarAdvertencia('Error inesperado', 'Se presento un error inesperado. Intente la acción nuevamente.');
            }
        });
    }

    
    function enableDocUploadEdit($cell) {
        var row_index = $cell.closest('tr').index();
        var rowdata = $(selectorTabla).DataTable().row(row_index).data();
        var idDat = "cert_" + rowdata.id;
        var ctrol = `
                    <form id="form-demo" onsubmit="return false">
                        <div class="input-group">
                        <span class="input-group-btn">
                            <input id=${idDat} class="btn btn-primary file-upload" type="file" name="file"/>
                        </span> 
                        </div>
                    </form>
                    `
        // https://www.cloudways.com/blog/the-basics-of-file-upload-in-php/
        //https://github.com/SiddharthaChowdhury/Async-File-Upload-using-PHP-Javascript-AJAX/blob/master/upload_form.html
        var ctrol2 = '<input id="uploadDoc" name="pdf" type="file">'
                    // <button type="button" name="action" class="btn btn-primary submitBtn" style="display: none;">cargarArchivo_tblEstudios</button>
                    

                    // `
                    // <form  enctype="multipart/form-data" action=${urlControlador} method="post">
                    //     <input id="uploadDoc" name="pdf" type="file" class='up_doc'>
                    //     <button type="button" name="action" class="btn btn-primary submitBtn" style="display: none;">cargarArchivo_tblEstudios</button>
                    // </form>
                    // `

                    // `
                    // <form id="form-demo" enctype="multipart/form-data" method="post" action=${urlControlador}>
                    //     <input type="file" id="pdf" name="pdf" class="btn btn-primary file-upload" accept="application/pdf"><br><br>
                    //     <input type="submit" name="action" class="btn btn-primary submitBtn" value="cargarArchivo_tblEstudios"/>
                    // </form>
                    // `
                    // `
                    // <form id="form-demo" enctype="multipart/form-data">
                    //     <input type="file" id="pdf" name="pdf" class="btn btn-primary file-upload" accept="application/pdf"><br><br>
                    //     <input type="submit" name="action" class="btn btn-primary submitBtn" value="cargarArchivo_tblEstudios"/>
                    // </form>
                    // `

                    // `
                    // <form id="form-demo" enctype="multipart/form-data" method="post" action=${urlControlador}>
                    //     <input type="file" id="pdf" name="pdf" class="btn btn-primary file-upload" accept="application/pdf"><br><br>
                    // </form>
                    // `
                    // <form id="form-demo" enctype="multipart/form-data" method="post" action=${urlControlador}>
                    //     <input type="file" id="pdf" name="pdf" class="btn btn-primary file-upload" accept="application/pdf"><br><br>
                    //     <input type="submit" name="submit" class="btn btn-primary submitBtn" value="SUBMIT"/>
                    // </form>
                    // `
        $cell.empty().append(ctrol2);
    }

    // $("input[type='file'].up_doc").change(function (e) {
    // // $('tbody tr td' ).on('change','#uploadDoc.up_doc' , function (e) {
    // // $(selectorTabla).on('click', '.submitBtn', function(e) {
    //     e.preventDefault();
    //     e.stopPropagation();
    //     var archivo, tipo_archivo;
    //     nombreTabla = e.target.parentElement.parentElement.parentElement.parentElement.id;
    //     filaEnEdicion = $(e.target.parentElement).closest('tr') .index();
    //     var input = $("#uploadDoc")[0];

    //     if (input.id == 'uploadDoc') {

    //         var img, file;
    //         archivo = input.files[0];
    //         tipo_archivo = input.files[0].type;
    //         var encode_permitidos = ['application/pdf', 'application/msword', 'application/vnd.ms-office', 'image/jpeg', 'image/png', 'image/jpg'];
            
    //         if((tipo_archivo == encode_permitidos[0]) || (tipo_archivo == encode_permitidos[1]) || (tipo_archivo == encode_permitidos[2])){
    //             file = new FileReader();
    //             file.onload = function () {
    //                 sendDoc(urlControlador);
    //             };
    //             file.onerror = function () {
    //                 alert("Este tipo de archivo no es válido o permitido en el sistema:" + tipo_archivo);
    //             };
    //             file.src = _URL.createObjectURL(archivo);
    //         }
    //         else {
    //             alert('Solo se permite la carga de archivos, PDF, DOC, DOCX en el sistema.');
    //             return false;
    //         }
            
    //     }
    // });

    
        
    function activar_upload(urlSend){
        const inputFile = document.getElementById("uploadDoc");
        const formData = new FormData();

        const handleSubmit = (e) => {
            e.preventDefault();

            var archivo, tipo_archivo;
            nombreTabla = e.target.parentElement.parentElement.parentElement.parentElement.id;
            filaEnEdicion = $(e.target.parentElement).closest('tr') .index();
            var input = $("#uploadDoc")[0];

            if (input.id == 'uploadDoc') {

                var img, file;
                archivo = input.files[0];
                tipo_archivo = input.files[0].type;
                var encode_permitidos = ['application/pdf', 'application/msword', 'application/vnd.ms-office', 'image/jpeg', 'image/png', 'image/jpg'];
                
                if((tipo_archivo == encode_permitidos[0]) || (tipo_archivo == encode_permitidos[1]) || (tipo_archivo == encode_permitidos[2])){
                    formData.append('file', $('#uploadDoc')[0].files[0]);
                    formData.append('action', "cargarArchivo_"+nombreTabla)
                    fetch(urlSend, {
                        method: 'POST',
                        body: formData,
                    }).then((resp) => {
                        if(resp['error']){
                            mostrarAdvertencia('Error inesperado', 'Se presento un error inesperado. Intente la acción nuevamente.');
                            return;
                        }
                        return resp.json();
                    }).then((json) => {
                        $("#uploadDoc").attr("value", json.data.data);
                    });
                    
                    // file = new FileReader();
                    // file.onload = function () {
                    //     sendDoc(urlSend);
                    // };
                    // file.onerror = function () {
                    //     alert("Este tipo de archivo no es válido o permitido en el sistema:" + tipo_archivo);
                    // };
                    // file.src = _URL.createObjectURL(archivo);
                }
                else {
                    alert('Solo se permite la carga de archivos, PDF, DOC, DOCX en el sistema.');
                    return false;
                }
                
            }
        };

        if(inputFile){inputFile.addEventListener("change", handleSubmit);}
    }


    function sendDoc(urlSend) {
        var formData = new FormData();
        formData.append('file', $('#uploadDoc')[0].files[0]);
        formData.append('action', "cargarArchivo_"+nombreTabla)
        fetch(urlSend, {
            method: 'POST',
            body: formData,
        }).then((resp) => {
            if(resp['error']){
                mostrarAdvertencia('Error inesperado', 'Se presento un error inesperado. Intente la acción nuevamente.');
                return;
            }
            return resp.json();
        }).then((json) => {
            $("#uploadDoc").attr("value", res.data);
        });
        // $.ajax({
        //     type: 'post',
        //     url: urlSend,
        //     data: formData,
        //     success: function (response) {
        //         var res = JSON.parse(response);
        //         if (res.status === 1) {
        //             // var my_path = "pdfs/" + status;
        //             $("#uploadDoc").attr("value", res.data);
        //             // filename = res.data;
        //         }
        //     },
        //     processData: false,
        //     contentType: false,
        //     error: function () {
        //         mostrarAdvertencia('Error inesperado', 'Se presento un error inesperado. Intente la acción nuevamente.');
        //     }
        // });
    }

    function updateRows(commit) {
        $(selectorTabla).find("tbody tr td i.bi."+claseBotonConfirmarRow).each(function(index, button) {
            updateRow($(button), commit);
        });
        if ( $( selectorCtrlDescripcion ).length ) {
            $( selectorCtrlDescripcion ).attr("disabled", "disabled");
        }
        if ( $( selectorBotonesGuardar ).length ) {
            $( selectorBotonesGuardar ).hide();
        }
        existenCambiosPendientes = false;
    }

    // recorrido por tipos de control en las columnas
    function updateRow($saveButton, commit) {
        $saveButton.removeClass().addClass('bi '+`${claseBotonEditarRow}`);
        $saveButton.show();

        var $row = $saveButton.closest("tr");

        $row.find('td').not(':first').not(':last').each(function(i, el) {
            var $input = $(this).find('input');
            // $(this).text(commit ? $input.val() : $input.data('original-text'));
            var nuevoValor = $input.context.firstChild.value;
            var antiguoValor = $input.data('original-text');

            // $input.val(commit ? nuevoValor : antiguoValor);
            // $(this).val(commit ? nuevoValor : antiguoValor).trigger( "change" );
            // $(this).val(commit ? nuevoValor : antiguoValor).trigger( "change" );
            $(this).prop('value', commit ? nuevoValor : antiguoValor);
        });

        $row.find('td.ddl').each(function(i, el) {
            var $input = $(this).find('select');
            var nuevoValor = $input.context.textContent; //context.firstChild.data;
            var antiguoValor = $input.context.value; //var antiguoValor = $input.data('original-text');
            // $input.val(commit ? ddl_estado_ops[nuevoValor] : $input.data('original-value')).change();
            // $input.val(commit ? ddl_estado_ops[nuevoValor] : $input.data('original-value')).trigger('change');
            // $(this).text(commit ? $input.val() : antiguoValor).trigger( "change" );
            $(this).prop('value', commit ? $input.val() : antiguoValor);
        });

        $row.find('td.img').each(function(i, el) {
            var $input = $(this).find('input');
            var nuevoValor = $input[0].files; // context.textContent; //context.firstChild.data;
            var antiguoValor = $input.context.value; //var antiguoValor = $input.data('original-text');
            // $input.val(commit ? ddl_estado_ops[nuevoValor] : $input.data('original-value')).change();
            // $input.val(commit ? ddl_estado_ops[nuevoValor] : $input.data('original-value')).trigger('change');
            // $(this).text(commit ? $input.val() : antiguoValor).trigger( "change" );
            $(this).prop('value', commit ? $input.val() : antiguoValor);
        });
        

        var $cancelButton = $saveButton.closest('tr').find("td:last-child i.bi."+claseBotonCancelarRow);
        $cancelButton.removeClass().addClass("bi "+claseBotonEliminarRow);
        $cancelButton.attr("aria-hidden", "true");
        $cancelButton.show();
    } 
    
    // $(".img").change(function (e) {
    //     e.preventDefault();
    //     if (typeof (FileReader) != "undefined") {
    //         var dvPreview = $("#divImageMediaPreview");
    //         dvPreview.html("");
    //         var imgCtrl = $("#ImageMedias")[0];            
    //         $(imgCtrl.files).each(function () {
    //             var file = $(this);                
    //                 var reader = new FileReader();
    //                 reader.onload = function (e) {
    //                     var img = $("<img />");
    //                     img.attr("style", "width: 150px; height:100px; padding: 10px");
    //                     img.attr("src", e.target.result);
    //                     dvPreview.append(img);
    //                 }
    //                 reader.readAsDataURL(file[0]);                
    //         });

    //         var filename = ''
    //         var formData =  {
    //                 action: "cargarArchivo_"+nombreTabla,
    //                 file : imgCtrl.files
    //         }

    //         // $.ajax({
    //         //     type:'POST',
    //         //     url: urlControlador,
    //         //     data:formData,
    //         //     // cache:false,
    //         //     // contentType: false,
    //         //     // processData: false,
    //         //     success:function(data){
    //         //         console.log("success");
    //         //         console.log(data);
    //         //         filename = data;
    //         //     },
    //         //     error: function(data){
    //         //         console.log("error");
    //         //         console.log(data);
    //         //     }
    //         // });

    //         formData = new FormData();
    //         formData.append("action", "cargarArchivo_"+nombreTabla);
    //         formData.append("img", imgCtrl.files);
            
    //         var filename = '';
            
    //         fetch(urlControlador, {
    //             method: "POST", 
    //             body: formData
    //         }).then((resp) => {
    //             console.log(resp.json());
    //         });
    //         return filename;

    //     } else {
    //         alert("Este navegador no soporta Lector de archivos con HTML5");
    //     }
    // });

    // $(selectorTabla).on('click', '.submitBtn', function(e) {
    //     e.preventDefault();
    //     var form = $(this).closest('tr'); //.find('#fupForm');  
    //     var file_data = form.find('td #form-demo input[name=pdf]')[0].value;
    //     var form_data = form.find('td #form-demo');                  
    //     form_data.append('pdf', file_data);
    //     form_data.append('action', 'cargarArchivo_'+nombreTabla);
    //     // alert(form_data);        

    //     $.ajax({
    //         method: 'POST',
    //         type: 'POST', // For jQuery < 1.9
    //         // url: 'http://localhost/SENA-devmanager/src/upload.php',
    //         url: urlControlador,
    //         data: form_data,
    //         // dataType: 'json',
    //         cache: false,
    //         contentType: false,
    //         processData: false,
    //         beforeSend: function(){
    //             $('.submitBtn').attr("disabled","disabled");
    //             $('#fupForm').css("opacity",".5");
    //         },
    //         success: function(response){
    //             $('.statusMsg').html('');
    //             if(response.status == 1){
    //                 $('#fupForm')[0].reset();
    //                 $('.statusMsg').html('<p class="alert alert-success">'+response.message+'</p>');
    //             }else{
    //                 $('.statusMsg').html('<p class="alert alert-danger">'+response.message+'</p>');
    //             }
    //             $('#fupForm').css("opacity","");
    //             $(".submitBtn").removeAttr("disabled");
    //         }
    //     });
    //     e.preventDefault();
    //     return false;
    // });
    
    activarModoCRUD(modoTabla, nombreTabla);
}

function activarModoCRUD(modoelegido, nombreTabla){
    var probar = modoelegido.toUpperCase();
    var selectorBtnCrear =  '#addRow'+nombreTabla;
    var selectorTabla =  '#'+nombreTabla;
    var test = probar.indexOf('C');
    if( probar.indexOf('C') < 0 ){
        $( selectorBtnCrear ).hide();
    }
}


// function fileUpload(event){
//     // Allowed file types
//     var allowedFileTypes = 'image.*|application/pdf'; //text.*|image.*|application.*

//     // Allowed file size
//     var allowedFileSize = 1024; //in KB

//     // Notify user about the file upload status
//     $("#dropBox").html(event.target.value+" uploading...");
    
//     // Get selected file
//     files = event.target.files;
    
//     // Form data check the above bullet for what it is  
//     var data = new FormData();                                   

//     // File data is presented as an array
//     for (var i = 0; i < files.length; i++) {
//         var file = files[i];
//         if(!file.type.match(allowedFileTypes)) {              
//             // Check file type
//             $("#dropBox").html('<p class="error">File extension error! Please select the allowed file type only.</p>');
//         }else if(file.size > (allowedFileSize*1024)){
//             // Check file size (in bytes)
//             $("#dropBox").html('<p class="error">File size error! Sorry, the selected file size is larger than the allowed size (>'+allowedFileSize+'KB).</p>');
//         }else{
//             // Append the uploadable file to FormData object
//             data.append('file', file, file.name);
            
//             // Create a new XMLHttpRequest
//             var xhr = new XMLHttpRequest();     
            
//             // Post file data for upload
//             xhr.open('POST', 'upload.php', true);  
//             xhr.send(data);
//             xhr.onload = function () {
//                 // Get response and show the uploading status
//                 var response = JSON.parse(xhr.responseText);
//                 if(xhr.status === 200 && response.status == 'ok'){
//                     $("#dropBox").html('<p class="success">File has been uploaded successfully. Click to upload another file.</p>');
//                 }else if(response.status == 'type_err'){
//                     $("#dropBox").html('<p class="error">File extension error! Click to upload another file.</p>');
//                 }else{
//                     $("#dropBox").html('<p class="error">Something went wrong, please try again.</p>');
//                 }
//             };
//         }
//     }
//     return response;
// }


async function sendArchivo(nombreTabla, urlEnviar){
    var newForm = document.getElementById("form-demo");
    var formData = new FormData(newForm);
    formData.append("action", "cargarArchivo_"+nombreTabla);
    
    var filename = '';
    
    await fetch(urlEnviar, {
        method: "POST", 
        body: formData
    }).then((resp) => {
        filename = resp.data;
    });
    return filename;
}


function desbloquearAccionesPantalla(nombreTabla='', filaEnEdicion=0){
       
    if(nombreTabla != ''){
        selectorTabla = '#'+nombreTabla;
    }

    $(selectorTabla+ ' tbody tr #botones').each(function(rowindex, divBotones) {
        if (rowindex != filaEnEdicion){
            $(divBotones).attr('style', 'display:none');
        }
    });
}

function bloquearAccionesPantalla(nombreTabla='', filaEnEdicion=0){
    var selectorTabla = '#'+nombreTabla;

    $(selectorTabla+ ' tbody tr #botones').each(function(rowindex, divBotones) {
        if (rowindex != filaEnEdicion){
            $(divBotones).attr('style', 'display:none');
        }
        // else{
        //     var btnEdit = $('#'+element.id+'td:last-child i.bi.'+claseBotonEditarRow);
        //     var btnDelete = $('#'+element.id+'td:last-child i.bi.'+claseBotonEliminarRow);
        //     if(btnDelete.length) btnDelete.hide();
        //     if(btnEdit.length) btnEdit.hide();
        // }
    });
    // $('table').each(function(index, element){
    //     // console.log("tabla", index, element.id);
        
    // });
}
// https://datatables.net/reference/event/user-select
// // Disparado en el evento de seleccion de una fila
// $( '#'+nombreTabla ).DataTable().on( 'user-select', function ( e, dt, type, cell, originalEvent ) {
//     if ( !existenCambiosPendientes && originalEvent.target.nodeName.toLowerCase() === 'div' ) {
//         e.preventDefault();
//     }
// } );

// // eventos de selección de fila
// function getIdRegistroSeleccionado(idTabla)
// {
//     var selectorTabla = '#'+idTabla;
//     $(selectorTabla+' tbody').on('click', 'tr', function () {
//         if($(this).hasClass('selected')) {
//             // var celda = dataTable.cell(this);
//             var rowindex = $(this).closest("tr").index();
//             console.log(selectorTabla, rowindex);
//             var data = dataTable.row( rowindex ).data();
//             return data.id;
//         }
//     });  
// }

export {    cargarTablaGenerica,
            bloquearAccionesPantalla,
            desbloquearAccionesPantalla,
            // fileUpload,
            claseBotonEditarRow,
            claseBotonEliminarRow,
            claseBotonConfirmarRow,
            claseBotonCancelarRow,
}