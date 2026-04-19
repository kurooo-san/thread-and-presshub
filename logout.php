<?php
require 'includes/config.php';

// Destroy all session data
session_destroy();

header("Location: index.php");
exit();
?>
