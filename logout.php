<?php
session_start();
session_destroy();
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/svpm/';
  header("Location: $base_url/authentication/");
?>