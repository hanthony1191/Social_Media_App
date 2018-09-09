<?php
  //Initiate session, Connect to DB, Include Header
  session_start();
  include 'Connect.php';
  include 'Header.php';
  include 'Functions.php';

  if($_SESSION['signin'] != true) {
      header("Location: SignIn.php");
  }
  //If signed in, form is displayed with validation using functions include
  else {
    echo '<h1>Social Media App</h1>
    <header>
      <nav>
        <ul>
          <li><a href="Home.php" title="Homepage">Home</a></li>
          <li><a href="Profile.php" title="Profile">Profile</a></li>
          <li><a href="Friends.php" title="Friends">Friends</a></li>
          <li><a href="Directory.php" title="Directory">Directory</a></li>
          <li><a href="SignOut.php" title="Sign Out">Sign Out</a></li>
        </ul>
      </nav>
    </header>';
    echo '<main>';
    echo '<section>';
        if (isset($_POST["delete"])) {
          $relationships = "DELETE FROM final_relationship
                            WHERE first_id = " . $_SESSION['user_id'] .
                            "OR second_id = " . $_SESSION['user_id'];

          $relationships_result = mysqli_query($connect, $relationships);

          $account = "DELETE FROM final_user
                        WHERE user_id = " . $_SESSION['user_id'];

          $account_result = mysqli_query($connect, $account);

          if(!$account_result) {
              echo 'Something went wrong. Please try again later.';
          }
          else {
              header("location: SignIn.php");
          }
        }

        echo '<h2>Delete Account</h2><br>';
        echo '<p>We are sorry to see you go!<br></p>';
        echo '<p>We hope you change your mind but if you do choose to go:<br></p>';
        echo '<p>Keep in mind that this action is not reversible.<br></p>';
        echo '<p>We hope you come back soon!<br></p>';

        echo '<form action="" method="post">';
        echo '<input type="hidden" name="user_id" value="' . $_SESSION['user_id'] . '"/>';
        echo '<input type="submit" name="delete" value="Delete Account" />
        </form>';
    echo '</section>';
    echo '</main>';
  }
  include 'Footer.php';
?>
