<?php
session_start();
include_once __DIR__ . '/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user information based on role
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($role === 'organizer') {
    $sql = "SELECT * FROM organizers WHERE id = ?";
} elseif ($role === 'attendee') {
    $sql = "SELECT * FROM attendees WHERE id = ?";
} else {
    die("Invalid role.");
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<?php @include("includes/head.php");?>
<body>
<div class="container-scroller">
    <?php @include("includes/header.php");?>
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel"><br>
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-md-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Profile</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name'] ?? ''); ?></p>
                                        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
                                        <p><strong>Role:</strong> <?php echo htmlspecialchars($role); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Your Events</h4>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Event Name</th>
                                            <th>Date</th>
                                            <th>Location</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        // Fetch events based on user role
                                        if ($role === 'organizer') {
                                            $sql = "SELECT * FROM events WHERE organizer_id = ?";
                                        } else {
                                            $sql = "SELECT e.name, e.date, e.location 
                                                    FROM events e
                                                    JOIN rsvps r ON e.id = r.event_id
                                                    WHERE r.user_id = ?";
                                        }

                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("i", $user_id);
                                        $stmt->execute();
                                        $events = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                                        $stmt->close();

                                        foreach ($events as $event) {
                                            echo '<tr>';
                                            echo '<td>' . htmlspecialchars($event['name']) . '</td>';
                                            echo '<td>' . htmlspecialchars($event['date']) . '</td>';
                                            echo '<td>' . htmlspecialchars($event['location']) . '</td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">About Us</h4>
                                <p>University Students stumbling onto new ambitions</p>
                                <p>Virtual University of Pakistan</p>
                                <p>Star My EMS project <a href="https://github.com/Dr_Atif_awan/">github.com/Dr_Atif_awan/</a> VU Pakistan</p>
                                <p>General Support BC 230213553 Muhammad Atif</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <?php @include("includes/footer.php");?>
        </div>
    </div>
</div>
<?php @include("includes/foot.php");?>
</body>
</html>
