<?php
include "/jwt/JWT.php";
include "/config.php";
include "/classes/users.php";

$username = (filter_input(INPUT_GET, 'username')) ? filter_input(INPUT_GET, 'username') : "";
$email = filter_input(INPUT_GET, 'email') ? filter_input(INPUT_GET, 'email') : "";

$user = new Users();
echo ($user -> checkUserExist($username, $email)) ? "true" : "false";

?>