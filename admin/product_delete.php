<?php 

session_start();
require '../config/config.php';
require '../config/common.php';

$stmt = $pdo->prepare("DELETE FROM products WHERE id=".$_GET['id']);
$stmt->execute();

header("Location: index.php");

?>