<?php
header("Content-Type: application/json");
include("config.php");

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

/* ==========================
   🔐 TOKEN VERIFY FUNCTION
========================== */

function verifyToken($connect){
    $headers = getallheaders();

    if(!isset($headers['Authorization'])){
        die(json_encode(["error"=>"Token Missing"]));
    }

    $token = str_replace("Bearer ","",$headers['Authorization']);

    $stmt = $connect->prepare(
        "SELECT * FROM users 
         WHERE auth_token=:token 
         AND token_expiry > NOW()"
    );

    $stmt->execute([":token"=>$token]);
    $user = $stmt->fetch();

    if(!$user){
        die(json_encode(["error"=>"Invalid or Expired Token"]));
    }

    return $user;
}

/* ==========================
   🚀 ROUTES
========================== */

switch($method){

/* ================= SIGNUP ================= */

case "POST":

    if($_GET['action']=="signup"){

        $hashPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        try{
            $stmt = $connect->prepare(
                "INSERT INTO users (name,email,password)
                 VALUES (:name,:email,:password)"
            );

            $stmt->execute([
                ":name"=>$data['name'],
                ":email"=>$data['email'],
                ":password"=>$hashPassword
            ]);

            echo json_encode([
                "message"=>"User Registered",
                "id"=>$connect->lastInsertId()
            ]);

        }catch(PDOException $e){
            echo json_encode(["error"=>"Email already exists"]);
        }
    }

/* ================= LOGIN ================= */

    if($_GET['action']=="login"){

        $stmt = $connect->prepare("SELECT * FROM users WHERE email=:email");
        $stmt->execute([":email"=>$data['email']]);
        $user = $stmt->fetch();

        if($user && password_verify($data['password'], $user['password'])){

            $token = bin2hex(random_bytes(32));
            $expiry = date("Y-m-d H:i:s", strtotime("+1 day"));

            $update = $connect->prepare(
                "UPDATE users 
                 SET auth_token=:token, token_expiry=:expiry 
                 WHERE id=:id"
            );

            $update->execute([
                ":token"=>$token,
                ":expiry"=>$expiry,
                ":id"=>$user['id']
            ]);

            echo json_encode([
                "message"=>"Login Successful",
                "token"=>$token,
                "user"=>[
                    "id"=>$user['id'],
                    "name"=>$user['name'],
                    "email"=>$user['email']
                ]
            ]);

        }else{
            echo json_encode(["error"=>"Invalid Credentials"]);
        }
    }

break;

/* ================= PROTECTED ROUTE ================= */

case "GET":

    if($_GET['action']=="profile"){

        $user = verifyToken($connect);

        echo json_encode([
            "message"=>"Profile Data",
            "user"=>[
                "id"=>$user['id'],
                "name"=>$user['name'],
                "email"=>$user['email']
            ]
        ]);
    }

break;

/* ================= LOGOUT ================= */

case "DELETE":

    if($_GET['action']=="logout"){

        $headers = getallheaders();
        $token = str_replace("Bearer ","",$headers['Authorization']);

        $stmt = $connect->prepare(
            "UPDATE users 
             SET auth_token=NULL, token_expiry=NULL 
             WHERE auth_token=:token"
        );

        $stmt->execute([":token"=>$token]);

        echo json_encode(["message"=>"Logged Out Successfully"]);
    }

break;

}
?>
