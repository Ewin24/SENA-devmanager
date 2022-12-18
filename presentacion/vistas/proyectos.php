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
        . '", fechaInicio: "' . $proyecto->getFechaInicio()
        . '", fechaFinalizacion: "' . $proyecto->getFechaFinalizacion()
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
<!-- <div class="container mt-12"> -->
    <table id="miTablaDatos" class="table table-light table-hover">
        <thead class="thead-light">
            <tr>
                <!-- FUNCION EN JS PARA ELIMINAR FILAS DE LA TABLA DEPENDIENDO DE UN FILTRO -->
                <th> <input id="nombre" type="text" placeholder="Nombre del Proyecto" onkeyup="busquedaColumna(this.id)"> </th>
                <th> <input id="descripcion" type="text" placeholder="Descripcion"></th>
                <th> <input id="estado" type="text" placeholder="Estado del Proyecto"></th>
                <th> <input id="fecha_inicio" type="text" placeholder="Fecha de Inicio"></th>
                <th> <input id="fecha_fin" type="text" placeholder="Fecha de finalizacion"></th>
                <?php
                    if (Usuario::esAdmin($identificacion) || Usuario::esDirector($identificacion)) {
                        // deja adicionar si el user es ADMIN o Director
                        echo  "<th><a href='principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoFormulario.php&accion=Adicionar'>Adicionar</a></th>";
                    }
                ?>
            </tr>
        </thead>
        <tbody>
        <!-- <tbody id="mostrarDatosJavascript">  -->
        <div id="mostrarDatosJavascript"></div>
            <!-- < ?php
                if (count($resultado) == 0) {
                    echo 'No se encontraron proyectos registrados'; //validar si se encontraron resultados
                }
                else
                {
                    echo '<div id="myDynamicBody"></div>';
                }
            ?> -->
        </tbody>
    </table>
<!-- </div> -->

<script type="text/javascript" src="assets/barraBusqueda.js"></script>
<script type="text/javascript">
    function eliminar(id) {
        var respuesta = confirm("Esta seguro de eliminar este registro?");
        if (respuesta) {
            location = "principal.php?CONTENIDO=presentacion/configuracion/proyecto/proyectoCRUD.php&accion=Eliminar&idEstudio=" + id;
        }
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
        arreglo.length = 0;
        var tBody = document.getElementById("mostrarDatosJavascript");
        tBody.innerHTML = '';
        arreglo = filtrarListaDatos(datos, objFiltroProyectos, event.target);
        pintarTabla();
    }

    function pintarTabla(){
        if (arreglo.length == 0 || arreglo == null){
            arreglo = [...datos];
            console.log("no hay datos");
        }
        genera_tabla(arreglo);
    }

    // function genera_tabla(arreglo) {

    //     var tabla = document.getElementById("miTablaDatos");
    //     var tBody = document.getElementById("mostrarDatosJavascript");

    //     // Crea las celdas
    //     for (var i = 0; i < arreglo.length; i++) {
    //         // Crea las hileras de la tabla
    //         var hilera = document.createElement("tr");

    //         for (var j = 0; j < arreglo[i].length; j++) {
    //         // Crea un elemento <td> y un nodo de texto, haz que el nodo de
    //         // texto sea el contenido de <td>, ubica el elemento <td> al final
    //         // de la hilera de la tabla
    //         var celda = document.createElement("td");
    //         var textoCelda = document.createTextNode(arreglo[i].valor);
    //         celda.appendChild(textoCelda);
    //         hilera.appendChild(celda);
    //         }

    //         // agrega la hilera al final de la tabla (al final del elemento tbody)
    //         tBody.appendChild(hilera);
    //     }

    //     // posiciona el <tbody> debajo del elemento <table>
    //     tabla.appendChild(tBody);
        
    //     // appends <table> into <body>
    //     // tableBody.appendChild(tabla);
    //     // modifica el atributo "border" de la tabla y lo fija a "2";
    //     miTablaDatos.setAttribute("border", "2");
    // }

    // // https://stackoverflow.com/questions/14643617/create-table-using-javascript
    // // https://linuxhint.com/create-table-from-array-objects-javascript/
    function genera_tabla(arreglo) {
        var tableBody = document.getElementById("mostrarDatosJavascript");

        tableBody.insertAdjacentHTML('afterend',
        `<table><tr>
            <TD>${arreglo.map(e=>Object.values(e).join('<TD>')).join('<tr><TD>')}
        </table>`)
        // `<table><tr>
        //     <th>${Object.keys(arreglo[0]).join('<th>')}
        //     </th>
        //     <tr>
        //     <TD>${arreglo.map(e=>Object.values(e).join('<TD>')).join('<tr><TD>')}
        // </table>`)

    //     // for (var i = 0; i < arreglo.length; i++) {
    //     //     var tr = document.createElement('TR');
    //     //     tableBody.appendChild(tr);

    //     //     for (var j = 0; j < arreglo[i].length; j++) {
    //     //         var td = document.createElement('TD');
    //     //         td.width = '75';
    //     //         td.appendChild(document.createTextNode(arreglo[i].valor));
    //     //         tr.appendChild(td);
    //     //     }
    //     // }
    //     //tableBody.appendChild(table);
    }

    let arreglo = [];
    <?php echo 'const datos = ' . $datos . ';'; ?>
    pintarTabla()

</script>