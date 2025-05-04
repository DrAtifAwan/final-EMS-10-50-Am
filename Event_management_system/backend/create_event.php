<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header("Location: login.php");
    exit();
}
include_once 'db_connect.php';

// Handle event creation
$success_message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_event'])) {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_location = $_POST['event_location'];
    $event_description = $_POST['event_description'];
    $event_capacity = $_POST['event_capacity'];

    // Handle file upload
    $image_path = "";
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $uploads_dir = '../uploads/';
        $image_path = $uploads_dir . basename($_FILES['event_image']['name']);
        if (!file_exists($uploads_dir)) {
            mkdir($uploads_dir, 0777, true); // Create directory if it doesn't exist
        }
        if (move_uploaded_file($_FILES['event_image']['tmp_name'], $image_path)) {
            $success_message = "Image uploaded successfully!";
        } else {
            $success_message = "Failed to upload image.";
        }
    }

    // Insert event details including image path and capacity
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

// Fetch unread notifications
$sql = "SELECT * FROM notifications ORDER BY created_at DESC";
$result = $conn->query($sql);
$notifications = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 16px;
    background-color: #f9f9f9;
    border-radius: 8px;
}

.alert {
    padding: 12px 16px;
    border: 1px solid transparent;
    border-radius: 4px;
    font-size: 14px;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.form-group {
    margin-bottom: 16px;
    display: flex;
    flex-direction: column;
}

.form-control {
    padding: 10px 12px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 14px;
    width: 100%;
}

.btn {
    display: inline-block;
    font-size: 14px;
    padding: 10px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-primary {
    background-color: #007bff;
    color: #fff;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-secondary {
    background-color: #6c757d;
    color: #fff;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

.mt-3 {
    margin-top: 24px;
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
        <h1>Create Event</h1>
        
        <?php if ($success_message): ?>
        <div class="alert alert-success">
            <?php echo $success_message; ?>
        </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="event_name">Event Name</label>
                <input type="text" name="event_name" id="event_name" class="form-control" placeholder="Event Name" required>
            </div>
            <div class="form-group">
                <label for="event_date">Event Date</label>
                <input type="date" name="event_date" id="event_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="event_location">Event Location</label>
                <input type="text" name="event_location" id="event_location" class="form-control" placeholder="Event Location" required>
            </div>
            <div class="form-group">
                <label for="event_description">Event Description</label>
                <textarea name="event_description" id="event_description" class="form-control" placeholder="Event Description" required></textarea>
            </div>
            <div class="form-group">
                <label for="event_image">Event Image</label>
                <input type="file" name="event_image" id="event_image" class="form-control">
            </div>
            <div class="form-group">
                <label for="event_capacity">Event Capacity</label>
                <input type="number" name="event_capacity" id="event_capacity" class="form-control" placeholder="Event Capacity" required>
            </div>
            <button type="submit" name="create_event" class="btn btn-primary">Create Event</button>
        </form>
        <a href="organizer_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>
</body>
</html>
