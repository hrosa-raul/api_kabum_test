<?php

require_once './core/BaseModel.php';

Class Addresses extends BaseModel{

  public function __construct(){
    $this->table = 'addresses';
    parent::__construct();
  }


  
}