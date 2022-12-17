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