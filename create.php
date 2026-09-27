<?php

include "includes/config.php";

$name = $_POST["name"];
$describtion = $_POST["describtion"];
$price = $_POST["price"];

$sql = "INSERT INTO `products`(`name`, `description`, `price`) VALUES ('$name','$describtion',$price)";
$res = mysqli_query($conn, $sql);

if ($res){
    header("location:index.php");
}
?>



