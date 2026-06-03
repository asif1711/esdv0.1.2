<?php
$stats = [
    'users' => 2458,
    'events' => 124,
    'otp' => 5842,
    'face' => 1842
];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Control Center</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
:root{
--color-primary:#FF4569;
--bg-canvas:#050505;
--bg-card:#10102A;
--text:#fff;
}
*{box-sizing:border-box;margin:0;padding:0}
body{
font-family:Inter,sans-serif;
background:#050505;
color:#fff;
padding:24px;
}
.card{
background:linear-gradient(135deg,rgba(16,16,42,.85),rgba(0,0,50,.6));
border:1px solid rgba(255,255,255,.08);
border-radius:24px;
padding:24px;
}
.grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin:20px 0;}
.layout{display:grid;grid-template-columns:260px 1fr;gap:24px;}
.sidebar{min-height:90vh}
.menu div{padding:14px;border-radius:12px;margin-bottom:8px}
.menu div:first-child{background:rgba(255,69,105,.15)}
.maingrid{display:grid;grid-template-columns:2fr 1fr;gap:20px}
h1{margin-bottom:10px}
.value{font-size:34px;font-weight:800}
canvas{max-height:320px}
</style>
</head>
<body>
<div class="layout">
<div class="card sidebar">
<h2>Admin Panel</h2>
<br>
<div class="menu">
<div>Dashboard</div>
<div>Users</div>
<div>Events</div>
<div>Verifications</div>
<div>Analytics</div>
<div>Reports</div>
<div>Settings</div>
<div><a href="../logout.php" style="color: inherit;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 14px;
    width: 100%;">Logout</a></div>
</div>
</div>

<div>
<h1>Admin Control Center</h1>
<p>Manage users, events, security and analytics</p>

<div class="grid">
<div class="card"><div>Total Users</div><div class="value"><?= $stats['users'] ?></div></div>
<div class="card"><div>Active Events</div><div class="value"><?= $stats['events'] ?></div></div>
<div class="card"><div>OTP Verifications</div><div class="value"><?= $stats['otp'] ?></div></div>
<div class="card"><div>Face Verifications</div><div class="value"><?= $stats['face'] ?></div></div>
</div>

<div class="maingrid">
<div class="card">
<h3>User Growth Analytics</h3>
<canvas id="growth"></canvas>
</div>
<div class="card">
<h3>Verification Status</h3>
<canvas id="verify"></canvas>
</div>
</div>

<br>

<div class="card">
<h3>Recent Logins</h3>
<br>
<table width="100%">
<tr><th align="left">User</th><th align="left">Role</th><th align="left">Status</th></tr>
<tr><td>Nurul Islam</td><td>Admin</td><td>Success</td></tr>
<tr><td>John Smith</td><td>Manager</td><td>Success</td></tr>
<tr><td>Sara Lee</td><td>Staff</td><td>Pending Face</td></tr>
</table>
</div>
</div>
</div>

<script>
new Chart(document.getElementById('growth'),{
type:'line',
data:{
labels:['Jan','Feb','Mar','Apr','May','Jun'],
datasets:[{
data:[120,210,380,620,840,1250],
borderColor:'#FF4569'
}]
}
});

new Chart(document.getElementById('verify'),{
type:'doughnut',
data:{
labels:['OTP','Face','Pending'],
datasets:[{
data:[68,24,8],
backgroundColor:['#FF4569','#2DD36F','#FFC857']
}]
}
});
</script>
</body>
</html>