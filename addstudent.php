<?php
  session_start();
  $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
  $sql = pg_query($conn, "SELECT COUNT(*) FROM student WHERE student_no = '".$_POST['student_no']."'")or die("Query failed with error: ".pg_last_error($conn));
  $count = pg_fetch_row($sql);
  if($count[0]==1 || $count2[0]==1) header('Location: createstudent.php?invalidsn');
  else
  {
    $query = "INSERT INTO student (student_no, first_name, middle_name, last_name, college_id, degree) VALUES ('".$_POST['student_no']."','".$_POST['first_name']."','".$_POST['middle_name']."','".$_POST['last_name']."','".$_POST['college_id']."','".$_POST['degree']."')";
    $result = pg_query($conn,$query);
    header('Location: createstudent.php?added');
  }
?>