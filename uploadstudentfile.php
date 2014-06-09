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
      move_uploaded_file($_FILES["file"]["tmp_name"],"upload/student.txt");
      if (($handle = fopen("upload/student.txt", "r")) !== FALSE)
      {
        $count = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
        {
          $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
          $query = "INSERT INTO student (student_no, first_name, middle_name, last_name, college_id, degree) VALUES ('".$data[0]."','".$data[1]."','".$data[2]."','".$data[3]."','".$data[4]."','".$data[5]."')";
          $result = pg_query($conn,$query);
          $count++;
        }
        $_SESSION['uploadedcount'] = $count;
        fclose($handle);
        header('Location: uploadstudent.php?uploaded');
      }
    }
  }
  else
  {
    header('Location: uploadstudent.php?invalid');
  }
?>