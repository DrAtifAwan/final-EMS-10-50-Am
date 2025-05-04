<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header("Location: login.php");
    exit();
}
include_once 'db_connect.php';
$success_message = isset($_SESSION['message']) ? $_SESSION['message'] : ""; 
unset($_SESSION['message']);
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
    $sql = "SELECT * FROM events";
    $result = $conn->query($sql);
    $events = $result->fetch_all(MYSQLI_ASSOC);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_event'])) {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_location = $_POST['event_location'];
    $event_description = $_POST['event_description'];
    $event_capacity = $_POST['event_capacity'];
    $image_path = "";
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $uploads_dir = '../uploads/';
        $image_path = $uploads_dir . basename($_FILES['event_image']['name']);
        if (!file_exists($uploads_dir)) {
            mkdir($uploads_dir, 0777, true);
        }
        if (move_uploaded_file($_FILES['event_image']['tmp_name'], $image_path)) {
            $success_message = "Image uploaded successfully!";
        } else {
            $success_message = "Failed to upload image.";
        }
    }
    $sql = "INSERT INTO events (name, date, location, description, organizer_id, image_path, capacity) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $event_name, $event_date, $event_location, $event_description, $_SESSION['user_id'], $image_path, $event_capacity);
    if ($stmt->execute()) {
        $success_message = "Event created successfully!";
    } else {
        $success_message = "Error: " . $stmt->error;
    }
    $stmt->close();
}
$sql = "SELECT * FROM notifications ORDER BY created_at DESC";
$result = $conn->query($sql);
$notifications = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Organizer Dashboard</title>
  <link rel="stylesheet" href="../assets/css/organizer_custom.css">
  <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<!-- Main Layout -->
<div class="container-fluid main-container">
  <div class="row">
  <div class="container-fluid flex-grow-1">
  <div class="row">
    <!-- Fixed Sidebar -->
    <div class="sidebar col-md-2">
      <?php include '../includes/sidebar.php'; ?>
    </div>

    <!-- Main Content Area -->
    <div class="col-md-10 content-area">
<!-- Hero Search Section -->
<!-- Search Container with Fixed Background -->
<div class="search-hero-container">
    <div class="search-hero-content">
        <h2 class="search-hero-title">Discover Amazing Events</h2>
        <form method="GET" action="organizer_dashboard.php" class="search-hero-form">
            <div class="input-group">
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Search events by name, location, or date..."
                       value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>
      <div class="full-container">
        

        <!-- Rest of your content (carousels, notifications, events, etc.) -->
        <!-- Hero Carousel -->
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <ol class="carousel-indicators">
        <li data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></li>
        <li data-bs-target="#heroCarousel" data-bs-slide-to="1"></li>
        <li data-bs-target="#heroCarousel" data-bs-slide-to="2"></li>
      </ol>
      <div class="carousel-inner">
        <div class="carousel-item active hero-slide" data-bg-image="../assets/images/img01.jpg">
          <div class="overlay"></div>
          <div class="carousel-caption d-none d-md-block">
          <a href="create_event.php" class="btn btn-primary">Create AN Event</a>
            <h1 class="display-4 text-white">Create Your Special Event</h1>
            <p class="lead text-white">Design unforgettable experiences with just a few clicks.</p>
          </div>
        </div>
        <div class="carousel-item hero-slide" data-bg-image="../assets/images/customer.webp">
          <div class="overlay"></div>
          <div class="carousel-caption d-none d-md-block">
            
            <h1 class="display-4 text-white">Inspire Creativity</h1>
            <p class="lead text-white">Bring your ideas to life with our intuitive platform.</p>
          </div>
        </div>
        <div class="carousel-item hero-slide" data-bg-image="../assets/images/girl6.jpeg">
          <div class="overlay"></div>
          <div class="carousel-caption d-none d-md-block">
            <a href="http://localhost/event_management_system/backend/create_event.php" class="btn create-event-btn">Create Event</a>
            <h1 class="display-4 text-white">Join the Experience</h1>
            <p class="lead text-white">Craft memorable events starting now.</p>
          </div>
        </div>
      </div>
      <a class="carousel-control-prev" href="#heroCarousel" role="button" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </a>
      <a class="carousel-control-next" href="#heroCarousel" role="button" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </a>
        </div>

        <?php if ($success_message): ?>
  <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
    <?php echo $success_message; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif; ?>

  <!-- Notifications Section -->
  <div class="container-fluid mt-4">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card shadow-lg notifications-card">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
              <i class="fas fa-bell me-2"></i>Notifications
            </h5>
            <button id="toggle-notifications" class="btn btn-light btn-sm">
              <i class="fas fa-chevron-down"></i>
            </button>
          </div>
          
          <div id="notifications-list" class="card-body">
            <?php if(empty($notifications)): ?>
              <div class="text-center text-muted py-3">
                No new notifications
              </div>
            <?php else: ?>
              <div class="list-group notification-items">
                <?php foreach ($notifications as $notification): ?>
                <div class="list-group-item list-group-item-action">
                  <div class="d-flex justify-content-between align-items-start">
                    <div class="me-3">
                      <div class="fw-bold"><?php echo $notification['message']; ?></div>
                      <small class="text-muted"><?php echo $notification['created_at']; ?></small>
                    </div>
                    <form method="POST" action="delete_notification.php" class="d-inline">
                      <input type="hidden" name="notification_id" value="<?php echo $notification['id']; ?>">
                      <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
<br>


<div class="events-carousel-container mb-5">
  <div id="eventsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-inner">
      <?php
      // Chunk events into groups of 3 per slide
      $chunks = array_chunk($events, 3);
      foreach ($chunks as $index => $event_chunk): ?>
        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
          <!-- Use a flex container with no wrapping so that event cards remain horizontal -->
          <div class="d-flex flex-nowrap">
            <?php foreach ($event_chunk as $event): ?>
              <div class="p-2">
                <!-- Each card has a fixed minimum width to prevent wrapping -->
                <div class="card event-card shadow" style="min-width:300px;">
                  <div class="card-img-top-container">
                    <?php if (!empty($event['image_path'])): ?>
                      <img src="<?php echo $event['image_path']; ?>" class="card-img-top" alt="Event Image">
                    <?php else: ?>
                      <img src="assets/images/default_event.jpg" class="card-img-top" alt="Default Image">
                    <?php endif; ?>
                    <div class="card-badge">
                      <span class="badge bg-primary">
                        <?php echo date('M d', strtotime($event['date'])); ?>
                      </span>
                    </div>
                  </div>
                  <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($event['name']); ?></h5>
                    <p class="card-text text-muted">
                      <i class="fas fa-map-marker-alt"></i>
                      <?php echo htmlspecialchars($event['location']); ?>
                    </p>
                    <p class="card-text">
                      <?php echo htmlspecialchars(substr($event['description'], 0, 100)) . '...'; ?>
                    </p>
                    <div class="d-flex justify-content-end align-items-center">
                      <a href="view_event.php?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-primary">
                        View Details
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#eventsCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#eventsCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</div>


<!-- Circular Button Container -->
<div class="circle-button-container" 
     style="background-image: url('../assets/images/event-btn-bg.jpg')">
    <button id="toggle-events" class="circle-btn">
        <i class="fas fa-calendar-alt"></i>
        Show Events
    </button>
</div>

<div id="events-list" class="hidden" style="margin-top: 20px;">
  <div class="table-responsive" style="max-width: 95%; margin: auto;">
    <table class="table table-bordered table-hover table-striped w-100">
      <thead class="table-primary">
        <tr>
          <th class="text-nowrap">Name</th>
          <th class="text-nowrap">Date</th>
          <th class="text-nowrap">Location</th>
          <th>Description</th>
          <th>Image</th>
          <th class="text-nowrap">Capacity</th>
          <th class="text-nowrap">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($events as $event): ?>
          <tr>
            <td class="align-middle"><?php echo htmlspecialchars($event['name']); ?></td>
            <td class="align-middle text-nowrap"><?php echo htmlspecialchars($event['date']); ?></td>
            <td class="align-middle"><?php echo htmlspecialchars($event['location']); ?></td>
            <td class="align-middle text-truncate" style="max-width: 200px;"><?php echo htmlspecialchars($event['description']); ?></td>
            <td class="align-middle">
              <img src="<?php echo htmlspecialchars($event['image_path']); ?>" 
                   alt="Event Image" class="img-thumbnail" 
                   style="width: 80px; height: 80px; object-fit: cover;">
            </td>
            <td class="align-middle"><?php echo htmlspecialchars($event['capacity']); ?></td>
            <td class="align-middle">
              <div class="d-flex flex-wrap gap-2">
                <form method="POST" action="view_event.php" class="d-inline">
                  <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                  <button type="submit" name="view_event" class="btn btn-sm btn-info">
                    <i class="bi bi-eye"></i> View
                  </button>
                </form>
                <form method="POST" action="edit_event.php" class="d-inline">
                  <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                  <button type="submit" name="edit_event" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                  </button>
                </form>
                <form method="POST" action="delete_event.php" onsubmit="return confirm('Are you sure?');" class="d-inline">
                  <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                  <button type="submit" name="delete_event" class="btn btn-sm btn-danger">
                    <i class="bi bi-trash"></i> Delete
                  </button>
                </form>
                <form method="POST" action="rsvp_details.php" class="d-inline">
                  <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                  <button type="submit" name="rsvp_details" class="btn btn-sm btn-secondary">
                    <i class="bi bi-info-circle"></i> RSVPs
                  </button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div>
</body>
<br>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    var carousel = new bootstrap.Carousel(document.getElementById("eventsCarousel"), {
        interval: 3000,  // Adjust speed (milliseconds)
        ride: "carousel" // Ensures auto-slide starts
    });
});
</script>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script> 
        <script src="../frontend/script.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/dashboard.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Optional: jQuery for additional functionality -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Custom JS -->
        <script src="../assets/js/dashboard.js"></script>
        <script>
document.getElementById('toggle-events').addEventListener('click', function () {
    const eventsList = document.getElementById('events-list');
    if (eventsList.classList.contains('hidden')) {
        eventsList.classList.remove('hidden'); // Show the table
        eventsList.style.display = 'block'; // Ensure the table is visible
        eventsList.scrollIntoView({ behavior: 'smooth' }); // Smooth scrolling
    } else {
        eventsList.classList.add('hidden'); // Hide the table
        eventsList.style.display = 'none'; // Ensure the table is hidden
    }
});

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
const toggleButton = document.getElementById("toggle-events");
const eventsList = document.getElementById("events-list");
toggleButton.addEventListener("click", () => {
    if (eventsList.classList.contains("hidden")) {
        eventsList.classList.remove("hidden");
        toggleButton.textContent = "Hide Events";
    } else {
        eventsList.classList.add("hidden");
        toggleButton.textContent = "Show Events";
    }
});

document.querySelectorAll('.hero-slide[data-bg-image]').forEach(slide => {
    const bgImage = slide.getAttribute('data-bg-image');
    if (bgImage) {
        slide.style.backgroundImage = `url('${bgImage}')`;
    }
});
        </script>
        <script>
// Corrected Notification Toggle
document.getElementById('toggle-notifications').addEventListener('click', function() {
    const notificationsList = document.getElementById('notifications-list');
    const icon = this.querySelector('i');
    
    // Toggle visibility
    notificationsList.classList.toggle('d-none');
    
    // Update chevron icon
    icon.classList.toggle('fa-chevron-down');
    icon.classList.toggle('fa-chevron-up');
    
    // Update button text
    this.querySelector('span').textContent = notificationsList.classList.contains('d-none') 
        ? 'Show Notifications' 
        : 'Hide Notifications';
});
</script>
<?php include '../includes/footer.php'; ?>
</body>
</html>

