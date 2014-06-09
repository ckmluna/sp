<?php
  session_start();
  $sem="";
  foreach($_POST['sem'] as $temp)
   $sem.=$temp.",";
  $sem = substr($sem, 0, strlen($str)-1);
  $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
  $query = pg_query($conn, "UPDATE course SET course_code='".$_POST['course_code']."', course_title='".$_POST['course_title']."', units='".$_POST['units']."', course_description='".$_POST['course_description']."', sem='".$sem."', prerequisites='".$_POST['prerequisites']."' WHERE course_id = '".$_SESSION['s_course_id']."'")or die("Query failed with error: ".pg_last_error($conn));
  $result = pg_query($conn,$query);
  header("Location: viewcourseinfo.php?id=".$_SESSION['s_course_id']."&edited");
?>