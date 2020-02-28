<?php 


Class Validation{

  protected $fields = [];
  protected $rules = [];
  public $isValid = true;

  public function __construct($fields){
    $this->fields = $fields;
  }

  public function rules($rules){
    $this->rules = $rules;
  }

  public function validate(){
    foreach ($this->rules as $key => $value) {
      var_dump($value);
      exit;
      if($value->is_array){

      }else{
        //call_user_func_array([$this, value], $arg]);
      }
      
    }
  }

}