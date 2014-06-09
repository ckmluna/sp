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
<div id="content-wrapper2">
  <div id="content2" style="font-size:small;">
    <h2>View workload</h2>
    <?php
      $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
      /**VIEWING OPTIONS CODE START HERE**/
      echo "
      <form action=\"#\" method=\"post\">
        Select department:
        <select name=\"filtercollege\">";
      $sql = pg_query($conn, "SELECT * FROM college")or die("Query failed with error: ".pg_last_error($conn));
      while ($row = pg_fetch_array($sql))
      {
        echo "<optgroup label=\"".$row['name']."\">";
        $sql2 = pg_query($conn, "SELECT * FROM department where college_id = '".$row['id_no']."'")or die("Query failed with error: ".pg_last_error($conn));
        while ($row2 = pg_fetch_array($sql2))
        {
          echo "<option value=\"".$row2['id_no']."\""; if($_POST['filtercollege']==$row2['id_no']) echo " selected"; echo ">".$row2['name']."</option>";
        }
        echo "</optgroup>";
      }
      echo "
        </select><br>
        Semester:
        <select name=\"filtersem\">
          <option value=\"1st Semester\""; if($_POST['filtersem']=='1st Semester') echo " selected"; echo ">1st Semester</option>
          <option value=\"2nd Semester\""; if($_POST['filtersem']=='2nd Semester') echo " selected"; echo ">2nd Semester</option>
          <option value=\"Summer\""; if($_POST['filtersem']=='Summer') echo " selected"; echo ">Summer</option>
        </select>
        Academic Year:
        <select name=\"filteryear\">";
      $sql = pg_query($conn, "SELECT DISTINCT academic_year FROM course_offering")or die("Query failed with error: ".pg_last_error($conn));
      while ($row = pg_fetch_array($sql))
      {
        echo "<option value=\"".$row['academic_year']."\""; if($_POST['filteryear']==$row['academic_year']) echo " selected"; echo ">".$row['academic_year']."</option>";
      }
      echo "
          </select>
        <input type=\"submit\" value=\"Go\">
      </form>";
      /**VIEWING OPTIONS CODE END HERE**/
      /**WORKLOAD TABLE STARTS HERE**/
      if(isset($_POST['filtercollege']) || isset($_POST['filtercollege']) || isset($_POST['filtersem']))
      {
        echo "
        Faculty Workload (".$_POST['filtersem']." ".$_POST['filteryear'].")
        <table class=\"course-offering\">
          <thead><tr>
            <th>Name of Faculty</th>
            <th>Rank</th>
            <th>Status</th>
            <th>Course</th>
            <th>Section</th>
            <th>Course<br>Credit</th>
            <th>Teaching<br>Load</th>
            <th>Activity</td>
            <th>Load</th>
            <th>Activity</td>
            <th>Load</th>
            <th>Activity</th>
            <th>Within<br>College</th>
            <th>Outside<br>College</th>
            <th>Community<br>Works</th>
            <th>Study<br>Load<br>Credit</th>
            <th>Total<br>Load</th>
            <th>Remarks</th>
          </tr></thead>
        ";
        $sql = pg_query($conn, "SELECT * FROM course_offering where semester='".$_POST['filtersem']."' AND academic_year='".$_POST['filteryear']."'")or die("Query failed with error: ".pg_last_error($conn));
        $row = pg_fetch_array($sql);
        $co_id = $row['id_no'];
        $sql = pg_query($conn, "SELECT * FROM faculty where dept_id='".$_POST['filtercollege']."'")or die("Query failed with error: ".pg_last_error($conn));
        while($row = pg_fetch_array($sql))
        {
          $names = explode(" ", $row['first_name']);
          $initials = "";
          foreach($names as $n)
          {
            $initials .= $n[0];
          }
          echo "
            <tr>
              <td>".$initials."".$row['last_name']."</td>
              <td>".$row['rank']."</td>
              <td></td>
              <td>
          ";
          if($co_id)
          {
            $sql2 = pg_query($conn, "SELECT * FROM lec_section WHERE co_id='".$co_id."'")or die("Query failed with error: ".pg_last_error($conn));
            while($row2 = pg_fetch_array($sql2))
            {
              $sql3 = pg_query($conn, "SELECT * FROM course WHERE course_id='".$row2['course_id']."'")or die("Query failed with error: ".pg_last_error($conn));
              $row3 = pg_fetch_array($sql3);
              if($row2['faculty_id']==$row['id_no']) echo $row3['course_code']."</br>";
              $sql4 = pg_query($conn, "SELECT * FROM lab_section WHERE lecture_id='".$row2['id_no']."' AND faculty_id='".$row['id_no']."'")or die("Query failed with error: ".pg_last_error($conn));
              while($row4 = pg_fetch_array($sql4))
              {
                echo $row3['course_code']."</br>";
              }
            }
          }
          echo "
              </td>
              <td>
          ";
          if($co_id)
          {
            $sql2 = pg_query($conn, "SELECT * FROM lec_section WHERE co_id='".$co_id."'")or die("Query failed with error: ".pg_last_error($conn));
            while($row2 = pg_fetch_array($sql2))
            {
              if($row2['faculty_id']==$row['id_no']) echo $row2['section_name']."</br>";
              $sql3 = pg_query($conn, "SELECT * FROM lab_section WHERE lecture_id='".$row2['id_no']."' AND faculty_id='".$row['id_no']."'")or die("Query failed with error: ".pg_last_error($conn));
              while($row3 = pg_fetch_array($sql3))
              {
                echo $row2['section_name']."-".$row3['section_no']."</br>";
              }
            }
          }
          echo "
              </td>
              <td>
          ";
          echo "
              </td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr class=\"total\">
              <td>Total</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          ";
        }
        echo "
        </table>
        ";
      }
      /**WORKLOAD TABLE ENDS HERE**/
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