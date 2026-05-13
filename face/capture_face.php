<?php
session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: /esd/login.php");
    exit();

}

$email = $_SESSION['user_email'];


// ===== DATASET PATH =====
$datasetPath = __DIR__ . "/dataset/" . $email;


// ===== CLEAN OLD DATASET =====
if (
    is_dir($datasetPath) &&
    basename($datasetPath) !== 'dataset'
) {

    array_map('unlink', glob("$datasetPath/*"));

    rmdir($datasetPath);

}


// ===== CREATE FRESH FOLDER =====
mkdir($datasetPath, 0777, true);

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
        OnlyYou | Secure Face Setup
    </title>

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{

    font-family:'Inter',sans-serif;

    min-height:100vh;

    background:
        radial-gradient(
            circle at top left,
            rgba(255,0,85,0.10),
            transparent 35%
        ),
        #020617;

    display:flex;

    align-items:center;
    justify-content:center;

    overflow:hidden;

    padding:18px;
}

.capture-card{

    width:100%;
    max-width:540px;

    background:
        linear-gradient(
            180deg,
            rgba(15,23,42,0.96),
            rgba(17,24,39,0.98)
        );

    border:
        1px solid rgba(255,255,255,0.06);

    border-radius:30px;

    padding:22px 22px 18px;

    position:relative;

    overflow:hidden;

    box-shadow:
        0 25px 70px rgba(0,0,0,0.42),
        inset 0 1px 0 rgba(255,255,255,0.04);
}

.capture-card::before{

    content:"";

    position:absolute;

    width:220px;
    height:220px;

    background:
        rgba(255,0,85,0.08);

    border-radius:50%;

    top:-80px;
    right:-80px;

    filter:blur(45px);
}

h2{

    text-align:center;

    color:white;

    font-size:22px;

    font-weight:700;

    margin-bottom:6px;

    line-height:1.1;
}

.divider{

    width:100%;
    height:1px;

    background:
        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,0.08),
            transparent
        );

    margin-bottom:14px;
}

.verify-subtitle{

    text-align:center;

    color:#9ca3af;

    font-size:13px;

    line-height:1.5;

    margin-bottom:14px;
}

.camera-wrapper{

    position:relative;

    width:100%;

    border-radius:22px;

    overflow:hidden;

    background:#0f172a;

    border:
        1px solid rgba(255,255,255,0.06);

    box-shadow:
        0 12px 35px rgba(0,0,0,0.35);
}

#video{

    width:100%;
    height:300px;

    object-fit:cover;

    transform:scaleX(-1);

    display:block;
}

.camera-overlay{

    position:absolute;

    inset:0;

    border-radius:22px;

    border:
        2px solid rgba(255,255,255,0.04);

    pointer-events:none;
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

    animation:scan 2.2s linear infinite;

    z-index:3;
}

@keyframes scan{

    0%{
        top:0;
    }

    100%{
        top:100%;
    }
}

.camera-label{

    position:absolute;

    top:14px;
    left:14px;

    background:
        rgba(0,0,0,0.45);

    color:white;

    padding:7px 14px;

    border-radius:999px;

    font-size:11px;

    font-weight:700;

    letter-spacing:1px;

    z-index:5;

    backdrop-filter:blur(6px);
}

.helper-text{

    text-align:center;

    color:#8b95a7;

    font-size:11px;

    line-height:1.5;

    margin-top:10px;
}

.capture-progress-wrapper{

    width:100%;

    height:6px;

    background:
        rgba(255,255,255,0.06);

    border-radius:999px;

    overflow:hidden;

    margin-top:14px;
}

.capture-progress-bar{

    width:0%;
    height:100%;

    border-radius:999px;

    background:
        linear-gradient(
            90deg,
            #ff4d6d,
            #ff758f
        );

    transition:none;
}

.capture-stats{

    display:flex;

    justify-content:space-between;
    align-items:center;

    margin-top:8px;

    color:#9ca3af;

    font-size:12px;
}

.status-box{

    display:none;
}

.success-state{

    background:
        rgba(34,197,94,0.08);

    border:
        1px solid rgba(34,197,94,0.22);

    color:#22c55e;

    border-radius:16px;

    padding:14px 16px;

    display:flex;

    align-items:center;

    gap:12px;

    width:100%;
}

.success-icon{

    width:36px;
    height:36px;

    border-radius:50%;

    background:#22c55e;

    display:flex;

    align-items:center;
    justify-content:center;

    color:white;

    font-weight:700;

    font-size:17px;

    flex-shrink:0;
}

.error-state{

    background:
        rgba(255,59,95,0.08);

    border:
        1px solid rgba(255,59,95,0.22);

    color:#ff4d6d;

    border-radius:16px;

    padding:14px 16px;

    width:100%;

    text-align:center;
}

#startBtn{

    width:100%;

    margin-top:10px;

    border:none;

    outline:none;

    cursor:pointer;

    padding:15px 18px;

    border-radius:16px;

    background:
        linear-gradient(
            135deg,
            #ff4d6d,
            #ff758f
        );

    color:white;

    font-weight:700;

    font-size:15px;

    font-family:'Inter',sans-serif;

    transition:0.25s ease;

    box-shadow:
        0 12px 30px rgba(255,77,109,0.22);
}

#startBtn:hover{

    transform:translateY(-2px);
}

#startBtn:disabled{

    opacity:0.7;

    cursor:not-allowed;
}


</style>

</head>

<body>

<div class="capture-card">

    <!-- TITLE -->
    <h2>
        Secure Face Setup
    </h2>

    <div class="divider"></div>

    <p class="verify-subtitle">
        Complete your secure facial verification setup.
    </p>

    <!-- CAMERA -->
    <div class="camera-wrapper">

        <div class="scanner-line"></div>

        <div class="camera-overlay"></div>

        <div class="camera-label">
            LIVE CAMERA
        </div>

        <video
            id="video"
            autoplay
            playsinline
            muted
        ></video>

    </div>

    <!-- HELPER -->
    <p class="helper-text">
        Slowly move your face slightly between captures
        for better recognition accuracy.
    </p>

    <!-- PROGRESS -->
    <div class="capture-progress-wrapper">

        <div
            class="capture-progress-bar"
            id="captureBar"
        ></div>

    </div>

    <!-- STATS -->
    <div class="capture-stats">

        <span id="counter">
            0 / 50 Captures
        </span>

        <span id="timerText">
            Initializing camera...
        </span>

    </div>

    <!-- STATUS -->
    <div class="status-box">

        <div id="status"></div>

    </div>

    <!-- BUTTON -->
    <button id="startBtn">

        Start Capturing

    </button>

</div>

<script>

const video = document.getElementById('video');

const statusText = document.getElementById('status');

const counter = document.getElementById('counter');

const captureBar = document.getElementById('captureBar');

const timerText = document.getElementById('timerText');

const startBtn = document.getElementById('startBtn');


startBtn.disabled = true;


let captureCount = 0;

const TOTAL_CAPTURES = 50;

const CAPTURE_INTERVAL = 700;


// CAMERA INIT
navigator.mediaDevices.getUserMedia({

    video: true

})

.then(stream => {

    video.srcObject = stream;

    video.onloadedmetadata = () => {

        timerText.innerText =
            "Ready to capture";

        startBtn.disabled = false;
    };

})

.catch(err => {

    console.error(err);

    timerText.innerText =
        "Camera unavailable";

    statusText.className = "error-state";

    statusText.innerHTML = `
        Camera access denied
    `;
});


// BUTTON START
startBtn.addEventListener("click", () => {

    startBtn.disabled = true;

    startBtn.innerText =
        "Preparing Capture...";

    startCaptureLoop();

});


// PREP TIMER
function startCaptureLoop(){

    let countdown = 3;

    timerText.innerText =
        `Capture starts in ${countdown}s`;

    const prep = setInterval(() => {

        countdown--;

        timerText.innerText =
            `Capture starts in ${countdown}s`;

        if(countdown <= 0){

            clearInterval(prep);

            timerText.innerText =
                "Capturing...";

            autoCapture();
        }

    }, 1000);
}


// AUTO CAPTURE
function autoCapture(){

    let captureIndex = 0;

    timerText.innerText = "Capturing...";

    animateCaptureBar();

    const interval = setInterval(() => {

        capture();

        captureIndex++;

        captureCount++;

        counter.innerText =
            `${captureCount} / 50 Captures`;

        animateCaptureBar();

        if(captureIndex >= TOTAL_CAPTURES){

            clearInterval(interval);

            setTimeout(() => {

                // UPDATE DATASET GENERATED FLAG
                fetch("update_dataset_flag.php", {
                    method: "POST"
                });

                timerText.innerText =
                    "Verification profile ready";

                statusText.className =
                    "success-state";

                statusText.innerHTML = `

                    <div class="success-icon">
                        ✓
                    </div>

                    <div>

                        <div style="
                            font-weight:700;
                            font-size:15px;
                        ">
                            Face Setup Completed
                        </div>

                        <div style="
                            font-size:12px;
                            opacity:0.8;
                            margin-top:4px;
                        ">
                            Your biometric profile is now secured
                        </div>

                    </div>
                `;

                document.querySelector(
                    '.capture-progress-wrapper'
                ).style.display = 'none';

                document.querySelector(
                    '.capture-stats'
                ).style.display = 'none';

                document.querySelector(
                    '.helper-text'
                ).style.display = 'none';

                startBtn.disabled = false;

                startBtn.innerText =
                    "Continue";

                startBtn.onclick = () => {
                    window.location.href =
                        "/esd/face_verify.php";
                };
                    
            }, 1200);
        }

    }, CAPTURE_INTERVAL);
}

function animateCaptureBar(){

    captureBar.style.transition =
        "none";

    captureBar.style.width =
        "0%";

    void captureBar.offsetWidth;

    captureBar.style.transition =
        `width ${CAPTURE_INTERVAL}ms linear`;

    captureBar.style.width =
        "100%";
}

// CAPTURE
function capture(){

    const canvas =
        document.createElement('canvas');

    canvas.width = 320;
    canvas.height = 240;

    const ctx = canvas.getContext('2d');

    ctx.drawImage(video, 0, 0, 320, 240);

    canvas.toBlob(blob => {

        const formData = new FormData();

        formData.append("image", blob);

        formData.append(
            "email",
            "<?php echo $email; ?>"
        );

        fetch(
            "http://127.0.0.1:5000/capture",
            {
                method: "POST",
                body: formData
            }
        )
        .catch(err => {

            console.error(
                "Capture failed:",
                err
            );

        });

    }, "image/jpeg", 0.7);
}

</script>

</body>
</html>