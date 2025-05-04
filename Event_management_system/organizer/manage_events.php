<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header("Location: login.php");
    exit();
}
include_once '../backend/db_connect.php';

/**
 * Helper function to get the RSVP list for an event.
 * Returns an array of attendee information (email and status).
 */
function getRSVPList($eventId, $conn) {
    $list = [];
    // Updated JOIN: using r.attendee_id instead of r.user_id
    $sql = "SELECT r.status, u.email FROM rsvps r JOIN users u ON r.attendee_id = u.id WHERE r.event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()){
        $list[] = $row;
    }
    $stmt->close();
    return $list;
}

// Fetch events for this organizer
$organizer_id = $_SESSION['user_id'];
$sql = "SELECT * FROM events WHERE organizer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $organizer_id);
$stmt->execute();
$result = $stmt->get_result();
$events = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Optional: Handle search/filtering if provided via GET parameters
$search = '';
if (isset($_GET['search'])) {
    $search = strtolower(trim($_GET['search']));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Events</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    body {
      background: linear-gradient(135deg, #1d2b64, #f8cdda);
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 20px;
      color: #333;
    }
    .container {
      margin-top: 50px;
    }
    .search-container {
      margin-bottom: 20px;
    }
    .event-card {
      margin-bottom: 30px;
    }
    .card-actions {
      margin-top: 15px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1 class="mb-4">Manage Events</h1>

    <?php if (isset($_SESSION['message'])): ?>
      <div class="alert alert-success"><?php echo $_SESSION['message']; ?></div>
      <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- Search/Filter Form -->
    <form method="GET" class="search-container">
      <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search events..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </form>

    <div class="row">
      <?php if (count($events) > 0): ?>
        <?php foreach ($events as $event): ?>
          <?php
            // Apply search filter if provided: match against event name or location.
            if (!empty($search)) {
                $name_match = strpos(strtolower($event['name']), $search);
                $location_match = strpos(strtolower($event['location']), $search);
                if ($name_match === false && $location_match === false) {
                    continue;
                }
            }
            // Retrieve RSVP count for this event
            $rsvp_sql = "SELECT COUNT(*) AS rsvp_count FROM rsvps WHERE event_id = ?";
            $rsvp_stmt = $conn->prepare($rsvp_sql);
            $rsvp_stmt->bind_param("i", $event['id']);
            $rsvp_stmt->execute();
            $result_status = $rsvp_stmt->get_result();
            $rsvp_data = $result_status->fetch_assoc();
            $rsvp_count = isset($rsvp_data['rsvp_count']) ? $rsvp_data['rsvp_count'] : 0;
            $rsvp_stmt->close();

            // Calculate remaining slots
            $remaining = $event['capacity'] - $rsvp_count;
          ?>
          <div class="col-md-4">
            <div class="card event-card">
              <?php if (!empty($event['image_path'])): ?>
                <img src="<?php echo $event['image_path']; ?>" class="card-img-top" alt="Event Image">
              <?php else: ?>
                <img src="../assets/images/default_event.jpg" class="card-img-top" alt="Default Image">
              <?php endif; ?>
              <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($event['name']); ?></h5>
                <p class="card-text"><strong>Date:</strong> <?php echo htmlspecialchars($event['date']); ?></p>
                <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                <p class="card-text"><strong>Capacity:</strong> <?php echo htmlspecialchars($event['capacity']); ?></p>
                <p class="card-text">
                  <strong>RSVP:</strong> <?php echo $rsvp_count; ?>
                  <small>(Remaining: <?php echo $remaining; ?>)</small>
                </p>
                <div class="card-actions">
                  <div class="btn-group" role="group">
                    <!-- Edit Event -->
                    <form method="POST" action="edit_event.php">
                      <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['id']); ?>">
                      <button type="submit" name="view_event" class="btn btn-sm btn-primary">Edit</button>
                    </form>
                    <!-- Delete Event -->
                    <form method="POST" action="delete_event.php" onsubmit="return confirm('Are you sure you want to delete this event?');">
                      <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['id']); ?>">
                      <button type="submit" name="delete_event" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                    <!-- View RSVP Details -->
                    <form method="POST" action="rsvp_details.php">
                      <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['id']); ?>">
                      <button type="submit" name="view_rsvps" class="btn btn-sm btn-info">RSVP Details</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-center">No events found.</p>
      <?php endif; ?>
    </div>

    
  </div>
  <a href="organizer_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
  </div>

  <!-- RSVP Details Modal -->
  <div class="modal fade" id="rsvpModal" tabindex="-1" aria-labelledby="rsvpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title" id="rsvpModalLabel">RSVP Details</h5>
           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
           <div class="row">
             <div class="col-md-6">
               <canvas id="rsvpChart" style="width: 100%; height: 300px;"></canvas>
             </div>
             <div class="col-md-6">
               <h6>Attendee List</h6>
               <table class="table table-sm">
                 <thead>
                   <tr>
                     <th>Email</th>
                     <th>Status</th>
                   </tr>
                 </thead>
                 <tbody id="rsvpTable">
                   <!-- RSVP list will be injected here -->
                 </tbody>
               </table>
             </div>
           </div>
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
         </div>
      </div>
    </div>
  </div>

  <!-- JavaScript for RSVP Modal & Chart -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const rsvpModal = document.getElementById('rsvpModal');
      if (rsvpModal) {
        rsvpModal.addEventListener('show.bs.modal', function(event) {
          const button = event.relatedTarget;
          const eventName = button.getAttribute('data-eventname');
          const attend = parseInt(button.getAttribute('data-attend'));
          const maybe = parseInt(button.getAttribute('data-maybe'));
          const decline = parseInt(button.getAttribute('data-decline'));
          const rsvpList = JSON.parse(button.getAttribute('data-rsvplist'));

          // Update Modal Title
          document.getElementById('rsvpModalLabel').textContent = `RSVP Details - ${eventName}`;

          // Render Chart
          const ctx = document.getElementById('rsvpChart').getContext('2d');
          if (window.rsvpChart) window.rsvpChart.destroy();
          window.rsvpChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Attending', 'Maybe', 'Declined'],
              datasets: [{
                data: [attend, maybe, decline],
                backgroundColor: [
                  '#00b4d8',
                  '#ff9f1c',
                  '#ff3860'
                ],
                borderWidth: 0
              }]
            },
            options: {
              responsive: true,
              plugins: {
                legend: { position: 'bottom' },
              }
            }
          });

          // Populate RSVP Table
          const rsvpTable = document.getElementById('rsvpTable');
          rsvpTable.innerHTML = '';
          rsvpList.forEach(rsvp => {
            const row = document.createElement('tr');
            row.innerHTML = `
              <td>${rsvp.email}</td>
              <td>
                <span class="badge ${rsvp.status === 'attend' ? 'bg-info' : rsvp.status === 'maybe' ? 'bg-warning' : 'bg-danger'}">
                  ${rsvp.status}
                </span>
              </td>
            `;
            rsvpTable.appendChild(row);
          });
        });
      }
    });
  </script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>

