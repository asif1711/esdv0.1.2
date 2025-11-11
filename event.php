<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Generate Access Code</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/plyr.css" type="text/css">
    <link rel="stylesheet" href="css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body>
    <!-- Header Section Begin -->
    <header class="header">
        <div class="container">
            <div class="row">
                <div class="col-lg-2">
                    <div class="header__logo">
                        <a href="./login.php"><img src="img/logo.png" alt=""></a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Header End -->

    <!-- Breadcrumb Section Begin -->
    <section class="normal-breadcrumb set-bg" style="margin-bottom: 10px;" data-setbg="img/normal-breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="normal__breadcrumb__text">
                        <h2>Generate Access Code</h2>
                        <p>Securely verify your phone number</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Form Section Begin -->
    <section class="login spad" style="padding-top: 30px; padding-bottom: 30px;">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-6">
                    <div class="login__form">
                        <h3>Enter Your Mobile Number</h3>
                        <form id="accessCodeForm" method="POST" action="/esd/generate-access-code.php">
							<div class="input__item">
								<input type="text" id="phone" name="phone" placeholder="Phone Number" required>
								<span class="icon_phone"></span>
							</div>
							<button type="submit" class="site-btn">Generate and Send Code</button>
						</form>

						<p id="responseMessage" class="mt-3 text-center"></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Form Section End -->

    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="footer__logo">
                        <a href="./login.php"><img src="img/logo.png" alt=""></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
		document.getElementById("accessCodeForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const phone = document.getElementById("phone").value;

    const formData = new FormData();
    formData.append('phone', phone);

    fetch("/esd/generate-access-code.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
    const responseMessage = document.getElementById("responseMessage");
    responseMessage.textContent = data.message;
    responseMessage.style.color = data.message.includes("successfully") ? "green" : "red";

    // Add Verify Code button if SMS sent successfully
    if (data.message.includes("successfully")) {
        if (!document.getElementById("verifyBtn")) {
            const btn = document.createElement("a");
            btn.href = "verify.html";
            btn.id = "verifyBtn";
            btn.textContent = "Go to Verify Code";
            btn.className = "site-btn mt-3 d-inline-block";
            responseMessage.insertAdjacentElement("afterend", btn);
        }
    }
})

    .catch(err => {
        console.error("Error:", err);
    });
});

    </script>
</body>

</html>
