<?php
header("Content-Type: application/json");

if (!isset($_GET['name'])) {
    echo json_encode(["status" => false, "message" => "Image name required"]);
    exit;
}

$fileName = basename($_GET['name']); 
$filePath = "images/" . $fileName;

if (file_exists($filePath)) {

    echo json_encode([
        "status" => true,
        "image_url" => "http://localhost/myapp/" . $filePath
    ]);

} else {
    echo json_encode([
        "status" => false,
        "message" => "Image not found"
    ]);
}