<?php
  //initialize error and missing arrays to store incorrect inputs
  $error = array();
  $missing = array();

  //Function to valid format of user string
  function field_format($value, $regex) {
    return preg_match($regex, $value) === 1;
  }

  //Loop through POST. Put empty fields in Missing
  if (isset($_POST["signup"])) {
    foreach ($_POST as $key => $value) {
      $value = trim($value);
      if (empty($value)) {
        $missing[] = $key;
        $$key = '';
      }
      //Create variables from POST keys
      else {
        $$key = $value;
      }
    }
    //Validate if first name input is long enough and alphabetic
    if (!field_format($user_first, "/^[a-z ,.'-]{2,30}$/i")) {
      $error["user_first"] = "PLEASE USE A VALID NAME BETWEEN 3-20 CHARACTERS</br>";
    }
    //Validate if last name input is long enough and alphabertic
    if (!field_format($user_last, "/^[a-z ,.'-]{2,30}$/i")) {
      $error["user_last"] = "PLEASE USE A VALID NAME BETWEEN 3-20 CHARACTERS</br>";
    }
    //Validate if email input is currect format
    if (!field_format($user_email, "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/")) {
      $error["user_email"] = "PLEASE USE A VALID EMAIL ADDRESS</br>";
    }
    //Validate if email input is unique
    $unique = "SELECT * FROM final_user WHERE user_email='".$user_email."'";
    $unique_result = mysqli_query($connect, $unique);
    $row = mysqli_num_rows($unique_result);
    if($row >= 1) {
        $error["user_email"] = "There Already Seems to be an Account with this Email Address</br>";
    }
  }

  if (isset($_POST["signin"])) {
    foreach ($_POST as $key => $value) {
      $value = trim($value);
      if (empty($value)) {
        $missing[] = $key;
        $$key = '';
      }
      //Create variables from POST keys
      else {
        $$key = $value;
      }
    }
    //Validate if email input is currect format
    if (!field_format($user_email, "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/")) {
      $error["user_email"] = "PLEASE USE A VALID EMAIL ADDRESS</br>";
    }
  }

  if (isset($_POST["editFirst"])) {
    foreach ($_POST as $key => $value) {
      $value = trim($value);
      if (empty($value)) {
        $missing[] = $key;
        $$key = '';
      }
      //Create variables from POST keys
      else {
        $$key = $value;
      }
    }
    //Validate if first name input is long enough and alphabetic
    if (!field_format($user_first, "/^[a-z ,.'-]{2,30}$/i")) {
      $error["user_first"] = "PLEASE USE A VALID NAME BETWEEN 3-20 CHARACTERS</br>";
    }
  }

  if (isset($_POST["editLast"])) {
    foreach ($_POST as $key => $value) {
      $value = trim($value);
      if (empty($value)) {
        $missing[] = $key;
        $$key = '';
      }
      //Create variables from POST keys
      else {
        $$key = $value;
      }
    }
    //Validate if last name input is long enough and alphabetic
    if (!field_format($user_last, "/^[a-z ,.'-]{2,30}$/i")) {
      $error["user_last"] = "PLEASE USE A VALID NAME BETWEEN 3-20 CHARACTERS</br>";
    }
  }

  if (isset($_POST["editEmail"])) {
    foreach ($_POST as $key => $value) {
      $value = trim($value);
      if (empty($value)) {
        $missing[] = $key;
        $$key = '';
      }
      //Create variables from POST keys
      else {
        $$key = $value;
      }
    }
    //Validate if email input is currect format
    if (!field_format($user_email, "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/")) {
      $error["user_email"] = "PLEASE USE A VALID EMAIL ADDRESS</br>";
    }
  }

  if (isset($_POST["editPass"])) {
    foreach ($_POST as $key => $value) {
      $value = trim($value);
      if (empty($value)) {
        $missing[] = $key;
        $$key = '';
      }
      //Create variables from POST keys
      else {
        $$key = $value;
      }
    }
  }

  if (isset($_POST["event"])) {
    foreach ($_POST as $key => $value) {
      $value = trim($value);
      if (empty($value)) {
        $missing[] = $key;
        $$key = '';
      }
      //Create variables from POST keys
      else {
        $$key = $value;
      }
    }
    //Validate if event name input is long enough and alphabetic
    if (!field_format($event_name, "/^[A-Za-z0-9\s\-_,\.\&;:()]{2,100}$/i")) {
      $error["event_name"] = "PLEASE USE A VALID TITLE BETWEEN 2-100 CHARACTERS</br>";
    }
    //Validate if event location input is long enough and alphabetic
    if (!field_format($event_location, "/^\w+(\s\w+\.?){2,}$/i")) {
      $error["event_location"] = "PLEASE USE A U.S. ADDRESS WITHOUT CITY/STATE/ZIP</br>";
    }
  }

  if (isset($_POST["create"])) {
    isset($_POST["friend"]) ? $_POST["friend"] : $_POST["friend"] = array();
    $friend = $_POST["friend"];

    foreach ($_POST as $key => $value) {
      if (!is_array($value)) {$value = trim($value);}
      if (empty($value)) {
        $missing[] = $key;
      }
    }

    foreach ($_POST as $key => $value) {
      if (is_array($value)) {
          foreach ($value as $key2 => $value2) {
            echo "friend: $value2<br>";
          }
        }
        else {
          echo "<br>$key: $value<br>";
        }
      }
    //Validate if group name input is long enough and alphabetic
    if (!field_format($group_name, "/^[A-Za-z0-9\s\-_,\.\&;:()]{2,100}$/i")) {
      $error["event_name"] = "PLEASE USE A VALID NAME BETWEEN 2-100 CHARACTERS</br>";
    }
  }
?>
