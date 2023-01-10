
# Actualizar con respecto a repo Edwin primera vez

1. Seleccionar rama master
`git checkout master`

2. Agregar el repositorio de Edwin en aguas arriba de mi repo
`git remote add upstream https://github.com/Ewin24/SENA-devmanager.git`

3. Obtener la última versión desde el repositorio upstream (Edwin)
`git fetch upstream`

4. Asegurar que mi master, tenga la última versión de upstream (Edwin)
`git rebase upstream/master`

5. Empujar mi última versión de mi repo, hacia Github luego de actualizar con Upstream
`git push -f origin master`

### Ajustes realizados
1. \logica\clases\Usuario.php 
   - ajustar las sentencias sql según la nueva estructura de la base
   - Ajustar las columnas a nombre_usuario, tipo_usuario
- \control\validar.php - Undefined array key "usuario" in C:\xampp\htdocs\SENA-devmanager\control\validar.php on line 6
- logica\clases\Proyecto.php - linea 196, ajustar la consulta para evitar error de información faltante en la carga de los datos de la vista (columna id)

2. presentacion\vistas\proyectos.php - **tener en cuenta esto para todas las vistas que usen datatables**
- para los campos con **control ddl** como el caso de estado, debe existir un arreglo con los valores a llenar (**ej: ddl_estado_ops**)
- si se cambio el nombre o existe mas de un **control ddl**, deben existe más de un arreglo para llenar el control ddl en el estádo de edición. 

- los botones de acción **edición** deben ser siempre `bi-pencil-square`
- los botones de acción **eliminación** deben ser siempre `bi-trash`
- los botones de acción **confirmación** deben ser siempre `bi-check-square`
- los botones de acción **cancelación** deben ser siempre `bi-x-square`

**IMPORTANTE!!!** :warning:
- Para id de tablas siempre usar prefijo **tbl** seguido de nombre en plural (ej: tblProyectos)
- Para id de **Plantilla de Nuevo registro**, usar prefijo **new-**, nombre en singular del objeto (ej: new-Proyecto)

Recordar que la estructura de la **Plantilla de Nuevo registro**, debe ir dentro de una tabla como se muestra en el siguiente ejemplo:

``` html
<table id="new-Proyecto" style="display:none" class="col-auto">
      <tbody>
         <tr>
            <td></td>
            <td>__id__</td>
            <td>__nombre__</td>
            <td>__descripcion__</td>
            <td>__estado__</td>
            <td>01/01/1900</td>
            <td>01/01/2099</td>
            <td>
                  <i class='bi '+`${claseBotonEditarRow}` aria-hidden="true"></i>
                  <i class='bi '+`${claseBotonEliminarRow}` aria-hidden="true"></i>
            </td>
         </tr>
      </tbody>
</table>
```
**Aclaraciones:**
- La primera columma en el ejemplo se encuentra vacía en la plantilla, pues es un ejemplo en el que la primera columna de la tabla se usa con un control checkbox. Si no es su caso, puede omitir esa celda en la plantilla.
- la última columna debe conservar la clase **'bi '+`${claseBotonEditarRow}`**

### Tabla genérica
Para invocar el metodo de crear tabla genérica, se tienen los parametros

cargarTablaGenerica(nombreTabla, arreglo, cols, ddl_estado_ops = [], campo_desc = false)
- **Obligatorios**: nombreTabla, arreglo, cols
- **Opcionales**: 
   - ddl_estado_ops: una estructura para llenar un campo ddl en una columna de la tabla
   - campo_desc: Si se quiere que la tabla cuente con campo descripción (siempre se requiere que el campo de la base de datos, tenga nombre **descripcion**)

## Trabajando con código

- clonar repo
- Instalar Xampp
- Correr xampp control y ejecutar mysql y apache
- Crear la base de datos devmanager2 (usando administrador de BD)
- Ejecutar Script nuevoModelo.sql
- Ubicar código fuente en htdocs (Xampp) (C:\xampp\htdocs\SENA-devmanager)
- Acceder desde navegador a http://localhost/SENA-devmanager/

### Ejecutar el proyecto


### Ejemplo de Ajax con datatable y PHP
* [CRUD con Datatables y PHP](https://www.nicesnippets.com/blog/php-datatables-crud-example)
* [Ejemplo de DataTable, Ajax, PHP y MySQL](https://evilnapsis.com/2022/09/19/ejemplo-de-datatable-ajax-php-y-mysql/)


## Referencias adicionales 
* insert a test row http://jsfiddle.net/55rfa8zb/1/
* https://datatables.net/forums/discussion/28186/how-to-add-a-row-in-an-editable-table-and-keep-all-the-html-attributes
* https://codepen.io/quanghuy1294/pen/OgNELB
* https://github.com/FilipeMazzon/Datatable-inline-Edit-Free
* agregar selección de fila https://datatables.net/examples/api/select_single_row.html
* select everything when editing field in focus https://datatables.net/forums/discussion/1723/editable-with-datepicker-inside-datatables
* https://stackoverflow.com/questions/14643617/create-table-using-javascript
* https://linuxhint.com/create-table-from-array-objects-javascript/
* generic crud draft datatebles http://jsfiddle.net/awbq0p4e/
* aprendiendo sobre jqeury datatables https://github.com/schoolofnetcom/jquery-datatables
* datatables con server processing https://phppot.com/demo/datatables-server-side-processing-using-php-with-mysql/
* crud básico con html y datatables https://github.com/tutsmake/crud-datatables-php-mysql-jquery-ajax-bootstrap-github/blob/main/index.php
* multiselección en datatables http://live.datatables.net/vamulagu/2/edit
* DataTables CRUD Operations using PHP Example https://www.nicesnippets.com/blog/php-datatables-crud-example
*  CRUD de datos usando dataTables, Bootstrap, PHP y MySQL https://obedalvarado.pw/blog/crud-de-datos-usando-datatables-bootstrap-php-y-mysql/