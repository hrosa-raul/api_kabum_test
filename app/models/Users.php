<?php

require_once './core/BaseModel.php';

Class Users extends BaseModel{

  public function __construct(){
    $this->table = 'users';
    parent::__construct();
  }


  
}