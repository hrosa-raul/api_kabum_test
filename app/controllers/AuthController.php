<?php

require_once './core/BaseController.php';

Class AuthController extends BaseController {

  private $loginRules = [
    'email' => [
      'required',
      'isValidEmail'
    ],
    'password' => [
      'required',
      'min' => 6
    ]
  ];


  public function login(){
     
    $this->validation->rules($this->loginRules);

    $this->validation->validate();


  }  

}