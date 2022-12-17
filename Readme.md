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

## Trabajando con código

- clonar repo
- Instalar Xampp
- Correr xampp control y ejecutar mysql y apache
- Crear la base de datos devmanager2 (usando administrador de BD)
- Ejecutar Script nuevoModelo.sql
- Ubicar código fuente en htdocs (Xampp) (C:\xampp\htdocs\SENA-devmanager)
- Acceder desde navegador a http://localhost/SENA-devmanager/

### Ajustes realizados
1. \logica\clases\Usuario.php 
   - ajustar las sentencias sql según la nueva estructura de la base
   - Ajustar las columnas a nombre_usuario, tipo_usuario
- \control\validar.php - Undefined array key "usuario" in C:\xampp\htdocs\SENA-devmanager\control\validar.php on line 6

### Ejecutar el proyecto
