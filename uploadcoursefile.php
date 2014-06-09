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
      move_uploaded_file($_FILES["file"]["tmp_name"],"upload/course.txt");
      if (($handle = fopen("upload/course.txt", "r")) !== FALSE)
      {
        $count = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
        {
          $course_code = $data[0];
          $course_title = $data[1];
          $units = $data[2];
          $data = fgets($handle, 1000);
          $course_description = $data;
          $data = fgets($handle, 1000);
          $sem = $data;
          $data = fgets($handle, 1000);
          $prerequisites = $data;
          $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
          $query = "INSERT INTO course (course_code, course_title, units, course_description, sem, prerequisites) VALUES ('".$course_code."', '".$course_title."', '".$units."', '".$course_description."', '".$sem."', '".$prerequisites."')";
          $result = pg_query($conn,$query);
          $count++;
        }
        $_SESSION['uploadedcount'] = $count;
        fclose($handle);
        header('Location: uploadcourse.php?uploaded');
      }
    }
  }
  else
  {
    header('Location: uploadcourse.php?invalid');
  }
?>