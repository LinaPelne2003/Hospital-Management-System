<?php
require 'config.php';

if (isset($_GET['id']) && isset($_GET['status'])) {

    $id = $_GET['id'];
    $status = $_GET['status'];

    // If status is Confirmed, insert into patient table
    if ($status == 'Confirmed') {

        // Get appointment data
        $stmt = $conn->prepare("SELECT * FROM appointments WHERE id = ?");
        $stmt->execute([$id]);
        $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($appointment) {

            $name = $appointment['patient_name'];
            $age = $appointment['age'];
            $gender = $appointment['gender'];
            $phone = $appointment['phone'];

            // Check if patient already exists (by phone)
            $check = $conn->prepare("SELECT patient_id FROM patient WHERE phone = ?");
            $check->execute([$phone]);

            if ($check->rowCount() == 0) {

                // Insert new patient
                $insert = $conn->prepare("
                    INSERT INTO patient (name, age, gender, phone, created_at)
                    VALUES (?, ?, ?, ?, NOW())
                ");
                $insert->execute([$name, $age, $gender, $phone]);
            }
        }
    }

    // Update appointment status
    $update = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
    $update->execute([$status, $id]);
}

header("Location: admin_dashboard.php");
exit;
?>