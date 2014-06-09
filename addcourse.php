<?php
  session_start();
  $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
  $sql = pg_query($conn, "SELECT COUNT(*) FROM course WHERE course_code = '".$_POST['course_code']."'")or die("Query failed with error: ".pg_last_error($conn));
  $count = pg_fetch_row($sql);
  if($count[0]==1) header('Location: createcourse.php?invalid');
  else
  {
    $sem="";
    foreach($_POST['sem'] as $temp)
     $sem.=$temp.",";
    $sem = substr($sem, 0, strlen($str)-1);
    $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
    $query = "INSERT INTO course (course_code, units, course_description, sem, course_title, prerequisites) VALUES ('".$_POST['course_code']."','".$_POST['units']."','".$_POST['course_description']."','".$sem."','".$_POST['course_title']."','".$_POST['prerequisites']."')";
    $result = pg_query($conn,$query)or die("Query failed with error: ".pg_last_error($conn));
    header('Location: createcourse.php?added');
  }
?>