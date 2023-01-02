// http://jsfiddle.net/awbq0p4e/
const claseBotonEditarRow = 'bi-pencil-square';
const claseBotonEliminarRow = 'bi-trash-fill';
const claseBotonConfirmarRow = 'bi-check-circle';
const claseBotonCancelarRow = 'bi-x-circle';

const ultimaColumna = "<td><div><button id='edit_row' class='bi "+claseBotonEditarRow +"' aria-hidden='true'></button><button id='delete_row' class='bi "+claseBotonEliminarRow +"' aria-hidden='true'></button><div></td>";  

var existenCambiosPendientes = false;
var insertandoNuevoRegistro = false;
var dataTable = null;
var dataUrl = null;

function cargarTablaGenerica(nombreTabla, arreglo, cols, modoTabla='CRUD', ddl_estado_ops = [], campo_desc = false)
{
    
    var selectorTabla = '#'+nombreTabla
    cols.push({    
                data: null,
                render:function(){return ultimaColumna;},
                // className: 'row-edit dt-center',
                orderable: false
            });

    dataTable = null;
    dataUrl = "./presentacion/vistas/proyectos.php";

    dataTable = $(selectorTabla).DataTable({
        // ajax: dataUrl,
        data: arreglo,
        columns: cols,
        rowReorder: {
            dataSrc: 'order',
            selector: 'tr'
        },
        destroy: true,
        select:{ style:'single' }, //toggleable: false},
        // fnInitComplete: function(oSettings, json) {
        //         // Seleccionar primera fila automáticamente;
        //         $(selectorTabla+' tbody tr:eq(0)').click();
        //         alert( 'DataTables has finished its initialisation.' );
        // },
        createdRow:function(row){
            $(".datepicker", row).datepicker();
        },
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

    var idBotonesGuardar = "guardarCambios"+nombreTabla;
    var selectorBotonesGuardar = '#'+idBotonesGuardar;
    var ctrlBotonesGuardar = `<br> 
    <div class="row">
        <div class="col align-self-start"></div>
        <div class="col align-self-center"></div>
        <div id=${idBotonesGuardar} class="col align-self-end" disabled="disabled">
            <button type="button" id="btn-cancel" class="btn btn-secondary" data-dismiss="modal">Revertir Cambios</button>
            <button type="button" id="btn-save" class="btn btn-primary" data-dismiss="modal">Guardar Cambios</button>
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

    function bloquearAccionesPantalla(){
        var clasesControlesBloquear = [
            '.addRow', 
            '.'+claseBotonConfirmarRow, 
            '.'+claseBotonEditarRow,
            '.'+claseBotonEliminarRow
        ];
        $.each(clasesControlesBloquear, function(cssClass){
            $(".dataTables_wrapper *").children(cssClass).attr("disabled", "disabled");
            $("body *").children(cssClass).off('mousedown', null); // bloquea botones editar en las filas
            $(selectorBotonesGuardar).children(cssClass).removeAttr("disabled");
        });
    }

    function desbloquearAccionesPantalla(){
        var clasesControlesDesbloquear = [
            '.addRow', 
            '.'+claseBotonConfirmarRow, 
            '.'+claseBotonEditarRow,
            '.'+claseBotonEliminarRow
        ];
        $.each(clasesControlesDesbloquear, function(cssClass){
            $(".dataTables_wrapper *").children(cssClass).removeAttr("disabled");
            $("body *").children(cssClass).on('mousedown', null); // bloquea botones editar en las filas
        });

        if ( $( selectorBotonesGuardar ).length ) $(selectorBotonesGuardar).hide();

        if( $(selectorCtrlDescripcion).length ) {
            var ctrolDesc = document.getElementById(idCtrlDescripcion)
            ctrolDesc.disabled = true;
            $(selectorCtrlDescripcion).hide();
            // $(selectorCtrlDescripcion).attr("disabled", "disabled"); 
        }
    }


    $(selectorTabla+' tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            if ( existenCambiosPendientes) {
                $( selectorCtrlDescripcion ).show();
            }
            else{
                $( selectorCtrlDescripcion ).hide();
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
    });

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
    // eliminar
    $(selectorTabla).on('mousedown', 'td .bi.'+`${claseBotonEliminarRow}`, function(e) {
        $(selectorTabla).DataTable().row($(this).closest("tr")).remove().draw();
    });

    $(selectorTabla).on('mousedown', 'input', function(e) {
        e.stopPropagation();
    });
    $(selectorTabla).on('mousedown', '.select-basic', function(e) {
        e.stopPropagation();
    });
    
    // Guardar Cambios
    $('#btn-save').on('click', function() {
        updateRows(true); // Update all edited rows
        existenCambiosPendientes = false;
        insertandoNuevoRegistro = false;
    });
    // Cancelar Cambios
    $('#btn-cancel').on('click', function() {
        updateRows(false); // Revert all edited rows
        existenCambiosPendientes = false;

        if ( insertandoNuevoRegistro ){
            insertandoNuevoRegistro = false;
            $(selectorTabla).DataTable().row(0).remove().draw();
        }
        desbloquearAccionesPantalla();
    });
    
    // Botón nuevo proyecto
    $(selectorTabla).css('border-top', 'none')
        .before($('<div>').addClass('addRow')
        .append($('<button>').attr('id', 'addRow'+nombreTabla).text('Nuevo '+nombreTabla.substring(3,nombreTabla.length-1))));

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
        enableRowEdit($(selectorTabla).find("tbody tr:first-child td i.bi."+claseBotonEditarRow));
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

        $row.find("td").not(':first').not(':last').each(function(i, el) {
            enableEditText($(this))
        });

        $row.find('td.ddl').each(function(i, el) {
            enableddlEdit($(this))
        });

        $row.find('td.datepicker').each(function(i, el) {
            enableDatePicker($(this))
        });

        var $cancelButton = $(selectorTabla).find("tbody tr:last-child td i.bi."+claseBotonEliminarRow);
        $cancelButton.removeClass().addClass("bi "+claseBotonCancelarRow);
        $cancelButton.attr("aria-hidden", "true");
        $cancelButton.hide();
    }

    function enableEditText($cell) {
        var txt = $cell.text();
        var defa = $cell.context.lastChild.data;
        var check = $cell.context.textContent;
        $cell.empty().append($('<input>', {
            type : 'text',
            value : txt
        }).data('original-text', txt));
    }

    function enableddlEdit($cell) {
        var txt = $cell.context.childNodes[0].value;
        $cell.empty().append($('<select>', {
            class : 'select-basic'
        }).append(ddl_estado_ops.map(function(option) {
        return $('<option>', {
                value : option.value,
                text : option.key
            })
        })).data('original-value', txt)).val(txt);
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
            $(this).text(commit ? nuevoValor : $input.data('original-text'));
        });

        $row.find('td.ddl').each(function(i, el) {
            var $input = $(this).find('select');
            var nuevoValor = $input.context.textContent; //context.firstChild.data;
            var antiguoValor = $input.context.value; //var antiguoValor = $input.data('original-text');
            $(this).text(commit ? ddl_estado_ops[nuevoValor] : $input.data('original-value'));
            // $(this).text(commit ? $input.val() : $input.data('original-value'));
        });

        var $cancelButton = $(selectorTabla).find("tbody tr:last-child td i.bi."+claseBotonCancelarRow);
        $cancelButton.removeClass().addClass("bi "+claseBotonEliminarRow);
        $cancelButton.attr("aria-hidden", "true");
        $cancelButton.show();
    }

    function activarModoCRUD(modoelegido, nombreTabla){
        var probar = modoelegido.toUpperCase();
        var selectorBtnCrear =  '#addRow'+nombreTabla;
        var test = probar.indexOf('C');
        if( probar.indexOf('C') < 0 ){
            $( selectorBtnCrear ).hide();
        }
        if( probar.indexOf('U') < 0 ){
            $( '#'+nombreTabla+' #edit_row' ).hide();
        }
        if( probar.indexOf('D') < 0 ){
            $( '#'+nombreTabla+' #delete_row' ).hide();
        }
    }

    activarModoCRUD(modoTabla, nombreTabla);
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

export { cargarTablaGenerica } //, getIdRegistroSeleccionado }