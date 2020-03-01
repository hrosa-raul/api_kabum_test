<?php

require_once './app/models/Users.php';
require_once './core/BaseController.php';


Class AuthController extends BaseController {
  
  public function __construct(){
    $this->isPrivate = false;
    parent::__construct();
  }

  //POST -> /auth/login
  public function login(){
    //Apica as regras de validações
    $this->validation->validate('email')
      ->required()->isEmail();
    $this->validation->validate('password')
      ->required()->min(6);

    //retorna caso ouve algum erro de validação
    if($this->validation->hasError()){
      $this->response->badRequest(
        $this->validation->getFirstError()
      );
    }

    $users = new Users();
    $user = $users->select()
      ->where(array([
        'email', '=', $this->requests['email']
      ]))
      ->get();
    
    if(!$user){
      $this->response->notFound('Usuário não encontrado.');
    }

    if(md5($this->requests['password']) != $user->password){
      $this->response->badRequest('Senha inválida.');
    }
    
    /**
     * Tiramos a senha do objeto e
     * adicionamos um token para validar o se 
     * o usuário esta logado 
     */
    unset($user->password);
    $user->token = md5($this->getHash());

    $this->response->success($user);
  }  
}