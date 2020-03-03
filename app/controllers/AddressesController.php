<?php

require_once './app/models/Addresses.php';
require_once './core/BaseController.php';


Class AddressesController extends BaseController {
  
  public function __construct(){
    $this->isPrivate = false;
    parent::__construct();
  }

  public function all($client_id){
    $addresses = new Addresses();

    $response = $addresses->select()
    ->where(array(['client_id', '=', (int)$client_id]))
    ->getAll();
      
    $this->response->success($response);
  }

  
  public function find($addres_id){
    $addresses = new Addresses();
    $address = $addresses->findAddress($addres_id);

    if(!$address){
      $this->response->notFound('Endereço não encontrado');
    }else{
      $this->response->success($address);
    }
  }

  private function getAddressData($client_id){
    $addressData = [
      'cep' => $this->requests['cep'],
      'street' => trim($this->requests['street']),
      'district' => trim($this->requests['district']),
      'city' => trim($this->requests['city']),
      'state' => trim($this->requests['state']),
    ];
    
    if(isset($this->requests['number']) && $this->requests['number'] !== ''){
      $addressData['number'] = $this->requests['number'];
    }

    if(isset($this->requests['complement']) && $this->requests['complement'] !== ''){
      $addressData['complement'] = $this->requests['complement'];
    }

    if($client_id !== null){
      $addressData['client_id'] = (int)$client_id;
    }
    

    return $addressData;
  }

  public function insert($client_id){
    $this->validation->validate('cep')->required();
    $this->validation->validate('street')->required();
    $this->validation->validate('district')->required();
    $this->validation->validate('city')->required();
    $this->validation->validate('state')->required();

    //retorna caso ouve algum erro de validação
    if($this->validation->hasError()){
      $this->response->badRequest(
        $this->validation->getFirstError()
      );
    }

    $addresses = new Addresses();
    $id = $addresses->insert($this->getAddressData($client_id));
    
    $newAddress = $addresses->select()->where(array(['id', '=', $id]))->get();

    $this->response->success($newAddress);

  }

  
  public function update($address_id){
    $this->validation->validate('cep')->required();
    $this->validation->validate('street')->required();
    $this->validation->validate('district')->required();
    $this->validation->validate('city')->required();
    $this->validation->validate('state')->required();

    //retorna caso ouve algum erro de validação
    if($this->validation->hasError()){
      $this->response->badRequest(
        $this->validation->getFirstError()
      );
    }
    
    $addresses = new Addresses();

    if(!$addresses->findAddress($address_id)){
      $this->response->notFound('Cliente não encontrado');
    }

    $addresses->update($address_id, $this->getAddressData(null));

    $address = $addresses->findAddress($address_id);

    $this->response->success($address);
  }

  public function delete($address_id){
    $addresses = new Addresses();
    
    /*
    $address = $addresses->findAddress($address_id);
     
    if(!$address){
      $this->response->notFound('Endereço não encontrado');
    }
    */
    $addresses->delete($address_id);

    $this->response->success('Endereço exclúido');
  }
   
}