<?php
include("./config.php");
$getusers=$connect->prepare("SELECT * FROM test");
$getusers->execute();
$users=$getusers->fetchAll(PDO::FETCH_ASSOC);
foreach($users as $user){
   echo "<h1>". $user["name"] ."</h1>";
   echo "<h1>". $user["email"] ."</h1>";
   echo "<h1>". $user["password"] ."</h1>";
};


?>