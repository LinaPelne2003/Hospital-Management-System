<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $patient_name = $_POST['patient_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];
    $date = $_POST['date'];
    $message = $_POST['message'];

    // Just insert appointment with department
    $insert = $conn->prepare("
        INSERT INTO appointments 
        (patient_name, age, gender, phone, department, date, message, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')
    ");

    $insert->execute([
        $patient_name,
        $age,
        $gender,
        $phone,
        $department,
        $date,
        $message
    ]);

    echo "<script>alert('Appointment Booked Successfully!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Appointment - My Hospital</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .appointment-card {
      max-width: 750px;
      margin: auto;
      border-radius: 15px;
    }
    .navbar-brand {
      font-weight: bold;
    }
    li{
      font-size: x-large;
    }
  </style>
</head>
<body>

  <!-- Navbar (same as homepage) -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand" href="index.html">My Hospital</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="Login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link active" href="appointment.php">Appointment</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Appointment Form -->
  <div class="container d-flex justify-content-center align-items-center" style="min-height: 90vh;">
    <div class="card appointment-card shadow-lg p-4">
      <h3 class="text-center text-primary mb-4">🩺 Book an Appointment</h3>
      <form method="POST">

        <!-- Patient Name -->
        <div class="mb-3">
          <label class="form-label">Patient Name</label>
          <input type="text" name="patient_name" class="form-control" placeholder="Enter patient's full name" required>
        </div>

        <!-- Age -->
        <div class="mb-3">
          <label class="form-label">Age</label>
          <input type="number" name="age" class="form-control" placeholder="Enter age" required>
        </div>

        <!-- Gender -->
        <div class="mb-3">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-control" required>
            <option value="">Select Gender</option>
            <option>Male</option>
            <option>Female</option>
            <option>Other</option>
          </select>
        </div>

        <!-- Phone -->
        <div class="mb-3">
          <label class="form-label">Phone Number</label>
          <input type="tel" name="phone" class="form-control" placeholder="Enter contact number" required>
        </div>

        <!-- Department -->
        <div class="mb-3">
          <label class="form-label">Select Department</label>
          <select name="department" class="form-control" required>
            <option value="">Choose Department</option>
            <option>Cardiology</option>
            <option>Orthopedics</option>
            <option>Neurology</option>
            <option>Pediatrics</option>
            <option>General Medicine</option>
          </select>
        </div>

        
        

        <!-- Date -->
        <div class="mb-3">
          <label class="form-label">Preferred Date</label>
          <input type="date" name="date" class="form-control" required>
        </div>

        

        <!-- Message -->
        <div class="mb-3">
          <label class="form-label">Additional Notes</label>
          <textarea name="message" class="form-control" rows="3" placeholder="Enter symptoms or special request..."></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-success w-100">Book Appointment</button>

      </form>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
