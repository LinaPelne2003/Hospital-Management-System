```php
<?php
session_start();

$conn = new mysqli("localhost","root","","hospital");

if($conn->connect_error){
    die("Connection Failed: ".$conn->connect_error);
}

if(!isset($_GET['id'])){
    die("Prescription ID Missing");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM prescriptions WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    die("Prescription Not Found");
}

$row = $result->fetch_assoc();

if(isset($_POST['update'])){

    $diagnosis = $_POST['diagnosis'];
    $medicine  = $_POST['medicine'];
    $notes      = $_POST['notes'];

    $stmt = $conn->prepare("
        UPDATE prescriptions
        SET diagnosis=?, medicine=?, notes=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "sssi",
        $diagnosis,
        $medicine,
        $notes,
        $id
    );

    if($stmt->execute()){
        echo "<script>
        alert('Prescription Updated Successfully');
        window.location='doctor_dashboard.php';
        </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Update Prescription</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container mt-5">

<div class="card shadow">
<div class="card-header bg-primary text-white">
<h4>Update Prescription</h4>
</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">
<label>Patient Name</label>
<input type="text"
class="form-control"
value="<?php echo htmlspecialchars($row['patient_name']); ?>"
readonly>
</div>

<div class="mb-3">
<label>Diagnosis</label>
<textarea name="diagnosis" class="form-control" rows="4"><?php echo htmlspecialchars($row['diagnosis']); ?></textarea>
</div>

<div class="mb-3">
<label>Medicine</label>
<textarea name="medicine" class="form-control" rows="4"><?php echo htmlspecialchars($row['medicine']); ?></textarea>
</div>

<div class="mb-3">
<label>Notes</label>
<textarea name="notes" class="form-control" rows="4"><?php echo htmlspecialchars($row['notes']); ?></textarea>
</div>

<button type="submit" name="update" class="btn btn-success">
Update Prescription
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
