<?php 
	
	include 'connect.php';

   setcookie('user_id', '', time() - 1, '/');


   $user_id = "{$_COOKIE["user_id"]}";
   $action = 'logout';

   $log_user = $conn->prepare("INSERT INTO `user_logs` (user_id, action) VALUES (?, ?)");
   $log_user->execute([$user_id, $action]);


   header('location:../home.php');

?>