// http://jsfiddle.net/awbq0p4e/
const claseBotonEditarRow = 'bi-pencil-square';
const claseBotonEliminarRow = 'bi-trash-fill';
const claseBotonConfirmarRow = 'bi-check-circle';
const claseBotonCancelarRow = 'bi-x-circle';

const ultimaColumna = "<td><i class='bi "+claseBotonEditarRow +"' aria-hidden='true'></i><i class='bi "+claseBotonEliminarRow +"' aria-hidden='true'></i></td>";  

var existenCambiosPendientes = false;

export function cargarTablaGenerica(nombreTabla, arreglo, cols, ddl_estado_ops){
    
    cols.push({    
                data: null,
                render:function(){return ultimaColumna;},
                // className: 'row-edit dt-center',
                orderable: false
            });

    var $table = $(nombreTabla);
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

    // eventos de selección de fila
    $(nombreTabla+' tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');

            if ( $( "#campoDescripcion").length ) {
                $('#campoDescripcion').hide();
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
            var campoId = celda.context[0].aoData[rowindex].anCells[1].textContent;
            var descripcion = celda.context[0].aoData[rowindex].anCells[2].textContent

            if ( $( "#campoDescripcion").length ) {
                $('#campoDescripcion').val(descripcion);
                $('#campoDescripcion').show();
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
        
        TODO: // esta logica activa campo descripción dentro de la tabla
        if ( $( "#campoDescripcion").length ) {
            $('#campoDescripcion').removeAttr("disabled");
            $('#campoDescripcion').val('agregue una descripción');
        }
        if ( $( "#botonesGuardarCambios").length ) {
            $('#botonesGuardarCambios').show();
        }

        // Toggle edit mode upon creation.
        enableRowEdit($table.find("tbody tr:last-child td i.bi."+claseBotonEditarRow));
    });

    // habilitar edición
    function enableRowEdit($editButton) {
        existenCambiosPendientes = true;
        if ( $( "#botonesGuardarCambios").length ) {
            $('#botonesGuardarCambios').show();
        }

        $editButton.removeClass().addClass("bi "+claseBotonConfirmarRow);
        $editButton.attr("aria-hidden", "true");

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

        $cancelButton = $table.find("tbody tr:last-child td i.bi."+claseBotonEliminarRow);
        $cancelButton.removeClass().addClass("bi "+claseBotonCancelarRow);
        $cancelButton.attr("aria-hidden", "true");
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
        if ( $( "#campoDescripcion").length ) {
            $('#campoDescripcion').attr("disabled", "disabled");
        }
        existenCambiosPendientes = false;
    }

    // recorrido por tipos de control en las columnas
    function updateRow($saveButton, commit) {
        $saveButton.removeClass().addClass('bi '+`${claseBotonEditarRow}`);
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

        $cancelButton = $table.find("tbody tr:last-child td i.bi."+claseBotonCancelarRow);
        $cancelButton.removeClass().addClass("bi "+claseBotonEliminarRow);
        $cancelButton.attr("aria-hidden", "true");
    }
}