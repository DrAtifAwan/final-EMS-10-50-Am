<?php
session_start();

// Check that the user is logged in and has the attendee role.
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'attendee') {
    header("Location: login.php");
    exit();
}

// Include your database connection file.
include_once '../backend/db_connect.php';

// Retrieve the current attendee's ID.
$attendee_id = $_SESSION['user_id'];

// Prepare and execute the SQL query to fetch events with RSVP status.
$sql = "SELECT e.*, r.status 
        FROM events e 
        INNER JOIN rsvps r ON e.id = r.event_id 
        WHERE r.attendee_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $attendee_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all events into an associative array.
$myEvents = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Events</title>
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <!-- Custom CSS for Attendee Dashboard -->
  <link rel="stylesheet" href="../assets/css/attendee_custom.css">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <style>
    /* Adjust the main content container to leave room for the left sidebar */
    .main-container {
      margin-left: 280px; /* Same as sidebar width */
      padding: 20px;
    }
  </style>
</head>
<body>
  <!-- Include the Attendee Sidebar -->
  <?php include '../includes/attendee_sidebar.php'; ?>

  <!-- Main Content Area -->
  <div class="container-fluid main-container">
    <h1>My Events</h1>

    <?php if (empty($myEvents)): ?>
      <p>You have not RSVP'd to any events yet.</p>
    <?php else: ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Event Name</th>
            <th>Date</th>
            <th>Location</th>
            <th>Description</th>
            <th>Capacity</th>
            <th>RSVP Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($myEvents as $event): ?>
            <tr>
              <td><?php echo htmlspecialchars($event['name']); ?></td>
              <td><?php echo htmlspecialchars($event['date']); ?></td>
              <td><?php echo htmlspecialchars($event['location']); ?></td>
              <td><?php echo htmlspecialchars($event['description']); ?></td>
              <td><?php echo htmlspecialchars($event['capacity']); ?></td>
              <td><?php echo htmlspecialchars($event['status']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <!-- Bootstrap Bundle with Popper -->
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
