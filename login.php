<?php
session_start();

// DB connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'vips';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];

            header('Location: /esd/index.php');
            exit();
        } else {
            $error_message = "Incorrect password.";
        }
    } else {
        $error_message = "No user found with that email.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnlyYou | Login</title>

    <!-- Modern Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Base + Auth CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
</head>

<body>

<!-- LOGIN SECTION -->
<section class="auth">
  <div class="auth__container">

    <!-- LEFT SIDE -->
    <div class="auth__left">
      <div class="brand">
        <img src="img/logo.png" class="brand__logo--large" />
        <p class="brand__tagline">Secure access. Only you.</p>
      </div>
    </div>

    <!-- RIGHT SIDE -->
    <div class="auth__right">
      <div class="auth__card">
        <h2>Access Portal</h2>

        <div class="divider"></div>

        <?php if ($error_message): ?>
          <div class="error-box">
            <?= htmlspecialchars($error_message) ?>
          </div>
        <?php endif; ?>

        <form method="POST" id="loginForm">

          <div class="input-group">
            <input type="email" name="email" placeholder="Email address" required>
          </div>

          <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
          </div>

          <button type="submit" id="loginBtn">
            <span class="btn-text">Login</span>

            <span class="btn-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                <path d="M5 12H19M19 12L13 6M19 12L13 18"
                        stroke="white"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"/>
                </svg>
            </span>
          </button>

        </form>

      </div>
    </div>

  </div>
</section>

<!-- UX Script -->
<script>
document.getElementById("loginForm").addEventListener("submit", function () {
  const btn = document.getElementById("loginBtn");
  btn.innerText = "Logging in...";
  btn.disabled = true;
});
</script>

</body>
</html>