<?php
session_start();
require_once 'db_connect.php';

$registration_success = false;
$registration_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $emailCheckSql = "SELECT * FROM organizers WHERE email = ?";
    $emailCheckStmt = $conn->prepare($emailCheckSql);
    $emailCheckStmt->bind_param("s", $email);
    $emailCheckStmt->execute();
    $emailCheckResult = $emailCheckStmt->get_result();

    if ($emailCheckResult->num_rows > 0) {

        $registration_message = 'Error: Email already exists. Please use a different email address.';
    } else {
        $sql = "INSERT INTO organizers (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            $registration_success = true;
            $registration_message = 'Registration successful! You can now log in.';
        } else {
            $registration_message = 'Error: ' . $stmt->error;
        }
        $stmt->close();
    }
    $emailCheckStmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['login_email']);
    $password = $_POST['login_password'];

    $sql = "SELECT * FROM organizers WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = 'organizer';
            echo "<script>alert('Login successful!'); window.location.href = 'organizer_dashboard.php';</script>";
            exit;
        } else {
            echo "<script>alert('Invalid password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('No user found with this email. Please register first.');</script>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Registration/Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
.col-lg-10 {
    background: linear-gradient(135deg,rgb(196, 69, 152),rgb(203, 116, 178),rgb(227, 166, 246)) !important;
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
                        <input type="radio" id="role-organizer" name="role" value="organizer" checked>
                        <label for="role-organizer" class="toggle-option active">Organizer</label>

                        <input type="radio" id="role-attendee" name="role" value="attendee">
                        <label for="role-attendee" class="toggle-option">Attendee</label>
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
                        <h1>Welcome to Event Horizon ! <br>Organizers</h1>
                        <p>Manage your events easily with us.</p>
                        <h4>Quote of the day</h4><br>
                        <p>Creativity is allowing yourself to make mistakes.<br>Art is knowing which ones to keep.</p>
                        <button id="show-register" class="btn btn-outline-success">Register Now</button>
                        <span>
                            <button class="btn btn-danger" onclick="window.location.href='http://localhost/event_management_system/backend/organizer_form.php'">Sign in</button>
                        </span>
                        <p><a href="http://localhost/Event_management_system/backend/attendee_form.php">R U Attendee? 🤮</a></p>
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
                                    <button type="submit" name="login" class="btn btn-danger">Login</button>
                                </div>
                                <div class="extra-links text-center mt-3">
                                    <a href="forgot_password.php">Forgot Password?</a> |
                                    <a href="http://localhost/event_management_system/">Home</a> |
                                    <a href="http://localhost/event_management_system/backend/attendee_form.php">R U Attendee? </a>
                                </div>
                            </form>
                        </div>

                        <div id="register-container" class="register-side d-none">
                            <h2 class="text-center">Create Account</h2>
                            <?php if ($registration_message): ?>
                                <p class="<?php echo $registration_success ? 'success-message' : 'error-message'; ?>">
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
                                    <button type="submit" name="register" class="btn btn-success">Register</button>
                                </div>
                                <div class="extra-links text-center mt-3">
                                    <a href="forgot_password.php">Forgot Password?</a> |
                                    <a href="http://localhost/event_management_system/">Home</a> |
                                    <a href="http://localhost/event_management_system/backend/attendee_form.php">R U Attendee? </a>
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

</body>
</html>

