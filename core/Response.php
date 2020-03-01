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
    
    http_response_code($code);
    echo json_encode($response);
    exit;
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




