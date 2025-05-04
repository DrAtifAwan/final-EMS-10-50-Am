<?php
session_start();
session_destroy();
header("Location: organizer_form.php");
exit();
