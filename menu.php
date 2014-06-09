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
    <h2>Choose an action:</h2>
    <h3>Department overview</h3>
    <a href="overview.php">Department overview</a><br>
    <h3>Manage colleges</h3>
    <a href="createcollege.php">Create and add a college</a><br>
    <a href="uploadcollege.php">Upload colleges from CSV file</a><br>
    <a href="viewcolleges.php">View all colleges</a><br>
    <h3>Manage departments</h3>
    <a href="createdepartment.php">Create and add a department</a><br>
    <a href="uploaddepartment.php">Upload departments from CSV file</a><br>
    <a href="viewdepartments.php">View all departments</a><br>
    <h3>Manage faculty members</h3>
    <a href="createuser.php">Create and add a faculty member</a><br>
    <a href="uploaduser.php">Upload faculty members from CSV file</a><br>
    <a href="viewusers.php">View all faculty members</a><br>
    <h3>Manage students</h3>
    <a href="createstudent.php">Create and add a student</a><br>
    <a href="uploadstudent.php">Upload students from CSV file</a><br>
    <a href="viewstudents.php">View all students</a><br>
    <h3>Manage courses</h3>
    <a href="createcourse.php">Create and add a course</a><br>
    <a href="uploadcourse.php">Upload courses from CSV file</a><br>
    <a href="viewcourses.php">View all courses</a><br>
    <h3>Manage course offerings</h3>
    <a href="uploadcourseoffering.php">Upload course offering from CSV file</a><br>
    <a href="viewcourseofferings.php">View all course offerings</a><br>
    <a href="viewworkload.php">View workload</a><br>
    <h3>Manage classlists</h3>
    <a href="uploadclasslist.php">Upload classlist from CSV file</a><br>
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