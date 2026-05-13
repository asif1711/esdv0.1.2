<?php

session_start();

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['face_verified'])
) {

    header("Location: /esd/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        OnlyYou | OTP Verification
    </title>

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- Base CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">

    <style>

        .otp-subtitle{

            text-align:center;

            color:rgba(255,255,255,0.62);

            font-size:14px;

            line-height:1.6;

            margin-top:10px;
            margin-bottom:28px;
        }

        .auth__card{

            position:relative;

            overflow:hidden;
        }

        .auth__card::before{

            content:"";

            position:absolute;

            width:180px;
            height:180px;

            background:
                rgba(255,59,92,0.06);

            border-radius:50%;

            top:-60px;
            right:-60px;

            filter:blur(40px);
        }

        .divider{

            margin-top:18px;
            margin-bottom:18px;
        }

        .input-group{

            margin-bottom:18px;
        }

        .input-group input{

            height:56px;
        }

        .readonly-field{

            opacity:0.72;

            cursor:not-allowed;
        }

        #verifyBtn{

            margin-top:6px;

            position:relative;
            z-index:2;
        }

        #verifyMessage{

            display:none;

            margin-top:18px;

            padding:14px 16px;

            border-radius:14px;

            font-size:14px;

            font-weight:500;

            text-align:center;
        }

        .success-box{

            display:block !important;

            background:
                rgba(34,197,94,0.10);

            border:
                1px solid rgba(34,197,94,0.18);

            color:#4ade80;
        }

        .error-box-custom{

            display:block !important;

            background:
                rgba(255,59,92,0.10);

            border:
                1px solid rgba(255,59,92,0.16);

            color:#ff7a92;
        }

        @media(max-width:992px){

            .auth__left{

                display:none;
            }

            .auth__container{

                grid-template-columns:1fr;
            }

            .auth__right{

                justify-content:center;
            }
        }

    </style>

</head>

<body>

<section class="auth">

    <div class="auth__container">

        <!-- LEFT -->
        <div class="auth__left">

            <div class="brand">

                <img
                    src="img/logo.png"
                    class="brand__logo--large"
                >

                <p class="brand__tagline">
                    Identity verified. Final secure access required.
                </p>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="auth__right">

            <div class="auth__card">

                <h2>
                    OTP Verification
                </h2>

                <div class="divider"></div>

                <p class="otp-subtitle">
                    Enter your secure access code to continue.
                </p>

                <form id="verifyForm">

    <!-- PHONE STEP -->
    <div id="phoneStep">

        <div class="input-group">

            <input
                type="text"
                id="verify_phone"
                name="phone"
                placeholder="+91 XXXXX XXXXX"
                required
            >

        </div>

        <button
            type="button"
            id="sendCodeBtn"
        >

            <span class="btn-text">
                Send Access Code
            </span>

            <span class="btn-icon">

                <svg
                    width="18"
                    height="18"
                    viewBox="0 0 24 24"
                    fill="none"
                >

                    <path
                        d="M5 12H19M19 12L13 6M19 12L13 18"
                        stroke="white"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />

                </svg>

            </span>

        </button>

    </div>

    <!-- OTP STEP -->
    <div
        id="otpStep"
        style="
            display:none;
            margin-top:18px;
        "
    >

        <div class="input-group">

            <input
                type="text"
                id="verify_code"
                name="code"
                placeholder="Enter access code"
            >

        </div>

        <button
            type="submit"
            id="verifyBtn"
        >

            <span class="btn-text">
                Verify Access
            </span>

            <span class="btn-icon">

                <svg
                    width="18"
                    height="18"
                    viewBox="0 0 24 24"
                    fill="none"
                >

                    <path
                        d="M5 12H19M19 12L13 6M19 12L13 18"
                        stroke="white"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />

                </svg>

            </span>

        </button>

    </div>

</form>

                <div id="verifyMessage"></div>

            </div>

        </div>

    </div>

</section>

<script>

const sendCodeBtn =
    document.getElementById("sendCodeBtn");

const otpStep =
    document.getElementById("otpStep");

const phoneInput =
    document.getElementById("verify_phone");

const verifyMessage =
    document.getElementById("verifyMessage");


sendCodeBtn.addEventListener("click", () => {

    const phone = phoneInput.value;

    if(!phone){

        verifyMessage.innerText =
            "Phone number required";

        verifyMessage.className =
            "error-box-custom";

        return;
    }

    sendCodeBtn.disabled = true;

    sendCodeBtn
        .querySelector(".btn-text")
        .innerText = "Sending...";

    const formData = new FormData();

    formData.append("phone", phone);

    fetch("/esd/generate-access-code.php", {

        method:"POST",
        body:formData

    })

    .then(res => res.json())

    .then(data => {

        verifyMessage.innerText =
            data.message;

        // SUCCESS
        if(data.status === "success"){

            verifyMessage.className =
                "success-box";

            otpStep.style.display = "block";

            phoneInput.readOnly = true;

            phoneInput.classList
                .add("readonly-field");

            sendCodeBtn
                .querySelector(".btn-text")
                .innerText = "Code Sent";

        }else{

            verifyMessage.className =
                "error-box-custom";

            sendCodeBtn.disabled = false;

            sendCodeBtn
                .querySelector(".btn-text")
                .innerText =
                    "Send Access Code";
        }

    })

    .catch(err => {

        console.error(err);

        verifyMessage.innerText =
            "Service unavailable";

        verifyMessage.className =
            "error-box-custom";

        sendCodeBtn.disabled = false;

        sendCodeBtn
            .querySelector(".btn-text")
            .innerText =
                "Send Access Code";
    });

});

document
.getElementById("verifyForm")
.addEventListener("submit", function(e){

    e.preventDefault();

    const btn =
        document.getElementById("verifyBtn");

    const msg =
        document.getElementById("verifyMessage");

    btn.disabled = true;

    btn.querySelector(".btn-text")
        .innerText = "Verifying...";

    const phone =
        document.getElementById("verify_phone").value;

    const code =
        document.getElementById("verify_code").value;

    const formData = new FormData();

    formData.append("phone", phone);
    formData.append("code", code);

    fetch("/esd/verify-code.php", {

        method:"POST",
        body:formData

    })

    .then(res => res.json())

    .then(data => {

        msg.innerText = data.message;

        if(
            data.message
            .toLowerCase()
            .includes("success")
        ){

            msg.className = "success-box";

            btn.querySelector(".btn-text")
                .innerText = "Verified";

            setTimeout(() => {

                window.location.href =
                    "/esd/index.php";

            },1200);

        }else{

            msg.className =
                "error-box-custom";

            btn.disabled = false;

            btn.querySelector(".btn-text")
                .innerText = "Verify Access";
        }

    })

    .catch(err => {

        console.error(err);

        msg.innerText =
            "Verification service unavailable";

        msg.className =
            "error-box-custom";

        btn.disabled = false;

        btn.querySelector(".btn-text")
            .innerText = "Verify Access";
    });

});

</script>

</body>
</html>