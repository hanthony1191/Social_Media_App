<?php
  //Start session to make sure same session
  session_start();
  include 'Connect.php';
  include 'Header.php';
  //Delete session variables
  session_destroy();
  //Link back to sign in page
  header('location:Login.php');
  include 'Footer.php';
?>
