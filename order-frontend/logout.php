<?php
session_start();

// Destroy session
session_destroy();

// Prevent browser from caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("Pragma: no-cache");

// Redirect to login or homepage
header("Location: index.html");
exit();
?>
