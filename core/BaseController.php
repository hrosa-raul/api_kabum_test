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
  public $isPrivate = true;
  private $hash = 'fT5ghE';

  public function __construct(){
    $this->response = new Response();
  }
  
  public function validateToken($token){
    if(md5($this->hash) != $token){
      $this->response->unauthorized();
    }
  }

  public function getHash(){
    return $this->hash;
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