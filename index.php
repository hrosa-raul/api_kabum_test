<?php 

  require_once 'core/Server.php';

  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: GET, POST');
  header("Access-Control-Allow-Headers: *");

  $server = new Server();

?>