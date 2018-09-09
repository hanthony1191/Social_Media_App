<?php
  //Initiate session, Connect to DB, Include Header, Include functions
  session_start();
  include 'Connect.php';
  include 'Header.php';
  include 'Functions.php';
?>
    <h1>Please Sign In Below</h1>
    <?php
          //If there are no errors check if user credentials are valid
          if (isset($_POST["signin"]) && empty($error) && empty($missing)) {
            $query = "SELECT user_id, user_first, user_last FROM final_user WHERE user_email = '" . htmlspecialchars($_POST['user_email']) . "' AND user_password = '" . sha1($_POST['user_password']) . "'";

            $result = mysqli_query($connect, $query);
            if (!$result) {
              echo 'Error signing in. Please try again: ';
              echo '<a href="SignIn.php">Sign In</a>.';
              echo 'Or, create an <a href="Login.php">Account</a>.';
            }
            //Error message for invalid credentials and redisplay form
            else {
              if ($result->num_rows == 0) {
                echo 'Wrong email/password combination. Please try again.';
                echo '<form action="" method="post">';
                      if (in_array("user_email", $missing)) :
                echo  '<p><b>Please include your email</b></p>';
                      endif;
                      if ($_POST && $user_email) {
                        echo  'Email: <input type="text" name="user_email" value="' . htmlentities($user_email) . '">';
                      }
                      else {
                        echo  'Email: <input type="text" name="user_email">';
                      }
                       if (isset($error["user_email"]) && !in_array("user_email", $missing)) {
                         echo $error["user_email"];
                       }
                       if (in_array("user_password", $missing)) :
                echo   '<p><b>Please include your password</b></p>';
                       endif;
                echo  'Password: <input type="password" name="user_password">
                      <input type="submit" name= "signin" value="Sign In" />
                      </form>';
              }
              //If credentials are valid, initialize SESSION variables and link to main
              else {
                $_SESSION['signin'] = true;
                while($row = mysqli_fetch_assoc($result)) {
                  $_SESSION['user_id']    = $row['user_id'];
                  $_SESSION['user_first']  = $row['user_first'];
                  $_SESSION['user_last']  = $row['user_last'];
                }
                // echo 'Welcome, ' . $_SESSION['user_first'] . '. <a href="Home.php">Home Page</a>.';

                header("Location: Home.php");
              }
            }
          }
          //If POST is not set, display form - displays errors using function include
          else {
            echo '<form action="" method="post">';
                  if (in_array("user_email", $missing)) :
            echo  '<p><b>Please include your email</b></p>';
                  endif;
                  if ($_POST && $user_email) {
                    echo  'Email: <input type="text" name="user_email" value="' . htmlentities($user_email) . '">';
                  }
                  else {
                    echo  'Email: <input type="text" name="user_email">';
                  }
                   if (isset($error["user_email"]) && !in_array("user_email", $missing)) {
                     echo $error["user_email"];
                   }
                   if (in_array("user_password", $missing)) :
            echo   '<p><b>Please include your password</b></p>';
                   endif;
            echo  'Password: <input type="password" name="user_password">
                  <input type="submit" name= "signin" value="Sign In" />
                  </form>';
          }
      include 'Footer.php';
    ?>
  </body>
</html>
