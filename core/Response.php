<?php

/**
 * Classe responsavel por fazer os envios dos responses
 */
class Response {

  private $responseBody = [
    'status' => '',
    'response' => ''
  ];

  private function sendResponse($code = 200, $message){
    $response = $this->responseBody;
    $response['status'] = $code;
    $response['response'] = $message;

    http_response_code($code);
    echo json_encode($response);
    exit;
  }

  public function notFound(){
    $this->sendResponse(404, 'Route not found');
  }


}




