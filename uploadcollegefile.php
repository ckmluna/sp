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
      move_uploaded_file($_FILES["file"]["tmp_name"],"upload/college.txt");
      if (($handle = fopen("upload/college.txt", "r")) !== FALSE)
      {
        $count = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
        {
          $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
          $query = "INSERT INTO college (name) VALUES ('".$data[0]."')";
          $result = pg_query($conn,$query);
          $count++;
        }
        $_SESSION['uploadedcount'] = $count;
        fclose($handle);
        header('Location: uploadcollege.php?uploaded');
      }
    }
  }
  else
  {
    header('Location: uploadcollege.php?invalid');
  }
?>