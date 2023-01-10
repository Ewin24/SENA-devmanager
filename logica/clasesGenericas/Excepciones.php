<?php

class customException extends Exception {
  public function errorMessage() {
    //error message
    $errorMsg = 'Error en la linea '.$this->getLine().' en '.$this->getFile()
    .': <b>'.$this->getMessage().'</b> este es un mensaje de error personalizado';
    return $errorMsg;
  }
}