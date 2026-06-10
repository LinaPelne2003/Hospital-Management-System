<?php
require 'config.php';

$id = $_GET['id'];

$stmt = $conn->prepare("UPDATE appointments SET status='Completed' WHERE id=?");
$stmt->execute([$id]);

header("Location: doctor_dashboard.php");
exit;
?>