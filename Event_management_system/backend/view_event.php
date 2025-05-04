<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array($_SESSION['role'], ['organizer', 'attendee'])) {
    header("Location: login.php");
    exit();
}

include_once 'db_connect.php';

// Initialize variables
$event = [];
$error = '';

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_id'])) {
        $event_id = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);
        
        if ($event_id) {
            $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
            $stmt->bind_param("i", $event_id);
            
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $event = $result->fetch_assoc();
            }
            $stmt->close();
        }
    }
    
    if (empty($event)) {
        header("Location: ".($_SESSION['role'] === 'organizer' ? 'organizer_dashboard.php' : 'attendee_dashboard.php'));
        exit();
    }
} catch (Exception $e) {
    $error = "Error loading event details: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($event['name'] ?? 'Event Details'); ?> - Eventify</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #6a11cb;
      --secondary-color: #2575fc;
      --light-purple: #f3e5ff;
      --dark-text: #2c3e50;
    }

    body {
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      min-height: 100vh;
      font-family: 'Poppins', sans-serif;
      color: var(--dark-text);
    }

    .event-view-container {
      max-width: 800px;
      margin: 2rem auto;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(106, 17, 203, 0.1);
      border: none;
      background: white;
    }

    .card-img-top {
      height: 400px;
      object-fit: cover;
      width: 100%;
      border-bottom: 3px solid var(--primary-color);
    }

    .card-body {
      padding: 2.5rem;
      position: relative;
    }

    .card-title {
      color: var(--primary-color);
      font-size: 2.2rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
    }

    .event-detail {
      font-size: 1.1rem;
      margin-bottom: 1.2rem;
      line-height: 1.6;
      padding-left: 1.5rem;
      position: relative;
    }

    .event-detail::before {
      content: "•";
      color: var(--primary-color);
      position: absolute;
      left: 0;
      font-size: 1.5rem;
      line-height: 1;
    }

    .btn-back {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      padding: 12px 30px;
      border-radius: 50px;
      font-weight: 600;
      margin-top: 2rem;
      transition: all 0.3s ease;
      color: white;
      display: inline-flex;
      align-items: center;
    }

    .btn-back:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(106, 17, 203, 0.2);
      color: white;
    }

    .error-alert {
      max-width: 800px;
      margin: 2rem auto;
    }

    @media (max-width: 768px) {
      .event-view-container {
        margin: 1rem;
        border-radius: 10px;
      }

      .card-img-top {
        height: 250px;
      }

      .card-body {
        padding: 1.5rem;
      }

      .card-title {
        font-size: 1.8rem;
      }
    }
  </style>
</head>
<body>
  
  <div class="container">
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger error-alert">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php else: ?>
      <div class="event-view-container">
        <?php if (!empty($event['image_path'])): ?>
          <img src="<?php echo htmlspecialchars($event['image_path']); ?>" class="card-img-top" alt="Event banner">
        <?php else: ?>
          <div class="card-img-top bg-light d-flex align-items-center justify-content-center">
            <i class="fas fa-calendar-alt fa-5x text-muted"></i>
          </div>
        <?php endif; ?>

        <div class="card-body">
          <h1 class="card-title"><?php echo htmlspecialchars($event['name']); ?></h1>
          
          <div class="event-detail">
            <strong>Date:</strong> 
            <?php echo date('F j, Y', strtotime($event['date'])); ?>
          </div>

          <div class="event-detail">
            <strong>Location:</strong> 
            <?php echo htmlspecialchars($event['location']); ?>
          </div>

          <?php if (!empty($event['capacity'])): ?>
            <div class="event-detail">
              <strong>Capacity:</strong> 
              <?php echo number_format($event['capacity']); ?> seats
            </div>
          <?php endif; ?>

          <div class="event-detail">
            <strong>Description:</strong><br>
            <?php echo nl2br(htmlspecialchars($event['description'])); ?>
          </div>

          <a href="<?php echo htmlspecialchars($_SESSION['role'] === 'organizer' ? 'organizer_dashboard.php' : 'attendee_dashboard.php'); ?>" 
             class="btn btn-back">
            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
          </a>
        </div>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>