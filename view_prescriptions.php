<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] != 'patient') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Prescriptions</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { background: #eef2ff; }
    .main { margin-top: 80px; margin-bottom: 80px; }
    footer { background: #343a40; color: white; position: fixed; bottom: 0; width: 100%; text-align:center; padding: 10px 0;}
</style>
</head>
<body>

<?php include "patient_navbar.php"; ?>

<div class="container main">
    <h2 class="text-center">Your Prescriptions</h2>

    <table class="table table-bordered table-striped mt-4">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Doctor</th>
                <th>Prescription</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>06-11-2025</td>
                <td>Dr. Sharma</td>
                <td>Take Vitamin D daily</td>
            </tr>
            <tr>
                <td>15-10-2025</td>
                <td>Dr. Mehta</td>
                <td>Braces follow-up next week</td>
            </tr>
        </tbody>
    </table>

    <br><p class="text-center mt-4">
              Go to Dashboard <a href="patient_dashboard.php">click here</a>
        </p>
</div>

<?php include "patient_footer.php"; ?>

</body>
</html>
