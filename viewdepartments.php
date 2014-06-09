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
  if(isset($_GET['deleted'])) echo "<div class=\"notif\">Department successfully deleted.</div>";
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
          <option value=\"name\"";
          if($_POST['sortedby']=='name') echo " selected";
          echo ">Name</option>
          <option value=\"college_id\"";
          if($_POST['sortedby']=='college_id') echo " selected";
          echo ">College</option>
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
      if(isset($_POST['sortedby']))
        $sql = pg_query($conn, "SELECT * FROM department ORDER BY ".$_POST['sortedby']." ".$_POST['sortorder'])or die("Query failed with error: ".pg_last_error($conn));
      else
        $sql = pg_query($conn, "SELECT * FROM department ORDER BY name ASC")or die("Query failed with error: ".pg_last_error($conn));
      echo "<table>";
      echo "<thead><th>Name</th><th>College</th><th></th><th></th></thead>";
      while ($row = pg_fetch_array($sql))
      {
        echo "<tr><td>".$row['name']."</td>";
        $sql2 = pg_query($conn, "SELECT * FROM college where id_no='".$row['college_id']."'")or die("Query failed with error: ".pg_last_error($conn));
        $row2 = pg_fetch_array($sql2);
        echo "<td>".$row2['name']."</td>";
        echo "<td>[<a href=\"viewdepartmentinfo.php?id=".$row['id_no']."\">Edit</a>]</td>";
        echo "<td>[<a href=\"deletedepartment.php?id=".$row['id_no']."\">Delete</a>]</tr></td>";
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