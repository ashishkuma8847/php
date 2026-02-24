<?php
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_FILES["image"])) {
        echo json_encode(["status" => false, "message" => "No file uploaded"]);
        exit;
    }

    $targetDir = "images/";

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); 
    }

    $fileName = time() . "_" . basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    $allowTypes = ['jpg','jpeg','png','gif','webp'];

    if (!in_array($fileType, $allowTypes)) {
        echo json_encode(["status" => false, "message" => "Invalid file type"]);
        exit;
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {

        echo json_encode([
            "status" => true,
            "message" => "Image uploaded successfully",
            "image_url" => "http://localhost/myapp/" . $targetFilePath
        ]);

    } else {
        echo json_encode(["status" => false, "message" => "Upload failed"]);
    }

} else {
    echo json_encode(["status" => false, "message" => "Invalid request"]);
}