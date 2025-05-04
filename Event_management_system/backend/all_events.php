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
    <title>All Events</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
* {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
body {
    height: 100vh;
    background: linear-gradient(135deg, #ff416c, #ff4b2b, #36d1dc, #5b86e5);
    background-size: 300% 300%;
    animation: gradientAnimation 10s ease infinite;
    color: #333;
}
@keyframes gradientAnimation {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
.role-heading {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    color: white;
    text-transform: uppercase;
    margin-bottom: 20px;
}

.container {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.left-side, .right-side {
    padding: 30px;
    border-radius: 10px;
}

.left-side {
    background: linear-gradient(135deg, #36d1dc, #5b86e5);
    color: white;
}

.right-side {
    background: linear-gradient(135deg, #ff416c, #ff4b2b);
    color: white;
}

/* 🎯 Active Right-Side */
.right-side.active {
    background: linear-gradient(135deg, #5b86e5, #36d1dc);
    transition: 0.5s;
}

.form-group {
    margin-bottom: 15px;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 2px solid white;
    border-radius: 5px;
    outline: none;
    font-size: 16px;
    background: transparent;
    color: white;
}

.form-group input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}
        .event-box {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px;
            border-radius: 5px;
        }
 /* General Styling */
body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(135deg, #1e1e2f, #252a34, #1b1b2f);
  color: #e0e0e0;
}

/* Container Styling */
.container {
  max-width: 1300px;
  margin: 0 auto;
}

/* Event Box Styling */
.event-box {
  padding: 20px;
  border-radius: 15px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
  transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.event-box:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.7);
}

/* Unique Multicolor Backgrounds */
.event-box:nth-child(1) {
  background: linear-gradient(135deg, #ff7eb3, #ff758c);
}

.event-box:nth-child(2) {
  background: linear-gradient(135deg, #6a11cb, #2575fc);
}

.event-box:nth-child(3) {
  background: linear-gradient(135deg, #ffb347, #ffcc33);
}

.event-box:nth-child(4) {
  background: linear-gradient(135deg, #00c9ff, #92fe9d);
}

.event-box:nth-child(5) {
  background: linear-gradient(135deg, #ff416c, #ff4b2b);
}

.event-box:nth-child(6) {
  background: linear-gradient(135deg, #8e2de2, #4a00e0);
}

.event-box h3 {
  font-size: 26px;
  font-weight: bold;
  color: #fff;
  margin-bottom: 10px;
}

.event-box p {
  font-size: 16px;
  color: #fff;
  margin: 5px 0;
}

/* Button Styling */
.btn {
  border-radius: 30px;
  padding: 10px 30px;
  font-weight: bold;
  text-transform: uppercase;
  transition: all 0.3s ease;
  border: none;
  font-size: 16px;
  margin-top: 10px;
}

.btn:hover {
  transform: scale(1.05);
}

/* Button Colors */
.btn-success {
  background: linear-gradient(135deg, #00ff87, #009688);
  color: #fff;
}

.btn-warning {
  background: linear-gradient(135deg, #ffb347, #ffcc33);
  color: #fff;
}

.btn-danger {
  background: linear-gradient(135deg, #ff416c, #ff4b2b);
  color: #fff;
}

/* Image Styling */
.card-img-top {
  border-radius: 15px;
  width: 100%;
  height: auto;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

/* Search Bar */
.form-control {
  border-radius: 10px;
  padding: 12px 20px;
  border: none;
  background: rgba(255, 255, 255, 0.1);
  color: #e0e0e0;
  font-size: 16px;
  transition: 0.3s ease;
}

.form-control::placeholder {
  color: #aaa;
}

.form-control:focus {
  background: rgba(255, 255, 255, 0.2);
  outline: none;
}

/* Search Button */
.btn-primary {
  background: linear-gradient(135deg, #2193b0, #6dd5ed);
  color: #fff;
}

/* Responsive Design */
@media (max-width: 768px) {
  .event-box {
    margin-bottom: 20px;
  }

  .btn {
    width: 100%;
  }
}
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
* {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
body {
    height: 100vh;
    background: linear-gradient(135deg, #ff416c, #ff4b2b, #36d1dc, #5b86e5);
    background-size: 300% 300%;
    animation: gradientAnimation 10s ease infinite;
    color: #333;
}
@keyframes gradientAnimation {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
.role-heading {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    color: white;
    text-transform: uppercase;
    margin-bottom: 20px;
}

.container {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.left-side, .right-side {
    padding: 30px;
    border-radius: 10px;
}

.left-side {
    background: linear-gradient(135deg, #36d1dc, #5b86e5);
    color: white;
}

.right-side {
    background: linear-gradient(135deg, #ff416c, #ff4b2b);
    color: white;
}

/* 🎯 Active Right-Side */
.right-side.active {
    background: linear-gradient(135deg, #5b86e5, #36d1dc);
    transition: 0.5s;
}

.form-group {
    margin-bottom: 15px;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 2px solid white;
    border-radius: 5px;
    outline: none;
    font-size: 16px;
    background: transparent;
    color: white;
}

.form-group input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}
    </style>
</head>
<body>
    <div class="container">
        <h1>All Events</h1>

        <?php if ($success_message): ?>
        <div class="alert alert-success">
            <?php echo $success_message; ?>
        </div>
        <?php endif; ?>

        <form method="GET" action="all_events.php" class="form-inline mb-3">
            <input type="text" name="search" class="form-control mr-2" placeholder="Search events" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <div class="row">
            <?php foreach($events as $event): ?>
            <div class="col-md-4">
                <div class="event-box">
                    <h2><?php echo htmlspecialchars($event['name']); ?></h2>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($event['date']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
                    <?php if ($event['image_path']): ?>
                    <p><img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="Event Image" style="width: 100px; height: 100px;"></p>
                    <?php endif; ?>
                    <p><strong>Capacity:</strong> <?php echo htmlspecialchars($event['capacity']); ?></p>
                    <p><strong>Remaining Spots:</strong> 
                    <?php
                    $sql = "SELECT COUNT(*) AS rsvp_count FROM rsvps WHERE event_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $event['id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $rsvp_count = $result->fetch_assoc()['rsvp_count'];
                    $stmt->close();
                    $remaining_spots = $event['capacity'] - $rsvp_count;
                    echo htmlspecialchars($remaining_spots);
                    ?>
                    </p>
                    <form method="POST" action="view_event.php">
                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                        <button type="submit" name="view_event" class="btn btn-info">View</button>
                    </form>
                    <form method="POST" action="edit_event.php">
                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                        <button type="submit" name="edit_event" class="btn btn-warning">Edit</button>
                    </form>
                    <form method="POST" action="delete_event.php" onsubmit="return confirm('Are you sure you want to delete this event?');">
                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                        <button type="submit" name="delete_event" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
