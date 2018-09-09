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

    //generate event using get method
    $event_query = "SELECT *
              FROM final_event
              WHERE event_id = " . $_GET['event_id'];

    $event_result = mysqli_query($connect, $event_query);
    $event_row = mysqli_fetch_assoc($event_result);

    //populate page using event query
    echo "<h3>Event Page: </h3>";
    echo "<h2>" . $event_row['event_name'] . "</h2>";

    echo "<main>";
    echo "<section>";

        //generate list of all users goin to the event
        $attend_query = "SELECT a.user_id, user_first, user_last
                        FROM final_user a JOIN final_response b
                        ON a.user_id = b.user_id
                        WHERE event_id = " . $_GET['event_id'] . " AND category_id = 3";

        $attend_result = mysqli_query($connect, $attend_query);

        //display table of group attendees
        if ($attend_result->num_rows > 0) {
          echo '<table>
                <tr>
                  <th>Attendees</th>
                </tr>';

          //Display attendees with page links
          while($row = mysqli_fetch_assoc($attend_result)) {
                  echo '<tr>';
                      echo '<td><a href="Page.php?user_id=' . $row['user_id'] . '">'. $row['user_first'] .' '. $row['user_last'] . '</a></td>';
                  echo '</tr>';
          }
          echo '</table>';
        }
        else {
          //display message if there are no attendees
          echo "<p>There doesn't seem to be any attendees.</p>";
        }
    echo "</section>";
  echo "</main>";
}
include 'Footer.php'
?>
