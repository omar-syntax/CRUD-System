<?php

include "includes/config.php";

$id = $_POST["id"];
$name = $_POST["name"];
$describtion = $_POST["description"];
$price = $_POST["price"];

$sql = "UPDATE `products` SET `name`='$name',`description`='$describtion',`price`='$price' WHERE `id`=$id";
$res = mysqli_query($conn, $sql);

if ($res){
    header("location:index.php");
};
?>



