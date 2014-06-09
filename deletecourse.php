<?php
  session_start();
  $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
  $query = "DELETE FROM course WHERE course_id = '".$_GET['id']."'";
  $result = pg_query($conn,$query)or die("Query failed with error: ".pg_last_error($conn));
  header('Location: viewcourses.php?deleted');
?>