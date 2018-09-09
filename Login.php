<?php
  include "Connect.php";
  include "Header.php";
  include "Functions.php";

  echo "<h1>Welcome to Social Media App</h1>";
  echo "<h2>Sign Up Below</h2>";

  if (isset($_POST["signup"]) && empty($error) && empty($missing)) {
    $sql = "INSERT INTO
                final_user(user_email, user_password, user_first, user_last)
            VALUES('" . htmlspecialchars($_POST['user_email']) . "',
                   '" . sha1($_POST['user_password']) . "',
                   '" . htmlspecialchars($_POST['user_first']) . "',
                   '" . htmlspecialchars($_POST['user_last']) . "')";

        $result = mysqli_query($connect, $sql);
        if(!$result) {
            //something went wrong, display the error
            echo 'Something went wrong while registering. Please try again later.';
        }
        else {
            echo 'Successfully registered. You can now <a href="SignIn.php">sign in</a>';
        }
  }
  else {
    echo '<form action="" method="post">';
          if (in_array("user_first", $missing)) :
    echo  '<p><b>Please include your first name</b></p>';
          endif;
          if ($_POST && $user_first) {
            echo  'First Name: <input type="text" name="user_first" value="' . htmlentities($user_first) . '"><br>';
          }
          else {
            echo  'First Name: <input type="text" name="user_first"><br>';
          }
           if (isset($error["user_first"]) && !in_array("user_first", $missing)) {
             echo $error["user_first"];
           }
         if (in_array("user_last", $missing)) :
   echo  '<p><b>Please include your last name</b></p>';
         endif;
         if ($_POST && $user_last) {
           echo  'Last Name: <input type="text" name="user_last" value="' . htmlentities($user_last) . '"><br>';
         }
         else {
           echo  'Last Name: <input type="text" name="user_last"><br>';
         }
          if (isset($error["user_last"]) && !in_array("user_last", $missing))
          {
            echo $error["user_last"];
          }
          if (in_array("user_email", $missing)) :
    echo  '<p><b>Please include your email</b></p>';
          endif;
          if ($_POST && $user_email) {
            echo  'Email: <input type="text" name="user_email" value="' . htmlentities($user_email) . '"><br>';
          }
          else {
            echo  'Email: <input type="text" name="user_email"><br>';
          }
           if (isset($error["user_email"]) && !in_array("user_email", $missing)) {
             echo $error["user_email"];
           }
           if (in_array("user_password", $missing)) :
    echo   '<p><b>Please include your password</b></p>';
           endif;
    echo  'Password: <input type="password" name="user_password"><br>
          <input type="submit" name= "signup" value="Create Account" />
          </form>';

    echo "<p><b>Already Have an Account? </b><a href='SignIn.php'>Sign In</a></p>";
  }
  include "Footer.php";
?>
