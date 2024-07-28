<?php
if (session_status() == PHP_SESSION_ACTIVE) {
  session_start();
  session_destroy();
}
if (!headers_sent()) {
  header("Location: ./");
  exit();
} else {
  echo '<p>Redirect failed: headers already sent.</p>';
}
