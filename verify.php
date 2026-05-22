<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];
$user_name = "Attendee";
$user_email = "";
$user_phone = $_GET['phone'] ?? '';

// Fetch the user's name from database dynamically
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($name, $email);
    if ($stmt->fetch()) {
        $user_name = $name;
        $user_email = $email;
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
    <title>OnlyYou | Identity & Ticket Access</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(255, 59, 92, 0.08), transparent 30%),
                radial-gradient(circle at bottom right, rgba(255, 59, 92, 0.06), transparent 25%),
                #040b1d;
            overflow-x: hidden;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wrapper {
            min-height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .auth-layout {
            width: 100%;
            max-width: 1200px;
            display: grid;
            grid-template-columns: 1fr 480px;
            align-items: center;
            gap: 80px;
            transition: all 0.8s cubic-bezier(0.25, 1, 0.5, 1);
        }

        /* LEFT SIDE */
        .brand-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand-logo {
            width: 160px;
            margin-bottom: 30px;
            filter: drop-shadow(0 10px 30px rgba(255, 59, 92, 0.25));
        }

        .brand-title {
            font-size: 58px;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 18px;
        }

        .brand-title span {
            color: #ff3b5c;
        }

        .brand-subtitle {
            max-width: 500px;
            color: rgba(255, 255, 255, 0.68);
            font-size: 17px;
            line-height: 1.7;
        }

        .security-badges {
            margin-top: 40px;
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
        }

        .badge-card {
            padding: 14px 18px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(12px);
            min-width: 170px;
        }

        .badge-title {
            font-size: 13px;
            color: #ff5a76;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .badge-text {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.72);
            line-height: 1.5;
        }

        /* RIGHT CARD (OTP Form) */
        .verify-card {
            position: relative;
            padding: 42px;
            border-radius: 28px;
            background:
                linear-gradient(180deg,
                    rgba(16, 22, 40, 0.98),
                    rgba(10, 15, 28, 0.98));
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.45);
            overflow: hidden;
            transition: all 0.5s ease;
        }

        .verify-card::before {
            content: "";
            position: absolute;
            top: -120px;
            right: -120px;
            width: 260px;
            height: 260px;
            background: rgba(255, 59, 92, 0.12);
            border-radius: 50%;
            filter: blur(40px);
        }

        .verify-header {
            position: relative;
            z-index: 2;
            margin-bottom: 35px;
        }

        .verify-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 50px;
            background: rgba(255, 59, 92, 0.12);
            border: 1px solid rgba(255, 59, 92, 0.2);
            color: #ff6b84;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 18px;
        }

        .verify-title {
            font-size: 38px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .verify-subtitle {
            color: rgba(255, 255, 255, 0.65);
            line-height: 1.7;
            font-size: 15px;
        }

        /* INPUTS */
        .input-group-custom {
            margin-bottom: 22px;
        }

        .input-label {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;
            height: 64px;
            border: none;
            outline: none;
            border-radius: 18px;
            padding: 0 60px 0 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.06);
            color: white;
            font-size: 15px;
            transition: 0.3s ease;
        }

        .input-wrapper input:focus {
            border-color: rgba(255, 59, 92, 0.45);
            box-shadow: 0 0 0 4px rgba(255, 59, 92, 0.08);
        }

        .input-wrapper input::placeholder {
            color: rgba(255, 255, 255, 0.35);
        }

        .input-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #ff4d6d;
            font-size: 18px;
        }

        /* BUTTON */
        .verify-btn {
            width: 100%;
            height: 64px;
            border: none;
            border-radius: 18px;
            background: linear-gradient(135deg, #ff3b5c, #ff5f7b);
            color: white;
            font-size: 16px;
            font-weight: 700;
            margin-top: 8px;
            transition: 0.3s ease;
            box-shadow: 0 18px 40px rgba(255, 59, 92, 0.28);
        }

        .verify-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 24px 45px rgba(255, 59, 92, 0.35);
        }

        #verifyMessage {
            margin-top: 24px;
            padding: 16px;
            border-radius: 16px;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            display: none;
        }

        .success {
            display: block !important;
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
            color: #4ade80;
        }

        .error {
            display: block !important;
            background: rgba(255, 59, 92, 0.1);
            border: 1px solid rgba(255, 59, 92, 0.2);
            color: #ff6b84;
        }

        /* --- MICROS-ANIMATED CHECKMARK TRANSITION --- */
        .success-transition-container {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 250px;
            text-align: center;
        }

        .success-checkmark {
            width: 88px;
            height: 88px;
            margin-bottom: 24px;
        }

        .checkmark {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            display: block;
            stroke-width: 4;
            stroke: #4ade80;
            stroke-miterlimit: 10;
            box-shadow: inset 0px 0px 0px #4ade80;
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s forwards;
        }

        .checkmark-circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 4;
            stroke-miterlimit: 10;
            stroke: #4ade80;
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        .checkmark-check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }

        @keyframes stroke {
            100% {
                stroke-dashoffset: 0;
            }
        }

        @keyframes scale {
            0%, 100% {
                transform: none;
            }
            50% {
                transform: scale3d(1.1, 1.1, 1);
            }
        }

        @keyframes fill {
            100% {
                box-shadow: inset 0px 0px 0px 44px rgba(34, 197, 94, 0.1);
            }
        }

        .transition-title {
            font-size: 24px;
            font-weight: 700;
            color: #4ade80;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            animation: fadeInUp 0.5s ease-out 0.3s both;
        }

        .transition-subtitle {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.65);
            animation: fadeInUp 0.5s ease-out 0.5s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* --- PREMIUM TICKET SUCCESS SCREEN --- */
        .ticket-wrapper {
            display: none;
            width: 100%;
            max-width: 820px;
            margin: 0 auto;
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .ticket-wrapper.active {
            display: block;
            opacity: 1;
            transform: scale(1);
        }

        .ticket {
            background: linear-gradient(135deg, rgba(20, 28, 52, 0.6), rgba(10, 15, 30, 0.7));
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 30px;
            box-shadow: 
                0 30px 70px rgba(0, 0, 0, 0.6), 
                inset 0 1px 0 rgba(255, 255, 255, 0.1),
                0 0 40px rgba(255, 59, 92, 0.1);
            display: flex;
            overflow: hidden;
            position: relative;
            transform-style: preserve-3d;
            will-change: transform;
        }

        /* Glowing aura effect behind the ticket card */
        .ticket::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 30px;
            background: radial-gradient(circle at var(--mx, 50%) var(--my, 50%), rgba(255, 59, 92, 0.15), transparent 60%);
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.5s;
        }

        .ticket:hover::after {
            opacity: 1;
        }

        /* Ticket Left Side (Content) */
        .ticket-left {
            flex: 1.6;
            padding: 42px 48px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .ticket-brand {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .ticket-brand img {
            height: 38px;
            filter: drop-shadow(0 4px 10px rgba(255, 59, 92, 0.2));
        }

        .badge-verified {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(34, 197, 94, 0.25));
            border: 1px solid rgba(34, 197, 94, 0.4);
            color: #4ade80;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 6px 12px;
            border-radius: 50px;
            letter-spacing: 0.5px;
            box-shadow: 0 0 15px rgba(34, 197, 94, 0.15);
        }

        .ticket-event {
            font-family: 'Oswald', sans-serif;
            font-size: 36px;
            font-weight: 700;
            text-transform: uppercase;
            color: white;
            line-height: 1.1;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
            background: linear-gradient(120deg, #ffffff, rgba(255, 255, 255, 0.7));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .ticket-desc {
            color: rgba(255, 255, 255, 0.55);
            font-size: 14px;
            margin-bottom: 35px;
            font-weight: 400;
        }

        .ticket-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 22px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.35);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 14px;
            color: white;
            font-weight: 600;
        }

        /* Vertical Tear Divider */
        .ticket-divider {
            position: relative;
            width: 1px;
            border-left: 2px dashed rgba(255, 255, 255, 0.12);
            height: auto;
            margin: 20px 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notch {
            position: absolute;
            width: 26px;
            height: 26px;
            background: #040b1d;
            border-radius: 50%;
            left: -14px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.8);
        }

        .notch-top {
            top: -34px;
        }

        .notch-bottom {
            bottom: -34px;
        }

        /* Ticket Right Side (QR) */
        .ticket-right {
            flex: 1;
            padding: 42px;
            background: rgba(255, 255, 255, 0.01);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            border-top-right-radius: 30px;
            border-bottom-right-radius: 30px;
        }

        .qr-label {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.35);
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .qr-frame {
            padding: 14px;
            background: white;
            border-radius: 20px;
            box-shadow: 
                0 15px 35px rgba(0, 0, 0, 0.4), 
                0 0 25px rgba(255, 59, 92, 0.1);
            transition: transform 0.4s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-frame:hover {
            transform: scale(1.05);
        }

        .qr-frame img {
            width: 148px;
            height: 148px;
            display: block;
        }

        .qr-status {
            font-size: 12px;
            color: #ff5f7b;
            font-weight: 600;
            margin-top: 20px;
            letter-spacing: 0.5px;
        }

        /* Ticket Action Buttons */
        .ticket-actions {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 16px;
        }

        .btn-action {
            height: 54px;
            padding: 0 28px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none !important;
        }

        .btn-print {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
        }

        .btn-print:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .btn-home {
            background: linear-gradient(135deg, #ff3b5c, #ff5f7b);
            color: white;
            box-shadow: 0 10px 25px rgba(255, 59, 92, 0.2);
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(255, 59, 92, 0.3);
            color: white;
        }

        /* RESPONSIVE DESIGN */
        @media(max-width: 992px) {
            body {
                overflow: auto;
            }

            .auth-layout {
                grid-template-columns: 1fr;
                gap: 50px;
            }

            .brand-section {
                text-align: center;
                align-items: center;
            }

            .brand-title {
                font-size: 42px;
            }

            .ticket {
                flex-direction: column;
            }

            .ticket-left {
                padding: 30px;
            }

            .ticket-right {
                padding: 35px 30px;
                border-top-right-radius: 0;
                border-bottom-left-radius: 30px;
            }

            .ticket-divider {
                width: calc(100% - 60px);
                height: 1px;
                border-left: none;
                border-top: 2px dashed rgba(255, 255, 255, 0.12);
                margin: 0 auto;
            }

            .notch {
                top: -14px;
            }

            .notch-top {
                left: -34px;
                right: auto;
            }

            .notch-bottom {
                right: -34px;
                left: auto;
            }
        }
    </style>
</head>

<body>

    <div class="wrapper">

        <div class="auth-layout" id="mainLayout">

            <!-- LEFT BRAND SECTION -->
            <div class="brand-section" id="brandSection">

                <img src="img/logo.png" class="brand-logo">

                <h1 class="brand-title">
                    Secure Identity <br>
                    <span>Verification</span>
                </h1>

                <p class="brand-subtitle">
                    Multi-layer authentication system powered by secure OTP validation.
                    Access is granted only after successful identity confirmation.
                </p>

                <div class="security-badges">

                    <div class="badge-card">
                        <div class="badge-title">Biometric Security</div>
                        <div class="badge-text">
                            AI-assisted layered identity validation system.
                        </div>
                    </div>

                    <div class="badge-card">
                        <div class="badge-title">OTP Protection</div>
                        <div class="badge-text">
                            Secure one-time passcode verification layer enabled.
                        </div>
                    </div>

                </div>

            </div>

            <!-- RIGHT SECURE CARD (OTP FORM) -->
            <div class="verify-card" id="formCard">

                <div class="verify-header">

                    <div class="verify-label">
                        ● Secure Access Layer
                    </div>

                    <h2 class="verify-title">
                        Verify Access Code
                    </h2>

                    <p class="verify-subtitle">
                        Enter the verification code sent to your registered phone number
                        to continue secure authentication.
                    </p>

                </div>

                <form id="verifyForm">

                    <div class="input-group-custom">

                        <label class="input-label">
                            Phone Number
                        </label>

                        <div class="input-wrapper">

                            <input type="text" id="verify_phone" name="phone" placeholder="+91 XXXXX XXXXX" value="<?= htmlspecialchars($user_phone) ?>" required>

                            <span class="input-icon">
                                ☎
                            </span>

                        </div>

                    </div>

                    <div class="input-group-custom">

                        <label class="input-label">
                            Access Code
                        </label>

                        <div class="input-wrapper">

                            <input type="text" id="verify_code" name="code" placeholder="Enter secure OTP" required>

                            <span class="input-icon">
                                ✦
                            </span>

                        </div>

                    </div>

                    <button type="submit" class="verify-btn">
                        Verify Identity
                    </button>

                </form>

                <div id="verifyMessage"></div>

                <!-- Circular drawing checkmark inside form card to play during success transition -->
                <div class="success-transition-container" id="successTransition">
                    <div class="success-checkmark">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" />
                            <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                        </svg>
                    </div>
                    <div class="transition-title">Access Granted</div>
                    <div class="transition-subtitle">Generating secure event ticket...</div>
                </div>

            </div>

        </div>

        <!-- STUNNING HOVER-INTERACTIVE GLASSMORPHIC EVENT TICKET SUCCESS SCREEN -->
        <div class="ticket-wrapper" id="ticketWrapper">
            <div class="ticket">
                <!-- Left Details -->
                <div class="ticket-left">
                    <div class="ticket-brand">
                        <img src="img/logo.png" alt="OnlyYou Logo">
                        <span class="badge-verified">✓ Verified Access</span>
                    </div>

                    <div>
                        <h2 class="ticket-event">Design Systems for Scale</h2>
                        <p class="ticket-desc">With Adam Cooper, Lead Product Designer</p>
                    </div>

                    <div class="ticket-info-grid">
                        <div class="info-item">
                            <span class="info-label">Attendee Name</span>
                            <span class="info-value"><?= htmlspecialchars($user_name) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Date & Time</span>
                            <span class="info-value">AUG 20 • 6:00 PM</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Venue</span>
                            <span class="info-value">Silicon Valley Cyber-Hub</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Ticket ID</span>
                            <span class="info-value">ONLYYOU-<?= str_pad($user_id, 5, '0', STR_PAD_LEFT) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Tear Tear Tear -->
                <div class="ticket-divider">
                    <div class="notch notch-top"></div>
                    <div class="notch notch-bottom"></div>
                </div>

                <!-- Right QR Code -->
                <div class="ticket-right">
                    <div class="qr-label">Scan for Entry</div>
                    <div class="qr-frame">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=ONLYYOU-TICKET-<?= urlencode($user_id . '-' . $user_name) ?>&color=ff3b5c&bgcolor=ffffff" alt="Entry QR Code">
                    </div>
                    <div class="qr-status">Access Level: VIP Attendee</div>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="ticket-actions">
                <button onclick="window.print()" class="btn-action btn-print">
                    <span>🖨️ Print Ticket</span>
                </button>
                <a href="index.php" class="btn-action btn-home">
                    <span>🏠 Return to Dashboard</span>
                </a>
            </div>
        </div>

    </div>

    <!-- JS Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script>
        document.getElementById("verifyForm").addEventListener("submit", function (e) {
            e.preventDefault();

            const phone = document.getElementById("verify_phone").value;
            const code = document.getElementById("verify_code").value;

            const formData = new FormData();
            formData.append("phone", phone);
            formData.append("code", code);

            fetch("verify-code.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                const msg = document.getElementById("verifyMessage");
                msg.innerText = data.message;

                if (data.message.toLowerCase().includes("success")) {
                    msg.className = "success";
                    msg.style.display = "block";

                    // Trigger the success micro-animations
                    setTimeout(() => {
                        triggerSuccessTransition();
                    }, 800);
                } else {
                    msg.className = "error";
                    msg.style.display = "block";
                }
            })
            .catch(err => {
                console.error(err);
                const msg = document.getElementById("verifyMessage");
                msg.innerText = "Verification service unavailable";
                msg.className = "error";
                msg.style.display = "block";
            });
        });

        function triggerSuccessTransition() {
            const form = document.getElementById("verifyForm");
            const verifyHeader = document.querySelector(".verify-header");
            const verifyMsg = document.getElementById("verifyMessage");
            const successTransition = document.getElementById("successTransition");
            const formCard = document.getElementById("formCard");

            // Fade out the Form details
            form.style.opacity = "0";
            form.style.transform = "translateY(-15px)";
            form.style.transition = "all 0.5s ease";
            verifyHeader.style.opacity = "0";
            verifyHeader.style.transform = "translateY(-15px)";
            verifyHeader.style.transition = "all 0.5s ease";
            verifyMsg.style.display = "none";

            setTimeout(() => {
                form.style.display = "none";
                verifyHeader.style.display = "none";

                // Show drawing checkmark container
                successTransition.style.display = "flex";

                // Expand form card dimensions into a neat container for the checkmark
                formCard.style.padding = "60px";

                // After 1.8 seconds of visual celebration, transition to full Ticket view
                setTimeout(() => {
                    revealPremiumTicket();
                }, 2200);

            }, 450);
        }

        function revealPremiumTicket() {
            const mainLayout = document.getElementById("mainLayout");
            const ticketWrapper = document.getElementById("ticketWrapper");

            // Shrink and fade out the whole original brand/form dual column layout
            mainLayout.style.opacity = "0";
            mainLayout.style.transform = "scale(0.92)";
            mainLayout.style.transition = "all 0.8s cubic-bezier(0.25, 1, 0.5, 1)";

            setTimeout(() => {
                mainLayout.style.display = "none";

                // Fade in and scale up the glassmorphic ticket success screen
                ticketWrapper.classList.add("active");

                // Initialize 3D Holographic Parallax Effect on the ticket
                init3DTilt();

            }, 750);
        }

        function init3DTilt() {
            const ticket = document.querySelector('.ticket');
            if (!ticket) return;

            ticket.addEventListener('mousemove', (e) => {
                const rect = ticket.getBoundingClientRect();
                const x = e.clientX - rect.left; // x coordinate inside element
                const y = e.clientY - rect.top;  // y coordinate inside element
                
                const xc = rect.width / 2;
                const yc = rect.height / 2;
                
                const dx = x - xc;
                const dy = y - yc;
                
                // Tilt calculation
                const tiltX = -(dy / yc) * 6; // Max tilt 6 degrees
                const tiltY = (dx / xc) * 6;  // Max tilt 6 degrees
                
                // Apply dynamic variables for radial gradient light source tracking
                ticket.style.setProperty('--mx', `${(x / rect.width) * 100}%`);
                ticket.style.setProperty('--my', `${(y / rect.height) * 100}%`);
                
                ticket.style.transform = `perspective(1200px) rotateX(${tiltX}deg) rotateY(${tiltY}deg) scale3d(1.02, 1.02, 1.02)`;
                ticket.style.transition = 'transform 0.1s ease, box-shadow 0.1s ease';
                ticket.style.boxShadow = `
                    ${-tiltY * 2}px ${tiltX * 2}px 40px rgba(0, 0, 0, 0.6),
                    0 30px 70px rgba(0, 0, 0, 0.5),
                    0 0 50px rgba(255, 59, 92, 0.15)
                `;
            });

            ticket.addEventListener('mouseleave', () => {
                ticket.style.transform = 'perspective(1200px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
                ticket.style.transition = 'transform 0.6s cubic-bezier(0.25, 1, 0.5, 1), box-shadow 0.6s cubic-bezier(0.25, 1, 0.5, 1)';
                ticket.style.boxShadow = '0 30px 70px rgba(0, 0, 0, 0.6), 0 0 40px rgba(255, 59, 92, 0.1)';
            });
        }
    </script>

</body>

</html>
