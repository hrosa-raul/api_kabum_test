<?php

require_once './app/models/Clients.php';
require_once './core/BaseController.php';

Class ClientsController extends BaseController {

  //Lista todos os clientes
  public function index(){
    $clients = new Clients();
    $response = $clients->select()->getAll();
    
    $this->response->success($response);
  }

  //prepara os dados do cliente para uma inserção/alteração
  private function getClientData(){
    $clientData = [
      'name' => $this->requests['name'],
      'date_of_birth' => $this->requests['date_of_birth'],
      'cpf' => $this->requests['cpf'],
      'rg' => $this->requests['rg'],
    ];
    
    if(isset($this->requests['phone'])){
      $clientData['phone'] = $this->requests['phone'];
    }

    return $clientData;
  }

  public function insert(){
    $this->validation->validate('name')->required();
    $this->validation->validate('date_of_birth')->required();
    $this->validation->validate('cpf')->required();
    $this->validation->validate('rg')->required();

    //retorna caso ouve algum erro de validação
    if($this->validation->hasError()){
      $this->response->badRequest(
        $this->validation->getFirstError()
      );
    }

    $clients = new Clients();
    $id = $clients->insert($this->getClientData());
    
    $newClient = $clients->select()->where(array(['id', '=', $id]))->get();

    $this->response->success($newClient);
  }

  //Altera um cliente
  public function update($id_client){
    $this->validation->validate('name')->required();
    $this->validation->validate('date_of_birth')->required();
    $this->validation->validate('cpf')->required();
    $this->validation->validate('rg')->required();

    //retorna caso ouve algum erro de validação
    if($this->validation->hasError()){
      $this->response->badRequest(
        $this->validation->getFirstError()
      );
    }

    $clients = new Clients();
    
    if(!$clients->findClient($id_client)){
      $this->response->notFound('Cliente não encontrado');
    }

    $clients->update($id_client, $this->getClientData());

    $client = $clients->findClient($id_client);

    $this->response->success($client);
  }

  //exclui um cliente
  public function delete($id_client){
    $clients = new Clients();
    
    $client = $clients->findClient($id_client);
         
    if(!$client){
      $this->response->notFound('Cliente não encontrado');
    }

    $clients->delete($id_client);

    $this->response->success('Cliente exclúido');
  }

}