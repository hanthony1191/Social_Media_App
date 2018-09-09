<?php
  //Initiate session, Connect to DB, Include Header
  session_start();
  include 'Connect.php';
  include 'Header.php';

  //Check if user is signed in
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

    //if event form is posted with no errors, insert into db
    if (isset($_POST["event"]) && empty($error) && empty($missing)) {
      $sql = "INSERT INTO final_event(event_name, event_location, event_date, user_id) VALUES('" . $_POST['event_name']. "', '" . $_POST['event_location'] . "', '" . $_POST['event_date'] . " " . $_POST['event_time'] . "', " . $_SESSION['user_id'] . ")";

      $result = mysqli_query($connect, $sql);

      //user feedback
      if(!$result) {
          echo 'Something went wrong. Please try again later.';
      }
      else {
          echo '<p>Event Created! View your <a href="Profile.php">events</a></p>';
      }
    }

    //options to reply to an event: yes, no, maybe
    if (isset($_POST["yes"])) {
      $old_resp = "DELETE FROM final_response
                    WHERE event_id = " . $_POST['event_id'] . " AND user_id = " . $_SESSION['user_id'];

      $old_resp_result = mysqli_query($connect, $old_resp);

      $yes_query = "INSERT INTO
                  final_response(category_id, event_id, user_id)
              VALUES(1, " . $_POST['event_id'] . ", " . $_SESSION['user_id'] . ")";

      $yes_result = mysqli_query($connect, $yes_query);
    }
    //options to reply to an event: yes, no, maybe
    if (isset($_POST["no"])) {
      $old_resp = "DELETE FROM final_response
                    WHERE event_id = " . $_POST['event_id'] . " AND user_id = " . $_SESSION['user_id'];

      $old_resp_result = mysqli_query($connect, $old_resp);

      $no_query = "INSERT INTO
                  final_response(category_id, event_id, user_id)
              VALUES(2, " . $_POST['event_id'] . ", " . $_SESSION['user_id'] . ")";

      $no_result = mysqli_query($connect, $no_query);
    }
    //options to reply to an event: yes, no, maybe
    if (isset($_POST["maybe"])) {
      $old_resp = "DELETE FROM final_response
                    WHERE event_id = " . $_POST['event_id'] . " AND user_id = " . $_SESSION['user_id'];

      $old_resp_result = mysqli_query($connect, $old_resp);

      $maybe_query = "INSERT INTO
                  final_response(category_id, event_id, user_id)
              VALUES(3, " . $_POST['event_id'] . ", " . $_SESSION['user_id'] . ")";

      $maybe_result = mysqli_query($connect, $maybe_query);
    }

    //Query to generate group information
    $group_query = "SELECT group_id, group_name
                    FROM final_group
                    WHERE user_id = " . $_SESSION['user_id'];

    $group_result = mysqli_query($connect, $group_query);
    $group_row = array();

    //loop through array to get all db records
    while($row = mysqli_fetch_assoc($group_result)){
      $group_row[] = $row;
    }

    echo "<br><h3>What's New?</h3>";
    echo "<section>";
      //form for event creation
      echo '<form action="" method="post">';
            if (in_array("event_name", $missing)) :
      echo   '<p><b>Please include a name for your event</b></p>';
            endif;
            //keep form inputs sticky
            if ($_POST && $event_name) {
              echo  'Event Name: <input type="text" name="event_name" value="' . htmlentities($event_name) . '"><br>';
            }
            else {
              echo  'Event Name: <input type="text" name="event_name"><br>';
            }
             if (isset($error["event_name"]) && !in_array("event_name", $missing)) {
               echo $error["event_name"];
             }
             if (in_array("event_name", $missing)) :
       echo   '<p><b>Please include a location for your event</b></p>';
             endif;
             //keep form inputs sticky
             if ($_POST && $event_location) {
               echo  'Event Location: <input type="text" name="event_location" value="' . htmlentities($event_location) . '"><br>';
             }
             else {
               echo  'Event Location: <input type="text" name="event_location"><br>';
             }
              if (isset($error["event_location"]) && !in_array("event_location", $missing)) {
                echo $error["event_location"];
              }
        //additional form inputs
        echo 'Event Date: <input type="date" name="event_date"><br>';
        echo 'Event Time: <input type="time" name="event_time"><br>';
        echo 'Group: <select name="$group_id">';
        echo '<option value="">None</option>';
          //loop through group query variable to display all group names
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
        //submit form
      echo '<input type="submit" name= "event" value="Create Event" />
      </form>';
    echo "</section>";

    //query to generate all events by users that are friends
    $query = "SELECT e.*, p.user_first, p.user_last
            FROM final_event e JOIN final_user p
            ON e.user_id = p.user_id
            WHERE e.user_id IN (SELECT r.first_id
                              FROM final_user p, final_relationship r
                              WHERE r.second_id = ".$_SESSION['user_id'].")
            OR e.user_id IN (SELECT r.second_id
                            FROM final_user p, final_relationship r
                            WHERE r.first_id = ".$_SESSION['user_id'].")";

    $result1 = mysqli_query($connect, $query);



    echo "<br><h3>Events</h3>";

    //generate table of events by friends
    if ($result1->num_rows > 0) {
      echo '<table>
            <tr>
              <th>Event</th>
              <th>Host</th>
              <th>Location</th>
              <th>Date</th>
            </tr>';
      //each category name links to Category view which changes based on category ID
      while($row = mysqli_fetch_assoc($result1)) {
              //tallies all types of responses in response table for counting method below
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

              //for each entry create a table row
              echo '<tr>';
                  echo '<td><a href="Event.php?event_id=' . $row['event_id'] . '">'. $row['event_name'] . '</a></td>';
                  echo '<td><a href="Page.php?user_id=' . $row['user_id'] . '">'. $row['user_first'] . ' ' . $row['user_last'] . '</a></td>';
                  echo '<td>'. $row['event_location'] . '</td>';
                  echo '<td>'. $row['event_date'] . '</td>';
              echo '</tr>';
              echo '<tr>';
                  echo '<td>';
                      echo '<form action="" method="post">';
                      echo '<input type="hidden" name="event_id" value="' . $row['event_id'] . '"/>';
                      echo '<input type="submit" name="yes" value="Yes" />
                      </form>';
                  echo '</td>';
                  //count how many types of replies are for this event
                  echo '<td>';
                      $count = 0;
                      while($row_1 = mysqli_fetch_assoc($yes_resp_result)) {
                        if ($row['event_id'] == $row_1['event_id']) {
                          $count += 1;
                        }
                      }
                      echo $count;
                  echo '</td>';
                  echo '<td>';
                      echo '<form action="" method="post">';
                      #if user is a friend remove, else add
                      echo '<input type="hidden" name="event_id" value="' . $row['event_id'] . '"/>';
                      echo '<input type="submit" name="no" value="No" />
                      </form>';
                  echo '</td>';
                  //count how many types of replies are for this event
                  echo '<td>';
                      $count_1 = 0;
                      while($row_2 = mysqli_fetch_assoc($no_resp_result)) {
                        if ($row['event_id'] == $row_2['event_id']) {
                          $count_1 += 1;
                        }
                      }
                      echo $count_1;
                  echo '</td>';
                  echo '<td>';
                      echo '<form action="" method="post">';
                      #if user is a friend remove, else add
                      echo '<input type="hidden" name="event_id" value="' . $row['event_id'] . '"/>';
                      echo '<input type="submit" name="maybe" value="Maybe" />
                      </form>';
                  echo '</td>';
                  //count how many types of replies are for this event
                  echo '<td>';
                      $count_2 = 0;
                      while($row_3 = mysqli_fetch_assoc($maybe_resp_result)) {
                        if ($row['event_id'] == $row_3['event_id']) {
                          $count_2 += 1;
                        }
                      }
                      echo $count_2;
                  echo '</td>';
              echo '</tr>';
      }
      echo '</table>';
    }
    //Display if user doesn't have any friend events
    else {
      echo "<br><p>You don't seem to have any friend events</p>";
    }
  }
  include 'Footer.php';
?>
