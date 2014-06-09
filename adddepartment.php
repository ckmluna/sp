<?php
  session_start();
  $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
  $sql = pg_query($conn, "SELECT COUNT(*) FROM department WHERE name = '".$_POST['name']."'")or die("Query failed with error: ".pg_last_error($conn));
  $count = pg_fetch_row($sql);
  if($count[0]==1) header('Location: createdepartment.php?invalid');
  else
  {
    $query = "INSERT INTO department (name, college_id) VALUES ('".$_POST['name']."','".$_POST['college_id']."')";
    $result = pg_query($conn,$query);
    header('Location: createdepartment.php?added');
  }
?>