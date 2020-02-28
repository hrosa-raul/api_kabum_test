<?php

require_once 'Response.php';


/**
 * Classe responsavel pela execução das chamadas 
 * e roteamento dos controllers
 */
Class Server{

  protected $controller = 'Auth';
  protected $method = '';
  protected $params = [];
  protected $controllersPath = "app/controllers/";
  protected $extensionControllerFile = "Controller.php";
  
  public function __construct(){
    
    $response = new Response();
    
    $url = self::parseUrl($_GET["url"]);
    
    /**
     * Aqui garantimos que o controller sera chamado com a 
     * primeira letra em maiuscula para prevenir erro no file_exists
     */
    $url[0] = ucfirst($url[0]);

    //Monta o caminho completo do controller
    $fullPathToController = $this->controllersPath . $url[0] . $this->extensionControllerFile;

    //Verificamos se existe o controller na pasta
    if(file_exists($fullPathToController)){
      $this->controller = $url[0].'Controller';
      unset($url[0]);
    } else {
      $response->notFound();
    }

    require_once $fullPathToController;
    $this->controller = new $this->controller;

    //Verificamos se o método existe na classe
    if(isset($url[1]) && method_exists($this->controller, $url[1])){
      $this->method = $url[1];
      unset($url[1]);
    } else {
      $response->notFound();
    }
    
    /**
     * o que restou da variavel $url são possiveis parametros
     * então armazenamos para uso
     */
    $this->params = $url ? array_values($url) : [];
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $this->controller->setRequests($_POST);
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