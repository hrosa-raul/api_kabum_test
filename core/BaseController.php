<?php

require_once 'Response.php';
require_once 'Validation.php';

/**
 * Classe de base para os controllers
 * aqui vamos deichar algums metodos para facilitar 
 */
Class BaseController{

  public $requests = [];
  public $response;
  public $validation;

  public function __construct(){
    $this->response = new Response();
  }

  /**
   * Preparamos os parametros vindo do post
   * e colocamos todos os campos já preparados para validações
   */
  public function setRequests($array){
    $this->requests = $array;
    $this->validation = new Validation($this->requests);
  }
}