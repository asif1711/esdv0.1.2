<?php
session_start();
require 'db.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // validation
    if (empty($email) || empty($name) || empty($password)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {

        // check existing user
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Email already registered.";
        } else {
            // hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // insert user
            $stmt = $conn->prepare("INSERT INTO users (email, name, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $name, $hashed_password);

            if ($stmt->execute()) {
                $success_message = "Registration successful. Please login.";
            } else {
                $error_message = "Something went wrong.";
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>OnlyYou | Sign Up</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
</head>

<body>

<section class="auth">
  <div class="auth__container">

    <!-- LEFT -->
    <div class="auth__left">
      <div class="brand">
        <img src="img/logo.png" class="brand__logo--large" />
        <p class="brand__tagline">Secure access. Only you.</p>
      </div>
    </div>

    <!-- RIGHT -->
    <div class="auth__right">
      <div class="auth__card">
        <h2>Create Account</h2>

        <div class="divider"></div>

        <?php if ($error_message): ?>
          <div class="error-box"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <?php if ($success_message): ?>
          <div style="color:green; text-align:center; margin-bottom:10px;">
            <?= $success_message ?>
          </div>
        <?php endif; ?>

        <form method="POST">

          <div class="input-group">
            <input type="email" name="email" placeholder="Email address" required>
          </div>

          <div class="input-group">
            <input type="text" name="name" placeholder="Full Name" required>
          </div>

          <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
          </div>

          <button type="submit" id="signupBtn">
            <span class="btn-text">Sign Up</span>
          </button>

        </form>

        <p style="margin-top:15px; text-align:center;">
          Already have an account? <a href="login.php">Login</a>
        </p>

      </div>
    </div>

  </div>
</section>

<script>
document.querySelector("form").addEventListener("submit", function () {
  const btn = document.getElementById("signupBtn");
  btn.innerText = "Creating...";
  btn.disabled = true;
});
</script>

</body>
</html>