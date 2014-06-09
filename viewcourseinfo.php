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
  if(isset($_GET['edited'])) echo "<div class=\"notif\">User successfully edited.</div>";
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
  <div id="content2">
    <h2>Edit an existing course record.</h2>
    <?php
      $_SESSION['s_course_id'] = $_GET['id'];
      function checkIfSubstring($str,$num)
      {
        for($i=0; $i<strlen($str); $i++)
        {
          if($str[$i]==$num) return "checked";
        }
      }
      $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
      $sql = pg_query($conn, "SELECT * FROM course where course_id = '".$_GET['id']."'")or die("Query failed with error: ".pg_last_error($conn));
      $row = pg_fetch_array($sql);
      echo "
      <form action=\"editcourse.php\" name=\"createcourse\" method=\"post\" onSubmit=\"return validatecourseinfo();\" autocomplete=\"off\">
        <table>
          <tr>
            <td class=\"fieldlabel\">Course Code: *</td>
            <td><input type=\"text\" name=\"course_code\" id=\"course_code\" value=\"".$row['course_code']."\"></td>
          </tr>
          <tr>
            <td class=\"fieldlabel\">Course Title: *</td>
            <td><input type=\"text\" name=\"course_title\" id=\"course_title\" value=\"".$row['course_title']."\"></td>
          </tr>
          <tr>
            <td class=\"fieldlabel\">Units: *</td>
            <td><input type=\"text\" name=\"units\" id=\"units\" value=\"".$row['units']."\"></td>
          </tr>
          <tr>
            <td class=\"fieldlabel\">Course Description: *</td>
            <td><textarea name=\"course_description\" id=\"course_description\">".$row['course_description']."</textarea></td>
          </tr>
          <tr>
            <td class=\"fieldlabel\">Semester/Offered: *</td>
            <td>
              <input type=\"checkbox\" name=\"sem[]\" value=\"1\" ".checkIfSubstring($row['sem'],'1').">1st Semester</option>
              <input type=\"checkbox\" name=\"sem[]\" value=\"2\" ".checkIfSubstring($row['sem'],'2').">2nd Semester</option>
              <input type=\"checkbox\" name=\"sem[]\" value=\"S\" ".checkIfSubstring($row['sem'],'S').">Summer</option>
            </td>
          </tr>
          <tr>
            <td class=\"fieldlabel\">Prerequisites: *</td>
            <td><input type=\"text\" name=\"prerequisites\" value=\"".$row['prerequisites']."\"></td>
          </tr>
          <tr>
            <td></td>
            <td><input type=\"submit\" value=\"Edit record\"></td>
          </tr>
        </table>
        * - Required field
      </form>";
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