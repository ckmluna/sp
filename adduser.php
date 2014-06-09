<?php
  session_start();
  $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
  $sql = pg_query($conn, "SELECT COUNT(*) FROM faculty WHERE username = '".$_POST['username']."'")or die("Query failed with error: ".pg_last_error($conn));
  $count = pg_fetch_row($sql);
  $sql = pg_query($conn, "SELECT COUNT(*) FROM faculty WHERE emp_no = '".$_POST['emp_no']."'")or die("Query failed with error: ".pg_last_error($conn));
  $count2 = pg_fetch_row($sql);
  if($count[0]==1 || $count2[0]==1) header('Location: createuser.php?invalid');
  else
  {
    $query = "INSERT INTO faculty (emp_no, username, password, first_name, middle_name, last_name, dept_id, rank) VALUES ('".$_POST['emp_no']."','".$_POST['username']."','".$_POST['password']."','".$_POST['first_name']."','".$_POST['middle_name']."','".$_POST['last_name']."','".$_POST['dept_id']."','".$_POST['rank']."','".$_POST['status']."')";
    $result = pg_query($conn,$query);
    header('Location: createuser.php?added');
  }
?>