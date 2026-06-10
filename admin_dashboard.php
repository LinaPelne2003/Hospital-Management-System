<?php
session_start();



// Security check
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require 'config.php';


// Total Doctors
$doctorCount = $conn->query("SELECT COUNT(*) FROM doctor")->fetchColumn();

// Total Patients
$patientCount = $conn->query("SELECT COUNT(*) FROM patient")->fetchColumn();

// Total Appointments
$appointmentCount = $conn->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard | Hospital Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f4f6f9;
}

/* Sidebar */
.sidebar {
    height: 100vh;
    width: 230px;
    background: #212529;
    position: fixed;
    padding-top: 20px;
}
.sidebar h4 {
    color: #fff;
    text-align: center;
    margin-bottom: 30px;
}
.sidebar a {
    color: #adb5bd;
    display: block;
    padding: 12px 20px;
    text-decoration: none;
    font-size: 15px;
}
.sidebar a:hover,
.sidebar a.active {
    background: #343a40;
    color: #fff;
}

/* Content */
.content {
    margin-left: 230px;
    padding: 30px;
}

/* Cards */
.stat-card {
    border-radius: 12px;
    color: white;
}

/* Section hidden */
.section {
    display: none;
}
</style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>🏥 Admin Panel</h4>
    <a href="#" onclick="showSection('dashboard')" class="active">📊 Dashboard</a>
    <a href="#" onclick="showSection('doctor')">👨⚕️ Doctors</a>
    <a href="#" onclick="showSection('appointment')">📅 Appointments</a>
    <a href="#" onclick="showSection('patient')">🧑 Patients</a>
    <a href="logout.php"
   class="text-warning"
   onclick="return confirm('Are you sure you want to logout?');">
   🚪 Logout
</a>
</div>

<!-- Main Content -->
<div class="content">

<!-- ================= DASHBOARD ================= -->
<div id="dashboard" class="section" style="display:block;">
    <h3 class="mb-4">Dashboard Overview</h3>

    <div class="row">
        <div class="col-md-4">
            <div class="card stat-card bg-primary p-3">
                <h5>Total Doctors</h5>
                <h2><?= $doctorCount; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-success p-3">
                <h5>Total Patients</h5>
                <h2><?= $patientCount; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card bg-warning p-3">
                <h5>Appointments</h5>
                <h2><?= $appointmentCount; ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- ================= DOCTOR ================= -->
<div id="doctor" class="section">
    <h3 class="mb-3">Doctor Management</h3>

    <form class="card p-4 mb-4 shadow-sm" method="POST" action="add_doctor.php" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Doctor Name</label>
                <input type="text" class="form-control" name="doctor_name" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Specialization</label>
                <select class="form-select" name="specialization" required>
                    <option value="">Select</option>
                    <option>Cardiologist</option>
                    <option>Dermatologist</option>
                    <option>Neurologist</option>
                    <option>General Physician</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Phone</label>
                <input type="text" class="form-control" name="phone" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="col-md-6 mb-3">
    <label>Doctor Image</label>
    <input type="file" class="form-control" name="doctor_image" accept="image/*" required>
</div>
        </div>
        <button class="btn btn-primary">Add Doctor</button>
    </form>



    <hr>
<h4 class="mb-3">Doctor List</h4>

<table class="table table-bordered table-hover bg-white shadow-sm text-center">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Image</th>
            <th>Name</th>
            <th>Specialization</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
<?php
$stmt = $conn->prepare("SELECT * FROM doctor ORDER BY doctor_id DESC");
$stmt->execute();
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

$serial = 1;

foreach ($doctors as $row) {
?>
<tr>
    <td><?= $serial++; ?></td>

    <td>
        <?php if (!empty($row['image'])) { ?>
            <img src="uploads/<?= htmlspecialchars($row['image']); ?>" 
                 width="60" height="60" style="object-fit:cover; border-radius:8px;">
        <?php } else { ?>
            No Image
        <?php } ?>
    </td>

    <td><?= htmlspecialchars($row['name']); ?></td>
    <td><?= htmlspecialchars($row['specialization']); ?></td>
    <td><?= htmlspecialchars($row['phone']); ?></td>
    <td><?= htmlspecialchars($row['email']); ?></td>

    <td>
        <?php if ($row['status'] == 'active') { ?>
            <span class="badge bg-success">Active</span>
        <?php } else { ?>
            <span class="badge bg-danger">Inactive</span>
        <?php } ?>
    </td>

    <td>
        <a href="delete_doctor.php?id=<?= $row['doctor_id']; ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Delete this doctor?')">
           Delete
        </a>
    </td>
</tr>
<?php } ?>
    </tbody>
</table>


</div>

<!-- ================= APPOINTMENT ================= -->
<div id="appointment" class="section">
    <h3 class="mb-3">Appointment Management</h3>

    <table class="table table-bordered table-hover bg-white shadow-sm text-center">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
<?php
$stmt = $conn->prepare("SELECT * FROM appointments ORDER BY id DESC");
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$serial = 1;

foreach ($appointments as $row) {
?>
<tr>
    <td><?= $serial++; ?></td>
    <td><?= htmlspecialchars($row['patient_name']); ?></td>
    <td><?= htmlspecialchars($row['department']); ?></td>
    <td><?= htmlspecialchars($row['date']); ?></td>
    <td>
        <?php if ($row['status'] == 'Confirmed') { ?>
            <span class="badge bg-success">Confirmed</span>
        <?php } elseif ($row['status'] == 'Cancelled') { ?>
            <span class="badge bg-danger">Cancelled</span>
        <?php } else { ?>
            <span class="badge bg-warning text-dark">Pending</span>
        <?php } ?>
    </td>
    <td>
        <a href="update_appointment.php?id=<?= $row['id']; ?>&status=Confirmed"
           class="btn btn-success btn-sm">Confirm</a>

        <a href="update_appointment.php?id=<?= $row['id']; ?>&status=Cancelled"
           class="btn btn-danger btn-sm">Cancel</a>
    </td>
</tr>
<?php } ?>
</tbody>
    </table>
</div>

<!-- ================= PATIENT ================= -->
<div id="patient" class="section">
    <h3 class="mb-3">Patient Management</h3>

    <table class="table table-bordered table-hover bg-white shadow-sm text-center">
        <thead class="table-dark">
<tr>
    <th>#</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Age</th>
    <th>Message</th>
    <th>Action</th>
</tr>
</thead>
        <tbody>
<?php
$stmt = $conn->prepare("
    SELECT 
        patient.patient_id,
        patient.name,
        patient.age,
        patient.phone,
        appointments.message
    FROM patient
    LEFT JOIN appointments 
        ON patient.phone = appointments.phone
        AND appointments.status = 'Confirmed'
    ORDER BY patient.patient_id DESC
");

$stmt->execute();
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

$serial = 1;

foreach ($patients as $row) {
?>
<tr>
    <td><?= $serial++; ?></td>
    <td><?= htmlspecialchars($row['name']); ?></td>
    <td><?= htmlspecialchars($row['phone']); ?></td>
    <td><?= htmlspecialchars($row['age']); ?></td>
    <td><?= htmlspecialchars($row['message'] ?? 'No Message'); ?></td>
    <td>
        <a href="delete_patient.php?id=<?= $row['patient_id']; ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Are you sure?')">Delete</a>
    </td>
</tr>
<?php } ?>
</tbody>
    </table>
</div>

</div>

<!-- JS -->
<script>
function showSection(id) {
    document.querySelectorAll('.section').forEach(sec => sec.style.display = 'none');
    document.getElementById(id).style.display = 'block';

    document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
    event.target.classList.add('active');
}

function setStatus(id, status) {
    document.getElementById(id).innerText = status;
}
</script>

</body>
</html>
