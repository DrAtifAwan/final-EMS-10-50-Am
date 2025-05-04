<?php
session_start();
include_once 'db_connect.php';

// Only allow access for organizers (adjust as needed)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header("Location: login.php");
    exit();
}

// Retrieve the event_id from POST or GET
if (isset($_POST['event_id'])) {
    $event_id = intval($_POST['event_id']);
} elseif (isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);
} else {
    header("Location: all_events.php");
    exit();
}

// Fetch event details
$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo "Invalid event.";
    exit();
}
$event = $result->fetch_assoc();
$stmt->close();

// Get the RSVP summary by status
$sql = "SELECT status, COUNT(*) as count FROM rsvps WHERE event_id = ? GROUP BY status";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$rsvp_summary = ['attend' => 0, 'maybe' => 0, 'decline' => 0];
while ($row = $result->fetch_assoc()) {
    $status = $row['status'];
    $rsvp_summary[$status] = $row['count'];
}
$stmt->close();

// Calculate remaining spots (assuming only confirmed 'attend' count)
$remaining_spots = $event['capacity'] - $rsvp_summary['attend'];
if ($remaining_spots < 0) { $remaining_spots = 0; }

// Retrieve detailed RSVP records (joined with users table to get attendee information)
// Retrieve detailed RSVP records (joined with attendee table to get attendee information)
// Retrieve detailed RSVP records (joined with attendees table to get attendee information)
$sql = "SELECT r.*, a.name AS username, a.email 
        FROM rsvps r 
        LEFT JOIN attendees a ON r.attendee_id = a.id 
        WHERE r.event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$rsvp_records = [];
while ($row = $result->fetch_assoc()) {
    // Fallback values in case data is missing
    $row['username'] = $row['username'] ?? 'Unknown Attendee';
    $row['email'] = $row['email'] ?? 'Unknown Email';
    $rsvp_records[] = $row;
}
$stmt->close();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RSVP Details for <?php echo htmlspecialchars($event['name']); ?></title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        /* Basic styling for the page */
        body {
            padding-top: 20px;
            padding-bottom: 20px;
            background-color:rgb(179, 214, 113);
        }
        .summary {
            margin-bottom: 30px;
        }
        .summary p {
            font-size: 18px;
        }
        table {
            background: #fff;
        }
        h1 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>RSVP Details for "<?php echo htmlspecialchars($event['name']); ?>"</h1>
        <div class="summary">
            <p><strong>Date:</strong> <?php echo htmlspecialchars($event['date']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
            <p><strong>Capacity:</strong> <?php echo htmlspecialchars($event['capacity']); ?></p>
            <p><strong>Total Attending:</strong> <?php echo $rsvp_summary['attend']; ?></p>
            <p><strong>Maybe:</strong> <?php echo $rsvp_summary['maybe']; ?></p>
            <p><strong>Declined:</strong> <?php echo $rsvp_summary['decline']; ?></p>
            <p><strong>Remaining Spots:</strong> <?php echo $remaining_spots; ?></p>
        </div>

        <h2>Attendee Responses</h2>
        <?php if (count($rsvp_records) > 0): ?>
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Attendee Name</th>
                        <th>Email</th>
                        <th>RSVP Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rsvp_records as $record): ?>
                        <tr>
                        <td><?php echo htmlspecialchars($record['username'] ?? 'Unknown Attendee', ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($record['email'] ?? 'Unknown Email', ENT_QUOTES, 'UTF-8'); ?></td>

                            <td><?php echo ucfirst(htmlspecialchars($record['status'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No RSVP records found for this event.</p>
        <?php endif; ?>

        <a href="all_events.php" class="btn btn-secondary">Back to Events</a>
    </div>
</body>
</html>
