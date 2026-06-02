<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /esd/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>OnlyYou | Authentication Pending</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

html,
body{

    margin:0;

    font-family:'Inter',sans-serif;

    background:
    radial-gradient(circle at top right,
        rgba(255,59,92,.12),
        transparent 30%),
    linear-gradient(
        135deg,
        #090A0F 0%,
        #11131A 50%,
        #180F17 100%
    );

    min-height:100vh;

    overflow:hidden;
}

.auth{

    width:100%;
    height:100vh;

    display:flex;
    justify-content:center;
    align-items:center;

    padding:40px;
}

.auth__container{

    width:100%;
    max-width:1400px;

    display:grid;
    grid-template-columns:1.4fr .9fr;

    gap:32px;
}

.panel{

    position:relative;

    background:rgba(18,18,22,.82);

    border:1px solid rgba(255,255,255,.08);

    backdrop-filter:blur(20px);

    border-radius:28px;

    box-shadow:
        0 20px 50px rgba(0,0,0,.45);

    overflow:hidden;
}

.panel::before{

    content:"";

    position:absolute;

    width:300px;
    height:300px;

    right:-120px;
    top:-120px;

    border-radius:50%;

    background:
        rgba(255,59,92,.08);

    filter:blur(60px);
}

.panel--main{

    padding:40px 50px;

    text-align:center;

    display:flex;
    flex-direction:column;
    justify-content:center;
}

.panel--contact{

    padding:35px;

    display:flex;
    flex-direction:column;
    justify-content:center;
}

.pending-icon{

    width:90px;
    height:90px;

    margin:0 auto 20px;

    border-radius:50%;

    background:rgba(255,255,255,.04);

    border:1px solid rgba(255,255,255,.08);

    display:flex;
    justify-content:center;
    align-items:center;

    color:#FF4569;

    box-shadow:
        0 0 40px rgba(255,69,105,.15);
}

.pending-icon svg{

    width:40px;
    height:40px;
}

h1{

    color:#fff;

    font-size:42px;

    font-weight:700;

    line-height:1.2;

    margin-bottom:10px;
}

.subtitle{

    color:#FF6C87;

    font-size:28px;

    font-weight:500;

    margin-bottom:18px;
}

.divider{

    width:300px;
    height:2px;

    background:#FF4569;

    margin:0 auto 25px;

    border-radius:20px;
}

.divider-small{

    margin-bottom:35px;
}

.description{

    color:rgba(255,255,255,.72);

    line-height:1.8;

    font-size:16px;

    max-width:650px;

    margin:0 auto 30px;
}

.status-pill{

    margin:auto;

    display:flex;
    align-items:center;
    gap:12px;

    padding:12px 24px;

    border-radius:999px;

    background:
        rgba(255,69,105,.08);

    border:
        1px solid rgba(255,69,105,.22);

    color:#FF7B96;

    font-size:18px;

    font-weight:600;
}

.dot{

    width:10px;
    height:10px;

    border-radius:50%;

    background:#FF4569;

    animation:pulse 1.8s infinite;
}

@keyframes pulse{

    0%{
        transform:scale(1);
        opacity:1;
    }

    50%{
        transform:scale(1.8);
        opacity:.35;
    }

    100%{
        transform:scale(1);
        opacity:1;
    }
}

.panel--contact h2{

    text-align:center;

    color:#fff;

    font-size:28px;

    font-weight:700;

    margin-bottom:10px;
}

.contact-card{

    display:flex;
    align-items:center;

    gap:16px;

    padding:20px;

    margin-bottom:18px;

    border-radius:20px;

    background:
        rgba(255,255,255,.03);

    border:
        1px solid rgba(255,255,255,.06);
}

.contact-icon{

    width:60px;
    height:60px;

    border-radius:50%;

    display:flex;
    align-items:center;
    justify-content:center;

    color:#FF4569;

    background:
        rgba(255,69,105,.05);

    border:
        1px solid rgba(255,69,105,.15);
}

.contact-label{

    color:rgba(255,255,255,.55);

    font-size:14px;
}

.contact-value{

    color:#fff;

    font-size:16px;

    font-weight:600;

    margin-top:4px;
}

.contact-footer{

    margin-top:20px;

    display:flex;
    gap:12px;

    color:rgba(255,255,255,.65);

    font-size:14px;

    line-height:1.8;
}

.contact-footer svg{
    color:#FF4569;
    flex-shrink:0;
}

</style>
</head>
<body>

<section class="auth">

    <div class="auth__container">

        <!-- LEFT CARD -->

        <div class="panel panel--main">

            <div class="pending-icon">

                <svg width="42" height="42" viewBox="0 0 24 24" fill="none">
                    <path d="M12 6V12L16 14"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"/>
                    <circle cx="12" cy="12" r="9"
                        stroke="currentColor"
                        stroke-width="2"/>
                </svg>

            </div>

            <div class="subtitle">
                Face Authentication In Progress
            </div>

            <div class="divider"></div>

            <p class="description">
                Your facial authentication data has been successfully submitted.
                Our verification engine is currently processing your profile.
                Access will become available once setup is completed.
            </p>

            <div class="status-pill">
                <span class="dot"></span>
                Pending Verification
            </div>

        </div>


        <!-- RIGHT CARD -->

        <div class="panel panel--contact">

            <h2>Need Assistance?</h2>

            <div class="divider divider-small"></div>

            <div class="contact-card">

                <div class="contact-icon">

                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path
                            d="M4 6H20V18H4V6Z"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linejoin="round"/>
                        <path
                            d="M4 7L12 13L20 7"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linejoin="round"/>
                    </svg>

                </div>

                <div>

                    <div class="contact-label">
                        Email Support
                    </div>

                    <div class="contact-value">
                        support@example.com
                    </div>

                </div>

            </div>

            <div class="contact-card">

                <div class="contact-icon">

                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path
                            d="M22 16.92V20C22 20.55 21.55 21 21 21C10.51 21 2 12.49 2 2C2 1.45 2.45 1 3 1H6.09L7.77 5.3L5.56 8.22C6.84 11.05 8.95 13.16 11.78 14.44L13.69 12.53L18.19 12.93C18.66 13.02 19 13.43 19 13.91V17"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"/>
                    </svg>

                </div>

                <div>

                    <div class="contact-label">
                        Phone Support
                    </div>

                    <div class="contact-value">
                        +91 98765 43210
                    </div>

                </div>

            </div>

            <div class="contact-footer">

                <svg width="26" height="26" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M12 3L5 6V11C5 16 8.5 20 12 21C15.5 20 19 16 19 11V6L12 3Z"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linejoin="round"/>
                </svg>

                <p>
                    If this status remains unchanged for an extended period,
                    please contact the system administrator.
                </p>

            </div>

        </div>

    </div>

</section>
</body>
</html>