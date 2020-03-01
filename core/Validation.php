<?php 

/**
 * Classe responsavel pela validação dos campos
 */
Class Validation{

  private $fields = [];
  private $valueOnValidation = NULL;
  private $fieldOnValidation;
  private $errors = [];
  
  //Aqui pegamos todos os valores dos campos 
  public function __construct($fields){
    $this->fields = $fields;
  }

  //Aramazenamos os erros para ser exibidos depois
  private function setError($validationRule, $message){
    $this->errors[$this->fieldOnValidation][$validationRule] = $message;
  }

  public function hasError(){
    return count($this->errors) > 0 ? true : false;
  }

  public function getFirstError(){
    return array_values($this->errors[array_key_first($this->errors)])[0];
  }

  //Prepara qual campo será validado
  public function validate($field){
    $this->fieldOnValidation = $field;
    $this->valueOnValidation = trim(@$this->fields[$field]); 
    return $this;
  }

  /** 
   * Daqui em diante são apenas as funções de validaçoes
   * sempre retornando $this para que possa chamar as funções 
   * de forma aninhada  
   */
  public function required(){
    if($this->valueOnValidation == NULL ||
      $this->valueOnValidation == '' ) 
    {
      $this->setError(
        'required', 
        'O campo ' . $this->fieldOnValidation . ' é obrigatório.'
      );
    }
    return $this;
  }

  public function isEmail(){
    if(strpos($this->valueOnValidation, '@') === false){
      $this->setError(
        'isEmail',
        'Email inválido.'
      );
    }
    return $this;
  }

  public function min($minDigits){
    if(strlen($this->valueOnValidation) < $minDigits){
      $this->setError(
        'min',
        'O campo ' . $this->fieldOnValidation . ' precisa ter no minimo '
        . $minDigits . ' digitos.' 
      );
    }
    return $this;
  }
}