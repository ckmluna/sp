<?php
  session_start();
  $allowedExts = array("txt");
  $temp = explode(".", $_FILES["file"]["name"]);
  $extension = end($temp);
  if ($_FILES["file"]["type"] == "text/plain" && in_array($extension, $allowedExts))
  {
    if ($_FILES["file"]["error"] > 0)
    {
      echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }
    else
    {
      move_uploaded_file($_FILES["file"]["tmp_name"],"upload/department.txt");
      if (($handle = fopen("upload/department.txt", "r")) !== FALSE)
      {
        $count = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
        {
          $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
          $query = "INSERT INTO department (name, college_id) VALUES ('".$data[0]."','".$data[1]."')";
          $result = pg_query($conn,$query);
          $count++;
        }
        $_SESSION['uploadedcount'] = $count;
        fclose($handle);
        header('Location: uploaddepartment.php?uploaded');
      }
    }
  }
  else
  {
    header('Location: uploaddepartment.php?invalid');
  }
?>