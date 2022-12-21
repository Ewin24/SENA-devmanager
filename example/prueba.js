var dataUrl = 'http://www.json-generator.com/api/json/get/ccTtqmPbkO?indent=2';
var options = [
  { key : 'option 1', value : 1 },
  { key : 'option 2', value : 2 },
  { key : 'option 3', value : 3 }
];

$(document).ready(function() {
  var $table = $('#example');
  var dataTable = null;

  $table.on('mousedown', 'td .fa.fa-minus-square', function(e) {
    dataTable.row($(this).closest("tr")).remove().draw();
  });

  $table.on('mousedown.edit', 'i.fa.fa-pencil-square', function(e) {
    enableRowEdit($(this));
  });

  $table.on('mousedown', 'input', function(e) {
    e.stopPropagation();
  });

  $table.on('mousedown.save', 'i.fa.fa-envelope-o', function(e) {
    updateRow($(this), true); // Pass save button to function.
  });

  $table.on('mousedown', '.select-basic', function(e) {
    e.stopPropagation();
  });

  dataTable = $table.DataTable({
    ajax: dataUrl,
    rowReorder: {
      dataSrc: 'order',
      selector: 'tr'
    },
    columns: [{
      data: 'order'
    }, {
      data: 'place'
    }, {
      data: 'name'
    }, {
      data: 'delete'
    }]
  });

  $table.css('border-bottom', 'none')
        .after($('<div>').addClass('addRow')
          .append($('<button>').attr('id', 'addRow').text('Add New Row')));

  // Add row
  $('#addRow').click(function() {
    var $row = $("#new-row-template").find('tr').clone();
    dataTable.row.add($row).draw();
    // Toggle edit mode upon creation.
    enableRowEdit($table.find('tbody tr:last-child td i.fa.fa-pencil-square'));
  });

  $('#btn-save').on('click', function() {
    updateRows(true); // Update all edited rows
  });

  $('#btn-cancel').on('click', function() {
    updateRows(false); // Revert all edited rows
  });

  function enableRowEdit($editButton) {
    $editButton.removeClass().addClass("fa fa-envelope-o");
    var $row = $editButton.closest("tr").off("mousedown");

    $row.find("td").not(':first').not(':last').each(function(i, el) {
      enableEditText($(this))
    });

    $row.find('td:first').each(function(i, el) {
      enableEditSelect($(this))
    });
  }
  
  function enableEditText($cell) {
    var txt = $cell.text();
    $cell.empty().append($('<input>', {
      type : 'text',
      value : txt
    }).data('original-text', txt));
  }

  function enableEditSelect($cell) {
    var txt = $cell.text();
    $cell.empty().append($('<select>', {
      class : 'select-basic'
    }).append(options.map(function(option) {
      return $('<option>', {
        text  : option.key,
        value : option.value
      })
    })).data('original-value', txt));
}

  function updateRows(commit) {
     $table.find('tbody tr td i.fa.fa-envelope-o').each(function(index, button) {
      updateRow($(button), commit);
    });
  }

  function updateRow($saveButton, commit) {
    $saveButton.removeClass().addClass('fa fa-pencil-square');
    var $row = $saveButton.closest("tr");

    $row.find('td').not(':first').not(':last').each(function(i, el) {
      var $input = $(this).find('input');
      $(this).text(commit ? $input.val() : $input.data('original-text'));
    });

    $row.find('td:first').each(function(i, el) {
      var $input = $(this).find('select');
      $(this).text(commit ? $input.val() : $input.data('original-value'));
    });
  }
});