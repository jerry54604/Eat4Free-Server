<?php
include "/jwt/JWT.php";
include "/config.php";
include "/classes/users.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (!empty($_POST["username"]) && !empty($_POST["pass"])) {
    $username = $_POST["username"];
    $pass = $_POST["pass"];
    
    $user = new Users();
    $user -> getUser($username, $pass);
    
    if ($user -> id != -1) {
      $issuedAt = time();
      $notBefore = $issuedAt; 

      $token = array(
        "iss" => "http://eat4free.com.my",
        "aud" => "http://eat4free.com.my",
        "iat" => $issuedAt,
        "nbf" => $notBefore,
        "data" => [ // Data related to the signer user
          "id"   => $user -> id, // userid from the users table
          "username" => $user -> username, // User name
          "name" => $user -> firstname . " " . $user -> lastname
        ]
      );

      $jwt = JWT::encode($token, Config::$key);
      
      echo $jwt;
    } else if ($user -> id != -99) {
      header("HTTP/1.0 400 Bad Request");
      echo "Shit just got real! Contact admin immediately!";
    } else {
      header("HTTP/1.0 400 Bad Request");
      echo "User not found!";
    }
  } else {
    header("HTTP/1.0 400 Bad Request");
    echo "Username and/or Password not found!";
  }
} else {
  header("HTTP/1.0 405 Method Not Allowed");
}

?>