<?php
// index.php
session_start();
if (isset($_SESSION['username'])) {
    header('Location: home.php'); exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>RJL Fitness</title>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <!-- your nav and background here (use your provided HTML/CSS) -->
  <div class="container mt-5">
    <?php if(isset($_GET['msg'])): ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>
    <h1 class="text-white">Welcome to RJL Powerfitness</h1>

    <!-- Login form (could be in a modal in your design) -->
    <div class="card p-4" style="max-width:420px;">
      <form action="login.php" method="POST">
        <div class="form-group">
          <label>Username</label>
          <input name="username" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input name="password" type="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="#register" data-toggle="collapse" class="btn btn-link">Register</a>
      </form>
    </div>

    <div id="register" class="collapse mt-3">
      <div class="card p-4" style="max-width:520px;">
        <form action="register.php" method="POST">
          <div class="form-group"><label>Full name</label><input name="full_name" class="form-control" required></div>
          <div class="form-group"><label>Username</label><input name="username" class="form-control" required></div>
          <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control"></div>
          <div class="form-group"><label>Phone</label><input name="phone" class="form-control"></div>
          <div class="form-group"><label>Password</label><input name="password" type="password" class="form-control" required></div>
          <button type="submit" class="btn btn-success">Register</button>
        </form>
      </div>
    </div>
  </div>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</body>
</html>