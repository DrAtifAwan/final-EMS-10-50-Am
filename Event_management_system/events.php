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
    <title>All Events</title>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="frontend/style.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="index.php">Eventify</a>
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
                        <a class="nav-link" href="contact_us.php">Contact Us</a>
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
        <h1 class="mb-4">All Events</h1>
        <div id="eventsCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($events as $index => $event): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="card mx-auto" style="width: 18rem;">
                            <?php if (!empty($event['image_path'])): ?>
                                <img src="<?php echo $event['image_path']; ?>" class="card-img-top" alt="Event Image">
                            <?php else: ?>
                                <img src="assets/images/default_event.jpg" class="card-img-top" alt="Default Image">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($event['name']); ?></h5>
                                <p class="card-text"><strong>Date:</strong> <?php echo htmlspecialchars($event['date']); ?></p>
                                <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                                <p class="card-text"><?php echo htmlspecialchars($event['description']); ?></p>
                                <p class="card-text"><strong>Capacity:</strong> <?php echo htmlspecialchars($event['capacity']); ?></p>
                                <p class="card-text"><strong>Remaining Spots:</strong> 
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
                                    echo htmlspecialchars($remaining_spots <= 0 ? "Sorry, No space left" : $remaining_spots);
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <a class="carousel-control-prev" href="#eventsCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#eventsCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    <footer class="mt-5">
        <p>&copy; 2024 Event Management System. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
