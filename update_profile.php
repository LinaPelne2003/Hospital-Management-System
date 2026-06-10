<?php
session_start();

// ✅ Security check
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] != 'patient') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Profile | My Hospital</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#eef3ff;
    font-family:'Segoe UI', sans-serif;
}
.main{
    margin-top:90px;
    margin-bottom:90px;
}
.card{
    border-radius:16px;
    box-shadow:0 15px 30px rgba(0,0,0,.1);
}
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
<script>
function togglePassword() {
    var pass = document.getElementById("password");

    if (pass.type === "password") {
        pass.type = "text";
    } else {
        pass.type = "password";
    }
}
</script>

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
          <a class="nav-link" href="patient_dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="book_appointment.php">Book Appointment</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="view_prescriptions.php">Prescriptions</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Update Profile</a>
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
  <div class="row justify-content-center">
    <div class="col-md-6">

      <div class="card p-4 bg-white">
        <h3 class="text-center mb-3">Update Profile</h3>
        <p class="text-center text-muted">
          Keep your personal information up to date
        </p>

        <form method="POST" action="#">
          
          <!-- Name -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Full Name</label>
            <input type="text" class="form-control" placeholder="Enter your name" required>
          </div>

          <!-- Email -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Email Address</label>
            <input type="email" class="form-control" placeholder="Enter your email" required>
          </div>

          <!-- Phone -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Phone Number</label>
            <input type="text" class="form-control" placeholder="Enter phone number" required>
          </div>

          <!-- Department -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Preferred Department</label>
            <select class="form-select">
              <option value="">Select Department</option>
              <option>General</option>
              <option>Cardiology</option>
              <option>Dental</option>
              <option>Neurology</option>
            </select>
          </div>

          <hr>

          <!-- Password -->
          <h5 class="mb-3">Change Password</h5>
          <div class="mb-3">
    <label class="form-label">New Password</label>

    <div class="input-group">
        <input type="password" id="password" class="form-control" placeholder="Enter new password">

        <span class="input-group-text" style="cursor:pointer;" onclick="togglePassword()">
            👁️
        </span>
    </div>
</div>

  </div>
</div>
        <div class="mb-4">
          <button type="submit" class="btn btn-primary w-100">
            Update Profile
          </button>
        </div>
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
  <a href="book_appointment.php">Appointments</a> |
  <a href="view_prescriptions.php">Prescriptions</a>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
