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

    if (isset($_POST["remove"])) {
      $delete = "DELETE FROM final_relationship
              WHERE first_id = " . $_SESSION['user_id'] . " AND second_id = " . $_POST['user_id'];

      $delete1 = "DELETE FROM final_relationship
              WHERE first_id = " . $_POST['user_id'] . " AND second_id = " . $_SESSION['user_id'];

      $delete_result = mysqli_query($connect, $delete);
      $delete1_result = mysqli_query($connect, $delete1);

      if(!$delete_result) {
          if(!$delete1_result) {
            //something went wrong, display the error
            echo 'Something went wrong. Please try again later.';
          }
          else {
              echo 'Successfully deleted.';
          }
      }
      else {
          echo 'Successfully deleted.';
      }
    }

    if (isset($_POST["add"])) {
      $insert = "INSERT INTO final_relationship(first_id, second_id, type_id)
                VALUES(" . $_SESSION['user_id'] . ", " . $_POST['user_id'] . ", 3)";

      $insert_result = mysqli_query($connect, $insert);
      if(!$insert_result) {
          //something went wrong, display the error
          echo 'Something went wrong. Please try again later.';
      }
      else {
          echo 'Successfully added.';
      }
    }

    $query = "SELECT user_id, user_first, user_last
              FROM final_user
              WHERE user_id <>" . $_SESSION['user_id'];

    $result1 = mysqli_query($connect, $query);

    $check = "SELECT user_id FROM final_user
              WHERE user_id IN (SELECT r.first_id
				                        FROM final_user p, final_relationship r
                                WHERE r.second_id = ".$_SESSION['user_id'].")
              OR user_id IN (SELECT r.second_id
				                      FROM final_user p, final_relationship r
                              WHERE r.first_id = ".$_SESSION['user_id'].")";

    $check_result = mysqli_query($connect, $check);
    $friend_list = mysqli_fetch_assoc($check_result);

    if ($result1->num_rows > 0) {
      echo '<table>
            <tr>
              <th>Friends</th>
            </tr>';
      //each category name links to Category view which changes based on category ID
      while($row = mysqli_fetch_assoc($result1)) {
              echo '<tr>';
                  echo '<td><a href="Page.php?user_id=' . $row['user_id'] . '">'. $row['user_first'] . ' ' . $row['user_last'] . '</a></td>';
                  echo '<td>';
                      if (in_array($row['user_id'], $friend_list)) {
                        echo '<form action="" method="post">';
                        echo '<input type="hidden" name="user_id" value="' . $row['user_id'] . '"/>';
                        echo '<input type="submit" name="remove" value="Remove Friend" />
                        </form>';
                      }
                      else {
                        echo '<form action="" method="post">';
                        echo '<input type="hidden" name="user_id" value="' . $row['user_id'] . '"/>';
                        echo '<input type="submit" name="add" value="Add Friend" />
                        </form>';
                      }
                  echo '</td>';
              echo '</tr>';
      }
      echo '</table>';
    }
  }
  include 'Footer.php';
?>
