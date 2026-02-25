<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "first";

try {
    $connect = new PDO(
        "mysql:host=$host;dbname=$database;charset=utf8",
        $username,
        $password
    );

    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


} catch (PDOException $err) {
    echo "❌ Connection Failed: " . $err->getMessage();
}
?>
