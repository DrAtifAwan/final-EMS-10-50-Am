<style>
  /* Custom Navbar Styles */
  .navbar-custom {
    background-color:rgb(125, 32, 148); /* Dark purple */
    box-shadow: 0 2px 5px rgba(209, 13, 227, 0.3);
  }
  .navbar-custom .navbar-brand {
    color: #ffffff !important;
    font-size: 1.8rem;
    font-weight: bold;
  }
  .navbar-custom .navbar-nav .nav-link {
    color: #ddd !important;
    font-size: 1rem;
    margin-left: 10px;
  }
  .navbar-custom .navbar-nav .nav-link:hover {
    color: #ffffff !important;
  }
  .navbar-custom .btn-danger {
    background-color: #d9534f;
    border-color: #d43f3a;
  }
  .navbar-custom .btn-danger:hover {
    background-color: #c9302c;
    border-color: #ac2925;
  }
  .navbar-custom .btn-secondary {
    background-color: #5bc0de;
    border-color: #46b8da;
  }
  .navbar-custom .btn-secondary:hover {
    background-color: #31b0d5;
    border-color: #269abc;
  }
</style>

<!-- In your HTML head section -->
<link rel="stylesheet" href="navbar.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Navbar HTML -->
<nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="organizer_dashboard.php">Welcome to Event Horizon </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="../frontend/profile.html">About Me</a></li>
        <li class="nav-item"><a class="nav-link" href="organizer_dashboard.php">Top</a></li>
        <li class="nav-item"><a class="nav-link" href="contact_us.php">Contact Us</a></li>
        <li class="nav-item">
          <a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a>
        </li>
        <li class="nav-item">
          <button onclick="window.location.href='all_events.php'" class="btn btn-secondary">All Events</button>
        </li>
      </ul>
    </div>
  </div>
</nav>
