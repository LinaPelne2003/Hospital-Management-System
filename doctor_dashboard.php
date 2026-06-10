<?php
session_start();



/* ===== DATABASE CONNECTION ===== */
$conn = new mysqli("localhost", "root", "", "hospital");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* ===== CHECK LOGIN ===== */
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] != "doctor") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$doctorName = $_SESSION['username'];
$today = date("Y-m-d");

/* ===== TODAY APPOINTMENTS COUNT ===== */
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM appointments WHERE date=?");
$stmt->bind_param("s", $today);
$stmt->execute();
$todayAppointments = $stmt->get_result()->fetch_assoc()['total'];

/* ===== TOTAL PATIENTS ===== */
$result = $conn->query("SELECT COUNT(*) as total FROM registration WHERE usertype='patient'");
$totalPatients = $result->fetch_assoc()['total'];

$pendingReports = 0;

/* ===== UPDATE PROFILE ===== */
if(isset($_POST['update_profile'])){

    $newPassword = trim($_POST['password']);

    // Get existing image
   $stmt = $conn->prepare("SELECT image FROM doctor WHERE doctor_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

$imagePath = $data['image'];

    // If new image uploaded
    if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0){
        $folder = "uploads/";
        if(!is_dir($folder)){
            mkdir($folder);
        }

        $newImageName = time() . "_" . basename($_FILES['profile_image']['name']);
        $imagePath = $folder . $newImageName;

        move_uploaded_file($_FILES['profile_image']['tmp_name'], $imagePath);
    }

    // Update only profile image
    $stmt = $conn->prepare("UPDATE doctor SET image=? WHERE doctor_id=?");
$stmt->bind_param("si", $imagePath, $user_id);
$stmt->execute();

    // Update password if entered
    if(!empty($newPassword)){
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE registration SET password=? WHERE id=?");
        $stmt->bind_param("si", $hashedPassword, $user_id);
        $stmt->execute();
    }

    echo "<script>alert('Profile Updated Successfully'); window.location='doctor_dashboard.php';</script>";
    exit();
}

/* ===== FETCH USER DATA WITH SPECIALIZATION FROM DOCTOR TABLE ===== */
$stmt = $conn->prepare("
    SELECT r.*, d.specialization, d.image
    FROM registration r
    LEFT JOIN doctor d ON r.id = d.doctor_id
    WHERE r.id=?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$profileImage = (!empty($user['image']) && file_exists($user['image']))
    ? $user['image']
    : "uploads/default.png";
?>

<!DOCTYPE html>
<html>
<head>
<title>Doctor Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#f4f6f9; }
.sidebar { height:100vh; width:230px; background:#0d6efd; position:fixed; padding-top:20px; }
.sidebar h4 { color:white; text-align:center; margin-bottom:30px; }
.sidebar a { color:#e9ecef; display:block; padding:12px 20px; text-decoration:none; }
.sidebar a:hover, .sidebar a.active { background:#084298; color:white; }
.content { margin-left:230px; padding:30px; }
.section { display:none; }
.stat-card { border-radius:12px; color:white; }
</style>
</head>

<body>

<div class="sidebar">
    <h4>Doctor Panel</h4>
    <a href="doctor_dashboard.php" onclick="showSection('dashboard', this)" class="active">Dashboard</a>
    <a href="#" onclick="showSection('appointments', this)">Appointments</a>
    <a href="#" onclick="showSection('patients', this)">Patients</a>
    <a href="#" onclick="showSection('profile', this)">Profile</a>
    <a href="logout.php"
   class="text-warning"
   onclick="return confirm('Are you sure you want to logout?');">
   🚪 Logout
</a>
</div>

<div class="content">

<!-- DASHBOARD -->
<div id="dashboard" class="section" style="display:block;">
<h3>Welcome, Dr. <?php echo htmlspecialchars($_SESSION['username']); ?></h3>
<div class="row mt-4">
<div class="col-md-4">
<div class="card stat-card bg-success p-3">
<h5>Today Appointments</h5>
<h2><?php echo $todayAppointments; ?></h2>
</div></div>
<div class="col-md-4">
<div class="card stat-card bg-primary p-3">
<h5>Total Patients</h5>
<h2><?php echo $totalPatients; ?></h2>
</div></div>
<div class="col-md-4">
<div class="card stat-card bg-warning p-3">
<h5>Pending Reports</h5>
<h2><?php echo $pendingReports; ?></h2>
</div></div>
</div>
</div>

<!-- APPOINTMENTS -->
<div id="appointments" class="section">
<h3>Today's Appointments</h3>
<table class="table table-bordered bg-white text-center">
<thead class="table-dark">
<tr>
<th>#</th>
<th>Patient Name</th>
<th>Date</th>
<th>Status</th>
</tr>
</thead>
<tbody>
<?php
$stmt = $conn->prepare("SELECT * FROM appointments WHERE date=?");
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
$count = 1;
while($row = $result->fetch_assoc()):
?>
<tr>
<td><?php echo $count++; ?></td>
<td><?php echo htmlspecialchars($row['patient_name']); ?></td>
<td><?php echo $row['date']; ?></td>
<td><?php echo $row['status']; ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>


<!-- PATIENTS -->
<div id="patients" class="section">

<h3>Patient Records & Prescriptions</h3>

<table class="table table-bordered table-striped bg-white">

<thead class="table-dark">
<tr>
    <th>Patient Name</th>
    <th>Appointment Date</th>
    <th>Status</th>
    <th>Diagnosis</th>
    <th>Medicine</th>
    <th>Notes</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

<?php

$query = "
SELECT
a.id as appointment_id,
a.patient_name,
a.date,
a.status,
p.id as prescription_id,
p.diagnosis,
p.medicine,
p.notes

FROM appointments a

LEFT JOIN prescriptions p
ON a.id = p.appointment_id

ORDER BY a.date DESC
";

$result = $conn->query($query);

while($row = $result->fetch_assoc()){

?>

<tr>

<td><?php echo htmlspecialchars($row['patient_name']); ?></td>

<td><?php echo $row['date']; ?></td>

<td><?php echo $row['status']; ?></td>

<td><?php echo $row['diagnosis'] ?? '-'; ?></td>

<td><?php echo $row['medicine'] ?? '-'; ?></td>

<td><?php echo $row['notes'] ?? '-'; ?></td>

<td>

<?php if(!empty($row['prescription_id'])){ ?>

<a href="update_prescription.php?id=<?php echo $row['prescription_id']; ?>"
class="btn btn-warning btn-sm">
Update
</a>

<a href="delete_prescription.php?id=<?php echo $row['prescription_id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete Prescription?')">
Delete
</a>

<?php } else { ?>

<a href="add_prescription.php?appointment_id=<?php echo $row['appointment_id']; ?>"
class="btn btn-success btn-sm">
Add Prescription
</a>

<?php } ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>



<!-- PROFILE -->
<div id="profile" class="section">
<h3>Edit Profile</h3>
<div class="card p-4 bg-white">
<form method="POST" enctype="multipart/form-data">

<div class="mb-3 text-center">
<img src="<?php echo $profileImage; ?>" width="120" height="120" class="rounded-circle border">
</div>

<!-- USERNAME (READONLY) -->
<div class="mb-3">
<label>Username</label>
<input type="text" 
       class="form-control"
       value="<?php echo htmlspecialchars($user['username']); ?>" 
       readonly>
</div>

<!-- SPECIALIZATION (READONLY FROM DOCTOR TABLE) -->
<div class="mb-3">
<label>Specialization</label>
<input type="text" 
       class="form-control"
       value="<?php echo htmlspecialchars($user['specialization'] ?? 'Not Set'); ?>" 
       readonly>
</div>

<!-- PASSWORD (EDITABLE) -->
<div class="mb-3">
<label>New Password</label>
<input type="password" name="password" class="form-control">
</div>

<!-- IMAGE (EDITABLE) -->
<div class="mb-3">
<label>Change Profile Image</label>
<input type="file" name="profile_image" class="form-control">
</div>

<button type="submit" name="update_profile" class="btn btn-primary">
Update Profile
</button>

</form>
</div>
</div>

</div>

<script>
function showSection(id, element){
document.querySelectorAll('.section').forEach(sec=>sec.style.display='none');
document.getElementById(id).style.display='block';
document.querySelectorAll('.sidebar a').forEach(a=>a.classList.remove('active'));
element.classList.add('active');
}
</script>

</body>
</html>







