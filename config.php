<?php
$host="localhost";
$username="root";
$password=null;
$database="first";

try {
    $connect = new PDO(
        "mysql:host=$host;dbname=$database",$username,$password
    );
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "database connected";
} catch (PDOException$err) {
echo "connection failed" .$err ->getMessage();
}
?>