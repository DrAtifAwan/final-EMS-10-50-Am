<?php
session_start();
require_once 'db_connect.php';

$registration_success = false;
$registration_message = '';

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($password) {
    return strlen($password) >= 8; // Minimum 8 characters
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!validateEmail($email)) {
        $registration_message = 'Invalid email format. Please enter a valid email.';
    } elseif (!validatePassword($password)) {
        $registration_message = 'Password must be at least 8 characters long.';
    } else {
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);

        $emailCheckSql = "SELECT * FROM attendees WHERE email = ?";
        $emailCheckStmt = $conn->prepare($emailCheckSql);
        $emailCheckStmt->bind_param("s", $email);
        $emailCheckStmt->execute();
        $emailCheckResult = $emailCheckStmt->get_result();

        if ($emailCheckResult->num_rows > 0) {
            $registration_message = 'Error: Email already exists. Please use a different email address.';
        } else {
            $sql = "INSERT INTO attendees (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $password_hashed);

            if ($stmt->execute()) {
                $registration_success = true;
                $registration_message = 'Registration successful! You can now log in.';
            } else {
                // Log error instead of displaying it
                error_log('Database error: ' . $stmt->error);
                $registration_message = 'An error occurred. Please try again later.';
            }
            $stmt->close();
        }
        $emailCheckStmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['login_email']);
    $password = $_POST['login_password'];

    if (!validateEmail($email)) {
        echo "<script>alert('Invalid email format.');</script>";
    } else {
        $sql = "SELECT * FROM attendees WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Regenerate session ID for security
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = 'attendee';
                echo "<script>alert('Login successful!'); window.location.href = 'attendee_dashboard.php';</script>";
                exit;
            } else {
                echo "<script>alert('Invalid password. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('No user found with this email. Please register first.');</script>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendee Registration/Login</title>
    <link rel="stylesheet" href="assets/css/attendee_form.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
 /* Attendee Form Container – Using a Purple Three-Shade Gradient */
.col-lg-10 {
    background: linear-gradient(135deg, #6a1b9a,rgb(175, 111, 249),rgb(224, 153, 245)) !important;
    background-size: 300% 300%;
}

/* Body Background – Also a Three-Shade Purple Gradient */
body {
    background: linear-gradient(135deg,rgb(217, 125, 230),rgb(192, 129, 209), #ab47bc);
    background-size: 300% 300%;
    animation: gradientAnimation 10s ease infinite;
    color: #333;
    height: auto;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

@keyframes gradientAnimation {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

    
</style>

</head>
<body class="min-vh-100 d-flex justify-content-center align-items-center bg-light">

<div class="container-fluid">
    <div class="row justify-content-center w-100">
        <div class="col-lg-10 shadow-lg p-4 rounded bg-white">
            <div class="top-toggles">
                <div class="role-heading h4 text-center fw-bold text-success">Event Management System!</div>
                <div class="role-toggle text-center my-3">
                    <div class="toggle-container d-inline-block">
                        <input type="radio" id="role-organizer" name="role" value="organizer">
                        <label for="role-organizer" class="toggle-option">Organizer</label>

                        <input type="radio" id="role-attendee" name="role" value="attendee" checked>
                        <label for="role-attendee" class="toggle-option active">Attendee</label>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const organizerRadio = document.getElementById("role-organizer");
                    const attendeeRadio = document.getElementById("role-attendee");

                    function navigateToForm() {
                        if (organizerRadio.checked) {
                            window.location.href = "http://localhost/event_management_system/backend/organizer_form.php";
                        } else if (attendeeRadio.checked) {
                            window.location.href = "http://localhost/event_management_system/backend/attendee_form.php";
                        }
                    }
                    organizerRadio.addEventListener("click", navigateToForm);
                    attendeeRadio.addEventListener("click", navigateToForm);
                });
            </script>

            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <img src="../images/logo1.png" 
                             alt="Logo" 
                             onclick="window.location.href='index.php'" 
                             style="cursor:pointer; width: 150px; height: auto;">
                        <h1>Welcome to Event Horizon >Attendees</h1>
                        <p>Enjoy your events easily with us.</p>
                        <h4>Quote of the day</h4><br>
                        <p>Creativity is allowing yourself to make mistakes.<br>Art is knowing which ones to keep.</p>
                        <button id="show-register" class="btn btn-outline-primary">Register Now</button>
                        <span>
                            <button class="btn btn-success" onclick="window.location.href='http://localhost/event_management_system/backend/attendee_form.php'">Sign in</button>
                        </span>
                        <p><a href="http://localhost/event_management_system/backend/organizer_form.php">R U Organizer? 🧐</a></p>
                    </div>
                    <div class="col-md-6">
                        <div id="login-container" class="right-side active">
                            <h2 class="text-center">Sign In</h2>
                            <form method="POST">
                                <div class="form-group mb-3">
                                    <input type="email" name="login_email" class="form-control" placeholder="Enter your email" required>
                                </div>
                                <div class="form-group mb-3">
                                    <input type="password" name="login_password" class="form-control" placeholder="Enter your password" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" name="login" class="btn btn-success">Login</button>
                                </div>
                                <div class="extra-links text-center mt-3">
                                    <a href="forgot_password.php">Forgot Password?</a> |
                                    <a href="http://localhost/event_management_system/">Home</a> |
                                    <a href="http://localhost/event_management_system/backend/organizer_form.php">R U Organizer? 🧐</a>
                                </div>
                            </form>
                        </div>

                        <div id="register-container" class="register-side d-none">
                            <h2 class="text-center">Create Account</h2>
                            <?php if ($registration_message): ?>
                                <p class="<?php echo $registration_success ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo $registration_message; ?>
                                </p>
                            <?php endif; ?>
                            <form method="POST">
                                <div class="form-group mb-3">
                                    <input type="text" name="name" class="form-control" placeholder="Enter Your Name" required>
                                </div>
                                <div class="form-group mb-3">
                                    <input type="email" name="email" class="form-control" placeholder="Enter Your Email" required>
                                </div>
                                <div class="form-group mb-3">
                                    <input type="password" name="password" class="form-control" placeholder="Create a Password" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" name="register" class="btn btn-primary">Register</button>
                                </div>
                                <div class="extra-links text-center mt-3">
                                    <a href="forgot_password.php">Forgot Password?</a> |
                                    <a href="http://localhost/event_management_system/">Home</a> |
                                    <a href="http://localhost/event_management_system/backend/organizer_form.php">R U Organizer? 🧐</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                const showRegister = document.getElementById("show-register");
                const loginContainer = document.getElementById("login-container");
                const registerContainer = document.getElementById("register-container");

                showRegister.addEventListener("click", () => {
                    loginContainer.classList.add("d-none");
                    registerContainer.classList.remove("d-none");
                });
            </script>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

