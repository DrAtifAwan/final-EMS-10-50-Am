<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header("Location: login.php");
    exit();
}
include_once 'db_connect.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get event details
if (isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];
    $sql = "SELECT * FROM events WHERE id = ? AND organizer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $event_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
    $stmt->close();
} else {
    header("Location: organizer_dashboard.php");
    exit();
}

// Handle event update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_event'])) {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_location = $_POST['event_location'];
    $event_description = $_POST['event_description'];

    // Handle file upload
    $image_path = $event['image_path'];
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $uploads_dir = '../uploads/';
        $image_path = $uploads_dir . basename($_FILES['event_image']['name']);
        if (!file_exists($uploads_dir)) {
            mkdir($uploads_dir, 0777, true); // Create directory if it doesn't exist
        }
        if (!move_uploaded_file($_FILES['event_image']['tmp_name'], $image_path)) {
            $image_path = $event['image_path']; // Revert to old image path if upload fails
        }
    }

    // Update event details
    $sql = "UPDATE events SET name = ?, date = ?, location = ?, description = ?, image_path = ? WHERE id = ? AND organizer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $event_name, $event_date, $event_location, $event_description, $image_path, $event_id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Event updated successfully!";
    } else {
        $_SESSION['message'] = "Error updating event: " . $stmt->error;
        error_log("Error updating event: " . $stmt->error);
    }
    $stmt->close();

    header("Location: organizer_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<style>
    <style>
    /* edit_event.css */
/* ======================== */
/* General Styles           */
/* ======================== */
body {
    background-color: #f8f9fa;
    font-family: 'Poppins', sans-serif;
    color: #2c3e50;
}

.container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(106, 17, 203, 0.1);
}

/* ======================== */
/* Heading Styles           */
/* ======================== */
h1 {
    color: #6a11cb;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 2rem;
    text-align: center;
}

/* ======================== */
/* Form Styles              */
/* ======================== */
.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    color: #6a11cb;
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: block;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #6a11cb;
    box-shadow: 0 0 8px rgba(106, 17, 203, 0.2);
}

/* ======================== */
/* File Upload Styles       */
/* ======================== */
.custom-file-input {
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.custom-file-input input[type="file"] {
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
    cursor: pointer;
}

.file-upload-label {
    display: block;
    padding: 12px;
    border: 2px dashed #e9ecef;
    border-radius: 10px;
    text-align: center;
    color: #6c757d;
    transition: all 0.3s ease;
}

.file-upload-label:hover {
    border-color: #6a11cb;
    background: #f3e5ff;
}

/* ======================== */
/* Button Styles            */
/* ======================== */
.btn-primary {
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    border: none;
    padding: 12px 30px;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    width: 100%;
    margin-top: 1rem;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(106, 17, 203, 0.2);
}

.btn-secondary {
    border: 2px solid #6a11cb;
    color: #6a11cb;
    padding: 10px 25px;
    border-radius: 50px;
    transition: all 0.3s ease;
    width: 100%;
}

.btn-secondary:hover {
    background: #6a11cb;
    color: white;
}

/* ======================== */
/* Responsive Design        */
/* ======================== */
@media (max-width: 768px) {
    .container {
        margin: 1rem;
        padding: 1.5rem;
    }
    
    h1 {
        font-size: 2rem;
    }
    
    .form-control {
        padding: 10px 12px;
    }
}
</style>
</style>
</head>
<body>
    <div class="container">
        <h1>Edit Event</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="event_name">Event Name</label>
                <input type="text" name="event_name" id="event_name" class="form-control" value="<?php echo htmlspecialchars($event['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="event_date">Event Date</label>
                <input type="date" name="event_date" id="event_date" class="form-control" value="<?php echo $event['date']; ?>" required>
            </div>
            <div class="form-group">
                <label for="event_location">Event Location</label>
                <input type="text" name="event_location" id="event_location" class="form-control" value="<?php echo htmlspecialchars($event['location']); ?>" required>
            </div>
            <div class="form-group">
                <label for="event_description">Event Description</label>
                <textarea name="event_description" id="event_description" class="form-control" required><?php echo htmlspecialchars($event['description']); ?></textarea>
            </div>
            <div class="form-group"> 
                <label for="event_capacity">Event Capacity</label> 
                <input type="number" name="event_capacity" id="event_capacity" class="form-control" value="<?php echo $event['capacity']; ?>" required> </div>
            <div class="form-group">
                <label for="event_image">Event Image</label>
                <input type="file" name="event_image" id="event_image" class="form-control">
            </div>
            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
            <button type="submit" name="update_event" class="btn btn-primary">Update Event</button>
         

        </form>
        <a href="organizer_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>
</body>
</html>
