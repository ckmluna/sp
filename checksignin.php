<?php
  $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
  $sql = pg_query($conn, "SELECT COUNT(*) FROM ovci WHERE username = '".$_POST['username']."' AND password = '".$_POST['password']."'")or die("Query failed with error: ".pg_last_error($conn));
  $count = pg_fetch_row($sql);
  if($count[0]==1)
  {
    $sql = pg_query($conn, "SELECT * FROM ovci WHERE username = '".$_POST['username']."' AND password = '".$_POST['password']."'")or die("Query failed with error: ".pg_last_error($conn));
    $row = pg_fetch_array($sql);
    session_start();
    $_SESSION['id_no'] = $row['id_no'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['password'] = $row['password'];
    $_SESSION['first_name'] = $row['first_name'];
    $_SESSION['middle_name'] = $row['middle_name'];
    $_SESSION['last_name'] = $row['last_name'];
    header('Location: menu.php');
  }
  else header('Location: signin.php?invalid');
?>