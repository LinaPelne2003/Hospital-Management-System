<?php
session_start();

// ✅ Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] != 'patient') {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Patient Dashboard | My Hospital</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f4f6fb;
    font-family: 'Segoe UI', sans-serif;
}

/* Navbar */
.navbar-brand{
    font-weight:600;
}

/* Dashboard spacing */
.dashboard{
    margin-top:90px;
    margin-bottom:90px;
}

/* Cards */
.dashboard-card{
    border-radius:16px;
    box-shadow:0 12px 25px rgba(0,0,0,.1);
    transition:.3s;
}
.dashboard-card:hover{
    transform:translateY(-6px);
}

.dashboard-card h4{
    font-weight:600;
}

/* Footer */
footer{
    background:#212529;
    color:#fff;
    position:fixed;
    bottom:0;
    width:100%;
    padding:12px 0;
    text-align:center;
    font-size:14px;
}
footer a{
    color:#ccc;
    margin:0 10px;
    text-decoration:none;
}
footer a:hover{
    color:#fff;
}
</style>
</head>

<body>

<!-- ✅ TOP NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">🏥 Patient Dashboard</a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto align-items-center">

                <li class="nav-item">
                    <a class="nav-link" href="book_appointment.php">Book Appointment</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="view_prescriptions.php">Prescriptions</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="update_profile.php">Profile</a>
                </li>

                <li class="nav-item ms-3">
                    <a href="logout.php"
   class="text-warning"
   onclick="return confirm('Are you sure you want to logout?');">
   🚪 Logout
</a>
                </li>

            </ul>
        </div>
    </div>
</nav>

<!-- ✅ MAIN DASHBOARD -->
<div class="container dashboard">

    <div class="text-center mb-5">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?> 👋</h2>
        <p class="text-muted">
            Manage your appointments, prescriptions, and personal details easily.
        </p>
    </div>

    <div class="row justify-content-center g-4">

        <!-- Book Appointment -->
        <div class="col-md-3">
            <div class="card dashboard-card text-center p-4 bg-primary text-white">
                <h4>Book Appointment</h4>
                <p class="mt-2">Schedule your visit with doctors</p>
                <a href="book_appointment.php" class="btn btn-light mt-3">Open</a>
            </div>
        </div>

        <!-- Prescriptions -->
        <div class="col-md-3">
            <div class="card dashboard-card text-center p-4 bg-success text-white">
                <h4>Prescriptions</h4>
                <p class="mt-2">View your medical prescriptions</p>
                <a href="view_prescriptions.php" class="btn btn-light mt-3">Open</a>
            </div>
        </div>

        <!-- Update Profile -->
        <div class="col-md-3">
            <div class="card dashboard-card text-center p-4 bg-warning text-dark">
                <h4>Update Profile</h4>
                <p class="mt-2">Edit your personal information</p>
                <a href="update_profile.php" class="btn btn-dark mt-3">Open</a>
            </div>
        </div>
         
    </div>

    </div>
</div>

<!-- ✅ FOOTER -->
<footer>
    <a href="book_appointment.php">Appointments</a> |
    <a href="view_prescriptions.php">Prescriptions</a> |
    <a href="update_profile.php">Profile</a> |
    <a href="logout.php" class="text-danger">Logout</a>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
