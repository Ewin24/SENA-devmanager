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




## Trabajando con código

- clonar repo
- Instalar Xampp
- Correr xampp control y ejecutar mysql y apache
- Crear la base de datos devmanager2 (usando administrador de BD)
- Ejecutar Script nuevoModelo.sql
- Ubicar código fuente en htdocs (Xampp) (C:\xampp\htdocs\SENA-devmanager)
- Acceder desde navegador a http://localhost/SENA-devmanager/



### Ejecutar el proyecto
