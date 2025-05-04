<body>
<?php include '../includes/navbar.php'; ?>

  <div class="full-container">
    
    <div class="search-container my-5">
      <form method="GET" action="organizer_dashboard.php" class="form-inline">
        <div class="input-group search-input-group">
          <input type="text" name="search" class="form-control" placeholder="Search events" value="<?php echo htmlspecialchars($search_query); ?>">
          <div class="input-group-append">
            <button type="submit" class="btn btn-primary">Search</button>
          </div>
        </div>
      </form>
    </div>
    
    <!-- Hero Carousel -->
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
      <ol class="carousel-indicators">
        <li data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></li>
        <li data-bs-target="#heroCarousel" data-bs-slide-to="1"></li>
        <li data-bs-target="#heroCarousel" data-bs-slide-to="2"></li>
      </ol>
      <div class="carousel-inner">
        <div class="carousel-item active hero-slide" data-bg-image="../assets/images/img01.jpg">
          <div class="overlay"></div>
          <div class="carousel-caption d-none d-md-block">
            <a href="http://localhost/eventify/backend/create_event.php" class="btn create-event-btn">Create Event</a>
            <h1 class="display-4 text-white">Create Your Special Event</h1>
            <p class="lead text-white">Design unforgettable experiences with just a few clicks.</p>
          </div>
        </div>
        <div class="carousel-item hero-slide" data-bg-image="../assets/images/customer.webp">
          <div class="overlay"></div>
          <div class="carousel-caption d-none d-md-block">
            <a href="http://localhost/eventify/backend/create_event.php" class="btn create-event-btn">Create Event</a>
            <h1 class="display-4 text-white">Inspire Creativity</h1>
            <p class="lead text-white">Bring your ideas to life with our intuitive platform.</p>
          </div>
        </div>
        <div class="carousel-item hero-slide" data-bg-image="../assets/images/girl6.jpeg">
          <div class="overlay"></div>
          <div class="carousel-caption d-none d-md-block">
            <a href="http://localhost/eventify/backend/create_event.php" class="btn create-event-btn">Create Event</a>
            <h1 class="display-4 text-white">Join the Experience</h1>
            <p class="lead text-white">Craft memorable events starting now.</p>
          </div>
        </div>
      </div>
      <a class="carousel-control-prev" href="#heroCarousel" role="button" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </a>
      <a class="carousel-control-next" href="#heroCarousel" role="button" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </a>
    </div>
    
    <!-- Notifications Card -->
    <?php if ($success_message): ?>
      <div class="alert alert-success">
          <?php echo $success_message; ?>
      </div>
    <?php endif; ?>
    <div class="container main-content mt-4">
      <div class="row">
        <div class="col-md-6">
          <div class="card shadow-lg notifications-card">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
              <h5 class="mb-0">Notifications</h5>
              <button id="toggle-notifications" class="btn btn-light btn-sm">Show Notifications</button>
            </div>
            <div id="notifications-list" class="card-body hidden">
              <h6>Recent Notifications</h6>
              <ul class="list-group mb-3">
                <?php foreach ($notifications as $notification): ?>
                  <li class="list-group-item">
                    <?php echo $notification['message']; ?>
                    <span class="badge badge-primary"><?php echo $notification['created_at']; ?></span>
                    <form method="POST" action="delete_notification.php" class="d-inline">
                      <input type="hidden" name="notification_id" value="<?php echo $notification['id']; ?>">
                      <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </div>
          <div class="col-md-6">
              <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                  <h5 class="mb-0">Send Notifications</h5>
                  <button onclick="window.location.href='notification.php'" class="btn btn-info">Send Notifications</button>
              </div>
              <div class="card shadow-lg send-notification-card">
                  <div class="card-body send-notification-body"></div>
              </div>
          </div>
      </div>
  </div>
  <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
          <?php
          $chunks = array_chunk($events, 3);
          foreach ($chunks as $index => $event_chunk): ?>
              <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                  <div class="row">
                      <?php foreach ($event_chunk as $event): ?>
                          <div class="col-md-4">
                              <div class="card event-card mx-auto mb-3">
                                  <?php if (!empty($event['image_path'])): ?>
                                      <img src="<?php echo $event['image_path']; ?>" class="card-img-top" alt="Event Image">
                                  <?php else: ?>
                                      <img src="assets/images/default_event.jpg" class="card-img-top" alt="Default Image">
                                  <?php endif; ?>
                                  <div class="card-body">
                                      <h5 class="card-title"><?php echo htmlspecialchars($event['name']); ?></h5>
                                      <p class="card-text"><strong>Date:</strong> <?php echo htmlspecialchars($event['date']); ?></p>
                                      <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                                      <p class="card-text"><?php echo htmlspecialchars($event['description']); ?></p>
                                      <p class="card-text"><strong>Capacity:</strong> <?php echo htmlspecialchars($event['capacity']); ?></p>
                                      <p class="card-text"><strong>Remaining Spots:</strong>
                                          <?php
                                          $sql = "SELECT COUNT(*) AS rsvp_count FROM rsvps WHERE event_id = ?";
                                          $stmt = $conn->prepare($sql);
                                          $stmt->bind_param("i", $event['id']);
                                          $stmt->execute();
                                          $result = $stmt->get_result();
                                          $rsvp_count = $result->fetch_assoc()['rsvp_count'];
                                          $stmt->close();
                                          $remaining_spots = $event['capacity'] - $rsvp_count;
                                          echo htmlspecialchars($remaining_spots <= 0 ? "Sorry, No space left" : $remaining_spots);
                                          ?>
                                      </p>
                                  </div>
                              </div>
                          </div>
                      <?php endforeach; ?>
                  </div>
              </div>
          <?php endforeach; ?>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </button>
  </div>
 
  <div class="search-container my-5">
      <button id="toggle-events" class="btn btn-info">Show Events</button>
  </div>
  <div id="events-list" class="hidden" style="margin-top: 20px;">
    <div class="table-responsive" style="max-width: 95%; margin: auto;">
        <table class="table table-bordered table-hover table-striped w-100">
            <thead class="table-primary">
                <tr>
                    <th class="text-nowrap">Name</th>
                    <th class="text-nowrap">Date</th>
                    <th class="text-nowrap">Location</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th class="text-nowrap">Capacity</th>
                    <th class="text-nowrap">Remaining</th>
                    <th class="text-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                <tr>
                    <td class="align-middle"><?php echo htmlspecialchars($event['name']); ?></td>
                    <td class="align-middle text-nowrap"><?php echo htmlspecialchars($event['date']); ?></td>
                    <td class="align-middle"><?php echo htmlspecialchars($event['location']); ?></td>
                    <td class="align-middle text-truncate" style="max-width: 200px;"><?php echo htmlspecialchars($event['description']); ?></td>
                    <td class="align-middle">
                        <img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="Event Image" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                    </td>
                    <td class="align-middle"><?php echo htmlspecialchars($event['capacity']); ?></td>
                    <td class="align-middle">
                        <?php
                        $sql = "SELECT COUNT(*) AS rsvp_count FROM rsvps WHERE event_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $event['id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $rsvp_count = $result->fetch_assoc()['rsvp_count'];
                        $stmt->close();
                        $event_capacity = isset($event['capacity']) ? $event['capacity'] : 0;
                        $remaining_spots = $event_capacity - $rsvp_count;
                        ?>
                        <span class="badge <?php echo ($remaining_spots <= 0) ? 'bg-danger' : 'bg-success'; ?>">
                            <?php echo $remaining_spots; ?>
                        </span>
                    </td>
                    <td class="align-middle">
                    <div class="d-flex flex-wrap gap-2">
    <form method="POST" action="view_event.php" class="d-inline">
        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
        <button type="submit" name="view_event" class="btn btn-sm btn-info">
            <i class="bi bi-eye"></i> View
        </button>
    </form>
    <form method="POST" action="edit_event.php" class="d-inline">
        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
        <button type="submit" name="edit_event" class="btn btn-sm btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </button>
    </form>
    <form method="POST" action="delete_event.php" onsubmit="return confirm('Are you sure?');" class="d-inline">
        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
        <button type="submit" name="delete_event" class="btn btn-sm btn-danger">
            <i class="bi bi-trash"></i> Delete
        </button>
    </form>
    <form method="POST" action="rsvp_details.php" class="d-inline">
        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
        <button type="submit" name="rsvp_details" class="btn btn-sm btn-secondary">
            <i class="bi bi-info-circle"></i> RSVPs
        </button>
    </form>
</div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>