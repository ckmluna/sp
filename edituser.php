<?php
  session_start();
  $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
  $query = pg_query($conn, "UPDATE faculty SET emp_no='".$_POST['emp_no']."', username='".$_POST['username']."', password='".$_POST['password']."', first_name='".$_POST['first_name']."', middle_name='".$_POST['middle_name']."', last_name='".$_POST['last_name']."', dept_id='".$_POST['dept_id']."', rank='".$_POST['rank']."', status='".$_POST['status']."' WHERE id_no = '".$_SESSION['s_id_no']."'")or die("Query failed with error: ".pg_last_error($conn));
  $result = pg_query($conn,$query);
  header("Location: viewuserinfo.php?id=".$_SESSION['s_id_no']."&edited");
?>