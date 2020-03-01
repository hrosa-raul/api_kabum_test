<?php

require_once 'Response.php';

/**
 * Classe base para os models
 */
Class BaseModel{
  private $dbhost = '127.0.0.1';
  private $dbuser = 'root';
  private $dbpass = '';
  private $dbname = 'kabum';
  private $pdo;
  private $response;
  private $query;
  
  public $table;


  public function __construct(){
    $this->response = new Response();
    try {
      $stringCon = "mysql:host={$this->dbhost};dbname={$this->dbname}";
      $this->pdo = new PDO($stringCon, $this->dbuser, $this->dbpass);
    } catch (PDOException $e) {
      $this->response->internalError();
    }
  }

  //Inserir registro
  public function insert($fields){
    $value = '';
    $field = '';
    $i = 0;
    foreach ($fields as $key => $val) {
      $i++;
      $separation = $i == count($fields) ? '' : ',';
      $field .= $key . $separation;
      $value .= $this->bindValues($val) . $separation;
    }

    $this->query = "INSERT INTO " . $this->table . " ( ";
    $this->query .= $field . ' ) VALUES ( ';
    $this->query .= $value . ' ) ';

    return $this->save();
  }
  
  //Alterar registro
  public function update($id, $fields){
    $i = 0;
    $this->query = "UPDATE " . $this->table . " SET ";
    foreach ($fields as $key => $val) {
      $i++;
      $separation = $i == count($fields) ? '' : ',';
      $this->query .= $key . ' = ' .  $this->bindValues($val) . $separation;
    }
    $this->query .= ' WHERE id = ' . $id;
    $this->save();
    return $id;
  }

  //Excluir registro
  public function delete($id){
    $this->query = "DELETE FROM " . $this->table;
    $this->query .= " WHERE id = " . $id;
    $this->save();
    return true; 
  }

  public function select(){
    $this->query = "SELECT * FROM " . $this->table;
    return $this;
  }

  //Prepara a query e a executa
  private function prepare(){
    $stm = $this->pdo->prepare($this->query);
    $this->query = null;
    try {
      $stm->execute();
    } catch (PDOException $e) {
      $this->response->internalError();
    }
    
    return $stm;
  }

  //executa a query e retorna o id
  public function save(){
    $stm = $this->prepare();
    return $this->pdo->lastInsertId();
  }

  public function getAll(){
    $stm = $this->prepare();
    return $stm->fetchAll(PDO::FETCH_OBJ);
  }

  public function get(){
    $stm = $this->prepare();
    return $stm->fetch(PDO::FETCH_OBJ);
  }

  private function bindValues($value){
    switch (gettype($value)) {
      case 'string':
        return '\'' . trim($value) . '\'';
      default:
        return trim($value);
    }
  }

  public function where($whereClause){
    
    $this->query .= ' WHERE ';

    foreach ($whereClause as $key => $value) {
      $this->query .= $key > 1 ? ' , AND ' : '';
      $this->query .= trim($value[0]) . ' ' . trim($value[1]) . ' ';
      $this->query .= $this->bindValues($value[2]);
          
    }
    return $this;
  }
}