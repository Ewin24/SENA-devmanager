<?php

$proyectos = Proyecto::getListaEnJson(null,null);
$empresas = Empresa::getListaEnJson(null,null);
$usuarios = Usuario::getListaEnJson(null,null);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Reporte de tabla</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>

    <script type="text/javascript">
        function cargarProyectos() {
            $(document).ready(function() {
                var proyectos = <?php echo $proyectos; ?>;
                $('#proyectos').DataTable({
                    data: proyectos,
                    columns: [
                        {title: "ID",data: "id"},
                        {title: "Nombre",data: "nombre"},
                        {title: "Descripcion",data: "descripcion"},
                        {title: "Estado",data: "estado"},
                        {title: "Fecha inicio",data: "fecha_inicio"},
                        {title: "Fecha Fin",data: "fecha_fin"},
                        {title: "Correo director",data: "correo_director"}
                    ],
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf'
                    ]
                });
            });
        }

        function cargarEmpresas() {
            $(document).ready(function() {
                var empresas = <?php echo $empresas; ?>;
                $('#empresas').DataTable({
                    data: empresas,
                    columns: [
                        {title: "Nit",data: "nit"},
                        {title: "Nombre",data: "nombre"},
                        {title: "Direccion",data: "direccion"},
                        {title: "Correo Empresa",data: "correo"},
                        {title: "Nombre representante",data: "nombre_representante"},
                        {title: "Correo representante",data: "correo_representante"}
                    ],
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf'
                    ]
                });
            });
        }

        function cargarUsuarios() {
            $(document).ready(function() {
                var usuarios = <?php echo $usuarios; ?>;
            console.log(usuarios)

                $('#usuarios').DataTable({
                    data: usuarios,
                    columns: [
                        {title: "Identificacion",data: "identificacion"},
                        {title: "Nombre",data: "nombres"},
                        {title: "Apellidos",data: "apellidos"},
                        {title: "Correo",data: "correo"},
                        {title: "Telefono",data: "telefono"},
                        {title: "Tipo Usuario",data: "tipo_usuario"},
                        {title: "Empresa donde trabaja",data: "id_empresa"}
                    ],
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf'
                    ]
                });
            });
        }

        cargarProyectos();
        cargarEmpresas();
        cargarUsuarios();
    </script>
</head>

<body>
    <h2 class="m-2">Reportes de proyectos</h2>
    <table id="proyectos" class="display" style="width:100%"></table>
    <hr>
    <h2 class="m-2">Reportes de empresas</h2>
    <table id="empresas" class="display" style="width:100%"></table>
    <hr>
    <h2 class="m-2">Reportes de usuarios</h2>
    <table id="usuarios" class="display" style="width:100%"></table>

</body>

</html>