<?php
header("Content-Type: application/json");
include("config.php");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case "GET":
        $stmt = $connect->prepare("SELECT id,name,email FROM test");
        $stmt->execute();
        echo json_encode($stmt->fetchAll());
        break;


    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);

        $name = $data['name'];
        $email = $data['email'];
        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt = $connect->prepare(
            "INSERT INTO test (name,email,password)
             VALUES (:name,:email,:password)"
        );

        $stmt->execute([
            ":name" => $name,
            ":email" => $email,
            ":password" => $password
        ]);

        echo json_encode(["message" => "User Created"]);
        break;


    case "PUT":
        $data = json_decode(file_get_contents("php://input"), true);

        $stmt = $connect->prepare(
            "UPDATE test SET name=:name WHERE id=:id"
        );

        $stmt->execute([
            ":name" => $data['name'],
            ":id" => $data['id']
        ]);

        echo json_encode(["message" => "User Updated"]);
        break;


    case "DELETE":
        $data = json_decode(file_get_contents("php://input"), true);

        $stmt = $connect->prepare(
            "DELETE FROM test WHERE id=:id"
        );

        $stmt->execute([
            ":id" => $data['id']
        ]);

        echo json_encode(["message" => "User Deleted"]);
        break;
}
?>
