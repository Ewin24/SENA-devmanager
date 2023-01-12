<?php

$bandera = upload::subirPdf();
// if (!$bandera == false) {
//     header("location: index.php?mensaje=$bandera");
// }
class upload
{
    public static function subirPdf()
    {
        // Crea la carpeta para almacenar los archivos PDF si no existe
        if (!is_dir('pdfs')) {
            mkdir('pdfs');
        }

        // Comprueba si se ha enviado el archivo
        if (isset($_FILES['pdf'])) {
            // Abre un recurso finfo
            // $finfo = finfo_open(FILEINFO_MIME_TYPE);
            // // // Obtiene el tipo de archivo real del archivo subido
            // $realType = finfo_file($finfo, $_FILES['pdf']['tmp_name']);
            // // Cierra el recurso finfo
            // finfo_close($finfo);
            // // Comprueba si el tipo de archivo es válido
            // if ($realType == 'application/pdf') {
            // Asigna un nombre único al archivo
            $fileName = uniqid() . '.pdf';
            // Mueve el archivo a la carpeta pdfs
            move_uploaded_file($_FILES['pdf']['tmp_name'], 'pdfs/' . $fileName);
            // Muestra un mensaje de éxito
            $mensaje = 'pdfs/' . $fileName;
            // } else {
            //     // El archivo no es un PDF válido, muestra un mensaje de error
            //     echo 'Error: solo se permiten archivos PDF';
            //     $mensaje = false;
            // }
            return $mensaje;
        }
    }

    public static function mostrarPdf($fileName)
    {
        $ruta = $fileName;
        //echo $ruta;
        // Obtiene el contenido del archivo PDF
        $file = file_get_contents($ruta);
        // Codifica el contenido del archivo en base64
        $encoded = base64_encode($file);
        // Muestra el archivo PDF en una etiqueta <iframe>
        return '<iframe src="data:application/pdf;base64,' . $encoded . '" style="width:100%;height:500px;"></iframe>';
    }
}
