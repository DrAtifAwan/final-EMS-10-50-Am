<!-- footer.php -->
<footer class="organizer-footer">
  <div class="footer-content">
    <div class="container">
      <div class="footer-top row g-4">
        <!-- Logo Column -->
        <div class="col-md-4 text-center text-md-start">
          <img src="../assets/images/ems.jpg" alt="EMS Logo" class="footer-logo mb-3">
          <p class="footer-text">Empowering Event Professionals</p>
          <div class="social-links">
            <a href="#" class="social-icon"><i class="fab fa-facebook"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-linkedin"></i></a>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="col-md-4">
          <h5 class="footer-heading">Organizer Tools</h5>
          <ul class="footer-menu">
            <li><a href="organizer_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="create_event.php"><i class="fas fa-calendar-plus"></i> New Event</a></li>
            <li><a href="manage_events.php"><i class="fas fa-tasks"></i> Manage Events</a></li>
            <li><a href="reports.php"><i class="fas fa-chart-pie"></i> Analytics</a></li>
          </ul>
        </div>

        <!-- Support Section -->
        <div class="col-md-4">
          <h5 class="footer-heading">Support Center</h5>
          <div class="support-info">
            <p><i class="fas fa-phone"></i> +92 300 1234567</p>
            <p><i class="fas fa-envelope"></i> support@eventify.com</p>
            <p><i class="fas fa-map-marker-alt"></i> Karachi, Pakistan</p>
          </div>
          <div class="footer-actions mt-3">
            <a href="contact_us.php" class="btn btn-outline-light btn-sm"><i class="fas fa-headset"></i> Help Desk</a>
            <a href="logout.php" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a>
          </div>
        </div>
      </div>

      <hr class="footer-divider">

      <!-- Footer Bottom -->
      <div class="footer-bottom row">
          <div class="col-md-6">
              <p class="copyright">
                  &copy; 2024 Eventify. All rights reserved.<br>
                  <small>v2.1.5 | Build 1245</small>
              </p>
          </div>
          <div class="col-md-6 text-md-end">
              <div class="legal-links">
                  <a href="privacy.php">Privacy Policy</a> | 
                  <a href="terms.php">Terms of Service</a> | 
                  <a href="cookies.php">Cookie Settings</a>
              </div>
          </div>
      </div>
    </div>
  </div>
</footer>


<!-- Updated Footer Styling: Footer appears at the bottom of the page, not fixed -->
<style>
.organizer-footer {
    background: linear-gradient(135deg, #1a2a3a, #2c3e50);
    color: #ecf0f1;
    padding: 1.5rem 0;
    border-top: 1px solid rgba(255,255,255,0.1);
    /* Removed fixed positioning so footer is relative and will appear after all content */
    position: relative;
    z-index: 1000;
    box-shadow: 0 -4px 15px rgba(0,0,0,0.1);
}

/* (Optional) Adjust main content container to prevent overlapping if needed */
.content-area {
    padding-bottom: 20px;
}
</style>
