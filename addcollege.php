<?php
  session_start();
  $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
  $sql = pg_query($conn, "SELECT COUNT(*) FROM college WHERE name = '".$_POST['name']."'")or die("Query failed with error: ".pg_last_error($conn));
  $count = pg_fetch_row($sql);
  if($count[0]==1) header('Location: createcollege.php?invalid');
  else
  {
    $query = "INSERT INTO college (name) VALUES ('".$_POST['name']."')";
    $result = pg_query($conn,$query);
    header('Location: createcollege.php?added');
  }
?>