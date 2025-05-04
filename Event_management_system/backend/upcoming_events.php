<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'attendee') {
    header("Location: login.php");
    exit();
}
include_once 'db_connect.php';

// Fetch upcoming events
$sql = "SELECT * FROM events WHERE date >= CURDATE() ORDER BY date ASC";
$result = $conn->query($sql);
$upcoming_events = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Events</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        .event-box {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin: 10px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            flex: 1 1 calc(33.33% - 20px);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .event-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .event-box h5 {
            margin-bottom: 10px;
            color: #007bff;
        }
        .event-box p {
            margin: 0;
        }
        .btn-rsvp {
            margin-top: 10px;
        }
        .no-events {
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Upcoming Events</h1>
        <div class="event-container">
            <?php if (!empty($upcoming_events)): ?>
                <?php foreach ($upcoming_events as $event): ?>
                    <?php
                    // Fetch the number of RSVPs for the event
                    $sql = "SELECT COUNT(*) AS rsvp_count FROM rsvps WHERE event_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $event['id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $rsvp_count = $result->fetch_assoc()['rsvp_count'];
                    $stmt->close();

                    $remaining_spots = $event['capacity'] - $rsvp_count;
                    ?>
                    <div class="event-box">
                        <h5><?php echo htmlspecialchars($event['name']); ?></h5>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($event['date']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                        <p><?php echo htmlspecialchars($event['description']); ?></p>
                        <p><strong>Capacity:</strong> <?php echo htmlspecialchars($event['capacity']); ?></p>
                        <p><strong>Remaining Spots:</strong> 
                            <?php
                            if ($remaining_spots <= 0) {
                                echo "Sorry, No space left";
                            } else {
                                echo $remaining_spots;
                            }
                            ?>
                        </p>
                        <form method="POST" action="rsvp_event.php">
                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                            <button type="submit" name="status" value="attend" class="btn btn-success btn-rsvp" <?php echo $remaining_spots <= 0 ? 'disabled' : ''; ?>>Attend</button>
                            <button type="submit" name="status" value="maybe" class="btn btn-warning btn-rsvp" <?php echo $remaining_spots <= 0 ? 'disabled' : ''; ?>>Maybe</button>
                            <button type="submit" name="status" value="decline" class="btn btn-danger btn-rsvp">Decline</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-events">No upcoming events found.</p>
            <?php endif; ?>
        </div>
        <a href="attendee_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
