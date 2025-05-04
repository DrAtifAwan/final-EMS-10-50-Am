<?php
session_start();
include_once 'backend/db_connect.php';

// Fetch events
$sql = "SELECT * FROM events ORDER BY date ASC";
$result = $conn->query($sql);
$events = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"> <!-- Optional Bootstrap Icons -->
    <link rel="stylesheet" href="assets/css/index.css"> <!-- Custom CSS for index page -->
    <link rel="stylesheet" href="frontend/style.css">
    <!-- Inline CSS for Background Image -->
     
    <style>


 

    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Event Horizon</a>
<span style="margin-right: 20px;"></span>
<a class="btn btn-info" href="backend/profile.php">Profile <i class="bi bi-person"></i></a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="events.php">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="backend/contact_us.php">Contact Us</a> <!-- Updated path -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white" href="backend/organizer_form.php">Get Started as Organizer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-secondary text-white" href="backend/attendee_form.php">Get Started as Attendee</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="container mt-5">
    <div class="container" style="background: url('assets/images/girl6.jpeg') no-repeat center center; background-size: cover; padding: 50px; border-radius: 10px; color: white;">

        <h1 class="text-center">Welcome to the Event Management System</h1>
        <h2>About the System</h2>
        <p>Our Event Management System simplifies the process of organizing and managing events. Whether you're an organizer or an attendee, this platform provides all the tools you need to ensure seamless event coordination. Register today and make event management effortless!</p>
        <h2>Choose Your Role</h2>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Organizer</h3>
                        <p class="card-text">Create and manage your events. Keep track of attendees and their RSVPs with ease.</p>
                        <a class="btn btn-primary" href="backend/organizer_form.php">Get Started as Organizer</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Attendee</h3>
                        <p class="card-text">Browse and RSVP to events. Stay updated with the latest event details.</p>
                        <a class="btn btn-secondary" href="backend/attendee_form.php">Get Started as Attendee</a>
                    </div>
                </div>
            </div>
        </div>

        <h2>Upcoming Events</h2>
<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php
        $chunks = array_chunk($events, 3);
        foreach ($chunks as $index => $event_chunk): ?>
            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                <div class="row">
                    <?php foreach ($event_chunk as $event): ?>
                        <div class="col-md-4">
                            <div class="card mx-auto mb-3" style="width: 18rem;">
                                <?php 
                                // Check if event image_path has a value.
                                // Assuming event['image_path'] contains just the file name.
                                if (!empty($event['image_path'])) {
                                    // Update the image source to point to the correct folder.
                                    $imgSource = "uploads/" . $event['image_path'];
                                } else {
                                    $imgSource = "uploads/default_event.jpg";
                                }
                                ?>
                                <img src="<?php echo htmlspecialchars($imgSource); ?>" class="card-img-top" alt="Event Image">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($event['name']); ?></h5>
                                    <p class="card-text"><strong>Date:</strong> <?php echo htmlspecialchars($event['date']); ?></p>
                                    <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                                    <p class="card-text"><?php echo htmlspecialchars($event['description']); ?></p>
                                    <p class="card-text"><strong>Capacity:</strong> <?php echo htmlspecialchars($event['capacity']); ?></p>
                                    <p class="card-text"><strong>Remaining Spots:</strong> 
                                        <?php
                                        $sql = "SELECT COUNT(*) AS rsvp_count FROM rsvps WHERE event_id = ?";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("i", $event['id']);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $rsvp_count = $result->fetch_assoc()['rsvp_count'];
                                        $stmt->close();

                                        $remaining_spots = $event['capacity'] - $rsvp_count;
                                        echo htmlspecialchars($remaining_spots <= 0 ? "Sorry, No space left" : $remaining_spots);
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>


        <div class="text-center mt-5">
            <a class="btn btn-info" href="events.php">View All Events</a> <!-- Button to show all events -->
        </div>
    </div>

    <footer class="mt-5">
        <div class="position-absolute login-icons">
            <a href="backend/contact_us.php"> <!-- Updated path -->
                <i class="fa fa-envelope fa-2x social-icon" aria-hidden="true"></i>
            </a>
            <a href="backend/contact_us.php"> <!-- Updated path -->
                <i class="fa fa-github fa-2x social-icon" aria-hidden="true"></i>
            </a>
        </div>
        <!-- footer.php -->

        <footer class="organizer-footer">
  <div class="footer-content">
    <div class="container-fluid px-0">
      <div class="row g-4">
        <!-- Logo Column -->
        <div class="col-md-4 text-center text-md-start">
          <img src="assets/images/ems.jpg" alt="EMS Logo" class="footer-logo mb-3">
          <p class="footer-text">Empowering Event Professionals</p>
          <div class="social-links">
            <a href="#" class="social-icon"><i class="fab fa-facebook"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-linkedin"></i></a>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="col-md-4">
          <h5 class="footer-heading">Organizer Tools</h5>
          <ul class="footer-menu">
            <li><a href="organizer_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="create_event.php"><i class="fas fa-calendar-plus"></i> New Event</a></li>
            <li><a href="manage_events.php"><i class="fas fa-tasks"></i> Manage Events</a></li>
            <li><a href="reports.php"><i class="fas fa-chart-pie"></i> Analytics</a></li>
          </ul>
        </div>

        <!-- Support Section -->
        <div class="col-md-4">
          <h5 class="footer-heading">Support Center</h5>
          <div class="support-info">
            <p><i class="fas fa-phone"></i> +92 307 6704995</p>
            <p><i class="fas fa-envelope"></i> support@event_horizon.com</p>
            <p><i class="fas fa-map-marker-alt"></i> Soon valley , Pakistan</p>
          </div>
          <div class="footer-actions mt-3">
            <a href="contact_us.php" class="btn btn-outline-light btn-sm"><i class="fas fa-headset"></i> Help Desk</a>
            <a href="logout.php" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> about</a>
          </div>
        </div>
      </div>

      <hr class="footer-divider">

      <!-- Footer Bottom -->
      <div class="row">
        <div class="col-md-6">
          <p class="copyright">
            &copy; 2025 event_horizon. All rights reserved.<br>
            <small>v2.1.5 | Build 1245</small>
          </p>
        </div>
        <div class="col-md-6 text-md-end">
          <div class="legal-links">
            <a href="privacy.php">Privacy Policy</a> | 
            <a href="terms.php">Terms of Service</a> | 
            <a href="cookies.php">Cookie Settings</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>


    <!-- Bootstrap JS CDN -->
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="frontend/script.js"></script>
    
</body>
</html>

