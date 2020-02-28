<?php

require_once 'Response.php';

/**
 * Classe de base para os controllers
 * aqui vamos deichar algums metodos para facilitar 
 */
Class BaseController{

  public $requests = [];
  public $response;
  public $validation;

  public function __construct(){
    $response = new Response();
    $validation = new Validation();
  }

  public function setRequests($array){
    $this->requests = $array ? array_values($array) : [];
  }



}