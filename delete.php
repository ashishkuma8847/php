<?php
include("./config.php");
$value=3;
$delete=$connect->prepare("delete from test where id='$value'");
echo $delete->execute();
?>