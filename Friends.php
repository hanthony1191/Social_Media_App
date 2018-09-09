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

    if (isset($_POST["destroy"])) {
      $delete_group = "DELETE FROM final_group
              WHERE group_id = " . $_POST['group_id'] . " AND user_id = " . $_SESSION['user_id'];

      $delete_result = mysqli_query($connect, $delete_group);

      if(!$delete_result) {
            echo 'Something went wrong. Please try again later.';
      }
      else {
          echo 'Successfully deleted.';
      }
    }

    $group = "SELECT group_id, group_name FROM final_group
              WHERE user_id = " . $_SESSION['user_id'];

    $group_result = mysqli_query($connect, $group);

    if ($group_result->num_rows > 0) {
      echo "<h3><a href='Create.php'>New Group</a></h3>";
      echo '<table>
            <tr>
              <th>Groups</th>
            </tr>';

      while($row1 = mysqli_fetch_assoc($group_result)) {
              echo '<tr>';
                  echo '<td><a href="Group.php?group_id=' . $row1['group_id'] . '">'. $row1['group_name'] . '</a></td>';
                  echo '<td>';
                      echo '<form action="" method="post">';
                      echo '<input type="hidden" name="group_id" value="' . $row1['group_id'] . '"/>';
                      echo '<input type="submit" name="destroy" value="Delete Group" />
                      </form>';
                  echo '</td>';
              echo '</tr>';
      }
      echo '</table>';
    }
    else {
      echo "<p>You don't seem to have any groups yet. Click <a href='Create.php'>HERE</a> to get started!</p>";
    }


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

    $query = "SELECT user_id, user_first, user_last FROM final_user
              WHERE user_id IN (SELECT r.first_id
				                        FROM final_user p, final_relationship r
                                WHERE r.second_id = ".$_SESSION['user_id'].")
              OR user_id IN (SELECT r.second_id
				                      FROM final_user p, final_relationship r
                              WHERE r.first_id = ".$_SESSION['user_id'].")";

    $result1 = mysqli_query($connect, $query);
    echo '<br>';
    if ($result1->num_rows > 0) {
      echo '<table>
            <tr>
              <th>Friends</th>
            </tr>';
      //each category name links to Category view which changes based on category ID
      while($row = mysqli_fetch_assoc($result1)) {
              echo '<tr>';
                  echo '<td><a href="Page.php?user_id=' . $row['user_id'] . '">'. $row['user_first'] .' '. $row['user_last'] . '</a></td>';
                  echo '<td>';
                      echo '<form action="" method="post">';
                      echo '<input type="hidden" name="user_id" value="' . $row['user_id'] . '"/>';
                      echo '<input type="submit" name="remove" value="Remove Friend" />
                      </form>';
                  echo '</td>';
              echo '</tr>';
      }
      echo '</table>';
    }
    else {
      echo "<p>You don't seem to have any friends yet. Check the <a href='Directory.php'>Directory</a> for someone you know!</p>";
    }
  }
  include 'Footer.php';
?>
