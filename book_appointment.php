<?php
session_start();

$conn = new mysqli("localhost", "root", "", "hospital");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] != 'patient') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Appointment | My Hospital</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f4f6fb;
    font-family:'Segoe UI', sans-serif;
}

/* Page spacing */
.main{
    margin-top:90px;
    margin-bottom:90px;
}

/* Card */
.appointment-card{
    border-radius:16px;
    box-shadow:0 15px 30px rgba(0,0,0,.1);
}

/* Heading */
.page-title{
    font-weight:600;
}

/* Footer */
footer{
    background:#212529;
    color:#ccc;
    position:fixed;
    bottom:0;
    width:100%;
    text-align:center;
    padding:12px 0;
    font-size:14px;
}
footer a{
    color:#ccc;
    text-decoration:none;
}
footer a:hover{
    color:#fff;
}
</style>
</head>

<body>

<!-- ✅ NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
    <div class="container-fluid">
        <a class="navbar-brand" href="patient_dashboard.php">🏥 Patient Panel</a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Book Appointment</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view_prescriptions.php">Prescriptions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="update_profile.php">Profile</a>
                </li>
                <li class="nav-item ms-3">
                    <a class="btn btn-danger btn-sm" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- ✅ MAIN CONTENT -->
<div class="container main">

    <div class="text-center mb-4">
        <h2 class="page-title">Book an Appointment</h2>
        <p class="text-muted">Choose your doctor and preferred date</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card appointment-card p-4 bg-white">

                <form method="POST" action="#">
                    
                   <!-- Doctor -->
<div class="mb-3">
    <label class="form-label fw-semibold">Select Doctor</label>

    <select name="doctor_id" class="form-select" required>
        <option value="">-- Select Doctor --</option>

        <?php
        $doctorQuery = $conn->query("
            SELECT doctor_id, name, specialization
            FROM doctor
            WHERE status='active'
            ORDER BY name
        ");

        while($doctor = $doctorQuery->fetch_assoc()){
        ?>
            <option value="<?php echo $doctor['doctor_id']; ?>">
                Dr. <?php echo htmlspecialchars($doctor['name']); ?>
                - <?php echo htmlspecialchars($doctor['specialization']); ?>
            </option>
        <?php
        }
        ?>
    </select>
</div>

                    <!-- Date -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Appointment Date</label>
                        <input type="date" class="form-control" required>
                    </div>

                    <!-- Time (optional but professional) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Preferred Time</label>
                        <select class="form-select">
                            <option>Morning (9 AM – 12 PM)</option>
                            <option>Afternoon (12 PM – 4 PM)</option>
                            <option>Evening (4 PM – 8 PM)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Confirm Appointment
                    </button>
                </form>

                <p class="text-center mt-4">
                    Back to <a href="patient_dashboard.php">Dashboard</a>
                </p>

            </div>

        </div>
    </div>

</div>

<!-- ✅ FOOTER -->
<footer>
    <a href="patient_dashboard.php">Dashboard</a> |
    <a href="view_prescriptions.php">Prescriptions</a> |
    <a href="update_profile.php">Profile</a>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
