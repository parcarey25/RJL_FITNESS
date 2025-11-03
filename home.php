<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>RJL Fitness | Home</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
<style>
/* === STYLE SECTION === */
body {
  background-color: #111;
  color: #fff;
  font-family: 'Poppins', sans-serif;
}
.navbar {
  background: linear-gradient(90deg, #000, #b30000);
  box-shadow: 0 2px 8px rgba(0,0,0,0.5);
}
.navbar-brand {
  font-weight: bold;
  color: #fff !important;
  letter-spacing: 1px;
}
.navbar-text { font-size: 1rem; }
.btn-danger { background-color: #b30000; border: none; }
.btn-danger:hover { background-color: #ff1a1a; }

/* Profile circle */
.profile-circle {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  overflow: hidden;
  border: 2px solid #ff3333;
  cursor: pointer;
  background-color: #222;
  transition: transform 0.2s, box-shadow 0.3s;
}
.profile-circle:hover {
  transform: scale(1.05);
  box-shadow: 0 0 10px #ff3333;
}
.profile-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Dashboard box */
.dashboard {
  background: #1a1a1a;
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0 0 15px rgba(255, 0, 0, 0.2);
  transition: transform 0.3s;
}
.dashboard:hover { transform: scale(1.01); }
.dashboard h2 { color: #ff3333; font-weight: 600; }

.btn-outline-light {
  border-color: #ff3333;
  color: #fff;
  transition: 0.3s;
}
.btn-outline-light:hover { background-color: #ff3333; color: #fff; }

.summary-card {
  background: #222;
  border-radius: 10px;
  padding: 20px;
  margin-top: 20px;
  border-left: 4px solid #ff3333;
}

footer {
  text-align: center;
  margin-top: 40px;
  padding: 15px;
  color: #aaa;
  background-color: #000;
  font-size: 0.9rem;
}

/* Modal animation */
.slide-down .modal-dialog {
  transform: translateY(-50px);
  transition: transform 0.4s ease-out, opacity 0.3s ease;
}
.slide-down.show .modal-dialog { transform: translateY(0); }

/* Fade-in content */
.fade-in {
  opacity: 0;
  animation: fadeIn 1s ease-in forwards;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<body>

<!-- ✅ SINGLE NAVBAR -->
<nav class="navbar navbar-dark">
  <div class="d-flex align-items-center">
    <a class="navbar-brand ml-3" href="#">
      <img src="photo/logo.jpg" height="35" class="mr-2" alt="RJL Fitness"> RJL Fitness
    </a>
  </div>

  <div class="d-flex align-items-center">
    <span class="navbar-text text-white mr-3">
      Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
    </span>

    <!-- ✅ One profile button -->
    <div class="profile-circle mr-3" id="profileBtn">
      <img src="photo/logo.jpg" alt="Profile" class="profile-img">
    </div>
  </div>
</nav>

<div class="container mt-5 fade-in">
  <div class="dashboard">
    <h2>Dashboard</h2>
    <p class="text-light">Manage and view your gym system below:</p>

    <div class="row mt-4">
      <div class="col-md-4">
        <a href="members.php" class="btn btn-outline-light btn-block">👥 Members</a>
        <a href="plans.php" class="btn btn-outline-light btn-block mt-2">📋 Plans</a>
        <a href="attendance.php" class="btn btn-outline-light btn-block mt-2">🕒 Attendance</a>
        <a href="payments.php" class="btn btn-outline-light btn-block mt-2">💳 Payments</a>
      </div>

      <div class="col-md-8">
        <div class="summary-card">
          <h4 class="text-danger">📊 Summary</h4>
          <?php
            $totalMembers = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='member'")->fetch_assoc()['c'];
            $totalTrainers = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='trainer'")->fetch_assoc()['c'];
            echo "<p><strong>Total Members:</strong> $totalMembers</p>";
            echo "<p><strong>Total Trainers:</strong> $totalTrainers</p>";
          ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ✅ Profile Modal -->
<div class="modal fade slide-down" id="profileModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content bg-dark text-white" style="border: 2px solid #ff3333;">
      <div class="modal-header border-danger">
        <h5 class="modal-title text-danger">👤 User Profile</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body text-center">
        <img src="images/profile.png" class="rounded-circle mb-3" width="100" height="100" alt="Profile">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>

        <?php
          $id = $_SESSION['user_id'];
          $res = $conn->query("SELECT email, full_name, id_number, valid_id FROM users WHERE id = $id");
          if ($res && $info = $res->fetch_assoc()) {
              echo "<p><strong>Full Name:</strong> " . htmlspecialchars($info['full_name']) . "</p>";
              echo "<p><strong>Email:</strong> " . htmlspecialchars($info['email']) . "</p>";
              echo "<p><strong>ID Number:</strong> " . htmlspecialchars($info['id_number'] ?? 'Not set') . "</p>";
              echo "<p><strong>Valid ID:</strong> " . htmlspecialchars($info['valid_id'] ?? 'Not uploaded') . "</p>";
          }
        ?>
      </div>
      <div class="modal-footer border-danger d-flex justify-content-between">
        <a href="change_password.php" class="btn btn-outline-light">Change Password</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
      </div>
    </div>
  </div>
</div>

<footer>
  © <?php echo date('Y'); ?> RJL Fitness Gym. All Rights Reserved.
</footer>

<!-- ✅ JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function(){
  $("#profileBtn").on("click", function(){
    $("#profileModal").modal("show");
  });
});
</script>
</body>
</html>