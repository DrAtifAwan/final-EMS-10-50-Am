<?php
include_once 'db_connect.php';

function sendNotification($event_id, $user_id, $message) {
    global $conn;

    // Insert notification into the database
    $sql = "INSERT INTO notifications (event_id, user_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $event_id, $user_id, $message);
    $stmt->execute();
    $stmt->close();

    // Send email notification (assuming mail configuration is done)
    $user_email = getUserEmail($user_id); // Function to get user's email by user_id
    mail($user_email, "Event Notification", $message);

    // Display the notification
    echo '
    <html>
    <head>
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap");
            * {
                font-family: "Poppins", sans-serif;
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
            .container {
                max-width: 1200px;
                margin: 20px auto;
                padding: 40px;
                background: rgba(255, 255, 255, 0.2);
                backdrop-filter: blur(10px);
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
                text-align: center;
                color: white;
            }
            .role-heading {
                text-align: center;
                font-size: 24px;
                font-weight: bold;
                text-transform: uppercase;
                margin-bottom: 20px;
            }
            .alert {
                padding: 12px 16px;
                border: 1px solid transparent;
                border-radius: 4px;
                font-size: 14px;
                margin-bottom: 20px;
            }
            .alert-success {
                background-color: #d4edda;
                border-color: #c3e6cb;
                color: #155724;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2 class="role-heading">Notification Sent</h2>
            <div class="alert alert-success">' . htmlspecialchars($message) . '</div>
        </div>
    </body>
    </html>';
}

function getUserEmail($user_id) {
    global $conn;

    $sql = "SELECT email FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    return $user['email'];
}

// Example usage:
sendNotification(1, 2, "Reminder: Your event is tomorrow!");
?>
