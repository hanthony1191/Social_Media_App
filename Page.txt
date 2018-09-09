<?php
  //Initiate session, Connect to DB, Include Header
  session_start();
  include 'Connect.php';
  include 'Header.php';
  include 'Functions.php';

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

    //use get method to get user id to query db and populate page
    $query = "SELECT *
              FROM final_user
              WHERE user_id = " . $_GET['user_id'];

    $result = mysqli_query($connect, $query);
    $row = mysqli_fetch_assoc($result);

    //populated by query using get method
    echo "<h2>" . $row['user_first'] . " " . $row['user_last'] . "</h2>";
    echo "<main>";
    echo "<section>";

      //if remove button is presssed relationship table is queried twice because friend id might be first or second part of composite key
      if (isset($_POST["remove"])) {
        $delete = "DELETE FROM final_relationship
                WHERE first_id = " . $_SESSION['user_id'] . " AND second_id = " . $_POST['user_id'];

        $delete1 = "DELETE FROM final_relationship
                WHERE first_id = " . $_POST['user_id'] . " AND second_id = " . $_SESSION['user_id'];

        //depending on which query produces the proper record, record deleted
        $delete_result = mysqli_query($connect, $delete);
        $delete1_result = mysqli_query($connect, $delete1);

        //feedback to the user depending on deletion query
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

      //if add button is pressed, record added to relationship table
      if (isset($_POST["add"])) {
        $insert = "INSERT INTO final_relationship(first_id, second_id, type_id)
                  VALUES(" . $_SESSION['user_id'] . ", " . $_POST['user_id'] . ", 3)";

        $insert_result = mysqli_query($connect, $insert);

        //feedback statement
        if(!$insert_result) {
            //something went wrong, display the error
            echo 'Something went wrong. Please try again later.';
        }
        else {
            echo 'Successfully added.';
        }
      }

      //query to check if the user is a friend. Checks first and second part of composite key in relationship table
      $check = "SELECT user_id, user_first, user_last FROM final_user
                WHERE user_id IN (SELECT r.first_id
                                  FROM final_user p, final_relationship r
                                  WHERE r.second_id = ".$_SESSION['user_id'].")
                OR user_id IN (SELECT r.second_id
                                FROM final_user p, final_relationship r
                                WHERE r.first_id = ".$_SESSION['user_id'].")";

      $check_result = mysqli_query($connect, $check);

      //generate a list of records based on query
      $friend_list = mysqli_fetch_assoc($check_result);

      //if the person is a friend show remove button, otherwise show add button. Both forms have hidden field to post id of the person
      if (in_array($row['user_id'], $friend_list)) {
        echo '<form action="" method="post">';
        #if user is a friend remove, else add
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
    echo "</section>";

    echo "<section>";

      //displays the events of the user
      $query1 = "SELECT * FROM final_user a JOIN final_event b ON a.user_id = b.user_id WHERE a.user_id = " . $_GET['user_id'];

      $result1 = mysqli_query($connect, $query1);

      //section title
      echo "<br><h3>" . $row['user_first'] . " " . $row['user_last'] . "'s Events</h3>";

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

      //generates table for events of person
      if ($result1->num_rows > 0) {
        echo '<table>
        <tr>
          <th>Event</th>
          <th>Location</th>
          <th>Date</th>
        </tr>';
        //e
        while($row1 = mysqli_fetch_assoc($result1)) {
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
                    echo '<td><a href="Event.php?event_id=' . $row1['event_id'] . '">'. $row1['event_name'] . '</a></td>';
                    echo '<td>'. $row1['event_location'] . '</td>';
                    echo '<td>'. $row1['event_date'] . '</td>';
                echo '</tr>';
                echo '<tr>';
                    if (in_array($row['user_id'], $friend_list)) {
                      echo '<td>';
                          echo '<form action="" method="post">';
                          echo '<input type="hidden" name="event_id" value="' . $row1['event_id'] . '"/>';
                          echo '<input type="submit" name="yes" value="Yes" />
                          </form>';
                      echo '</td>';
                    }
                    //count how many types of replies are for this event
                    echo '<td>';
                        $count = 0;
                        while($row_1 = mysqli_fetch_assoc($yes_resp_result)) {
                          if ($row1['event_id'] == $row_1['event_id']) {
                            $count += 1;
                          }
                        }
                        echo 'Going: ';
                        echo $count;
                    echo '</td>';
                    if (in_array($row['user_id'], $friend_list)) {
                      echo '<td>';
                          echo '<form action="" method="post">';
                          echo '<input type="hidden" name="event_id" value="' . $row1['event_id'] . '"/>';
                          echo '<input type="submit" name="no" value="No" />
                          </form>';
                      echo '</td>';
                    }
                    //count how many types of replies are for this event
                    echo '<td>';
                        $count_1 = 0;
                        while($row_2 = mysqli_fetch_assoc($no_resp_result)) {
                          if ($row1['event_id'] == $row_2['event_id']) {
                            $count_1 += 1;
                          }
                        }
                        echo 'Can\'t: ';
                        echo $count_1;
                    echo '</td>';
                    if (in_array($row['user_id'], $friend_list)) {
                      echo '<td>';
                          echo '<form action="" method="post">';
                          echo '<input type="hidden" name="event_id" value="' . $row1['event_id'] . '"/>';
                          echo '<input type="submit" name="maybe" value="Maybe" />
                          </form>';
                      echo '</td>';
                    }
                    //count how many types of replies are for this event
                    echo '<td>';
                        $count_2 = 0;
                        while($row_3 = mysqli_fetch_assoc($maybe_resp_result)) {
                          if ($row1['event_id'] == $row_3['event_id']) {
                            $count_2 += 1;
                          }
                        }
                        echo 'Maybes: ';
                        echo $count_2;
                    echo '</td>';
                echo '</tr>';
        }
        echo '</table>';
      }
      echo "</section>";
    echo "</main>";
  // }
  include 'Footer.php'
?>
