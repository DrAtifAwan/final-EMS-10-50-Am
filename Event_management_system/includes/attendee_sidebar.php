<!-- includes/attendee_sidebar.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Attendee Sidebar</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    /* Sidebar Styling */
    .sidebar-attendee {
      width: 280px;
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      z-index: 1000;
      background-color: #6a1b9a; /* Purple background */
      color: #fff;
      padding: 1rem;
    }
    .sidebar-attendee .sidebar-header {
      text-align: center;
      margin-bottom: 1.5rem;
    }
    .sidebar-attendee .sidebar-header h4 {
      margin-bottom: 0.5rem;
      font-weight: bold;
    }
    .sidebar-attendee hr {
      border-top: 1px solid #fff;
      margin: 0.5rem 0;
    }
    .sidebar-attendee ul.nav {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .sidebar-attendee ul.nav li.nav-item {
      margin-bottom: 0.75rem;
    }
    .sidebar-attendee ul.nav li.nav-item a.nav-link {
      color: #fff;
      text-decoration: none;
      display: block;
      padding: 0.5rem 1rem;
      transition: background-color 0.3s ease;
    }
    .sidebar-attendee ul.nav li.nav-item a.nav-link:hover,
    .sidebar-attendee ul.nav li.nav-item a.nav-link.active {
      background: rgba(255, 255, 255, 0.2);
    }
    .sidebar-attendee .btn-light-purple {
      display: block;
      width: 100%;
      text-align: center;
      background-color: #b5838d; /* Light purple */
      color: #fff;
      border: none;
      margin-top: 1rem;
      padding: 0.75rem;
      text-decoration: none;
      border-radius: 30px;
    }
  </style>
</head>
<!-- includes/attendee_sidebar.php -->
<div class="sidebar-attendee bg-purple text-white p-3" style="width: 280px; height: 100vh; position: fixed; left: 0; top: 0; z-index: 1000;">
    <div class="sidebar-header text-center mb-4">
        <h4>Attendee Panel</h4>
        <hr class="border-white">
    </div>
    
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link text-white" href="my_events.php">
                <i class="bi bi-calendar-check me-2"></i> My Events
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="upcoming_events.php">
                <i class="bi bi-search me-2"></i> Browse Events
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="notifications.php">
                <i class="bi bi-bell me-2"></i> Notifications
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="profile.php">
                <i class="bi bi-person me-2"></i> Profile
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="settings.php">
                <i class="bi bi-gear me-2"></i> Settings
            </a>
        </li>
        <li class="nav-item mt-4">
            <a class="btn btn-light-purple w-100" href="logout.php">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
        </li>
    </ul>
</div>

</html>
