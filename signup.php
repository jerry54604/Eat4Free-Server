<?php
include "/jwt/JWT.php";
include "/config.php";
include "/classes/users.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (!empty($_POST["username"]) && !empty($_POST["pass"]) && !empty($_POST["email"]) && !empty($_POST["firstname"]) && !empty($_POST["lastname"])) {
    $user = new Users();
    $user -> username = $_POST["username"];
    $user -> email = $_POST["email"];
    $user -> firstname = $_POST["firstname"];
    $user -> lastname = $_POST["lastname"];
    
    if ($user -> signup($_POST["pass"])) {
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
    } else {
      header("HTTP/1.0 400 Bad Request");
      echo "Shit just got real! Contact admin immeadiately!";
    }
  } else {
    header("HTTP/1.0 400 Bad Request");
    echo "Please fill in all required field!";
  }
} else {
  header("HTTP/1.0 405 Method Not Allowed");
}

?>