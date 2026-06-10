<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - My Hospital</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .contact-card {
      max-width: 700px;
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
          <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="appointment.php">Appointment</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Contact Form -->
  <div class="container d-flex justify-content-center align-items-center" style="min-height: 90vh;">
    <div class="card contact-card shadow-lg p-4">
      <h3 class="text-center text-primary mb-4">📞 Contact Us</h3>
      <form>
        
        <!-- Name -->
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>

        <!-- Phone -->
        <div class="mb-3">
          <label class="form-label">Phone Number</label>
          <input type="tel" name="phone" class="form-control" placeholder="Enter your phone number" required>
        </div>

        <!-- Message -->
        <div class="mb-3">
          <label class="form-label">Your Message</label>
          <textarea name="message" class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-secondary w-100">Send Message</button>

      </form>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
