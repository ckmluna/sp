<?php
  session_start();
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
<?php
  if(isset($_GET['restricted'])) echo "<div class=\"notif\" style=\"display:none;\">You do not have the proper privileges to view that page! Please sign in to continue.</div>";
  if(isset($_GET['signedout'])) echo "<div class=\"notif\" style=\"display:none;\">You have successfully signed out.</div>";
  if(isset($_GET['invalid'])) echo "<div class=\"notif\" style=\"display:none;\">Invalid username and/or password.</div>";
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
  <div id="content">
    <h2>Welcome to UPLB Unit Academic Analytics!</h2>
    <p>
    UPLB Unit Academic Analytics aims to provide the administrative staff and faculty members of a UPLB unit an application that will assist in academics management and provide analytics. Analytics is used to monitor progress on key institutional goals, such as student retention, faculty productivity, and the impact of outreach and engagement.
    </p>
  </div>
  <div id="signin">
    <h4>Sign in</h4>
    <form action="checksignin.php" method="post">
      <label for="username">Username</label><br>
      <input type="text" name="username" id="username" class="sign-field" /><br>
      <label for="password">Password</label><br>
      <input type="password" name="password" id="password" class="sign-field" /><br>
      <input type="submit" class="sign-button" value="Sign in" />
    </form>
  </div>
</div>
<br>
<div id="footer">
  &#169; 2013 UPLB
</div>
</body>
</html>