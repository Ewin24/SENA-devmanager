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
        //$resultado = Proyecto::getListaEnObjetos(null, null);
        // for ($i = 0; $i < count($resultado); $i++) {

        //     $proyecto = $resultado[$i];
        //     echo $proyecto;
        //     $datos .=
        //         '{ id: "' . $proyecto->getIdProyecto()
        //         . '", nombre: "' . $proyecto->getNombre()
        //         . '", descripcion: "' . $proyecto->getDescripcion()
        //         . '", estado: "' . $proyecto->getEstado()
        //         . '", fechaInicio: "' . $proyecto->getFechaInicio()
        //         . '", fechaFinalizacion: "' . $proyecto->getFechaFinalizacion()
        //         . '"},';

        //     $lista .= '<tr>';
        //     $lista .= "<td>{$proyecto->getIdProyecto()}</td>";
        //     $lista .= "<td>{$proyecto->getNombre()}</td>";
        //     $lista .= "<td>{$proyecto->getDescripcion()}</td>";
        //     $lista .= "<td>{$proyecto->getEstado()}</td>";
        //     $lista .= "<td>{$proyecto->getFechaInicio()}</td>";
        //     $lista .= "<td>{$proyecto->getFechaFinalizacion()}</td>";

        //     if ($USUARIO->esAdmin($USUARIO->getIdentificacion())) { //esta misma validación se hace para todos, en caso de que sea trabajador se deja que postule o agregue estudios o habilidades
        //         $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoFormulario.php&accion=Modificar&idProyecto={$proyecto->getIdproyecto()}' title='modificar proyecto'> Modificar </a></td>";
        //         $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoCRUD.php&accion=Eliminar&idProyecto={$proyecto->getIdproyecto()}' onclick='eliminar({$proyecto->getIdproyecto()})' title='Eliminar proyecto'>Eliminar</a></td>";
        //     }

        //     $lista .= "<td><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoCRUD.php&accion=Postularse&idProyecto={$proyecto->getIdproyecto()}&idUsuario={$USUARIO->getIdentificacion()}' title='Postular a proyecto'>Postularse</a></td>";
        //     $lista .= "<td></td>";
        //     $lista .= "</tr>";
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
<!-- <script type="text/javascript" src="librerias/datatable-inline-edit.js"></script> -->
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
    console.log(datos);
    if (arreglo.length == 0 || arreglo == null){
            arreglo = [...datos];
    }

    //genera_tabla(arreglo);    

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
        
    
    $(document).ready(function () {
        tabla = $('#example').DataTable({
            select:{style:'single'},
            searching: true,
            ordering: true,
            data: arreglo,
            createdRow:function(row){
                $(".datecell", row).datepicker();
            },
            fields: [
                {   label: "id:",   name: "id" },
                {   label: "Nombre:",   name: "nombre" },
                {   label: "Descripcion:",  name: "descripcion" },
                {   label: "Estado:",  name: "estado" },
                {
                    label: "Fecha inicio:",
                    name: "fecha_inicio",
                    type:  'datetime',
                    def:   function () { return new Date(); },
                    format: 'D-M-Y',
                },
                {
                    label: 'Fecha finalización:',
                    name: 'fecha_fin',
                    type:  'datetime',
                    def:   function () { return new Date(); },
                    format: 'D-M-Y',
                },
            ],
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

    // https://github.com/FilipeMazzon/Datatable-inline-Edit-Free
    $(document).ready(function () {
        var table = $('#example').DataTable();
        $("#example tbody").on('click', 'td', function () {
            var celda = table.cell(this);
            var celda_data = celda.context[0].aoData[0]._aData[0];
            console.log(this, celda, celda_data);
            var columna = celda["0"][0].column;
            var campoId = celda.context[0].aoColumns[1];//.data();
            var test = campoId.data();
            var campo = celda.context[0].aoColumns[columna].sTitle;
            var className = celda.context[0].aoColumns[columna].className
            var data = celda.data();
            if (data.search('<input') === -1) {
                if(className==="datecell"){
                    celda.data('<input type="text" class="datecell" id="input' + celda_data + '" value="' + data + '"/>');
                    $(".datecell", celda).datepicker();    
                }else{
                    celda.data('<input type="text" id="input' + celda_data + '" value="' + data + '"/>');
                }
                var input = document.getElementById(`input${celda_data}`);
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

    



    // https://datatables.net/forums/discussion/1723/editable-with-datepicker-inside-datatables
    // // select everything when editing field in focus

    

    // // https://stackoverflow.com/questions/14643617/create-table-using-javascript
    // // // https://linuxhint.com/create-table-from-array-objects-javascript/
    
</script>

