<!-- Updated Sidebar with Enhanced Styling -->
<div class="sidebar">
  <!-- Sidebar Header with Logo -->
  <div class="sidebar-header text-center py-4">
    <h4 class="text-white mb-0">
      <i class="fas fa-calendar-star me-2"></i> Event Organizer
    </h4>
  </div>

  <!-- Navigation Menu -->
  <ul class="nav flex-column px-3">
    <li class="nav-item">
      <a class="nav-link" href="organizer_dashboard.php">
        <i class="fas fa-home me-2"></i> Dashboard Home
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="create_event.php">
        <i class="fas fa-plus-circle me-2"></i> Create Event
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="../organizer/manage_events.php">
        <i class="fas fa-tasks me-2"></i> Manage Events
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="rsvp_details.php">
        <i class="fas fa-clipboard-list me-2"></i> RSVP Details
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="notification.php">
        <i class="fas fa-bell me-2"></i> Send Notifications
        <span class="badge bg-danger rounded-pill ms-auto">3</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="reports.php">
        <i class="fas fa-chart-pie me-2"></i> Reports
      </a>
    </li>
    <li class="nav-item mt-3">
      <a class="nav-link" href="settings.php">
        <i class="fas fa-cog me-2"></i> Settings
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-warning" href="../logout.php">
        <i class="fas fa-sign-out-alt me-2"></i> Logout
      </a>
    </li>
  </ul>

  <!-- Current User Profile -->
  <div class="sidebar-footer px-3 py-3 text-center">
    <div class="user-avatar mb-2">
      <i class="fas fa-user-circle fa-3x text-white-50"></i>
    </div>
    <p class="text-white mb-1">Welcome, <strong>Organizer</strong></p>
    <small class="text-white-50">Last login: Today</small>
  </div>
</div>