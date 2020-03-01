<?php

require_once './core/BaseModel.php';

Class Clients extends BaseModel{

  public function __construct(){
    $this->table = 'clients';
    parent::__construct();
  }
 

  public function findClient($id_client){
    return $this->select()
      ->where(array(
        ['id', '=', $id_client]
      ))
      ->get();
  }
}