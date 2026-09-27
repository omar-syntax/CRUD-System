<?php

include "includes/config.php";

$id = $_POST["id"];

$sql = "DELETE FROM `products` WHERE `id`=$id";
$res = mysqli_query($conn, $sql);

if ($res){
    header("location:index.php");
};
?>



