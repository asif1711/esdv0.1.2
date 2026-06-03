<?php

session_start();

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['face_verified']) ||
    !isset($_SESSION['otp_verified'])
) {

    header("Location: /esd/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ESD</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/plyr.css" type="text/css">
    <link rel="stylesheet" href="css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/custom.css" type="text/css"> 
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Header Section Begin -->
    <header class="header">
        <div class="container">
            <div class="row">
                <div class="col-lg-2">
                    <div class="header__logo">
                        <a href="index.php">
                            <img src="img/logo.png" class="logo-main">
                        </a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="header__nav">
                        <nav class="header__menu mobile-menu">
                            <ul>
    
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="header__right">
                        <div class="header-user">
                        <a href="logout.php" class="logout-icon">
                            <img src="img/sign-out.svg" class="logout-img">
                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div id="mobile-menu-wrap"></div>
        </div>
    </header>
    <!-- Header End -->

    <!-- Hero Section Begin -->
    <section class="hero-featured">
  <div class="hero-featured__main">

    <img src="img/events/1.jpg" alt="">

    <div class="hero-featured__overlay" style="min-width: 800px;">
      <span class="event-time">AUG 20 • 6:00 PM</span>

      <h1 style="font-family:'Inter',sans-serif;">Design Systems for Scale</h1>
      <p style="font-family:'Inter',sans-serif;">With Adam Cooper, Lead Product Designer</p>

      <a href="event.php" class="btn-primary">
        <span class="btn-text" style="font-family:'Inter',sans-serif;">Access Ticket</span>
        <span class="btn-edge-icon">
            <img src="img/arrow-2.svg" alt="">
        </span>
      </a>
    </div>

  </div>

  <!-- Thumbnail Strip -->
  <div class="hero-thumbnails">

    <div class="thumb active">
      <img src="img/events/1.jpg">
      <span style="font-family:'Inter',sans-serif;">Design Systems</span>
    </div>

    <div class="thumb">
      <img src="img/events/2.webp">
      <span style="font-family:'Inter',sans-serif;">Startup Meetup</span>
    </div>

    <div class="thumb">
      <img src="img/events/3.webp">
      <span style="font-family:'Inter',sans-serif;">Security Summit</span>
    </div>

  </div>
</section>
    <!-- Hero Section End -->

    <!-- Product Section Begin -->
    <section class="dashboard-preview">
  <div class="container">

    <h2 style="font-family:'Inter',sans-serif;">Quick Access</h2>

    <div class="dashboard-grid">

      <div class="dashboard-card">
        <h3 style="font-family:'Inter',sans-serif;">Events</h3>
        <p style="font-family:'Inter',sans-serif;">Access and manage all events</p>
        <a href="event.php" style="font-family:'Inter',sans-serif;">Open →</a>
      </div>

      <div class="dashboard-card">
        <h3 style="font-family:'Inter',sans-serif;">Profile</h3>
        <p style="font-family:'Inter',sans-serif;">Manage your account settings</p>
        <a href="#" style="font-family:'Inter',sans-serif;">Open →</a>
      </div>

      <div class="dashboard-card">
        <h3 style="font-family:'Inter',sans-serif;">Security</h3>
        <p style="font-family:'Inter',sans-serif;">Control access and permissions</p>
        <a href="#" style="font-family:'Inter',sans-serif;">Open →</a>
      </div>

    </div>

  </div>
</section>
<!-- Product Section End -->

  <!-- Search model Begin -->
  <div class="search-model">
    <div class="h-100 d-flex align-items-center justify-content-center">
        <div class="search-close-switch"><i class="icon_close"></i></div>
        <form class="search-model-form">
            <input type="text" id="search-input" placeholder="Search here.....">
        </form>
    </div>
</div>
<!-- Search model end -->

<!-- Js Plugins -->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/player.js"></script>
<script src="js/jquery.nice-select.min.js"></script>
<script src="js/mixitup.min.js"></script>
<script src="js/jquery.slicknav.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/main.js"></script>


</body>

</html>