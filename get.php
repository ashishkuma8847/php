<?php
include("./config.php");
$getusers=$connect->prepare("SELECT * FROM test"); 
// agar yha par * ki jgah koi spesafic cheez mangi jaye to wo only hi mila jayegi jese id email etc.
$getusers->execute();
$users=$getusers->fetchAll(PDO::FETCH_ASSOC);
foreach($users as $user){
   echo "<h1>". $user["name"] ."</h1>";
   echo "<h1>". $user["email"] ."</h1>";
   echo "<h1>". $user["password"] ."</h1>";
};


?>