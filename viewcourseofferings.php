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
<div id="content-wrapper">
  <div id="content2" style="font-size:small;">
    <?php
      echo "
      <form action=\"#\" method=\"post\">
        Sort by:
        <select name=\"sortedby\">
          <option value=\"department\"";
          if($_POST['sortedby']=='department') echo " selected";
          echo ">Department</option>
          <option value=\"time\"";
          if($_POST['sortedby']=='time') echo " selected";
          echo ">Chronological order</option>
        </select>
        Department order:
        <select name=\"deptorder\">
          <option value=\"ASC\"";
          if($_POST['deptorder']=='ASC') echo " selected";
          echo ">Alphabetical order</option>
          <option value=\"DESC\"";
          if($_POST['deptorder']=='DESC') echo " selected";
          echo ">Reverse alphabetical order</option>
        </select>
        Chronological order:
        <select name=\"timeorder\">
          <option value=\"DESC\"";
          if($_POST['timeorder']=='DESC') echo " selected";
          echo ">From most recent</option>
          <option value=\"ASC\"";
          if($_POST['timeorder']=='ASC') echo " selected";
          echo ">From earliest</option>
        </select>
        <input type=\"submit\" value=\"Go\">
      </form>";
      $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
      if(isset($_POST['sortedby']))
      {
        if($_POST['sortedby']=='department')
        {
          $sql = pg_query($conn, "SELECT * FROM course_offering ORDER BY dept_id ".$_POST['deptorder'].", academic_year ".$_POST['timeorder'].", semester ".$_POST['timeorder'])or die("Query failed with error: ".pg_last_error($conn));
        }
        else if($_POST['sortedby']=='time')
        {
          $sql = pg_query($conn, "SELECT * FROM course_offering ORDER BY academic_year ".$_POST['timeorder'].", semester ".$_POST['timeorder'].", dept_id ".$_POST['deptorder'])or die("Query failed with error: ".pg_last_error($conn));
        }
      }
      else
      {
        $sql = pg_query($conn, "SELECT * FROM course_offering ORDER BY dept_id ASC, academic_year DESC, semester DESC")or die("Query failed with error: ".pg_last_error($conn));
      }
      while($row = pg_fetch_array($sql))
      {
        $sql2 = pg_query($conn, "SELECT * FROM department WHERE id_no=".$row['dept_id']."")or die("Query failed with error: ".pg_last_error($conn));
        $row2 = pg_fetch_array($sql2);
        $dept = $row2['name'];
        $sql2 = pg_query($conn, "SELECT * FROM college WHERE id_no=".$row2['college_id']."")or die("Query failed with error: ".pg_last_error($conn));
        $row2 = pg_fetch_array($sql2);
        $college = $row2['name'];
        echo "<strong>".strtoupper($college)."<br>".strtoupper($dept)."</strong>";
        echo "
          <table class=\"courseoffering\">
            <tr>
              <td colspan=\"6\"><strong>Course Offering for ".$row['semester'].", A.Y. ".$row['academic_year']."</strong></td>
              <td><strong>Faculty-in-charge</strong></td>
              <td><strong>Actual size</strong></td>
            </tr>";
        $sql2 = pg_query($conn, "SELECT DISTINCT course_id FROM lec_section")or die("Query failed with error: ".pg_last_error($conn));
        while($row2 = pg_fetch_array($sql2))
        {
          $sql3 = pg_query($conn, "SELECT * FROM course WHERE course_id='".$row2['course_id']."'")or die("Query failed with error: ".pg_last_error($conn));
          $row3 = pg_fetch_array($sql3);
          echo "
            <tr>
              <td><strong>".$row3['course_code']."</strong></td>
              <td colspan=\"7\"><strong>".$row3['course_title']." (".$row3['units'].")</strong></td>
            </tr>
            <tr>
              <td><strong>".$row3['sem']."</strong></td>
              <td colspan=\"7\"><strong>PR: ".$row3['prerequisites']."</strong></td>
            </tr>
          ";
          $sql3 = pg_query($conn, "SELECT * FROM lec_section WHERE course_id='".$row2['course_id']."' AND co_id='".$row['id_no']."' ORDER by section_name ASC")or die("Query failed with error: ".pg_last_error($conn));
          while($row3 = pg_fetch_array($sql3))
          {
            $sql4 = pg_query($conn, "SELECT * FROM faculty WHERE id_no='".$row3['faculty_id']."'")or die("Query failed with error: ".pg_last_error($conn));
            $row4 = pg_fetch_array($sql4);
            echo "
              <tr>
                <td></td>
                <td>Lec</td>
                <td>".$row3['section_name']."</td>
                <td>".$row3['time']."</td>
                <td>".$row3['day']."</td>
                <td>".$row3['room']."</td>
                <td>".$row4['last_name']."</td>
                <td>".$row3['class_size']."</td>
              </tr>
            ";
            $sql4 = pg_query($conn, "SELECT * FROM lab_section WHERE lecture_id='".$row3['id_no']."' ORDER by section_no ASC")or die("Query failed with error: ".pg_last_error($conn));
            $firstlab = true;
            while($row4 = pg_fetch_array($sql4))
            {
              $sql5 = pg_query($conn, "SELECT * FROM faculty WHERE id_no='".$row4['faculty_id']."'")or die("Query failed with error: ".pg_last_error($conn));
              $row5 = pg_fetch_array($sql5);
              echo "
                <tr>
                  <td></td>
                  <td>";
              if($firstlab) echo "Lab";
              echo "
                  </td>
                  <td>".$row3['section_name']."-".$row4['section_no']."</td>
                  <td>".$row4['time']."</td>
                  <td>".$row4['day']."</td>
                  <td>".$row4['room']."</td>
                  <td>".$row5['last_name']."</td>
                  <td>".$row4['class_size']."</td>
                </tr>
              ";
              $firstlab = false;
            }
            echo "<tr><td colspan=\"\"></td></tr>";
          }
        }
        echo "</table>";
      }
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