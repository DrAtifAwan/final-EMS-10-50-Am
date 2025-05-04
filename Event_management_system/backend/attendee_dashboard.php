<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'attendee') {
    header("Location: login.php");
    exit();
}
include_once 'db_connect.php';

// Handle search query
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $sql = "SELECT * FROM events WHERE name LIKE ? OR location LIKE ? OR date LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_param = "%" . $search_query . "%";
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
    $events = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    // Fetch all events
    $sql = "SELECT * FROM events";
    $result = $conn->query($sql);
    $events = $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch RSVP status message
$rsvp_message = isset($_SESSION['rsvp_message']) ? $_SESSION['rsvp_message'] : "";
unset($_SESSION['rsvp_message']);

// Fetch notifications
$sql = "SELECT * FROM notifications WHERE attendee_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attendee Dashboard</title>
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <!-- Custom CSS for attendee dashboard -->
  <link rel="stylesheet" href="../assets/css/attendee_custom.css">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <style>
    /* Main Content Area – Leave room for the fixed left sidebar */
    .main-content {
      margin-left: 280px;  /* Adjust based on the sidebar width */
      padding: 20px;
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>
  <!-- Sidebar is included on the left side -->
  <?php include '../includes/attendee_sidebar.php'; ?>

  <!-- Main Content -->
  <div class="container-fluid main-content">
    <?php if (isset($rsvp_message) && $rsvp_message): ?>
      <div class="alert alert-info">
        <?php echo $rsvp_message; ?>
      </div>
    <?php endif; ?>

    <h2>Upcoming Events</h2>

    <form method="GET" action="attendee_dashboard.php" class="form-inline mb-3">
      <input type="text" name="search" class="form-control mr-2" placeholder="Search events" value="<?php echo htmlspecialchars($search_query); ?>">
      <button type="submit" class="btn btn-primary">Search</button>
    </form>



    <!-- Notifications Toggle -->
    <button id="toggle-notifications" class="btn btn-info mt-3">Show Notifications</button>
    <div id="notifications-list" class="hidden mt-2" style="background: #f3e5f5; padding: 15px; border-radius: 8px;">
      <h2>Notifications</h2>
      <ul>
        <?php foreach ($notifications as $notification): ?>
          <li>
            <?php echo htmlspecialchars($notification['message']); ?> -
            <span class="text-success"><?php echo htmlspecialchars($notification['created_at']); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <button id="toggle-events" class="btn btn-info mt-3">Show Upcoming Events</button>

    <div id="events-list" class="hidden mt-3">
      <table class="table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Date</th>
            <th>Location</th>
            <th>Description</th>
            <th>Capacity</th>
            <th>Remaining Spots</th>
            <th>RSVP</th>
            <th>View</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($events as $event): ?>
            <tr>
              <td><?php echo $event['name']; ?></td>
              <td><?php echo $event['date']; ?></td>
              <td><?php echo $event['location']; ?></td>
              <td><?php echo $event['description']; ?></td>
              <td><?php echo $event['capacity']; ?></td>
              <td>
                <?php
                  $sql = "SELECT COUNT(*) AS rsvp_count FROM rsvps WHERE event_id = ?";
                  $stmt = $conn->prepare($sql);
                  $stmt->bind_param("i", $event['id']);
                  $stmt->execute();
                  $result = $stmt->get_result();
                  $rsvp_count = $result->fetch_assoc()['rsvp_count'];
                  $stmt->close();
                  $remaining_spots = $event['capacity'] - $rsvp_count;
                  if ($remaining_spots <= 0) {
                    echo "Sorry, No space left";
                  } else {
                    echo $remaining_spots;
                  }
                ?>
              </td>
              <td>
                <form method="POST" action="rsvp_event.php">
                  <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                  <button type="submit" name="status" value="attend" class="btn btn-success" <?php echo $remaining_spots <= 0 ? 'disabled' : ''; ?>>
                    <i class="bi bi-check-circle"></i> Attend
                  </button>
                  <button type="submit" name="status" value="maybe" class="btn btn-warning" <?php echo $remaining_spots <= 0 ? 'disabled' : ''; ?>>
                    <i class="bi bi-question-circle"></i> Maybe
                  </button>
                  <button type="submit" name="status" value="decline" class="btn btn-danger">
                    <i class="bi bi-x-circle"></i> Decline
                  </button>
                </form>
              </td>
              <td>
                <form method="POST" action="view_event.php">
                  <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                  <button type="submit" name="view_event" class="btn btn-info">
                    <i class="bi bi-eye"></i> View
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    // Toggle Upcoming Events list
    const toggleButton = document.getElementById("toggle-events");
    const eventsList = document.getElementById("events-list");
    toggleButton.addEventListener("click", () => {
      if (eventsList.classList.contains("hidden")) {
        eventsList.classList.remove("hidden");
        toggleButton.textContent = "Hide Upcoming Events";
      } else {
        eventsList.classList.add("hidden");
        toggleButton.textContent = "Show Upcoming Events";
      }
    });

    // Toggle Notifications list
    const toggleNotificationsButton = document.getElementById("toggle-notifications");
    const notificationsList = document.getElementById("notifications-list");
    toggleNotificationsButton.addEventListener("click", () => {
      if (notificationsList.classList.contains("hidden")) {
        notificationsList.classList.remove("hidden");
        toggleNotificationsButton.textContent = "Hide Notifications";
      } else {
        notificationsList.classList.add("hidden");
        toggleNotificationsButton.textContent = "Show Notifications";
      }
    });
  </script>

  <script src="frontend/script.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

