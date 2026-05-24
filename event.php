<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];
$user_name = "Attendee";
$user_email = "";

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
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>OnlyYou | Ticket Access & Verification</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(255, 59, 92, 0.08), transparent 30%),
                radial-gradient(circle at bottom right, rgba(255, 59, 92, 0.06), transparent 25%),
                #040b1d;
            overflow-x: hidden;
            color: white;
            min-height: 100vh;
        }

        .header {
            background: rgba(4, 11, 29, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .login__form {
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
            transition: all 0.6s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .login__form::before {
            content: "";
            position: absolute;
            top: -120px;
            right: -120px;
            width: 260px;
            height: 260px;
            background: rgba(255, 59, 92, 0.1);
            border-radius: 50%;
            filter: blur(40px);
            pointer-events: none;
        }

        .login__form h3 {
            color: #ffffff;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 12px;
            text-align: center;
        }

        .login__form p.form-subtitle {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            text-align: center;
            margin-bottom: 30px;
        }

        .input__item {
            position: relative;
            margin-bottom: 22px;
        }

        .input__item input {
            width: 100%;
            height: 64px;
            border: none;
            outline: none;
            border-radius: 18px;
            padding: 0 60px 0 24px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.06);
            color: white;
            font-size: 15px;
            transition: 0.3s ease;
        }

        .input__item input:focus {
            border-color: rgba(255, 59, 92, 0.45);
            box-shadow: 0 0 0 4px rgba(255, 59, 92, 0.08);
        }

        .input__item span {
            position: absolute;
            right: 22px;
            top: 50%;
            transform: translateY(-50%);
            color: #ff3b5c;
            font-size: 20px;
        }

        .site-btn {
            width: 100%;
            height: 64px;
            border: none;
            border-radius: 18px;
            background: linear-gradient(135deg, #ff3b5c, #ff5f7b);
            color: white;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s ease;
            box-shadow: 0 18px 40px rgba(255, 59, 92, 0.25);
        }

        .site-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 24px 45px rgba(255, 59, 92, 0.35);
        }

        #responseMessage {
            padding: 14px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.5;
            display: none;
        }

        .success-msg {
            display: block !important;
            background: rgba(34, 197, 94, 0.08);
            border: 1px solid rgba(34, 197, 94, 0.2);
            color: #4ade80;
        }

        .error-msg {
            display: block !important;
            background: rgba(255, 59, 92, 0.08);
            border: 1px solid rgba(255, 59, 92, 0.2);
            color: #ff6b84;
        }

        /* Verification Form Inline slide-down style */
        .verify-section {
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            margin-top: 28px;
            padding-top: 28px;
        }

        .verify-section h4 {
            color: white;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            text-align: center;
        }

        /* Checkmark animations */
        .success-transition-container {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 220px;
            text-align: center;
        }

        .success-checkmark {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
        }

        .checkmark {
            width: 80px;
            height: 80px;
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
            100% { stroke-dashoffset: 0; }
        }

        @keyframes scale {
            0%, 100% { transform: none; }
            50% { transform: scale3d(1.1, 1.1, 1); }
        }

        @keyframes fill {
            100% { box-shadow: inset 0px 0px 0px 40px rgba(34, 197, 94, 0.1); }
        }

        .transition-title {
            font-size: 22px;
            font-weight: 700;
            color: #4ade80;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .transition-subtitle {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
        }

        /* --- PREMIUM TICKET SUCCESS SCREEN --- */
        .ticket-wrapper {
            display: none;
            width: 100%;
            max-width: 820px;
            margin: 40px auto;
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

        .notch-top { top: -34px; }
        .notch-bottom { bottom: -34px; }

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

        @media(max-width: 992px) {
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

            .notch { top: -14px; }
            .notch-top { left: -34px; right: auto; }
            .notch-bottom { right: -34px; left: auto; }
        }

        /* --- PREMIUM TICKET PRINT STYLING --- */
        @media print {
            /* Hide all page layouts, menus, buttons, footers, headers */
            header.header,
            .normal-breadcrumb,
            footer.footer,
            .ticket-actions,
            #formRow,
            #mainLayout,
            .brand-section,
            .verify-card,
            #generateFlow,
            #verifyFlow,
            #verifyMessage {
                display: none !important;
            }

            /* Reset background and colors for clean print */
            html, body {
                background: #040b1d !important;
                color: white !important;
                margin: 0 !important;
                padding: 0 !important;
                height: 100% !important;
                width: 100% !important;
                overflow: hidden !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Clear any wrapper limits and paddings */
            .wrapper, .spad, #mainContainer {
                min-height: auto !important;
                padding: 0 !important;
                margin: 0 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                height: 100vh !important;
                width: 100% !important;
            }

            /* Center and format the ticket */
            .ticket-wrapper {
                display: block !important;
                opacity: 1 !important;
                transform: none !important;
                max-width: 820px !important;
                width: 90% !important;
                margin: auto !important;
                padding: 0 !important;
                box-sizing: border-box !important;
                page-break-inside: avoid !important;
            }

            .ticket {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                background: linear-gradient(135deg, #141c34, #0a0f1e) !important;
                backdrop-filter: none !important;
                border: 1px solid rgba(255, 255, 255, 0.15) !important;
                border-radius: 30px !important;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5) !important;
                transform: none !important;
                opacity: 1 !important;
                width: 100% !important;
                box-sizing: border-box !important;
                page-break-inside: avoid !important;
            }

            .ticket::after {
                display: none !important;
            }

            /* Force details to stay horizontal on print */
            .ticket-left {
                flex: 1.6 !important;
                padding: 40px !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
                box-sizing: border-box !important;
            }

            .ticket-brand {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                margin-bottom: 24px !important;
            }

            .ticket-brand img {
                height: 38px !important;
            }

            .badge-verified {
                display: inline-flex !important;
                background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(34, 197, 94, 0.25)) !important;
                border: 1px solid rgba(34, 197, 94, 0.4) !important;
                color: #4ade80 !important;
            }

            .ticket-divider {
                display: flex !important;
                width: 1px !important;
                height: auto !important;
                border-left: 2px dashed rgba(255, 255, 255, 0.15) !important;
                border-top: none !important;
                margin: 20px 0 !important;
            }

            .notch {
                display: block !important;
                left: -14px !important;
                background: #040b1d !important;
            }

            .notch-top {
                top: -34px !important;
                bottom: auto !important;
            }

            .notch-bottom {
                bottom: -34px !important;
                top: auto !important;
            }

            .ticket-right {
                flex: 1 !important;
                padding: 40px !important;
                background: rgba(255, 255, 255, 0.01) !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
                border-top-right-radius: 30px !important;
                border-bottom-right-radius: 30px !important;
                border-bottom-left-radius: 0 !important;
                box-sizing: border-box !important;
            }

            .qr-frame {
                background: white !important;
                padding: 12px !important;
                border-radius: 20px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                box-shadow: none !important;
                transform: none !important;
            }

            .qr-frame img {
                width: 140px !important;
                height: 140px !important;
                display: block !important;
            }
        }
    </style>
</head>

<body>
    <!-- Header Section Begin -->
    <header class="header">
        <div class="container">
            <div class="row">
                <div class="col-lg-2">
                    <div class="header__logo">
                        <a href="./login.php"><img src="img/logo.png" alt="OnlyYou Logo"></a>
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
                        <h2>Access Code Verification</h2>
                        <p>Verify your phone number to reveal your entry pass</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Form Section Begin -->
    <section class="login spad" style="padding-top: 30px; padding-bottom: 30px;">
        <div class="container" id="mainContainer">
            <div class="row d-flex justify-content-center" id="formRow">
                <div class="col-lg-6">
                    <div class="login__form" id="formCard">
                        
                        <!-- GENERATE FLOW -->
                        <div id="generateFlow">
                            <h3>Enter Your Mobile Number</h3>
                            <p class="form-subtitle">We will send a secure verification code to your registered mobile device.</p>
                            <form id="accessCodeForm" method="POST">
                                <div class="input__item">
                                    <input type="text" id="phone" name="phone" placeholder="+91 XXXXX XXXXX" required>
                                    <span class="icon_phone"></span>
                                </div>
                                <button type="submit" class="site-btn">Generate and Send Code</button>
                            </form>
                            <p id="responseMessage" class="mt-3 text-center"></p>
                        </div>

                        <!-- VERIFY FLOW (INLINE SLIDE DOWN) -->
                        <div id="verifyFlow" class="verify-section" style="display: none;">
                            <h4>Enter Verification Code</h4>
                            <p class="form-subtitle" style="margin-bottom: 20px;">Type the 6-digit access code sent to your device.</p>
                            <form id="verifyForm">
                                <div class="input__item">
                                    <input type="text" id="verify_code" name="code" placeholder="Enter secure OTP" required>
                                    <span class="icon_lock"></span>
                                </div>
                                <button type="submit" class="site-btn">Verify Identity</button>
                            </form>
                            <div id="verifyMessage" class="mt-3 text-center" style="padding: 14px; border-radius: 14px; display: none;"></div>
                        </div>

                        <!-- SUCCESS TRANSITION DRAWING CHECKMARK -->
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
            </div>

            <!-- PREMIUM HOVER-INTERACTIVE EVENT TICKET (REVEALS HERE) -->
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

                    <!-- Tear tear -->
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

                <!-- Ticket Action Buttons -->
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
    </section>
    <!-- Form Section End -->

    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="footer__logo">
                        <a href="./login.php"><img src="img/logo.png" alt="OnlyYou Logo"></a>
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
        let globalPhone = ""; // store phone globally for verification

        // GENERATE FLOW SUBMISSION
        document.getElementById("accessCodeForm").addEventListener("submit", function(e) {
            e.preventDefault();
            const phone = document.getElementById("phone").value;
            globalPhone = phone;

            const formData = new FormData();
            formData.append('phone', phone);

            fetch("generate-access-code.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const responseMessage = document.getElementById("responseMessage");
                responseMessage.textContent = data.message;
                
                if (data.message.includes("successfully")) {
                    responseMessage.className = "mt-3 text-center success-msg";
                    
                    // Slide down and reveal the verification code input field inline!
                    setTimeout(() => {
                        $("#verifyFlow").slideDown(500);
                    }, 400);
                } else {
                    responseMessage.className = "mt-3 text-center error-msg";
                }
            })
            .catch(err => {
                console.error("Error:", err);
            });
        });

        // VERIFY FLOW SUBMISSION
        document.getElementById("verifyForm").addEventListener("submit", function (e) {
            e.preventDefault();

            const code = document.getElementById("verify_code").value;

            const formData = new FormData();
            formData.append("phone", globalPhone);
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
                    msg.className = "mt-3 text-center success-msg";
                    msg.style.display = "block";

                    // Trigger the success micro-animations inline
                    setTimeout(() => {
                        triggerSuccessTransition();
                    }, 800);
                } else {
                    msg.className = "mt-3 text-center error-msg";
                    msg.style.display = "block";
                }
            })
            .catch(err => {
                console.error(err);
                const msg = document.getElementById("verifyMessage");
                msg.innerText = "Verification service unavailable";
                msg.className = "mt-3 text-center error-msg";
                msg.style.display = "block";
            });
        });

        function triggerSuccessTransition() {
            const generateFlow = document.getElementById("generateFlow");
            const verifyFlow = document.getElementById("verifyFlow");
            const successTransition = document.getElementById("successTransition");
            const formCard = document.getElementById("formCard");

            // Fade out the forms
            generateFlow.style.opacity = "0";
            generateFlow.style.transform = "translateY(-10px)";
            generateFlow.style.transition = "all 0.5s ease";
            
            verifyFlow.style.opacity = "0";
            verifyFlow.style.transform = "translateY(-10px)";
            verifyFlow.style.transition = "all 0.5s ease";

            setTimeout(() => {
                generateFlow.style.display = "none";
                verifyFlow.style.display = "none";

                // Show drawing checkmark container
                successTransition.style.display = "flex";
                formCard.style.padding = "60px";

                // After visual celebration, transition to Ticket layout
                setTimeout(() => {
                    revealPremiumTicket();
                }, 2200);

            }, 450);
        }

        function revealPremiumTicket() {
            const formRow = document.getElementById("formRow");
            const ticketWrapper = document.getElementById("ticketWrapper");

            // Shrink and fade out the form card row
            formRow.style.opacity = "0";
            formRow.style.transform = "scale(0.92)";
            formRow.style.transition = "all 0.8s cubic-bezier(0.25, 1, 0.5, 1)";

            setTimeout(() => {
                formRow.style.display = "none";

                // Fade in and scale up the event ticket
                ticketWrapper.classList.add("active");

                // Initialize 3D Holographic Parallax Effect on the ticket card
                init3DTilt();

            }, 750);
        }

        function init3DTilt() {
            const ticket = document.querySelector('.ticket');
            if (!ticket) return;

            ticket.addEventListener('mousemove', (e) => {
                const rect = ticket.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const xc = rect.width / 2;
                const yc = rect.height / 2;
                
                const dx = x - xc;
                const dy = y - yc;
                
                const tiltX = -(dy / yc) * 6; // Max 6 deg
                const tiltY = (dx / xc) * 6;
                
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
