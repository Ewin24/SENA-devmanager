<?php

$bandera = upload::subirPdf();
// if (!$bandera == false) {
//     header("location: index.php?mensaje=$bandera");
// }
class upload
{

    public static function subirArchivo(){

        // // Tipos de arhivos permitidos
        $ext_permitidas = array('pdf','doc','docx','jpg','png','jpeg'); 

        // // respuesta json por defecto 
        $response = array( 
            'data' => null,
            'status' => 0, 
            'message' => 'La carga del archivo ha fallado, intente nuevamente.' 
        ); 

        $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

        // $datos = '';
        // // If form is submitted 
        // if(isset($_FILES[$tipo_file]))
        // {
            
            switch($ext){
                case 'pdf':
                case 'doc':
                case 'docx':
                    $carpeta_servidor = 'pdfs/';
                    break;

                case 'jpg':
                case 'jpeg':
                case 'png':
                    $carpeta_servidor = 'imgs/';
                    break;

                default:
                    // caeran en 'archivos_usuarios/'
                    break;
            }
            
            // Crea la carpeta para almacenar los archivos PDF si no existe
            if (!is_dir($carpeta_servidor)) {
                mkdir($carpeta_servidor);
            }

            // echo "hola";
            // Upload file 
            if(!empty($_FILES['file']["name"])){
                // File path config
                $fileName = basename($_FILES['file']["name"]); 
                // get uploaded file's extension
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $ruta_destino = "$carpeta_servidor".uniqid().".$ext"; 

                // echo 'hola', $ruta_destino;
                // Allow certain file formats to upload 
                if(in_array($ext, $ext_permitidas)){ 

                    // Upload file to the server 
                    $result = move_uploaded_file($_FILES['file']["tmp_name"], $ruta_destino);
                    // echo $ext, $result;

                    if($result){ 
                        $response['data'] = $ruta_destino; 
                        $response['status'] = 1;
                        $response['message'] = 'El proceso de carga al servidor ha sido exitoso';
                    }else{ 
                        $response['status'] = 0; 
                        $response['message'] = 'Ha ocurrido un error en la carga del archivo'; 
                    } 
                }
                else{
                    $response['status'] = 0; 
                    $response['message'] ='Solo las extensiones'.implode('/', $ext_permitidas).' son permitidas para cargar.'; 
                }
            }
            // Si se deseara se haría inserción a tabla en BD
        return $response;
    }

    public static function subirPdf($tipo_file='pdf', $carpeta_documentos = 'pdfs/')
    {
        // Crea la carpeta para almacenar los archivos PDF si no existe
        if (!is_dir($carpeta_documentos)) {
            mkdir($carpeta_documentos);
        }

        // Comprueba si se ha enviado el archivo
        if (isset($_FILES[$tipo_file])) {
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
            move_uploaded_file($_FILES[$tipo_file]['tmp_name'], 'pdfs/' . $fileName);
            // Muestra un mensaje de éxito
            $mensaje = $carpeta_documentos . $fileName;
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
