<?php
require_once '../logica/clasesGenericas/ConectorBD.php';
require_once '../logica/clases/Usuario.php';
//require_once '../logica/clases/TipoUsuario.php';

$usuario = $_REQUEST['usuario'];
$clave = $_REQUEST['clave'];
$usuario = Usuario::validar($usuario, $clave);

if ($usuario == null) header('location: ../index.php?mensaje=Usuario o contraseña no valida'); //hacemos de de nuevo una redireccion a index
else {
    session_start();
    $_SESSION['usuario'] = serialize($usuario);
    header('location: ../principal.php?CONTENIDO=presentacion/inicio.php');
}

// https://www.delftstack.com/howto/php/php-uuid/
function v4_UUID() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      // 32 bits for the time_low
      mt_rand(0, 0xffff), mt_rand(0, 0xffff),
      // 16 bits for the time_mid
      mt_rand(0, 0xffff),
      // 16 bits for the time_hi,
      mt_rand(0, 0x0fff) | 0x4000,

      // 8 bits and 16 bits for the clk_seq_hi_res,
      // 8 bits for the clk_seq_low,
      mt_rand(0, 0x3fff) | 0x8000,
      // 48 bits for the node
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
  }