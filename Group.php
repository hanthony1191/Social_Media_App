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

    $group_query = "SELECT *
              FROM final_group
              WHERE group_id = " . $_GET['group_id'];

    $group_result = mysqli_query($connect, $group_query);
    $group_row = mysqli_fetch_assoc($group_result);

    echo "<h3>Group Page: </h3>";
    echo "<h2>" . $group_row['group_name'] . "</h2>";

    echo "<main>";
    echo "<section>";

    if (isset($_POST["drop"])) {
      $drop_query = "DELETE FROM final_user_group
              WHERE user_id = " . $_POST['user_id'] . " AND group_id = " . $_GET['group_id'];

      $drop_result = mysqli_query($connect, $drop_query);

      if(!$drop_result) {
          echo 'Something went wrong. Please try again later.';
      }
      else {
          header ("Refresh:0");
      }
    }

        $member_query = "SELECT a.user_id, user_first, user_last
                        FROM final_user a JOIN final_user_group b
                        ON a.user_id = b.user_id
                        WHERE group_id = " . $_GET['group_id'];

        $member_result = mysqli_query($connect, $member_query);

        if ($member_result->num_rows > 0) {
          echo '<table>
                <tr>
                  <th>Members</th>
                </tr>';

          while($row = mysqli_fetch_assoc($member_result)) {
                  echo '<tr>';
                      echo '<td><a href="Page.php?user_id=' . $row['user_id'] . '">'. $row['user_first'] .' '. $row['user_last'] . '</a></td>';
                      echo '<td>';
                          echo '<form action="" method="post">';
                          echo '<input type="hidden" name="user_id" value="' . $row['user_id'] . '"/>';
                          echo '<input type="submit" name="drop" value="Remove Member" />
                          </form>';
                      echo '</td>';
                  echo '</tr>';
          }
          echo '</table>';
        }
        else {
          echo "<p>You don't seem to have any members. Add from the list below!</p>";
        }
    echo "</section>";
    echo "<section>";

        if (isset($_POST["join"])) {
          $join_query = "INSERT INTO final_user_group(group_id, user_id)
                    VALUES(" . $_GET['group_id'] . ", " . $_POST['user_id'] . ")";

          $join_result = mysqli_query($connect, $join_query);
          if(!$join_result) {
              //something went wrong, display the error
              echo 'Something went wrong. Please try again later.';
          }
          else {
              header ("Refresh:0");
          }
        }

        $list_query = "SELECT user_id, user_first, user_last
                      FROM final_user
                      WHERE (user_id IN (SELECT r.first_id
                      				FROM final_user p, final_relationship r
                                      WHERE r.second_id =" . $_SESSION['user_id'] . ")
                      OR user_id IN (SELECT r.second_id
                      				FROM final_user p, final_relationship r
                                      WHERE r.first_id = " . $_SESSION['user_id'] . "))
                      AND user_id NOT IN (SELECT u.user_id
                      						FROM final_user u JOIN final_user_group g
                      						ON u.user_id = g.user_id
                      						WHERE group_id = " . $_GET['group_id'] . ");";

        $list_result = mysqli_query($connect, $list_query);

        if ($list_result->num_rows > 0) {
          echo '<table>
                <tr>
                  <th>Friends</th>
                </tr>';

          while($list_row = mysqli_fetch_assoc($list_result)) {
                  echo '<tr>';
                      echo '<td><a href="Page.php?user_id=' . $list_row['user_id'] . '">'. $list_row['user_first'] .' '. $list_row['user_last'] . '</a></td>';
                      echo '<td>';
                          echo '<form action="" method="post">';
                          echo '<input type="hidden" name="user_id" value="' . $list_row['user_id'] . '"/>';
                          echo '<input type="submit" name="join" value="Add Member" />
                          </form>';
                      echo '</td>';
                  echo '</tr>';
          }
          echo '</table>';
        }
        // else {
        //   echo "<p>You don't seem to have any friends yet. Check the <a href='Directory.php'>Directory</a> for someone you know!</p>";
        // }

    echo "</section>";
  echo "</main>";
}
include 'Footer.php'
?>
