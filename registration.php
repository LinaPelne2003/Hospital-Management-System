<?php
$conn = new mysqli("localhost", "root", "", "hospital");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $usertype = $_POST['usertype'] ?? '';
    $rawpass  = $_POST['password'] ?? '';
    $specialization = $_POST['specialization'] ?? '';

    if(empty($username) || empty($email) || empty($phone) || empty($usertype) || empty($rawpass)){
        echo "<script>alert('All fields are required!');</script>";
    } else {

        $password = password_hash($rawpass, PASSWORD_BCRYPT);

        $check = $conn->prepare("SELECT id FROM registration WHERE email=?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Email already registered!');</script>";
        } else {

            $stmt = $conn->prepare(
              "INSERT INTO registration (username,email,phone,usertype,password)
               VALUES (?,?,?,?,?)"
            );
            $stmt->bind_param("sssss", $username, $email, $phone, $usertype, $password);
            $stmt->execute();

            /* ===== DOCTOR EXTRA INSERT ===== */
            if($usertype == 'doctor') {

                $imageName = '';

                if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0){

                    $uploadDir = "uploads/";
                    if(!is_dir($uploadDir)){
                        mkdir($uploadDir, 0777, true);
                    }

                    $imageName = time() . "_" . $_FILES['profile_image']['name'];
                    move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadDir . $imageName);
                }

                $insertDoctor = $conn->prepare("
                    INSERT INTO doctor 
                    (name, specialization, image, status, phone, email)
                    VALUES (?, ?, ?, 'active', ?, ?)
                ");

                $insertDoctor->bind_param(
                    "sssss",
                    $username,
                    $specialization,
                    $imageName,
                    $phone,
                    $email
                );

                $insertDoctor->execute();
            }

            echo "<script>alert('Registration Successful!'); window.location='login.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register | My Hospital</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    min-height:100vh;
    background: linear-gradient(120deg,#43cea2,#185a9d);
    display:flex;
    align-items:center;
    justify-content:center;
    font-family: 'Segoe UI', sans-serif;
}

.register-box{
    width:950px;
    background:#fff;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(0,0,0,.25);
}

.left-panel{
    background:linear-gradient(135deg,#0d6efd,#6dd5fa);
    color:#fff;
    padding:60px;
}

.left-panel h1{
    font-weight:700;
}

.register-card{
    padding:50px;
}

.form-control, .form-select{
    border-radius:10px;
}

.btn-register{
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





document.addEventListener("DOMContentLoaded", function() {
    const usertype = document.querySelector("select[name='usertype']");
    const specField = document.getElementById("specializationField");
    const imageField = document.getElementById("imageField");

    usertype.addEventListener("change", function() {
        if (this.value === "doctor") {
            specField.style.display = "block";
            imageField.style.display = "block";
        } else {
            specField.style.display = "none";
            imageField.style.display = "none";
        }
    });
});

</script>

</head>

<body>

<div class="register-box row g-0">

    <!-- LEFT PANEL -->
    <div class="col-md-5 left-panel d-flex flex-column justify-content-center">
        <h1>Join My Hospital</h1>
        <p class="mt-3">
            Create your account to access hospital services.
        </p>
        <ul class="mt-4">
            <li>✔ Doctor & Patient Portal</li>
            <li>✔ Secure Login System</li>
            <li>✔ Easy Appointments</li>
        </ul>
    </div>

    <!-- REGISTRATION FORM -->
    <div class="col-md-7 register-card">
        <h3 class="text-primary text-center mb-4">User Registration</h3>

        <form method="POST" enctype="multipart/form-data">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">User Type</label>
                <select name="usertype" class="form-select" required>
                    <option value="">Select</option>
                    <option value="doctor">Doctor</option>
                    <option value="patient">Patient</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="mb-3" id="specializationField" style="display:none;">
    <label class="form-label">Specialization</label>
    <select name="specialization" class="form-select">
        <option value="">Select Specialization</option>
        <option value="Cardiologist">Cardiologist</option>
        <option value="Neurologist">Neurologist</option>
        <option value="Orthopedic">Orthopedic</option>
        <option value="Dermatologist">Dermatologist</option>
        <option value="General Physician">General Physician</option>
    </select>
</div>

<div class="mb-3" id="imageField" style="display:none;">
    <label class="form-label">Profile Image</label>
    <input type="file" name="profile_image" class="form-control" accept="image/*">
</div>



<div class="mb-3">
    <label class="form-label">Password</label>
    
    <div class="input-group">
        <input type="password" 
               name="password"
               id="password" 
               class="form-control" 
               placeholder="Enter new password"
               required>

        <span class="input-group-text" 
              style="cursor:pointer;" 
              onclick="togglePassword()">
            👁️
        </span>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-primary w-100 btn-register">
    Register
</button>

</div>
        </form>

        <p class="text-center mt-4">
            Already registered? <a href="login.php">Login here</a>
        </p>
        <br><p class="text-center mt-4">
            Need  go to Home Page<a href="home.php">click here</a>
        </p>
    </div>

</div>

</body>
</html>
