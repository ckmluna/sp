<?php
  session_start();
  $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
  $query = pg_query($conn, "UPDATE student SET student_no='".$_POST['student_no']."', first_name='".$_POST['first_name']."', middle_name='".$_POST['middle_name']."', last_name='".$_POST['last_name']."', college_id='".$_POST['college_id']."', degree='".$_POST['degree']."' WHERE id_no = '".$_SESSION['s_id_no']."'")or die("Query failed with error: ".pg_last_error($conn));
  $result = pg_query($conn,$query);
  header("Location: viewstudentinfo.php?id=".$_SESSION['s_id_no']."&edited");
?>