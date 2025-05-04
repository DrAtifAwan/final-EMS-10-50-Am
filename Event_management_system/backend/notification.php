<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header("Location: login.php");
    exit();
}
include_once 'db_connect.php';

// Initialize success message
$success_message = "";

// Function to send notification
function sendNotification($event_id, $user_id, $message) {
    global $conn;

    // Insert notification into the database
    $sql = "INSERT INTO notifications (event_id, attendee_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $event_id, $user_id, $message);
    $stmt->execute();
    $stmt->close();
}

// Handle send notification
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_notification'])) {
    $event_id = $_POST['event_id'];
    $notification_message = $_POST['notification_message'];
    $attendees = $_POST['attendees']; // Array of attendee IDs

    foreach ($attendees as $attendee_id) {
        sendNotification($event_id, $attendee_id, $notification_message);
    }

    // Set success message
    $success_message = "Notifications sent successfully!";
}

// Fetch events
$sql = "SELECT * FROM events";
$result = $conn->query($sql);
$events = $result->fetch_all(MYSQLI_ASSOC);

// Fetch attendees
$sql = "SELECT id, name FROM attendees";
$result = $conn->query($sql);
$attendees = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Notification</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        h2 {
            color: #2c3e50;
            border-bottom: 3px solid #6a11cb;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 8px rgba(106, 17, 203, 0.2);
        }

        textarea {
            min-height: 150px;
            resize: vertical;
        }

        .btn-info {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            transition: transform 0.2s;
        }

        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(106, 17, 203, 0.3);
        }

        .alert-success {
            border-radius: 8px;
            background: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        select[multiple] {
            height: 200px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Send Notification</h2>

    <?php if ($success_message): ?>
    <div class="alert alert-success">
        <?php echo $success_message; ?>
    </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="event_id">Select Event</label>
            <select name="event_id" id="event_id" class="form-control" required>
                <?php foreach ($events as $event): ?>
                    <option value="<?php echo $event['id']; ?>"><?php echo $event['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="notification_message">Notification Message</label>
            <textarea name="notification_message" id="notification_message" class="form-control" placeholder="Enter notification message" required></textarea>
        </div>
        <div class="form-group">
            <label for="attendees">Select Attendees</label>
            <select name="attendees[]" id="attendees" class="form-control" multiple required>
                <?php foreach ($attendees as $attendee): ?>
                    <option value="<?php echo $attendee['id']; ?>"><?php echo $attendee['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" name="send_notification" class="btn btn-info">Send Notification</button>
    </form>
    <button onclick="window.location.href='organizer_dashboard.php'" class="btn btn-secondary mt-3">Back to Dashboard</button>
</div>
</body>
</html>
