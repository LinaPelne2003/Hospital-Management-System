<?php
require 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM doctor WHERE doctor_id = ?");
    $stmt->execute([$id]);
}

header("Location: admin_dashboard.php");
exit;
?>