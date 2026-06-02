<?php

session_start();

require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /esd/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT name
    FROM users
    WHERE id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

$user_name = $user['name'] ?? 'Attendee';

$event = $conn->query("
    SELECT *
    FROM events
    WHERE status = 'Published'
    ORDER BY event_date ASC
    LIMIT 1
")->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>OnlyYou | Event Ticket</title>

<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/auth.css">
<link rel="stylesheet" href="css/event-ticket.css">

</head>

<body class="canvas">

<section class="ticket-page">

    <div class="ticket-container">

       
    </div>

        <!-- MAIN TICKET -->

        <div class="event-ticket">

            <!-- LEFT -->

            <div class="event-details">

                <div class="event-banner">

                    <img
                        src="img/events/1.jpg"
                        alt="<?= htmlspecialchars($event['event_title']) ?>"
                    >

                </div>

                <div class="event-content">

                    <h2>
                        <?= htmlspecialchars($event['event_title']) ?>
                    </h2>

                    <div class="event-meta">

                        <div class="meta-row">
                            Event Attendee
                            <strong>
                                <?= htmlspecialchars($user_name) ?>
                            </strong>
                        </div>

                        <div class="meta-row">
                            Venue
                            <strong>
                                <?= htmlspecialchars($event['event_venue']) ?>
                            </strong>
                        </div>

                        <div class="meta-row">
                            Date & Time
                            <strong>
                                <?= date('M d, Y', strtotime($event['event_date'])) ?>
                                •
                                <?= date('g:i A', strtotime($event['event_time'])) ?>
                            </strong>
                        </div>

                    </div>

                </div>

            </div>


            <!-- RIGHT -->

            <div class="ticket-qr">

                <div class="qr-frame">

                    <img
                        src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=ONLYYOU-TICKET-<?= urlencode($user_id.'-'.$user_name) ?>"
                    >

                </div>

                <div class="ticket-id-box">

    <span class="ticket-id-label">
        Ticket ID
    </span>

    <span class="ticket-id-value">

        <span class="ticket-prefix">
            ONLYYOU-
        </span>

        <span class="ticket-number">
            <?= str_pad($user_id,5,'0',STR_PAD_LEFT) ?>
        </span>

    </span>

</div>

                <div class="ticket-actions">

            <button
    onclick="window.print()"
    class="btn-ticket"
>

    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">

        <path
            d="M6 9V3H18V9"
            stroke="currentColor"
            stroke-width="2"/>

        <path
            d="M6 14H18V21H6V14Z"
            stroke="currentColor"
            stroke-width="2"/>

        <path
            d="M4 9H20C21.1 9 22 9.9 22 11V16C22 17.1 21.1 18 20 18H18"
            stroke="currentColor"
            stroke-width="2"/>

    </svg>

    Print Ticket

</button>

            <a
    href="index.php"
    class="btn-ticket btn-secondary"
>

    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">

        <path
            d="M15 18L9 12L15 6"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"/>

    </svg>

    Back to Events

</a>

        </div>

            </div>

            

        </div>

    </div>

</section>

</body>
</html>