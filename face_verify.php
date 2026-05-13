<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>OnlyYou | Face Verification</title>

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{

            font-family:'Inter',sans-serif;

            min-height:100vh;

            display:flex;
            align-items:center;
            justify-content:center;

            padding:24px;

            overflow:hidden;

            background:
            radial-gradient(
                circle at top left,
                rgba(255,0,85,0.10),
                transparent 35%
            ),
            #020617;

            color:white;
        }

        .verify-container{

            width:100%;
            display:flex;
            align-items:center;
            justify-content:center;
        }

        .verify-card{

            width:100%;
            max-width:700px;
            height: auto;
            padding:30px 30px;

            border-radius:34px;

            background:
            linear-gradient(
                135deg,
                rgba(7,16,40,0.98),
                rgba(15,23,42,0.98)
            );

            border:1px solid rgba(255,255,255,0.06);

            box-shadow:
            0 20px 60px rgba(0,0,0,0.45),
            0 0 80px rgba(255,0,85,0.05);

            position:relative;
            overflow:hidden;
        }

        .verify-card::before{

            content:"";

            position:absolute;

            width:260px;
            height:260px;

            background:rgba(255,0,85,0.06);

            border-radius:50%;

            top:-120px;
            right:-120px;

            filter:blur(40px);
        }

        h1{

            text-align:center;

            font-size:24px;
            font-weight:700;

            line-height:1;
            margin-top: -10px;
            margin-bottom:5px;
        }

        .verify-subtitle{

            text-align:center;

            color:#9ca3af;

            font-size:14px;

            line-height:1;

            margin-bottom:10px;
        }

        .camera-wrapper{

            position:relative;

            width:100%;
            height:360px;

            border-radius:28px;

            overflow:hidden;

            background:#081128;

            border:1px solid rgba(255,255,255,0.08);

            box-shadow:
            0 10px 30px rgba(0,0,0,0.35);
        }

        #video{

            width:100%;
            height:100%;

            object-fit:cover;

            transform:scaleX(-1);
        }

        .camera-label{

            position:absolute;

            top:18px;
            left:18px;

            padding:12px 20px;

            border-radius:40px;

            background:rgba(0,0,0,0.40);

            backdrop-filter:blur(10px);

            font-size:12px;
            font-weight:700;

            letter-spacing:1px;

            z-index:5;
        }

        .scanner-line{

            position:absolute;

            width:100%;
            height:3px;

            background:
            linear-gradient(
                90deg,
                transparent,
                #ff4d6d,
                transparent
            );

            left:0;
            top:0;

            z-index:4;

            box-shadow:
            0 0 15px rgba(255,77,109,0.8);

            animation:scanMove 3s ease-in-out infinite;
        }


        /* SCAN ANIMATION */
        @keyframes scanMove{

            0%{
                top:8%;
                opacity:0.6;
            }

            50%{
                top:88%;
                opacity:1;
            }

            100%{
                top:8%;
                opacity:0.6;
            }
        }

        .helper-text{

            text-align:center;

            color:#94a3b8;

            font-size:12px;

            line-height:1;

            margin-top:10px;
            margin-bottom:0px;
        }

        #status{

            width:100%;
        }

        .success-state{

            width:100%;

            display:flex;
            align-items:center;
            justify-content:center;

            gap:14px;

            background:rgba(34,197,94,0.10);

            border:1px solid rgba(34,197,94,0.25);

            color:#22c55e;

            padding:18px;

            border-radius:20px;

            animation:pop 0.3s ease;
        }

        .error-state{

            width:100%;

            display:flex;
            align-items:center;
            justify-content:center;

            text-align:center;

            background:rgba(255,77,109,0.10);

            border:1px solid rgba(255,77,109,0.20);

            color:#ff5b77;

            padding:18px;

            border-radius:20px;
        }

        .success-icon{

            width:42px;
            height:42px;

            border-radius:50%;

            background:#22c55e;

            display:flex;
            align-items:center;
            justify-content:center;

            font-size:22px;
            font-weight:700;

            color:white;

            box-shadow:
            0 0 20px rgba(34,197,94,0.45);
        }

        @keyframes pop{

            from{
                opacity:0;
                transform:scale(0.92);
            }

            to{
                opacity:1;
                transform:scale(1);
            }
        }

        #verifyBtn{

            width:100%;

            margin-top:14px;

            border:none;
            outline:none;

            padding:15px;

            border-radius:22px;

            cursor:pointer;

            font-size:16px;
            font-weight:600;

            color:white;

            background:
            linear-gradient(
                135deg,
                #ff4f75,
                #f56b8a
            );

            box-shadow:
            0 14px 35px rgba(255,79,117,0.28);

            transition:0.25s;
        }

        #verifyBtn:hover{

            transform:translateY(-2px);

            box-shadow:
            0 18px 40px rgba(255,79,117,0.38);
        }

        #verifyBtn:disabled{

            opacity:0.7;

            cursor:not-allowed;

            transform:none;
        }

        @media(max-width:768px){

            body{
                padding:14px;
            }

            .verify-card{

                padding:24px 18px 18px;
            }

            h1{

                font-size:40px;
            }

            .camera-wrapper{

                height:300px;
            }

            #verifyBtn{

                font-size:18px;
                padding:18px;
            }
        }

    </style>

</head>

<body>

<div class="verify-container">

    <div class="verify-card">

        <h1>Face Verification</h1>

        <p class="verify-subtitle">
            Complete your secure facial verification to continue.
        </p>

        <!-- CAMERA -->
        <div class="camera-wrapper">

            <div class="camera-label">
                LIVE CAMERA
            </div>

            <div class="scanner-line"></div>

            <video id="video" autoplay playsinline></video>

        </div>

        <div class="helper-text">
            Position your face within the frame for secure biometric authentication.
        </div>

       <div id="actionArea">

            <button onclick="capture()" id="verifyBtn">

                Verify

            </button>

        </div>

    </div>

</div>

<script>

const video = document.getElementById('video');
const actionArea = document.getElementById('actionArea');
const btn = document.getElementById('verifyBtn');


// OPEN CAMERA
navigator.mediaDevices.getUserMedia({

    video:true

})

.then(stream => {

    video.srcObject = stream;

})

.catch(err => {

    console.error(err);

    actionArea.innerHTML = `

        <div class="error-state">

            Camera access denied.
            Please allow webcam permissions.

        </div>

    `;
});


// VERIFY FACE
function capture(){

    btn.disabled = true;

    actionArea.innerHTML = `

    <button id="verifyBtn" disabled>

        Verifying Biometric Signature...

    </button>

`;

    const canvas = document.createElement('canvas');

    canvas.width = 640;
    canvas.height = 480;

    const ctx = canvas.getContext('2d');

    ctx.drawImage(video,0,0,640,480);

    canvas.toBlob(blob => {

        const formData = new FormData();

        formData.append("image",blob);

        fetch("http://127.0.0.1:5000/verify",{

            method:"POST",
            body:formData

        })

        .then(res => res.json())

        .then(data => {

            console.log(data);

            // SUCCESS
            if(data.status === "granted"){

                actionArea.innerHTML = `

                    <div class="success-state">

                        <div class="success-icon">
                            ✓
                        </div>

                        <div>

                            <div style="
                                font-weight:700;
                                font-size:16px;
                            ">
                                Access Granted
                            </div>

                            <div style="
                                font-size:13px;
                                opacity:0.8;
                                margin-top:3px;
                            ">
                                Facial verification completed
                            </div>

                        </div>

                    </div>

                `;

                // SET FACE VERIFIED SESSION
                fetch("/esd/auth/set-face-verified.php", {

                    method:"POST"

                })

                .then(res => res.json())

                .then(sessionData => {

                    setTimeout(() => {

                        window.location.href = "/esd/otp_verify.php";

                    },1500);

                })

                .catch(err => {

                    console.error(err);

                });

            }

            // FAILED
            else{

               actionArea.innerHTML = `

                    <div class="error-state">

                        Authentication failed.
                        Unauthorized biometric signature detected.

                    </div>

                    <button onclick="capture()" id="verifyBtn" style="margin-top:14px;">

                        Retry Verification

                    </button>

                `;

            }

        })

        .catch(err => {

            console.error(err);

            actionArea.innerHTML = `

                <div class="error-state">

                    Verification server unavailable.

                </div>

                <button onclick="capture()" id="verifyBtn" style="margin-top:14px;">

                    Retry Verification

                </button>

            `;

        });

    },"image/jpeg",0.8);
}

</script>

</body>
</html>