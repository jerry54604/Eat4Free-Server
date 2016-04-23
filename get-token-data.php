<?php
include "/jwt/JWT.php";
include "/config.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $authHeader = getallheaders()["Authorization"];
  /*
   * Look for the "authorization" header
   */
  if ($authHeader) {
    /*
     * Extract the jwt from the Bearer
     */
    $jwt = str_replace("Bearer ", "", $authHeader);

    if ($jwt) {
      try {
        /*
         * decode the jwt using the key from config
         */
        
        $token = JWT::decode($jwt, Config::$key, array("HS256"));
        
        /*
         * return protected asset
         */
        header("Content-type: application/json");
        echo json_encode(
          $token -> data
        );

      } catch (Exception $e) {
        /*
         * the token was not able to be decoded.
         * this is likely because the signature was not able to be verified (tampered token)
         */
        header("HTTP/1.0 401 Unauthorized");
      }
    } else {
      /*
       * No token was able to be extracted from the authorization header
       */
      header("HTTP/1.0 400 Bad Request");
      echo "Token not found in request";
    }
  } else {
    /*
     * The request lacks the authorization token
     */
    header("HTTP/1.0 400 Bad Request");
    echo "Token not found in request";
  }
} else {
  header("HTTP/1.0 405 Method Not Allowed");
}

?>