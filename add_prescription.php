
<?php
session_start();

$conn = new mysqli("localhost", "root", "", "hospital");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];

if (!isset($_GET['appointment_id'])) {
    die("Appointment ID Missing");
}

$appointment_id = intval($_GET['appointment_id']);

/* Fetch Appointment Details */
$stmt = $conn->prepare("
    SELECT *
    FROM appointments
    WHERE id=?
");

$stmt->bind_param("i", $appointment_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Appointment Not Found");
}

$appointment = $result->fetch_assoc();

$patient_name = $appointment['patient_name'];

/* Save Prescription */
if (isset($_POST['save'])) {

    $diagnosis = trim($_POST['diagnosis']);
    $medicine  = trim($_POST['medicine']);
    $notes     = trim($_POST['notes']);

    $stmt = $conn->prepare("
        INSERT INTO prescriptions
        (
            appointment_id,
            doctor_id,
            patient_name,
            diagnosis,
            medicine,
            notes
        )
        VALUES
        (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "iissss",
        $appointment_id,
        $doctor_id,
        $patient_name,
        $diagnosis,
        $medicine,
        $notes
    );

    if ($stmt->execute()) {

        echo "<script>
            alert('Prescription Added Successfully');
            window.location='doctor_dashboard.php';
        </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Add Prescription</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f4f6f9;
}
</style>

</head>
<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">
    <h4>Add Prescription</h4>
</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">
<label class="form-label">Patient Name</label>
<input
type="text"
class="form-control"
value="<?php echo htmlspecialchars($patient_name); ?>"
readonly>
</div>

<div class="mb-3">
<label class="form-label">Diagnosis</label>
<textarea
name="diagnosis"
class="form-control"
rows="4"
required></textarea>
</div>

<div class="mb-3">
<label class="form-label">Medicine</label>
<textarea
name="medicine"
class="form-control"
rows="4"
required></textarea>
</div>

<div class="mb-3">
<label class="form-label">Notes</label>
<textarea
name="notes"
class="form-control"
rows="4"></textarea>
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
```
