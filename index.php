<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include("config.php");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // ==========================
    // GET (All + Single)
    // ==========================
    case "GET":

        if (isset($_GET['id']) && !empty($_GET['id'])) {

            $stmt = $connect->prepare("SELECT * FROM user_data WHERE id=:id");
            $stmt->execute([":id" => $_GET['id']]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            echo $data
                ? json_encode($data)
                : json_encode(["message" => "Data Not Found"]);

        } else {

            $stmt = $connect->prepare("SELECT * FROM user_data");
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }

        break;


    // ==========================
    // POST (Dynamic Insert)
    // ==========================
    case "POST":

        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            echo json_encode(["message" => "Invalid Input"]);
            exit;
        }

        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ":" . $col, $columns);

        $sql = "INSERT INTO user_data (" . implode(",", $columns) . ")
                VALUES (" . implode(",", $placeholders) . ")";

        $stmt = $connect->prepare($sql);
        $stmt->execute($data);

        echo json_encode([
            "message" => "Data Created",
            "last_id" => $connect->lastInsertId()
        ]);

        break;


    // ==========================
    // PUT (Dynamic Update)
    // ==========================
    case "PUT":

        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['id'])) {
            echo json_encode(["message" => "ID Required"]);
            exit;
        }

        $id = $data['id'];
        unset($data['id']);

        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }

        $sql = "UPDATE user_data SET " . implode(",", $fields) . " WHERE id = :id";

        $data['id'] = $id;

        $stmt = $connect->prepare($sql);
        $stmt->execute($data);

        echo json_encode(["message" => "Data Updated"]);
        break;


    // ==========================
    // DELETE
    // ==========================
    case "DELETE":

        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['id'])) {
            echo json_encode(["message" => "ID Required"]);
            exit;
        }

        $stmt = $connect->prepare("DELETE FROM user_data WHERE id=:id");
        $stmt->execute([":id" => $data['id']]);

        echo json_encode(["message" => "Data Deleted"]);
        break;


    default:
        echo json_encode(["message" => "Invalid Request"]);
        break;
}