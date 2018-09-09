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
    echo "<main>";
    echo "<section>";
        echo "<h2>Create Group</h2>";
        if (isset($_POST["create"])) {

          $group_query = "INSERT INTO final_group(group_name, user_id)
                          VALUES('". $_POST['group_name'] . "', " . $_SESSION['user_id'] . ")";

          $group_result = mysqli_query($connect, $group_query);

          $group_query_2 = "SELECT group_id FROM final_group WHERE user_id = " . $_SESSION['user_id'] . " AND group_name = '" . $_POST['group_name'] . "' LIMIT 1";

          $group_result_2 = mysqli_query($connect, $group_query_2);

          $group_row = mysqli_fetch_assoc($group_result_2);

          if(!$group_result) {
              //something went wrong, display the error
              echo 'Something went wrong while creating. Please try again later.';
          }
          else {
            foreach ($_POST['friend'] as $key => $value) {
              $insert_query = "INSERT INTO final_user_group(group_id, user_id)
                              VALUES(" . $group_row['group_id'] . ", " . $value . ")";

              $insert_result = mysqli_query($connect, $insert_query);
            }

            echo 'Successfully created. Return to <a href="Friends.php">Groups</a>';

          }
        }
        else {
          $friend_query = "SELECT user_id, user_first, user_last FROM final_user
                    WHERE user_id IN (SELECT r.first_id
      				                        FROM final_user p, final_relationship r
                                      WHERE r.second_id = ".$_SESSION['user_id'].")
                    OR user_id IN (SELECT r.second_id
      				                      FROM final_user p, final_relationship r
                                    WHERE r.first_id = ".$_SESSION['user_id'].")";

          $friend_result = mysqli_query($connect, $friend_query);

          echo '<form action="" method="post">';
                if (in_array("group_name", $missing)) :
          echo  '<p><b>Please include your group name</b></p>';
                endif;
                if ($_POST && $group_name) {
                  echo  'Group Name: <input type="text" name="group_name" value="' . htmlentities($group_name) . '"><br>';
                }
                else {
                  echo  'Group Name: <input type="text" name="group_name"><br>';
                }
                 if (isset($error["group_name"]) && !in_array("group_name", $missing)) {
                   echo $error["group_name"];
                 }

                 while($row = mysqli_fetch_assoc($friend_result)) {
                   if (in_array("friend", $missing)) :
            echo   '<p><b>Please select at least one friend</b></p>';
                  endif;
                   echo '<input type="checkbox" name="friend[]" value=' . $row['user_id'];
                   if ($_POST && in_array($row['user_id'], $friend)) {
                     echo "checked";
                   }
                    echo '>';
                    echo '<label><a href="Page.php?user_id=' . $row['user_id'] . '">'. $row['user_first'] . ' ' . $row['user_last'] . '</a></label><br>';
                }

          echo  '<input type="submit" name= "create" value="Create Group" />
                </form>';
        }
        echo "</section>";
      echo "</main>";
    }
    include 'Footer.php'
?>
