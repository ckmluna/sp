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
  <script type="text/javascript" src="tooltip.js"></script>
  <link rel="stylesheet" type="text/css" href="tooltip.css">
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
  <div id="content2">
    <h2>Department overview</h2>
    <?php
      function draw($_graphfilename)
      {
        // This is the filename of this HTML file
        global $_wrapperfilename;
        $_wrapperfilename = basename (__FILE__);
        
        // Create a random mapname used to connect the image map with the image
        $_mapname = '__mapname'.rand(0,1000000).'__';
         
        // This is the actual graph script
        require_once ($_graphfilename);
         
        // Get hold of the image map to include it in the HTML page
        $imgmap = $graph->GetHTMLImageMap($_mapname);
        echo $imgmap;

        // We now create the <img> tag
        $imgtag = $graph->GetCSIMImgHTML($_mapname,$_graphfilename);
        echo $imgtag;
      }
      
      echo "
      <form action=\"#\" method=\"post\">
        <select name=\"metric\">
          <option>[Select a metric]</option>
          //faculty rank
          <option value=\"rank\"";
          if($_POST['metric']=='rank') echo " selected";
          echo ">Faculty rank</option>
          //faculty status
          <option value=\"status\"";
          if($_POST['metric']=='status') echo " selected";
          echo ">Faculty status</option>
        </select>
        <select name=\"displayby\">
          <option>[Display by]</option>
          //department
          <option value=\"department\"";
          if($_POST['displayby']=='department') echo " selected";
          echo ">Department</option>
          //semester
          <option value=\"semester\"";
          if($_POST['displayby']=='semester') echo " selected";
          echo ">Semester</option>
        </select>";
      $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
      if ($_POST['displayby']=='department')
      {
        echo "
        <br>
        <select name=\"sem\">
          <option>[Semester]</option>
          <option value=\"1st Semester\""; if($_POST['sem']=='1st Semester') echo " selected"; echo ">1st Semester</option>
          <option value=\"2nd Semester\""; if($_POST['sem']=='2nd Semester') echo " selected"; echo ">2nd Semester</option>
          <option value=\"Summer\""; if($_POST['sem']=='Summer') echo " selected"; echo ">Summer</option>
        </select>
        <select name=\"year\">
          <option>[Academic Year]</option>";
        $sql = pg_query($conn, "SELECT DISTINCT academic_year FROM course_offering")or die("Query failed with error: ".pg_last_error($conn));
        while ($row = pg_fetch_array($sql))
        {
          echo "<option value=\"".$row['academic_year']."\""; if($_POST['year']==$row['academic_year']) echo " selected"; echo ">".$row['academic_year']."</option>";
        }
        echo "
        </select><br>
        <select name=\"dept1\" id=\"dept1\">
          <option>[Select college]</option>
          <option value=\"All\" ";
        if($_POST['dept1']=="All") echo "selected";
        echo ">All</option>";
        $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
        $sql = pg_query($conn, "SELECT * FROM college")or die("Query failed with error: ".pg_last_error($conn));
        while($row2 = pg_fetch_array($sql))
        {
          echo "<optgroup label=\"".$row2['name']."\">";
          $sql2 = pg_query($conn, "SELECT * FROM department where college_id = '".$row2['id_no']."'")or die("Query failed with error: ".pg_last_error($conn));
          while($row2 = pg_fetch_array($sql2))
          {
            echo "<option value=\"".$row2['id_no']."\" ";
            if($_POST['dept1']==$row2['id_no']) echo "selected";
            echo ">".$row2['name']."</option>";
          }
        }
        echo "
          </optgroup>
        </select>
        vs.
        <select name=\"dept2\" id=\"dept2\">
          <option>[Select college]</option>
          <option value=\"All\" ";
        if($_POST['dept2']=="All") echo "selected";
        echo ">All</option>";
        $sql = pg_query($conn, "SELECT * FROM college")or die("Query failed with error: ".pg_last_error($conn));
        while($row2 = pg_fetch_array($sql))
        {
          echo "<optgroup label=\"".$row2['name']."\">";
          $sql2 = pg_query($conn, "SELECT * FROM department where college_id = '".$row2['id_no']."'")or die("Query failed with error: ".pg_last_error($conn));
          while($row2 = pg_fetch_array($sql2))
          {
            echo "<option value=\"".$row2['id_no']."\" ";
            if($_POST['dept2']==$row2['id_no']) echo "selected";
            echo ">".$row2['name']."</option>";
          }
        }
        echo "
          </optgroup>
        </select>";
      }
      else if ($_POST['displayby']=='semester')
      {
        echo "
        <br>
        <select name=\"dept\" id=\"dept\">
          <option>[Select college]</option>
          <option value=\"All\">All</option>";
        $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
        $sql = pg_query($conn, "SELECT * FROM college")or die("Query failed with error: ".pg_last_error($conn));
        while($row2 = pg_fetch_array($sql))
        {
          echo "<optgroup label=\"".$row2['name']."\">";
          $sql2 = pg_query($conn, "SELECT * FROM department where college_id = '".$row2['id_no']."'")or die("Query failed with error: ".pg_last_error($conn));
          while($row2 = pg_fetch_array($sql2))
          {
            echo "<option value=\"".$row2['id_no']."\" ";
            if($_POST['dept']==$row2['id_no']) echo "selected";
            echo ">".$row2['name']."</option>";
          }
        }
        echo "
          </optgroup>
        </select><br>
        <select name=\"sem1\">
          <option>[Semester]</option>
          <option value=\"1st Semester\""; if($_POST['sem1']=='1st Semester') echo " selected"; echo ">1st Semester</option>
          <option value=\"2nd Semester\""; if($_POST['sem1']=='2nd Semester') echo " selected"; echo ">2nd Semester</option>
          <option value=\"Summer\""; if($_POST['sem1']=='Summer') echo " selected"; echo ">Summer</option>
        </select>
        <select name=\"year1\">
          <option>[Academic Year]</option>";
        $sql = pg_query($conn, "SELECT DISTINCT academic_year FROM course_offering")or die("Query failed with error: ".pg_last_error($conn));
        while ($row = pg_fetch_array($sql))
        {
          echo "<option value=\"".$row['academic_year']."\""; if($_POST['year1']==$row['academic_year']) echo " selected"; echo ">".$row['academic_year']."</option>";
        }
        echo "
        </select>
        vs.
        <select name=\"sem2\">
          <option>[Semester]</option>
          <option value=\"1st Semester\""; if($_POST['sem2']=='1st Semester') echo " selected"; echo ">1st Semester</option>
          <option value=\"2nd Semester\""; if($_POST['sem2']=='2nd Semester') echo " selected"; echo ">2nd Semester</option>
          <option value=\"Summer\""; if($_POST['sem2']=='Summer') echo " selected"; echo ">Summer</option>
        </select>
        <select name=\"year2\">
          <option>[Academic Year]</option>";
        $sql = pg_query($conn, "SELECT DISTINCT academic_year FROM course_offering")or die("Query failed with error: ".pg_last_error($conn));
        while ($row = pg_fetch_array($sql))
        {
          echo "<option value=\"".$row['academic_year']."\""; if($_POST['year2']==$row['academic_year']) echo " selected"; echo ">".$row['academic_year']."</option>";
        }
        echo "
        </select>";
      }
      echo "<input type=\"submit\" value=\"Go\">
      </form>";
      
      // The name of the graph script file (change as needed!)
      if(isset($_POST['metric']) && isset($_POST['displayby']))
      {
        if($_POST['displayby']=='department')
        {
          if(isset($_POST['dept1']) && isset($_POST['dept2']))
          {
            $_SESSION['dept1'] = $_POST['dept1'];
            $_SESSION['dept2'] = $_POST['dept2'];
            if($_POST['metric']=='rank')
              draw('pierank.php');
            else if($_POST['metric']=='status')
              draw('piestatus.php');
          }
        }
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