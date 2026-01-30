<?php
// // connection using mysqli
// $host ="localhost";
// $username="root";
// $password=null;
// $database="first";
// $connect = new mysqli( $host,$username,$password,$database);
// if($connect->connect_error){
//     return ( "data base not connected".$connect->connect_error);
// }else{
//     echo "connected with database";
// };
// $res = $connect->query("show tables")->fetch_all();
// print_r($res)

// // connection using PDO

// $host="localhost";
// $username="root";
// $password=null;
// $database="first";

// try {
//     $connect = new PDO(
//         "mysql:host=$host;dbname=$database",$username,$password
//     );
//     $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     echo "database connected";
// } catch (PDOException$err) {
// echo "connection failed" .$err ->getMessage();
// }
// $res = $connect->query("show tables");
// while($row=$res->fetch(PDO::FETCH_NUM)){
//     print_r($row);
// }
?>