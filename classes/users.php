<?php

class Users
{
  public $id = -1;
  public $username = "";
  public $firstname = "";
  public $lastname = "";
  public $email = "";
  public $reg_date = null;
  public $update_date = null;
  
  function __construct() {
  }
  
  public function checkUserExist($u, $e) {
    // Create connection
    $conn = new mysqli(Config::$servername, Config::$db_username, Config::$db_password, Config::$dbname);
    // Check connection
    if ($conn -> connect_error) {
      die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "SELECT id 
            FROM users 
            WHERE username = '" . $u . "' OR email = '" . $e . "'";
    
    $result = $conn -> query($sql);
    
    $conn->close();
    
    if ($result -> num_rows == 1) {
      return true;
    } else if ($result -> num_rows > 1) {
      return true;
    }
    
    return false;
  }
  
  public function getUser($u, $p) {
    // Create connection
    $conn = new mysqli(Config::$servername, Config::$db_username, Config::$db_password, Config::$dbname);
    // Check connection
    if ($conn -> connect_error) {
      die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "SELECT id, username, email, firstname, lastname, reg_date, update_date 
            FROM users 
            WHERE username = '" . $u . "' AND pass = '" . md5($p) . "'";
            
    $result = $conn -> query($sql);

    if ($result -> num_rows == 1) {
      $row = $result -> fetch_assoc();
      //echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
      $this -> id = $row["id"];
      $this -> username = $u;
      $this -> email = $row["email"];
      $this -> firstname = $row["firstname"];
      $this -> lastname = $row["lastname"];
      $this -> reg_date = $row["reg_date"];
      $this -> update_date = $row["update_date"];
      
    } else if ($result -> num_rows > 1) {
      $this -> $id = -99;
    }
    
    $conn->close();
  }
  
  public function signup($p) {
    $con = mysqli_connect(Config::$servername, Config::$db_username, Config::$db_password, Config::$dbname);
    // Check connection
    if (mysqli_connect_errno()) {
      return false;
    }
    
    $sql = "INSERT INTO users (username, pass, email, firstname, lastname) 
            VALUES ('" . $this -> username . "', '" . md5($p) . "', '" . $this -> email . "', '" . $this -> firstname . "', '" . $this -> lastname . "')";
    
    mysqli_query($con, $sql);
    
    $this -> id = mysqli_insert_id($con);
    
    if (!$this -> id) {
      return false;
    }
    
    $this -> reg_date = time();

    mysqli_close($con);
    return true;
  }
}

?>