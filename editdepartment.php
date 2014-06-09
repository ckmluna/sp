<?php
  session_start();
  $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
  $query = pg_query($conn, "UPDATE department SET name='".$_POST['name']."', college_id='".$_POST['college_id']."' WHERE id_no = '".$_SESSION['s_id_no']."'")or die("Query failed with error: ".pg_last_error($conn));
  $result = pg_query($conn,$query);
  header("Location: viewdepartmentinfo.php?id=".$_SESSION['s_id_no']."&edited");
?>