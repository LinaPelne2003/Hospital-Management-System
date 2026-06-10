<?php
session_start();

$conn = new mysqli("localhost", "root", "", "hospital");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] != "doctor") {
    header("Location: login.php");
    exit();
}

$appointment_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM appointments WHERE id=?");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$patient = $stmt->get_result()->fetch_assoc();

if(isset($_POST['save'])){

    $diagnosis = $_POST['diagnosis'];
    $medicine = $_POST['medicine'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("
        INSERT INTO prescriptions
        (appointment_id, doctor_id, patient_name, diagnosis, medicine, notes)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "iissss",
        $appointment_id,
        $_SESSION['user_id'],
        $patient['patient_name'],
        $diagnosis,
        $medicine,
        $notes
    );

    $stmt->execute();

    echo "<script>
    alert('Prescription Saved Successfully');
    window.location='doctor_dashboard.php';
    </script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Prescription</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">
<h3>Create Prescription</h3>
</div>

<div class="card-body">

<p><strong>Patient:</strong>
<?php echo htmlspecialchars($patient['patient_name']); ?>
</p>

<form method="POST">

<div class="mb-3">
<label>Diagnosis</label>
<textarea name="diagnosis" class="form-control" required></textarea>
</div>

<div class="mb-3">
<label>Medicine</label>
<textarea name="medicine" class="form-control" required></textarea>
</div>

<div class="mb-3">
<label>Notes</label>
<textarea name="notes" class="form-control"></textarea>
</div>

<button type="submit" name="save" class="btn btn-success">
Save Prescription
</button>

<a href="doctor_dashboard.php" class="btn btn-secondary">
Back
</a>

</form>

</div>
</div>
</div>

</body>
</html>