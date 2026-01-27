<?php
include("./config.php");

$check = $connect->prepare(
    "SELECT id FROM test WHERE email = :email"
);
$check->execute([
    ":email" => "rahul@gmail.com"
]);

if ($check->rowCount() > 0) {
    echo "Email already exists";
    exit;
}

$createdata=$connect->prepare("INSERT INTO test (name,email,password)
VALUES (:name,:email,:password)
");

$res=$createdata->execute(
    [
        ":name"=>"rahul",
        ":email"=>"rahul@gmail.com",
        ":password"=>"123456"
    ]
);
if ($res) {
echo "data created";
}else{
    echo "data not created";
}
?>