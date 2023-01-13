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

function cargarTablaGenerica(nombreTabla, cols, modoTabla='CRUD', urlControlador='', payloadInicial = {}, ddl_ops = [], campo_desc = false, arreglo={}, )
{
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
                        case 'U':
                            ultimaColumna += botonEditar; //+"</td>";
                            break;
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
                console.log(json);

                if( json.ddl_ops!=null && json.ddl_ops[0].length != 0){
                    var opciones = JSON.parse(json.ddl_ops[0])[nombreTabla];
                    var obj = {};
                    for(const op of opciones){
                        var key = Object.keys(op)[0];
                        obj[key] = op[key];
                        console.log(key, op);
                    }
                    ddl_ops = obj;
                }
                // ddl_ops = JSON.parse(json.ddl_ops[0])[nombreTabla];
                return json.data;
            }, 
            // success:function(response){
            //     alert("Status: "+response);
            //     console.log(response);
            //     existenCambiosPendientes = false;
            //     insertandoNuevoRegistro = false;
            // }, 
            // error: function(XMLHttpRequest, textStatus, errorThrown) { 
            //     alert("Status: " + textStatus); 
            //     alert("Error: " + errorThrown); 
            // }
        },
        // data: arreglo,
        columns: cols,
        rowReorder: {
            dataSrc: 'order',
            selector: 'tr'
        },
        lengthMenu: [
            [ 3, 10, 20, -1 ],
            [ '3', '10', '20', 'Todos los' ]
        ],
        // scrollY: 400,
        // scrollX: true,
        destroy: true,
        processing: true,
        select:{ style:'single', toggleable: true},
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
            {
                targets: '_all',
                "createdCell": function (td, cellData, rowData, row, col) {
                    var t = nombreTabla;
                    if(col != 0 && col != cols.length ){
                        var campo = cols[col].name;
                        $(td).attr('id', campo);


                        // encontrando id de referencia a la tabla Padre 
                        // TODO: verificar
                        if(payloadInicial.hasOwnProperty(campo)){
                            idPadre = payloadInicial[campo];
                        }

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
                        if(cols[col].className == 'fUpload'){
                            var hiperlink = jQuery('<a>').attr('href', rowData[campo]).text(cellData.split('/', -1));
                            $(td).empty().append(hiperlink);
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

    // $(selectorTabla+' tbody').on('click', 'tr', function () {
    //     if ($(this).hasClass('selected')) {
    //         if ( $( selectorCtrlDescripcion ).length ) {
    //             if ( existenCambiosPendientes) {
    //                 $( selectorCtrlDescripcion ).show();
    //             }
    //             else{
    //                 $( selectorCtrlDescripcion ).hide();
    //             }
    //         }
    //     }
    //     else {
    //         // $(this).addClass('selected');
    //         // $(this).removeClass('selected');

    //         if ( $( selectorCtrlDescripcion ).length ) {
    //             if ( existenCambiosPendientes) {
    //                 $( selectorCtrlDescripcion ).removeAttr("disabled");
    //             }
    //             $( selectorCtrlDescripcion ).show();

    //             var tr = $(this).closest("tr");
    //             var rowindex = tr.index();
    //             var data = $(selectorTabla).DataTable().row( rowindex ).data();
    //             $( selectorCtrlDescripcion ).val(data.descripcion);
    //         }
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
        $(selectorTabla).DataTable().row($(this)).select().draw();

        filaEnEdicion = $(this).closest('tr').index();

        $(selectorTabla+ ' tbody tr #botones').each(function(rowindex, divBotones) {
            if (rowindex != filaEnEdicion){
                $(divBotones).attr('style', 'display:none');
            }
        });

        // var nonSelected = $(selectorTabla).DataTable().rows( { selected: false } ).nodes().each(function(row){
        //     var but = $(selectorTabla).DataTable().row(row).node().attr('disabled', 'disabled'); //find("td:last-child i.bi."+claseBotonEliminarRow);
        //     but.hide();//.attr('disabled', 'disabled');
        // }); 
        
        e.stopPropagation();
        e.preventDefault();
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
                // alert("Status: "+response);
                console.log(rowdata);
                existenCambiosPendientes = false;
                insertandoNuevoRegistro = false;
                $(selectorTabla).DataTable().row($(this).closest("tr")).remove().draw();
            }, 
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                alert("Status: " + textStatus); 
                alert("Error: " + errorThrown); 
            }
        });
        $(selectorTabla).DataTable().ajax.reload();
    });

    $(selectorTabla).on('mousedown', 'input', function(e) {
        e.stopPropagation();
    });
    $(selectorTabla).on('mousedown', '.select-basic', function(e) {
        e.stopPropagation();
    });
    
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

    // $(selectorTabla).on('click', '.submitBtn', function(event) {
        
    //     event.preventDefault();
    //     // retrieve form element
    //     var form = this.closest('form');
    //     // prepare data
    //     var dReq = {
    //         'pdf': form[0].files,
    //         'action': 'cargarArchivo_tblEstudios'
    //     }
    //     // get url
    //     var url = form.action;
    //     url = urlControlador;
        

    //     // send request
    //     $.ajax({
    //         type: 'POST',
    //         method: 'POST',
    //         url: url,
    //         data: dReq,
    //         cache: false,
    //         contentType: false,
    //         processData: false,
    //     });

    // });

    
    $(selectorTabla+" tbody tr td #form-demo").on('submit',(function(e) {

        var idx = $(selectorTabla).DataTable().rows({selected: true}).indexes()[0];
        rowdata = $(selectorTabla).DataTable().row(idx).data();

        var fila = $(selectorTabla+' tbody tr:first');

        var form = fila.find('td #form-demo')[0];
        var btn = fila.find('td .submitBtn')
        // btn.click();
        var archivo = '';//{'pdf': form[0].files};

        var formData = new FormData(document.getElementById("form-demo"));
        formData.append("action", "cargarArchivo_"+nombreTabla);

        $.ajax({
                dataType:"json",
                method:"POST",
                url: urlControlador,
                data: formData,
                processData: false,
                contentType: false,
                success:function(response){
                    // alert("Status: "+response);
                    // console.log(response.data);
                    archivo = response.data;
                    rowdata[elemento.id] = archivo;
                    existenCambiosPendientes = false;
                    insertandoNuevoRegistro = false;
                }, 
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    alert("Status: " + textStatus); 
                    alert("Error: " + errorThrown); 
                }
            });
            rowdata[elemento.id] = archivo;
        })
    );

    // Guardar Cambios
    $('#btn-save-'+nombreTabla).on('click', function() {
        updateRows(true); // Update all edited rows
        
        var rowdata = null;
        var accionCRUD = '';
        var archivos = {};

        if(insertandoNuevoRegistro){
            var row = $(selectorTabla).DataTable().row(0);
            rowdata = row.data();

            var fila = $(selectorTabla+' tbody tr:first');
            var cells = fila.find("td").not(':first').not(':last');
            cells.each(function(i, elemento) {

                if(elemento.className.toUpperCase().indexOf('FUPLOAD')<0){
                    rowdata[elemento.id] = elemento.value;
                }
                else
                {
                    var form = fila.find('td #form-demo')[0];
                    var btn = fila.find('td .submitBtn')
                    // btn.click();
                    var archivo = '';//{'pdf': form[0].files};

                    var formData = new FormData(document.getElementById("form-demo"));
                    formData.append("action", "cargarArchivo_"+nombreTabla);
                    
                    // $.ajax({
                    //     dataType:"json",
                    //     method:"POST",
                    //     url: urlControlador,
                    //     data: formData,
                    //     processData: false,
                    //     contentType: false,
                    //     success:function(response){
                    //         // alert("Status: "+response);
                    //         // console.log(response.data);
                    //         archivo = response.data;
                    //         rowdata[elemento.id] = archivo;
                    //         existenCambiosPendientes = false;
                    //         insertandoNuevoRegistro = false;
                    //     }, 
                    //     error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    //         alert("Status: " + textStatus); 
                    //         alert("Error: " + errorThrown); 
                    //     }
                    // });
                    // rowdata[elemento.id] = archivo;
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
        else{   
            var idx = $(selectorTabla).DataTable().rows({selected: true}).indexes()[0];
            rowdata = $(selectorTabla).DataTable().row(idx).data();

            var fila = $(selectorTabla+' tbody tr:eq('+idx+')');
            var cells = fila.find("td").not(':first').not(':last');
            cells.each(function(i, elemento) {
                rowdata[elemento.id] = elemento.value;
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
                // alert("Status: "+response);
                console.log(rowdata);
                existenCambiosPendientes = false;
                insertandoNuevoRegistro = false;
            }, 
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                alert("Status: " + textStatus); 
                alert("Error: " + errorThrown); 
            }
        });
        
        $(selectorTabla).DataTable().ajax.reload();
    });

    // Cancelar Cambios
    $('#btn-cancel-'+nombreTabla).on('click', function() {
        updateRows(false); // Revert all edited rows
        existenCambiosPendientes = false;

        if ( insertandoNuevoRegistro ){
            insertandoNuevoRegistro = false;
            $(selectorTabla).DataTable().row(0).remove().draw();
        }
        $(selectorTabla).DataTable().ajax.reload();
    });
    
    // Botón nuevo proyecto
    $(selectorTabla).css('border-top', 'none')
        .before($('<div>').addClass('addRow')
        .append($('<button>')
        .attr('id', 'addRow'+nombreTabla)
        .text('Nuevo '+nombreTabla.substring(3,nombreTabla.length-1))));

    // Add row
    $('#addRow'+nombreTabla).click(function() {
        existenCambiosPendientes = true;
        insertandoNuevoRegistro = true;
        filaEnEdicion = 0;

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
        console.log(index-1, rowCount, insertedRow);

        for (var i=rowCount; i>0; i--) {
            tempRow = $(selectorTabla).DataTable().row(i-1).data();
            $(selectorTabla).DataTable().row(i).data(tempRow);
            $(selectorTabla).DataTable().row(i-1).data(insertedRow);
        }
        
        //refresh the page
        $(selectorTabla).DataTable().row(0).select();
        $(selectorTabla).DataTable().page(currentPage).draw(false);
        	
        // https://datatables.net/beta/1.8/examples/api/add_row.html
        // https://stackoverflow.com/questions/52792749/how-to-get-datatables-header-name
        // var headerNames = dataTable.columns().header().map(d => d.textContent).toArray()
        // var headerNames = dataTable.columns().header().map(d => "<td>dato "+d.textContent+"</td>").toArray()
        // //$(selectorTabla).dataTable().fnAddData(headerNames);
        // var t = dataTable.row.add(headerNames).draw(true);

        // Toggle edit mode upon creation.
        // enableRowEdit($(selectorTabla).find("tbody tr:first-child td i.bi."+claseBotonEditarRow));
        enableRowEdit($(selectorTabla).find("tbody tr:first-child td i.bi"));
        bloquearAccionesPantalla(nombreTabla, filaEnEdicion);
        // TODO: Posterior a esta acción, en la tabla proyectos se causa una excepción
    });

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

        $row.find('td.fUpload').each(function(i, el) {
            enablefileUploadEdit($(this))
        });

        $row.find("td").not('.ddl').not('.fUpload')//.not('.datepicker')
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

    function enablefileUploadEdit($cell) {
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
        var ctrol2 = 
                    `
                    <form id="form-demo" enctype="multipart/form-data" action=${urlControlador} method="post">
                        <input name="pdf" type="file">
                        <button type="submit" name="action" class="btn btn-primary submitBtn" style="display: none;">cargarArchivo_tblEstudios</button>
                    </form>
                    `

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

        // $cell.empty().append(
        // $('<select>', {class : 'select-basic'})
        // .append(options.map(function(option) {
        // var r = option[key];
        // if(option[key] === valor) {commit = true;}
        // $('<option>', {
        //         value : option.key,
        //         text : option.value,
        //     })
        // })).val(commit ? valor : $cell.data('original-text')));
        // /*prop('selectedIndex', 
        //             commit ? 
        //             valor : 
        //             $cell.data('original-text'))
        //         );
        // */
    }

    function enableDatePicker($cell) {
        var txt = $cell.context.childNodes[0].value;
        $cell.empty().append($('<input>', {
            class: 'datepicker',
            type : 'date',
            value : txt
        }).data('original-text', txt));
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

        var $cancelButton = $saveButton.closest('tr').find("td:last-child i.bi."+claseBotonCancelarRow);
        $cancelButton.removeClass().addClass("bi "+claseBotonEliminarRow);
        $cancelButton.attr("aria-hidden", "true");
        $cancelButton.show();
    }    

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
            claseBotonEditarRow,
            claseBotonEliminarRow,
            claseBotonConfirmarRow,
            claseBotonCancelarRow,
}