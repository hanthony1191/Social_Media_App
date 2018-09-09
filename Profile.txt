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
    //Left navigation out of header.php for future implementation
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
    //use session method to get user id to query db and populate page
    $query = "SELECT *
              FROM final_user
              WHERE user_id = " . $_SESSION['user_id'];

    $result = mysqli_query($connect, $query);
    $row = mysqli_fetch_assoc($result);

    //populated by query using session method
    echo "<h2>" . $row['user_first'] . " " . $row['user_last'] . "</h2>";
    echo "<main>";
      echo "<section>";

        //if edit button is pressed, filled, and error free, user table is updated
        if (isset($_POST["editFirst"]) && empty($error) && empty($missing)) {
          $edit_first = "UPDATE final_user
                        SET user_first = '" . $_POST['user_first'] . "'
                        WHERE user_id = " . $_SESSION['user_id'];

          $name_result = mysqli_query($connect, $edit_first);
          if ($first_result) {
            //page is reloaded
            header("Location: Profile.php");
          }
        }
        //if edit button is pressed, filled, and error free, user table is updated
        if (isset($_POST["editLast"]) && empty($error) && empty($missing)) {
          $edit_last = "UPDATE final_user
                        SET user_last = '" . $_POST['user_last'] . "'
                        WHERE user_id = " . $_SESSION['user_id'];

          $last_result = mysqli_query($connect, $edit_last);
          if ($last_result) {
            //page is reloaded
            header("Location: Profile.php");
          }
        }
        //if edit button is pressed, filled, and error free, user table is updated
        if (isset($_POST["editEmail"]) && empty($error) && empty($missing)) {
          $edit_email = "UPDATE final_user
                        SET user_email = '" . $_POST['user_email'] . "'
                        WHERE user_id = " . $_SESSION['user_id'];

          $email_result = mysqli_query($connect, $edit_email);
          if ($email_result) {
            //page is reloaded
            header("Location: Profile.php");
          }
        }
        //if edit button is pressed, filled, and error free, user table is updated
        if (isset($_POST["editPass"]) && empty($error) && empty($missing)) {
          $edit_pass = "UPDATE final_user
                        SET user_password = '" . sha1($_POST['user_password']) . "'
                        WHERE user_id = " . $_SESSION['user_id'];

          $pass_result = mysqli_query($connect, $edit_pass);
          if ($pass_result) {
            //page is reloaded
            header("Location: Profile.php");
          }
        }

        echo "<h3>Settings</h3>";

        //form populated by user data from user table query
        echo '<form action="" method="post">';
              if (in_array("user_first", $missing)) :
                //error message for empty field
        echo  '<p><b>Please include your first name</b></p>';
              endif;
        echo  'Change First Name: <input type="text" name="user_first" value="' .$row["user_first"]. '"<br>';
        if (isset($error["user_first"]) && !in_array("user_first", $missing))
        {
          echo $error["user_first"];
        }
        echo '<input type="submit" name= "editFirst" value="Edit" />
        </form><br>';
        //form populated by user data from user table query
        echo '<form action="" method="post">';
              if (in_array("user_last", $missing)) :
        echo  '<p><b>Please include your last name</b></p>';
              endif;
        echo  'Change Last Name: <input type="text" name="user_last" value="' .$row["user_last"]. '"<br>';
        if (isset($error["user_last"]) && !in_array("user_last", $missing))
        {
          echo $error["user_last"];
        }
        echo '<input type="submit" name= "editLast" value="Edit" />
        </form><br>';
        //form populated by user data from user table query
        echo '<form action="" method="post">';
              if (in_array("user_email", $missing)) :
        echo  '<p><b>Please include your email</b></p>';
              endif;
        echo  'Change Email: <input type="text" name="user_email" value="' .$row["user_email"]. '"<br>';
        if (isset($error["user_email"]) && !in_array("user_email", $missing))
        {
          echo $error["user_email"];
        }
        echo '<input type="submit" name= "editEmail" value="Edit" />
        </form><br>';
        //form populated by user data from user table query
        echo '<form action="" method="post">';
              if (in_array("user_password", $missing)) :
        echo   '<p><b>Please include your password</b></p>';
              endif;
        echo  'Change Password: <input type="password" name="user_password">';
        echo '<input type="submit" name= "editPass" value="Edit" />
        </form>';

        //redirects to delete page for account deletion
        echo '<p><a href="Delete.php">Delete Account</a></p>';
      echo "</section>";

      //if event is posted, filled, and without error new record is inserted into event table
      if (isset($_POST["event"]) && empty($error) && empty($missing)) {
        $create = "INSERT INTO final_event(event_name, event_location, event_date, user_id) VALUES('" . $_POST['event_name']. "', '" . $_POST['event_location'] . "', '" . $_POST['event_date'] . " " . $_POST['event_time'] . "', " . $_SESSION['user_id'] . ")";

        $create_result = mysqli_query($connect, $create);

        if(!$create_result) {
            echo 'Something went wrong. Please try again later.';
        }
        else {
            //refresh the page
            header("Refresh:0");
        }

      }

      //group information queried for display
      $group_query = "SELECT group_id, group_name
                      FROM final_group
                      WHERE user_id = " . $_SESSION['user_id'];

      $group_result = mysqli_query($connect, $group_query);
      $group_row = array();
      while($row = mysqli_fetch_assoc($group_result)){
        $group_row[] = $row;
      }

      //form for event creation
      echo "<br><h3>What's New?</h3>";
      echo "<section>";
        echo '<form action="" method="post">';
              //error message for missing field
              if (in_array("event_name", $missing)) :
        echo   '<p><b>Please include a name for your event</b></p>';
              endif;
              //keep form sticky in case of errors
              if ($_POST && $event_name) {
                echo  'Event Name: <input type="text" name="event_name" value="' . htmlentities($event_name) . '"><br>';
              }
              else {
                echo  'Event Name: <input type="text" name="event_name"><br>';
              }
               if (isset($error["event_name"]) && !in_array("event_name", $missing)) {
                 echo $error["event_name"];
               }
               //error message for missing field
               if (in_array("event_name", $missing)) :
         echo   '<p><b>Please include a location for your event</b></p>';
               endif;
               //keep form sticky in case of errors
               if ($_POST && $event_location) {
                 echo  'Event Location: <input type="text" name="event_location" value="' . htmlentities($event_location) . '"><br>';
               }
               else {
                 echo  'Event Location: <input type="text" name="event_location"><br>';
               }
                if (isset($error["event_location"]) && !in_array("event_location", $missing)) {
                  echo $error["event_location"];
                }
          //additional event information
          echo 'Event Date: <input type="date" name="event_date"><br>';
          echo 'Event Time: <input type="time" name="event_time"><br>';
          echo 'Group: <select name="$group_id">';
          echo '<option value="NULL">None</option>';
            foreach ($group_row as $key => $value) {
              foreach($value as $key2 => $value2){
                if ($key2 == "group_name") {
                  echo '<option value="' . $value["group_id"] . '"';
                     // keeps field sticky by printing name if submitted
                     // if ($_POST && $group_id == $key) {
                     //   echo "selected";
                     // }
                   echo '>'. $value2 .'</option>';
               }
             }
          }
          echo '</select><br>';
        echo '<input type="submit" name= "event" value="Create Event" />
        </form>';
      echo "</section>";

      //if remove event button form is pressed, related record is deleted
      echo "<section>";
      if (isset($_POST["remove_event"])) {
        $remove_event = "DELETE FROM final_event
                WHERE user_id = " . $_SESSION['user_id'] . " AND event_id = " . $_POST['event_id'];

        $event_result = mysqli_query($connect, $remove_event);

        //feedback for the user
        if(!$delete_result) {
            echo 'Something went wrong. Please try again later.';
        }
        else {
            header("location: Profile.php");
        }
      }
        //generate all events created by user account
        $query1 = "SELECT event_id, event_name, event_location, event_date FROM final_user a JOIN final_event b ON a.user_id = b.user_id WHERE a.user_id = " . $_SESSION['user_id'];

        $result1 = mysqli_query($connect, $query1);

        echo "<br><h3>Events</h3>";

        //count all types of reply from response table
        $yes_resp = "SELECT *
                    FROM final_response
                    WHERE category_id = 1";

        $yes_resp_result = mysqli_query($connect, $yes_resp);

        $no_resp = "SELECT *
                    FROM final_response
                    WHERE category_id = 2";

        $no_resp_result = mysqli_query($connect, $no_resp);

        $maybe_resp = "SELECT *
                    FROM final_response
                    WHERE category_id = 3";

        $maybe_resp_result = mysqli_query($connect, $maybe_resp);

        //generate user created events table
        if ($result1->num_rows > 0) {
          echo '<table>
          <tr>
            <th>Event</th>
            <th>Location</th>
            <th>Date</th>
          </tr>';
          //each category name links to Category view which changes based on category ID
          while($row = mysqli_fetch_assoc($result1)) {
                  //event table per row
                  echo '<tr>';
                      echo '<td><a href="Event.php?event_id=' . $row['event_id'] . '">'. $row['event_name'] . '</a></td>';
                      echo '<td>'. $row['event_location'] . '</td>';
                      echo '<td>'. $row['event_date'] . '</td>';
                  echo '</tr>';
                  echo '<tr>';
                      //counting method to tally responses
                      echo '<td>';
                          $count = 0;
                          while($row_1 = mysqli_fetch_assoc($yes_resp_result)) {
                            if ($row['event_id'] == $row_1['event_id']) {
                              $count += 1;
                            }
                          }
                          echo 'Going: ';
                          echo $count;
                      echo '</td>';
                      //counting method to tally responses
                      echo '<td>';
                          $count_1 = 0;
                          while($row_2 = mysqli_fetch_assoc($no_resp_result)) {
                            if ($row['event_id'] == $row_2['event_id']) {
                              $count_1 += 1;
                            }
                          }
                          echo 'Can\'t: ';
                          echo $count_1;
                      echo '</td>';
                      //counting method to tally responses
                      echo '<td>';
                          $count_2 = 0;
                          while($row_3 = mysqli_fetch_assoc($maybe_resp_result)) {
                            if ($row['event_id'] == $row_3['event_id']) {
                              $count_2 += 1;
                            }
                          }
                          echo 'Maybes: ';
                          echo $count_2;
                      echo '</td>';
                      echo '<td>';
                          echo '<form action="" method="post">';
                          echo '<input type="hidden" name="event_id" value="' . $row['event_id'] . '"/>';
                          //submit form data with hidden event id attribute
                          echo '<input type="submit" name="remove_event" value="Delete Event" />
                          </form>';
                      echo '</td>';
                  echo '</tr>';
          }
          echo '</table>';
        }
        //message if user hasn't created any events
        else {
          echo "<br><p>You haven't created any events yet!</p>";
        }
      echo "</section>";
    echo "</main>";
  }
  include 'Footer.php'
?>
