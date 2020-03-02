<?php

require_once 'Response.php';


/**
 * Classe responsavel pela execução das chamadas 
 * e roteamento dos controllers
 */
Class Server{

  private $controller = 'Auth';
  private $method = 'index';
  private $params = [];
  private $controllersPath = "app/controllers/";
  private $extensionControllerFile = ".php";
  
  public function __construct(){
    
    $response = new Response();
    
    $url = self::parseUrl($_GET["url"]);
    
    /**
     * Aqui garantimos que o controller sera chamado com a 
     * primeira letra em maiuscula para prevenir erro no file_exists
     */
    $this->controller = ucfirst($url[0]).'Controller';
    $this->method = isset($url[1]) ? $url[1] : $this->method;

    //Monta o caminho completo do controller
    $fullPathToController = $this->controllersPath . $this->controller . $this->extensionControllerFile;
    
    //Verificamos se existe o controller na pasta
    if(file_exists($fullPathToController)){
      unset($url[0]);
    } else {
      $response->notFound();
    }

    require_once $fullPathToController;
    $this->controller = new $this->controller;

    //Verificamos se o método existe na classe
    if(method_exists($this->controller, $this->method)){
      unset($url[1]);
    }
    
    /**
     * o que restou da variavel $url são possiveis parametros
     * então armazenamos para uso
     */
    $this->params = $url ? array_values($url) : [];
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      // Read the input stream
      $body = file_get_contents("php://input");
      
      // Decode the JSON object
      $object = json_decode($body, true);
      
      $this->controller->setRequests($object);
    }

    /**
     * se for uma rota privada tera que validar o token
     */
    if($this->controller->isPrivate){
      $this->controller->validateToken(@$_SERVER['HTTP_TOKEN']);
    }

    call_user_func_array([$this->controller, $this->method], $this->params);
  }

  /**
   * Faz o parse da url chamada
   */
  private function parseUrl($url){
    return explode('/', filter_var(trim($url), FILTER_SANITIZE_URL));
  }
}