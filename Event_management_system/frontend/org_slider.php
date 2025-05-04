<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="path/to/your/custom.css">
    <style>
        .side-nav-toggle {
            cursor: pointer;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
        }
        .side-nav {
            height: 100%;
            width: 0;
            position: fixed;
            left: 0;
            top: 0;
            background-color: white;
            overflow-x: hidden;
            transition: 0.5s;
            box-shadow: 3px 0 5px rgba(0,0,0,0.2);
        }
        .side-nav a {
            padding: 8px 8px 8px 16px;
            text-decoration: none;
            font-size: 18px;
            color: black;
            display: block;
            transition: 0.3s;
        }
        .side-nav a:hover {
            color: #007bff;
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 25px;
            font-size: 36px;
        }
    </style>
</head>
<body>
    <div id="side-navigation" class="side-nav">
        <a href="javascript:void(0)" class="close-btn" onclick="closeNav()">&times;</a>
        <a href="all_events.php">All Events</a>
        <a href="notification.php">Show Notifications</a>
        <a href="notification.php">Send Notifications</a>
        <a href="organizer_dashboard.php#search">Search Events</a>
        <a href="organizer_dashboard.php#events-list">Show Events</a>
        <a href="logout.php">Logout</a>
    </div>
    <script>
        function openNav() {
            document.getElementById("side-navigation").style.width = "250px";
        }
        function closeNav() {
            document.getElementById("side-navigation").style.width = "0";
        }
    </script>
</body>
</html>
