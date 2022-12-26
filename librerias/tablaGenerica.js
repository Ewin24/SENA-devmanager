// http://jsfiddle.net/awbq0p4e/
const claseBotonEditarRow = 'bi-pencil-square';
const claseBotonEliminarRow = 'bi-trash-fill';
const claseBotonConfirmarRow = 'bi-check-circle';
const claseBotonCancelarRow = 'bi-x-circle';

const ultimaColumna = "<td><i class='bi "+claseBotonEditarRow +"' aria-hidden='true'></i><i class='bi "+claseBotonEliminarRow +"' aria-hidden='true'></i></td>";  

var existenCambiosPendientes = false;

export function cargarTablaGenerica(nombreTabla, arreglo, cols, ddl_estado_ops){
    
    var selectorTabla = '#'+nombreTabla
    cols.push({    
                data: null,
                render:function(){return ultimaColumna;},
                // className: 'row-edit dt-center',
                orderable: false
            });

    var $table = $(selectorTabla);
    var dataTable = null;

    dataTable = $table.DataTable({
        // ajax: dataUrl,
        data: arreglo,
        columns: cols,
        rowReorder: {
            dataSrc: 'order',
            selector: 'tr'
        },
        select:{style:'single'},
        createdRow:function(row){
            $(".datepicker", row).datepicker();
        },
        dom: '<"row"<"col-sm-2"><"col-sm-5 text-center"f><"col-sm-5">>t<"row"<"col-sm-3"l><"col-sm-5 text-center"p><"col-sm-4"i>>'
    });

    var idCtrlDescripcion = 'campoDescripcion'; //'desc'+nombreTabla;
    var selectorCtrlDescripcion = '#campoDescripcion'; //'desc'+nombreTabla;
    var ctrlDescripcion = '<div class="w-auto p-3 align-self-center"><textarea id='+idCtrlDescripcion+' type="text" class="w-auto p-3 form-control" style="min-width: 100%" rows="5" disabled="disabled"></textarea></div>';
    $table.after(ctrlDescripcion);

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
    $(selectorCtrlDescripcion).after(ctrlBotonesGuardar);
    // $(selectorBotonesGuardar).children().attr("disabled","disabled");
    $( selectorBotonesGuardar ).hide();

    // eventos de selección de fila
    $(selectorTabla+' tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');

            if ( $( selectorCtrlDescripcion ).length ) {
                $( selectorCtrlDescripcion ).hide();
            }
        } 
        else {
            dataTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            var celda = dataTable.cell(this);
            // var celda_data = celda.context[0].aoData[0];//._aData[0];
            // var columna = celda["0"][0].column; // columna descripcion
            var tr = $(this).closest("tr");
            var rowindex = tr.index();
            var data = dataTable.row( rowindex ).data();

            if ( $( selectorCtrlDescripcion ).length ) {
                $( selectorCtrlDescripcion ).val(data.descripcion);
                $( selectorCtrlDescripcion ).show();
            }
        }
    });

    // eliminar
    $table.on('mousedown', 'td .bi.'+`${claseBotonEliminarRow}`, function(e) {
        dataTable.row($(this).closest("tr")).remove().draw();
    });
    // editar
    $table.on('mousedown.edit', 'i.bi.'+`${claseBotonEditarRow}`, function(e) {
        enableRowEdit($(this));
        existenCambiosPendientes = true;
    });

    // boton confirmar
    $table.on('mousedown.save', "i.bi."+claseBotonConfirmarRow, function(e) {
        updateRow($(this), true); // Pass save button to function.
    });

    $table.on('mousedown', 'input', function(e) {
        e.stopPropagation();
    });
    $table.on('mousedown', '.select-basic', function(e) {
        e.stopPropagation();
    });
    
    // Guardar Cambios
    $('#btn-save').on('click', function() {
        updateRows(true); // Update all edited rows
        existenCambiosPendientes = false;
    });
    // Cancelar Cambios
    $('#btn-cancel').on('click', function() {
        updateRows(false); // Revert all edited rows
        existenCambiosPendientes = false;
    });
    
    // Botón nuevo proyecto
    $table.css('border-top', 'none')
        .before($('<div>').addClass('addRow')
        .append($('<button>').attr('id', 'addRow').text('Nuevo Proyecto')));

    // Add row
    $('#addRow').click(function() {
        var $row = $("#new-row-template").find('tr').clone();
        dataTable.row.add($row).draw();
        	
        // https://datatables.net/beta/1.8/examples/api/add_row.html
        // https://stackoverflow.com/questions/52792749/how-to-get-datatables-header-name
        // var headerNames = dataTable.columns().header().map(d => d.textContent).toArray()
        // var headerNames = dataTable.columns().header().map(d => "<td>dato "+d.textContent+"</td>").toArray()
        // //$(selectorTabla).dataTable().fnAddData(headerNames);
        // var t = dataTable.row.add(headerNames).draw(true);

        TODO: // esta logica activa campo descripción dentro de la tabla
        if ( $( selectorCtrlDescripcion ).length ) {
            $( selectorCtrlDescripcion ).removeAttr("disabled");
            $( selectorCtrlDescripcion ).val('agregue una descripción');
        }
        if ( $( selectorBotonesGuardar).length ) {
            $( selectorBotonesGuardar ).show();
        }

        // Toggle edit mode upon creation.
        enableRowEdit($table.find("tbody tr:last-child td i.bi."+claseBotonEditarRow));
    });

    // habilitar edición
    function enableRowEdit($editButton) {
        existenCambiosPendientes = true;
        if ( $( selectorBotonesGuardar ).length ) {
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

        var $cancelButton = $table.find("tbody tr:last-child td i.bi."+claseBotonEliminarRow);
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
        $table.find("tbody tr td i.bi."+claseBotonConfirmarRow).each(function(index, button) {
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

        var $cancelButton = $table.find("tbody tr:last-child td i.bi."+claseBotonCancelarRow);
        $cancelButton.removeClass().addClass("bi "+claseBotonEliminarRow);
        $cancelButton.attr("aria-hidden", "true");
        $cancelButton.show();
    }
}