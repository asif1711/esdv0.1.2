<?php
// Dummy Data
$metrics = [
    'revenue' => '$148,950',
    'events' => 287,
    'tickets' => '21,842',
    'attendees' => '14,589'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Event Manager Dashboard</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<style>

/* ====================================
   ONLYYOU DESIGN SYSTEM
==================================== */

:root{

    --color-primary:#FF4569;
    --color-primary-hover:#D62F53;

    --color-success:#2DD36F;
    --color-warning:#FFC857;
    --color-danger:#FF4D4D;
    --color-info:#4EA8FF;

    --bg-canvas:#050505;
    --bg-surface:#0A0A0A;
    --bg-surface-elevated:#10102A;
    --bg-navy:#0B0B2B;

    --text-primary:#FFFFFF;
    --text-secondary:#CBCBCB;
    --text-muted:rgba(255,255,255,.65);

    --glass-bg:rgba(255,255,255,.04);
    --glass-border:rgba(255,255,255,.08);

    --radius-sm:16px;
    --radius-md:22px;
    --radius-lg:32px;
}

/* ====================================
   GLOBAL
==================================== */

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{

    font-family:'Inter',sans-serif;

    background:

        radial-gradient(
            circle at top right,
            rgba(255,69,105,.12),
            transparent 35%
        ),

        radial-gradient(
            circle at bottom left,
            rgba(255,69,105,.08),
            transparent 40%
        ),

        linear-gradient(
            135deg,
            #050505,
            #080808,
            #0A0A0A
        );

    min-height:100vh;
    color:white;
}

/* ====================================
   LAYOUT
==================================== */

.dashboard{

    display:flex;
    min-height:100vh;
}

/* ====================================
   SIDEBAR
==================================== */

.sidebar{

    width:280px;

    background:
        linear-gradient(
            180deg,
            rgba(16,16,42,.82),
            rgba(0,0,30,.72)
        );

    backdrop-filter:blur(25px);

    border-right:
        1px solid rgba(255,255,255,.08);

    padding:30px;

    display:flex;
    flex-direction:column;
}

.logo{

    font-size:26px;
    font-weight:800;
    color:#fff;

    margin-bottom:50px;
}

.logo span{
    color:var(--color-primary);
}

.nav-menu{

    display:flex;
    flex-direction:column;
    gap:12px;
}

.nav-item{

    display:flex;
    align-items:center;
    gap:14px;

    padding:14px 18px;

    border-radius:18px;

    color:rgba(255,255,255,.7);

    cursor:pointer;

    transition:.3s;
}

.nav-item:hover{

    background:rgba(255,255,255,.04);
    color:white;
}

.nav-item.active{

    background:
        linear-gradient(
            135deg,
            rgba(255,69,105,.18),
            rgba(255,69,105,.08)
        );

    color:white;

    border:
        1px solid rgba(255,69,105,.2);
}

/* ====================================
   MAIN
==================================== */

.main{

    flex:1;
    padding:30px;
}

/* ====================================
   TOPBAR
==================================== */

.topbar{

    display:flex;
    justify-content:space-between;
    align-items:center;

    margin-bottom:30px;
}

.page-title{

    font-size:36px;
    font-weight:800;
    color:#fff;
}

.page-title span{

    color:var(--color-primary);
}

.profile{

    display:flex;
    align-items:center;
    gap:15px;
}

.avatar{

    width:52px;
    height:52px;

    border-radius:50%;

    background:
        linear-gradient(
            135deg,
            #FF4569,
            #D62F53
        );
}

/* ====================================
   KPI GRID
==================================== */

.kpi-grid{

    display:grid;

    grid-template-columns:
        repeat(4,1fr);

    gap:25px;

    margin-bottom:30px;
}

.kpi-card{

    background:
        linear-gradient(
            135deg,
            rgba(16,16,42,.72),
            rgba(0,0,50,.55)
        );

    border:
        1px solid rgba(255,255,255,.08);

    border-radius:32px;

    backdrop-filter:blur(25px);

    padding:30px;

    box-shadow:
        0 25px 60px rgba(0,0,0,.35),
        inset 0 1px 0 rgba(255,255,255,.06);
}

.kpi-label{

    color:#BFBFBF;
    font-size:14px;
}

.kpi-value{

    font-size:36px;
    font-weight:800;

    margin-top:10px;
}

.kpi-change{

    margin-top:12px;

    color:#2DD36F;

    font-size:14px;
}

/* ====================================
   CHART PLACEHOLDER
==================================== */

.chart-row{

    display:grid;

    grid-template-columns:
        2fr 1fr;

    gap:25px;
}

.chart-card{

    background:
        linear-gradient(
            135deg,
            rgba(16,16,42,.72),
            rgba(0,0,50,.55)
        );

    border-radius:32px;

    border:
        1px solid rgba(255,255,255,.08);

    padding:30px;

    height:420px;
}

.section-title{

    font-size:20px;
    font-weight:700;
    margin-bottom:25px;
}

@media(max-width:1200px){

    .kpi-grid{
        grid-template-columns:repeat(2,1fr);
    }

    .chart-row{
        grid-template-columns:1fr;
    }
}

@media(max-width:768px){

    .sidebar{
        display:none;
    }

    .kpi-grid{
        grid-template-columns:1fr;
    }
}

.brand__logo--large {
  width: 160px;
  border-radius: 20px;
  padding: 2px;
  background: rgba(255,255,255,0.03);
  border: 1px solid rgba(255,255,255,0.06);
  box-shadow: 0 10px 30px rgba(0,0,0,0.4);
  margin-bottom: 20px;
  transition: 0.3s ease;
}

.brand__logo--large:hover {
  transform: translateY(-2px);
  box-shadow: 0 15px 40px rgba(255,59,59,0.15);
}

/* Branding */
.brand {
  text-align: center;
  z-index: 1;
}

.logout-img {
  width: 30px;
  height: 30px;
  filter: brightness(0) invert(1); /* makes it white */
  transition: 0.3s;
  display: block;
  margin: auto;
}

.logout-icon:hover .logout-img {
  transform: translateX(4px);
}

.logout-icon {
  display: flex;
  align-items: center;
  justify-content: center;

  width: 50px;
  height: 50px;

  border-radius: 12px;

  background: rgba(255,255,255,0.03);
  border: 1px solid rgba(255,255,255,0.08);

  backdrop-filter: blur(10px);

  transition: 0.3s ease;
}

.logout-icon:hover {
  transform: scale(1);
}

.header-user {
  display: flex;
  align-items: center;
  gap: 8px;
}

</style>
</head>
<body>

<div class="dashboard">

    <!-- Sidebar -->
    <aside class="sidebar">

        <div class="brand">
            <a href="index.php">
                <img src="../img/logo.png" class="brand__logo--large" alt="Logo">
            </a>
        </div>

        <div class="nav-menu">

            <div class="nav-item active">
                <i data-lucide="layout-dashboard"></i>
                Dashboard
            </div>

            <div class="nav-item">
                <i data-lucide="calendar"></i>
                Events
            </div>

            <div class="nav-item">
                <i data-lucide="ticket"></i>
                Tickets
            </div>

            <div class="nav-item">
                <i data-lucide="users"></i>
                Attendees
            </div>

            <div class="nav-item">
                <i data-lucide="wallet"></i>
                Revenue
            </div>

            <div class="nav-item">
                <i data-lucide="settings"></i>
                Settings
            </div>

        </div>

    </aside>

    <!-- Main -->
    <main class="main">

        <div class="topbar">

            <h1 class="page-title">
                Event <span>Dashboard</span>
            </h1>

            <div class="profile">

                <div>
                    <strong>Nurul Islam</strong><br>
                    <small>Administrator</small>
                </div>

                <div class="header-user">
                        <a href="../logout.php" class="logout-icon">
                            <img src="../img/sign-out.svg" class="logout-img">
                        </a>
                </div>

            </div>

        </div>

        <!-- KPI -->
        <div class="kpi-grid">

            <div class="kpi-card">

                <div class="kpi-label">
                    Total Revenue
                </div>

                <div class="kpi-value">
                    <?= $metrics['revenue']; ?>
                </div>

                <div class="kpi-change">
                    ▲ +18.6%
                </div>

            </div>

            <div class="kpi-card">

                <div class="kpi-label">
                    Events
                </div>

                <div class="kpi-value">
                    <?= $metrics['events']; ?>
                </div>

                <div class="kpi-change">
                    ▲ +12%
                </div>

            </div>

            <div class="kpi-card">

                <div class="kpi-label">
                    Tickets Sold
                </div>

                <div class="kpi-value">
                    <?= $metrics['tickets']; ?>
                </div>

                <div class="kpi-change">
                    ▲ +22%
                </div>

            </div>

            <div class="kpi-card">

                <div class="kpi-label">
                    Attendees
                </div>

                <div class="kpi-value">
                    <?= $metrics['attendees']; ?>
                </div>

                <div class="kpi-change">
                    ▲ +14%
                </div>

            </div>

        </div>

        <!-- Charts -->
        <div class="chart-row">

            <div class="chart-card">

                <h2 class="section-title">
                    Revenue Analytics
                </h2>

                <canvas id="revenueChart"></canvas>

            </div>

            <div class="chart-card">

                <h2 class="section-title">
                    Event Categories
                </h2>

                <canvas id="categoryChart"></canvas>

            </div>

        </div>

    </main>

</div>

<script>

lucide.createIcons();

new Chart(
document.getElementById('revenueChart'),
{
    type:'line',
    data:{
        labels:['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets:[{
            label:'Revenue',
            data:[15,18,23,28,34,42,55,62,71,82,96,148],
            borderColor:'#FF4569',
            backgroundColor:'rgba(255,69,105,.15)',
            fill:true,
            tension:.4
        }]
    }
});

new Chart(
document.getElementById('categoryChart'),
{
    type:'doughnut',
    data:{
        labels:['Music','Corporate','Sports','Tech','Exhibitions'],
        datasets:[{
            data:[35,22,18,15,10],
            backgroundColor:[
                '#FF4569',
                '#4EA8FF',
                '#2DD36F',
                '#FFC857',
                '#2A2A2A'
            ]
        }]
    }
});

</script>

</body>
</html>