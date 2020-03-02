<?php

/**
 * Classe responsavel por fazer os envios dos responses
 */
class Response {

  private $responseBody = [
    'status' => '',
    'response' => ''
  ];

  private function sendResponse($code, $message){
    $response = $this->responseBody;
    $response['status'] = $code;
    $response['response'] = $message;
    
    /**
     * por algum motivo, no front end
     * não esta conseguindo receber as messagens de erros
     * quando o http response é diferente de 200
     * então mandei o http code dentro de status no response
     */
    //http_response_code($code);
    //header('Content-Type: application/json');
    header('Status: ' .$code );
    exit(json_encode($response));

  }

  public function success($message){
    $this->sendResponse(200, $message);
  } 

  public function notFound($message = 'Route not found'){
    $this->sendResponse(404, $message);
  }

  public function unauthorized(){
    $this->sendResponse(401, 'Acesso não permitido');
  }

  public function badRequest($message){
    $this->sendResponse(400, $message);
  }

  public function internalError(){
    $this->sendResponse(500, 'Internal Server Error');
  }
}




