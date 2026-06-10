<?php
session_start();

$conn = new mysqli("localhost", "root", "", "hospital");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usernameOrEmail = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM registration WHERE email=? OR username=? LIMIT 1");
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        if (
            password_verify($password, $row['password']) ||
            $password === $row['password']
        ) {

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['usertype'] = strtolower($row['usertype']);

            if ($_SESSION['usertype'] == "doctor") {
                header("Location: doctor_dashboard.php");
                exit();
            }

            if ($_SESSION['usertype'] == "patient") {
                header("Location: patient_dashboard.php");
                exit();
            }

            if ($_SESSION['usertype'] == "admin") {
                header("Location: admin_dashboard.php");
                exit();
            }

        } else {
            echo "<script>alert('Invalid Password');</script>";
        }

    } else {
        echo "<script>alert('User not found');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login | My Hospital</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    min-height:100vh;
    background: linear-gradient(120deg,#007bff,#6dd5fa);
    display:flex;
    align-items:center;
    justify-content:center;
    font-family: 'Segoe UI', sans-serif;
}

.login-box{
    width:900px;
    background:#fff;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(0,0,0,.2);
}

.left-panel{
    background:linear-gradient(135deg,#0d6efd,#4facfe);
    color:#fff;
    padding:60px;
}

.left-panel h1{
    font-weight:700;
}

.left-panel p{
    opacity:.9;
}

.login-card{
    padding:60px;
}

.form-control{
    border-radius:10px;
}

.btn-login{
    border-radius:10px;
    font-weight:600;
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

<div class="login-box row g-0">

    <!-- LEFT INFO PANEL -->
    <div class="col-md-6 left-panel d-flex flex-column justify-content-center">
        <h1>My Hospital</h1>
        <p class="mt-3">
            Secure Hospital Management System<br>
            Admin • Doctor • Patient Access
        </p>
        <ul class="mt-4">
            <li>✔ Secure Login</li>
            <li>✔ Role Based Dashboard</li>
            <li>✔ Easy Appointment System</li>
        </ul>
    </div>

    <!-- RIGHT LOGIN FORM -->
    <div class="col-md-6 login-card">
        <h3 class="text-primary mb-4 text-center">User Login</h3>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Email or Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                
               <div class="input-group">
        <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>

        <span class="input-group-text" style="cursor:pointer;" onclick="togglePassword()">
            👁️
        </span>
    </div>

            <button class="btn btn-primary w-100 btn-login">Login</button>

        </form>

        <p class="text-center mt-4">
            New user? <a href="registration.php">Register here</a>
        </p>
          <br><p class="text-center mt-4">
            Need  go to Home Page<a href="home.php">click here</a>
        </p>
    </div>

</div>

</body>
</html>
