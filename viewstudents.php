<?php
  session_start();
	if(isset($_SESSION['id_no']))
  {
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="style.css">
  <title>UPLB Unit Academic Analytics</title>
  <script type="text/javascript" src="src/jquery-1.10.2.min.js"></script>
  <script type="text/javascript">
    $(document).ready(
      function()
      {
        $(".notif").fadeIn(1000);
        $(".notif").delay(2000).fadeOut(1000);
      }
    );
  </script>
  <link rel="stylesheet" type="text/css" href="src/bjqs.css">
  <link rel="stylesheet" type="text/css" href="src/demo.css">
  <script type="text/javascript" src="src/bjqs-1.3.js"></script>
  <script src="script.js"></script>
</head>
<body>
<?php
  if(isset($_GET['deleted'])) echo "<div class=\"notif\">Student successfully deleted.</div>";
?>
<div id="masthead">
  <div id="header-wrapper">
    <h1>UPLB Unit Academic Analytics</h1>
    <?php
      if(isset($_SESSION['id_no']))
      {
        echo "
        <div id=\"info-badge\">
          <table>
            <tr>
              <td><img src=\"images/nopic.gif\"></td>
              <td>".$_SESSION['first_name']." ".$_SESSION['middle_name']." ".$_SESSION['last_name']."</td>
              <td><a href=\"signout.php\">My Account</a></td>
              <td><a href=\"signout.php\">Sign out</a></td>
            </tr>
          </table>
        </div>";
      }
    ?>
  </div>
</div>
<div id="auxmenu">
  <div id="nav-wrapper">
    <table id="nav">
      <tr>
        <td><a href="index.php">Home</a></td>
        <td><a href="menu.php">Menu</a></td>
        <td>
        <?php
          if(isset($_SESSION['id_no'])) echo "<a href=\"signout.php\">Sign Out</a>";
          else echo "<a href=\"signin.php\">Sign In</a>";
        ?>
        </td>
      </tr>
    </table>
    <form action="#" method="get">
      <table id="search">
        <tr>
          <td id="search-field">
            <input type="text" placeholder="Search...">
          </td>
          <td id="search-button">
            <label for="search-submit"><a>Go</a></label>
            <input type="submit" id="search-submit" style="display:none;">
          </td>
        </tr>
      </table>
    </form>
	</div>
</div>
<div id="content-wrapper">
  <div id="content2" style="font-size:small;">
    <?php
      echo "
      <form action=\"#\" method=\"post\">
        Sort by:
        <select name=\"sortedby\">
          <option value=\"student_no\"";
          if($_POST['sortedby']=='student_no') echo " selected";
          echo ">Student number</option>
          <option value=\"last_name\"";
          if($_POST['sortedby']=='last_name') echo " selected";
          echo ">Last name</option>
          <option value=\"first_name\"";
          if($_POST['sortedby']=='first_name') echo " selected";
          echo ">First name</option>
          <option value=\"middle_name\"";
          if($_POST['sortedby']=='middle_name') echo " selected";
          echo ">Middle name</option>
          <option value=\"college_id\"";
          if($_POST['sortedby']=='college_id') echo " selected";
          echo ">College</option>
          <option value=\"degree\"";
          if($_POST['sortedby']=='degree') echo " selected";
          echo ">Degree</option>
        </select>
        Sort order:
        <select name=\"sortorder\">
          <option value=\"ASC\"";
          if($_POST['sortorder']=='ASC') echo " selected";
          echo ">Ascending</option>
          <option value=\"DESC\"";
          if($_POST['sortorder']=='DESC') echo " selected";
          echo ">Descending</option>
        </select>
        <input type=\"submit\" value=\"Go\">
      </form>";
      $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
      $sql = pg_query($conn, "SELECT COUNT(*) FROM student")or die("Query failed with error: ".pg_last_error($conn));
      $count = pg_fetch_row($sql);
      echo "
            <table>
              <thead><tr>
                <th>Student number</th>
                <th>Last name</th>
                <th>First name</th>
                <th>Middle name</th>
                <th>College</th>
                <th>Degree</th>
                <th></th>
                <th></th>
              </tr></thead>
      ";
      
      if(isset($_POST['sortedby']))
        $sql = pg_query($conn, "SELECT * FROM student ORDER BY ".$_POST['sortedby']." ".$_POST['sortorder'])or die("Query failed with error: ".pg_last_error($conn));
      else
        $sql = pg_query($conn, "SELECT * FROM student ORDER BY student_no ASC")or die("Query failed with error: ".pg_last_error($conn));
      for ($i=0; $i<$count[0]; $i++)
      {
        $row = pg_fetch_array($sql);
        $sql2 = pg_query($conn, "SELECT * FROM college where id_no = ".$row['college_id']."")or die("Query failed with error: ".pg_last_error($conn));
        $row2 = pg_fetch_array($sql2);
        echo "
              <tr>
                <td>".$row['student_no']."</td>
                <td>".$row['last_name']."</td>
                <td>".$row['first_name']."</td>
                <td>".$row['middle_name']."</td>
                <td>".$row2['name']."</td>
                <td>".$row['degree']."</td>
                <td>[<a href=\"viewstudentinfo.php?id=".$row['id_no']."\">Edit</a>]</td>
                <td>[<a href=\"deletestudent.php?id=".$row['id_no']."\">Delete</a>]</td>
              </tr>";
      }
      echo "</table>";
    ?>
  </div>
</div>
<br>
<div id="footer">
  &#169; 2013 UPLB
</div>
</body>
</html>
<?php
  }
  else header('Location: signin.php?restricted');
?>