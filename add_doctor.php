<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['doctor_name'];
    $specialization = $_POST['specialization'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $imageName = "";

    if(isset($_FILES['doctor_image']) && $_FILES['doctor_image']['error'] == 0){

        $uploadDir = "uploads/";

        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0777, true);
        }

        $imageName = time() . "_" . basename($_FILES['doctor_image']['name']);

        move_uploaded_file(
            $_FILES['doctor_image']['tmp_name'],
            $uploadDir . $imageName
        );
    }

    $stmt = $conn->prepare("
        INSERT INTO doctor
        (name, specialization, phone, email, image, status)
        VALUES (?, ?, ?, ?, ?, 'active')
    ");

    $stmt->execute([
        $name,
        $specialization,
        $phone,
        $email,
        $imageName
    ]);

    header("Location: admin_dashboard.php");
    exit();
}
?>