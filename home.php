<?php
require 'config.php';

/* ===== FETCH ACTIVE DOCTORS FROM DOCTOR TABLE ===== */
$stmt = $conn->prepare("SELECT * FROM doctor WHERE status = 'active' ORDER BY doctor_id DESC");
$stmt->execute();
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Starlight Hospital</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background-color:#f8f9fa; scroll-behavior:smooth; }
.navbar-brand { font-weight:700; }
.nav-item { font-weight:600; }
.hero { background:linear-gradient(135deg,#0d6efd,#20c997); color:white; padding:120px 0; }
.hero h1 { font-weight:700; }
.hero .stat-box { background:rgba(255,255,255,0.18); padding:15px; border-radius:12px; }
section { padding:70px 0; }
.card { 
  border:none; 
  transition:0.3s;
  height:100px;
width:100px;   /* makes card smaller */
  width:100%;
}
.card:hover { transform:translateY(-8px); 
  box-shadow:0 15px 30px rgba(0,0,0,0.15); }
.doctor-img { 
    height:120px;   /* smaller height */
    width:120px;    /* fixed width */
    object-fit:cover; 
    border-radius:50%;   /* makes it round (optional) */
    margin:15px auto 0;  /* center image */
    display:block;
}
.facility-icon { font-size:55px; }
.trust-strip { background:#e9f2ff; }
footer { background:#212529; color:#ccc; }
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="#">🏥 Starlight Hospital</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#departments">Doctors</a></li>
        <li class="nav-item"><a class="nav-link" href="#facilities">Facilities</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="registration.php">Register</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero text-center mt-5">
  <div class="container">
    <h1>Care That Puts You First</h1>
    <p class="lead">Trusted doctors • Modern facilities • Easy appointments</p>
    <a href="appointment.php" class="btn btn-light btn-lg mt-3">Book Appointment</a>
  </div>
</section>

<!-- DOCTORS SECTION -->
<section id="departments" class="bg-light">
  <div class="container">
    <h2 class="text-center mb-5">Our Expert Doctors</h2>
    <div class="row g-4">

<?php if(!empty($doctors)): ?>
<?php foreach($doctors as $doctor): 

    // Safe image handling
    $image = "uploads/default.jpg";
    if(!empty($doctor['image']) && file_exists("uploads/".$doctor['image'])){
        $image = "uploads/".$doctor['image'];
    }
?>

<div class="col-lg-3 col-md-4 col-sm-6 d-flex justify-content-center">
  <div class="card shadow h-100">

    <img src="<?php echo htmlspecialchars($image); ?>" 
         class="doctor-img"
         alt="Doctor Image">

    <div class="card-body text-center">
      <h5 class="text-primary">
        <?php echo htmlspecialchars($doctor['specialization']); ?>
      </h5>

      <p class="fw-bold mb-1">
        Dr. <?php echo htmlspecialchars($doctor['name']); ?>
      </p>

      <?php if(!empty($doctor['experience'])): ?>
        <small class="text-muted">
          <?php echo htmlspecialchars($doctor['experience']); ?>+ Years Experience
        </small><br>
      <?php endif; ?>

      <a href="appointment.php?doctor_id=<?php echo $doctor['doctor_id']; ?>" 
         class="btn btn-outline-primary btn-sm mt-3">
         Consult Now
      </a>
    </div>

  </div>
</div>

<?php endforeach; ?>
<?php else: ?>

<div class="col-12 text-center">
  <p class="text-muted">No Doctors Available Currently.</p>
</div>

<?php endif; ?>

    </div>
  </div>
</section>

<!-- FACILITIES -->
<section id="facilities" class="bg-white">
  <div class="container">
    <h2 class="text-center mb-5">Facilities Provided</h2>
    <div class="row g-4 text-center">

      <div class="col-md-3">
       <div class="card shadow h-100 p-5 text-center">
          <div class="facility-icon">🩸</div>
          <h5 class="mt-3">Blood Bank</h5>
        </div>
      </div>

      <div class="col-md-3">
       <div class="card shadow h-100 p-5 text-center">
          <div class="facility-icon">🔬</div>
          <h5 class="mt-3">Diagnostic Lab</h5>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card shadow h-100 p-5 text-center">
          <div class="facility-icon">🚑</div>
          <h5 class="mt-3">Ambulance</h5>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card shadow h-100 p-5 text-center">
          <div class="facility-icon">💊</div>
          <h5 class="mt-3">Pharmacy</h5>
        </div>
      </div>

    </div>
  </div>
</section>

<footer class="text-center py-4">
  <p class="mb-1">📍 Pune, Maharashtra | 📞 +91 9XXXXXXX</p>
  <small>© 2026 Starlight Hospital. All Rights Reserved.</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>