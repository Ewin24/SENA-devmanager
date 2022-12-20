<?php
// session_start();
if (!isset($_SESSION['usuario'])) header('location: ../../index.php?mensaje=Ya hay una sesión activa, acceso no autorizado'); //sesiones activas al tiempo
else {
    $USUARIO = unserialize($_SESSION['usuario']);
}

$lista = '';
$identificacion = $USUARIO->getIdentificacion();

$datos = '[';

$resultado = Proyecto::getListaEnObjetos(null, null);
for ($i = 0; $i < count($resultado); $i++)
{
    $proyecto = $resultado[$i];
    $datos .=
        '{ id: "' . $proyecto->getIdProyecto()
        . '", nombre: "' . $proyecto->getNombre()
        . '", descripcion: "' . $proyecto->getDescripcion()
        . '", estado: "' . $proyecto->getEstado()
        . '", fecha_inicio: "' . $proyecto->getFechaInicio()
        . '", fecha_fin: "' . $proyecto->getFechaFinalizacion()
        . '"},';
}
$datos .= ']';

switch ($USUARIO->getTipoUsuario()) {
    case 'A': //muestra todos los proyectos y opciones porque es admin
        // $resultado = Proyecto::getListaEnObjetos(null, null);
        // for ($i = 0; $i < count($resultado); $i++) {

        //     $proyecto = $resultado[$i];
        //     $datos .=
        //         '{ id: "' . $proyecto->getIdProyecto()
        //         . '", nombre: "' . $proyecto->getNombre()
        //         . '", descripcion: "' . $proyecto->getDescripcion()
        //         . '", estado: "' . $proyecto->getEstado()
        //         . '", fechaInicio: "' . $proyecto->getFechaInicio()
        //         . '", fechaFinalizacion: "' . $proyecto->getFechaFinalizacion()
        //         . '"},';

            // $lista .= '<tr>';
            // $lista .= "<td>{$proyecto->getIdProyecto()}</td>";
            // $lista .= "<td>{$proyecto->getNombre()}</td>";
            // $lista .= "<td>{$proyecto->getDescripcion()}</td>";
            // $lista .= "<td>{$proyecto->getEstado()}</td>";
            // $lista .= "<td>{$proyecto->getFechaInicio()}</td>";
            // $lista .= "<td>{$proyecto->getFechaFinalizacion()}</td>";

            // if ($USUARIO->esAdmin($USUARIO->getIdentificacion())) { //esta misma validación se hace para todos, en caso de que sea trabajador se deja que postule o agregue estudios o habilidades
            //     $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoFormulario.php&accion=Modificar&idProyecto={$proyecto->getIdproyecto()}' title='modificar proyecto'> Modificar </a></td>";
            //     $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoCRUD.php&accion=Eliminar&idProyecto={$proyecto->getIdproyecto()}' onclick='eliminar({$proyecto->getIdproyecto()})' title='Eliminar proyecto'>Eliminar</a></td>";
            // }

            // $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoCRUD.php&accion=Postularse&idProyecto={$proyecto->getIdproyecto()}&idUsuario={$USUARIO->getIdentificacion()}' title='Postular a proyecto'>Postularse</a></td>";
            // $lista .= "<td></td>";
            // $lista .= "</tr>";
        // }
        break;

    case 'D': //proyectos de los que es director

        break;

    default:
        //trabajador: proyectos en los que esta activo, o proyectos en los que puede postularse según sus habilidades
        break;
}
?>

<h3 class="text-center">LISTA DE PROYECTOS</h3>
<?php
    if (Usuario::esAdmin($identificacion) || Usuario::esDirector($identificacion)) {
        // deja adicionar si el user es ADMIN o Director
        // echo  '<span><button type="button" class="btn btn-primary"><a href="principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoFormulario.php&accion=Adicionar"></a>Nuevo Proyecto</button></span> ';
        echo  '<button type="button" id="NuevoProyecto" class="btn btn-primary" onclick="crearNuevoProyecto()">Nuevo Proyecto</button></span> ';
    }
?>
<div class="row justify-content-center">
    <div class="col-auto">
        <table id="example" class="table table-responsive table-striped table-borded dataTable-content" cellpacing="0" width="100%"></table>
    </div>
</div>
    
     <!-- <table id="miTablaDatos" class="table table-light table-hover"></table> -->
    <!--    <thead class="thead-light">
            <tr>
                /* FUNCION EN JS PARA ELIMINAR FILAS DE LA TABLA DEPENDIENDO DE UN FILTRO */
                <th> <input id="nombre" type="text" placeholder="Nombre del Proyecto" onkeyup="busquedaColumna(this.id)"> </th>
                <th> <input id="descripcion" type="text" placeholder="Descripcion"></th>
                <th> <input id="estado" type="text" placeholder="Estado del Proyecto"></th>
                <th> <input id="fecha_inicio" type="text" placeholder="Fecha de Inicio"></th>
                <th> <input id="fecha_fin" type="text" placeholder="Fecha de finalizacion"></th> 
            </tr>
        </thead>
    </table> -->


<script type="text/javascript" src="assets/barraBusqueda.js"></script>
<script type="text/javascript">
    
    function editar(boton) {
        var action     = boton.dataset.action,
            row        = boton.closest('tr'),
            row_cloned = row.cloneNode(true);
            // id         = parseInt(row_cloned.id);

        switch ( action ) {
                case 'edit':
                    for (let index=0; index<row.cells.length-2; index++) { // omitir botones de edición
                            row.cells[index].contentEditable = "true";
                        }
                        row.firstChild.focus();
                break;
            }
    }


    function eliminar(id) {
        var respuesta = confirm("Esta seguro de eliminar este registro?");
        if (respuesta) {
            location = "principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoCRUD.php&accion=Eliminar&idEstudio=" + id;
        }
    }

    function crearNuevoProyecto() {
        var t = $('#example').DataTable();
        t.row.add(['<td><input id="N_nombre" type="text" placeholder="Nombre del Proyecto"></td>',,,,]).draw(false);
            // '<td><input id="N_nombre" type="text" placeholder="Nombre del Proyecto"></td>',
            // '<input id="N_descripcion" type="text" placeholder="Descripcion">',
            // '<input id="N_estado" type="text" placeholder="Estado del Proyecto">',
            // '<input id="N_fecha_inicio" type="text" placeholder="Fecha de Inicio">',
            // '<input id="N_fecha_fin" type="text" placeholder="Fecha de finalizacion">',
            // '<button type="button" class="btn-close" aria-label="Close"></button>']).draw(true);
    }
    
    let arreglo = [];
    <?php echo 'const datos = ' . $datos . ';'; ?>
    if (arreglo.length == 0 || arreglo == null){
            arreglo = [...datos];
    }

    //genera_tabla(arreglo);    


    $(document).ready(function () {
        tabla = $('#example').DataTable({
            select: true,
            searching: true,
            ordering: true,
            data: arreglo,
            fields: [
                {   label: "Nombre:",   name: "nombre" },
                {   label: "Descripcion:",  name: "descripcion" },
                {   label: "Estado:",  name: "estado" },
                {
                    label: "Fecha inicio:",
                    name: "fecha_inicio",
                    type: "datetime",
                    format: 'D-M-Y',
                },
                {
                    label: "Fecha finalización:",
                    name: "fecha_fin",
                    type: "datetime",
                    format: 'D-M-Y',
                },
            ],
            columns: [
                {
                    data: null,
                    defaultContent: '',
                    className: 'select-checkbox',
                    orderable: false
                },
                { title: 'Nombre', data: 'nombre' },
                { title: 'Descripcion', data: 'descripcion' },
                { title: 'Estado', data: 'estado' },
                { 
                    title: 'Fecha inicio' , 
                    data: 'fecha_inicio', 
                    defaultContent: '<input type="text" class="datepicker-input" placeholder="Fecha de Inicio">',
                },
                { title: 'Fecha finalización', data: 'fecha_fin', type: "datetime" },
                {
                    data: null,
                    defaultContent: '<button class="bi bi-pencil-square" data-action="edit" onclick="editar(this)"></button>', //'<span class="bi bi-pencil-square tool" onclick="editar()">',
                    className: 'row-edit dt-center',
                    orderable: false
                },
                {
                    data: null,
                    defaultContent: '<button class="bi bi-trash-fill" data-action="delete" onclick="eliminar(this.id)"></button>',
                    className: 'row-remove dt-center',
                    orderable: false
                },
            ],
            buttons: [ {
                    extend: "createInline",
                    editor: this,
                    formOptions: {
                        submitTrigger: -2,
                        submitHtml: '<span class="bi bi-trash-fill"></span>'
                    }
                } ],
                ColumnDefs: [ { "Class": 'ui-datepicker-inline', "Targets": [ 4,5 ] } ],
        //     columnDefs: [
        //             {
        //                 targets: -1,
        //                 data: null,
        //                 defaultContent: '<span class="bi bi-pencil-square"></span><span class="bi bi-trash-fill"></span>',
        //             },
        //         ],
        });

    // // agregar selección de fila https://datatables.net/examples/api/select_single_row.html
    // $('#example tbody').on('click', 'tr', function () {
    //     if ($(this).hasClass('selected')) {
    //         $(this).removeClass('selected');
    //     } else {
    //         tabla.$('tr.selected').removeClass('selected');
    //         $(this).addClass('selected');
    //     }
    // });

    // $('.datepicker-input').datepicker({
    //     dateFormat: 'mm-dd-yy',
    //     onClose: function(dateText, inst) {
    //         $(this).parent().find('.date').focus().html(dateText).blur();
    //     }
    // });

    // // Shows the datepicker when clicking on the content editable div
    // $('.date').click(function() {
    //     $(this).parent().find('.datepicker-input').datepicker("show");
    // });

    const url = "principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoCRUD.php&accion=Modificar&idEstudio=" + id;";
    const updateField = (_id, data, callback) => {
        $.ajax({
            url: `${url}${_id}`,
            data,
            type: 'POST',
            success: (data) => {
                callback(null);
            },
            error: (err) => {
                callback(err);
            }
        });
    };
        
    // https://github.com/FilipeMazzon/Datatable-inline-Edit-Free
    $(document).ready(function () {
        var table = $('#example').DataTable();
        $("#example tbody").on('click', 'td', function () {
            var myCell = table.cell(this);
            var _id = myCell.context[0].aoData[0]._aData[0];
            console.log(_id);
            var column = myCell["0"][0].column;
            var field = myCell.context[0].aoColumns[column].sTitle;
            var data = myCell.data();
            if (data.search('<input') === -1) {
                myCell.data('<input type="text" id="input' + _id + '" value="' + data + '"/>');
                var input = document.getElementById(`input${_id}`);
                input.addEventListener("keyup", function (event) {
                    if (event.key === "Enter") {
                        event.preventDefault();
                        var newData = {};
                        newData[field] = input.value;
                        updateField(_id, newData, (err) => {
                            if (err) alert(err);
                            else {
                                myCell.data(input.value);
                            }
                        });
                    }
                })
            }
        })
    });
    



    // https://datatables.net/forums/discussion/1723/editable-with-datepicker-inside-datatables
    // // select everything when editing field in focus

    /*
    function crearNuevoProyecto() {
        
        var tableRow = document.getElementById("miTablaDatos");
        var row = tableRow.insertRow(0);
        // var rowCount = table.rows.length;
        row.insertCell(0).innerHTML= '<th> <input id="N_nombre" type="text" placeholder="Nombre del Proyecto"> </th>';
        row.insertCell(1).innerHTML= '<th> <input id="N_descripcion" type="text" placeholder="Descripcion"></th>';
        row.insertCell(2).innerHTML= '<th> <input id="N_estado" type="text" placeholder="Estado del Proyecto"></th>';
        row.insertCell(3).innerHTML= '<th> <input id="N_fecha_inicio" type="text" placeholder="Fecha de Inicio"></th>';
        row.insertCell(4).innerHTML= '<th> <input id="N_fecha_fin" type="text" placeholder="Fecha de finalizacion"></th>';
        row.insertCell(5).innerHTML= '<th><button type="button" class="btn-close" aria-label="Close"></button></th>';
    }

    const objFiltroProyectos = {
        nombre: {
          valor: '',
          tipoDato: tiposDatos.TEXT
        },
        descripcion: {
          valor: '',
          tipoDato: tiposDatos.TEXT
        },
        estado: {
          valor: '',
          tipoDato: tiposDatos.TEXT
        },
        fecha_inicio: {
          valor: new Date('1900-01-01'),
          tipoDato: tiposDatos.DATE,
        },
        fecha_fin: {
          valor: new Date('2999-12-31'),
          tipoDato: tiposDatos.DATE,
        },
      };

    function filtrarListaDatos(ListaDatos, objFiltroPantalla, filtro) 
    {
        objFiltroPantalla[filtro.id].valor = filtro.value;

        switch(objFiltroPantalla[filtro.id].tipoDato) {
            case tiposDatos.TEXT:
                return filtrarTexto(ListaDatos, filtro.id, filtro.value);
                break;
            // case tiposDatos.DATE:
            //     const rangoFecha = {
            //         fecha_inicio: filtros.fechaInicio.valor,
            //         fecha_fin: filtros.fechaFin.valor,
            //     };
            //     nuevoArreglo = filtrarFecha(nuevoArray, filtro.id, rangoFecha);
            //     break;
            default:
                return ListaDatos;
        }
    }

    function busquedaColumna(id) {
        const campoBuscar = document.getElementById(id);
        // campoBuscar.addEventListener('change', (e) => filtrarListaDatos(datos, objFiltroProyectos, event.target));

        if ( campoBuscar.nodeName == 'INPUT'){
            const tabla = document.getElementById("miTablaDatos");
            var tableBody = tabla.getElementsByTagName('tbody')[0];
            tabla.removeChild(tableBody);
            arreglo.length = 0;
        }

        arreglo = filtrarListaDatos(datos, objFiltroProyectos, event.target);
        genera_tabla(arreglo);
    }

    // // https://stackoverflow.com/questions/14643617/create-table-using-javascript
    // // // https://linuxhint.com/create-table-from-array-objects-javascript/
    function genera_tabla(arreglo) {

        const tabla = document.getElementById("miTablaDatos");
        // var tableBody = tabla.getElementsByTagName('tbody')[0];
        var tableBody = document.createElement("tbody");
        tableBody.setAttribute('id', 'miBodyDatos');
        tabla.appendChild(tableBody);

        if (arreglo.length == 0 || arreglo == null){
            console.log("no hay datos");
            console.log(arreglo);
            //arreglo = [...datos];
        }

        const tableData = arreglo.map(value => {
        return (
            `<tr>
                <td>${value.nombre}</td>
                <td>${value.descripcion}</td>
                <td>${value.estado}</td>
                <td>${value.fecha_inicio}</td>
                <td>${value.fecha_fin}</td>
            </tr>`);
        }).join('');

        tableBody.innerHTML = tableData;
    }
    */

    });
</script>

