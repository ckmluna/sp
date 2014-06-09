<?php
  session_start();
  require_once ('jpgraph/src/jpgraph.php');
  require_once ('jpgraph/src/jpgraph_pie.php');
  
  // Create pie graph
  $graph = new PieGraph(800,400,'auto');
  $graph->SetFrame(false);
  $graph->SetAntiAliasing();
  
  // Set graph title
  $graph->title->Set("Display faculty members by status");
  $graph->title->SetFont(FF_ARIAL,FS_BOLD,16);
  
  //Set graph legend
  $graph->legend->Pos(0.5,0.1);
  $graph->legend->SetFont(FF_ARIAL);
  $graph->legend->SetFillColor("white@1");
  $graph->legend->SetFrameWeight(0);
  $graph->legend->SetShadow(false);
  
  $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=password")or die("Could not connect to server!\n");
  if($_SESSION['dept1']=="All")
    $sql = pg_query($conn, "SELECT DISTINCT status FROM faculty")or die("Query failed with error: ".pg_last_error($conn));
  else
    $sql = pg_query($conn, "SELECT DISTINCT status FROM faculty WHERE dept_id='".$_SESSION['dept1']."'")or die("Query failed with error: ".pg_last_error($conn));
  while ($row = pg_fetch_array($sql))
  {
    $legends[] = $row['status'];
    if($_SESSION['dept1']=="All")
      $sql2 = pg_query($conn, "SELECT COUNT(*) FROM faculty WHERE status='".$row['status']."'")or die("Query failed with error: ".pg_last_error($conn));
    else
      $sql2 = pg_query($conn, "SELECT COUNT(*) FROM faculty WHERE dept_id='".$_SESSION['dept1']."' AND status='".$row['status']."'")or die("Query failed with error: ".pg_last_error($conn));
    $count = pg_fetch_row($sql2);
    if($_SESSION['dept1']=="All")
      $sql2 = pg_query($conn, "SELECT COUNT(*) FROM faculty")or die("Query failed with error: ".pg_last_error($conn));
    else
      $sql2 = pg_query($conn, "SELECT COUNT(*) FROM faculty WHERE dept_id='".$_SESSION['dept1']."'")or die("Query failed with error: ".pg_last_error($conn));
    $count2 = pg_fetch_row($sql2);
    $values[] = $count[0];
    $targs[] = "#";
    $alts[] = $row['status']." (".$count[0]."/".$count2[0].")";
  }

  // Create pie plot
  $p1 = new PiePlot($values);
  $p1->SetCenter(0.25,0.6);
  //$p1->SetLegends($legends);
  $p1->SetColor("white");
  $p1->ExplodeAll(1);

  // Set slice labels
  $p1->SetLabelPos(0.6);
  $p1->value->SetColor("white");
  $p1->value->SetFont(FF_ARIAL,FS_BOLD);
  $p1->value->SetFormat("%.2f%%");
  
  $p1->SetCSIMTargets($targs,$alts);
  
  
  if($_SESSION['dept2']=="All")
    $sql = pg_query($conn, "SELECT DISTINCT status FROM faculty")or die("Query failed with error: ".pg_last_error($conn));
  else
    $sql = pg_query($conn, "SELECT DISTINCT status FROM faculty WHERE dept_id='".$_SESSION['dept2']."'")or die("Query failed with error: ".pg_last_error($conn));
  while ($row = pg_fetch_array($sql))
  {
    $legends2[] = $row['status'];
    if($_SESSION['dept2']=="All")
      $sql2 = pg_query($conn, "SELECT COUNT(*) FROM faculty WHERE status='".$row['status']."'")or die("Query failed with error: ".pg_last_error($conn));
    else
      $sql2 = pg_query($conn, "SELECT COUNT(*) FROM faculty WHERE dept_id='".$_SESSION['dept2']."' AND status='".$row['status']."'")or die("Query failed with error: ".pg_last_error($conn));
    $count = pg_fetch_row($sql2);
    if($_SESSION['dept2']=="All")
      $sql2 = pg_query($conn, "SELECT COUNT(*) FROM faculty")or die("Query failed with error: ".pg_last_error($conn));
    else
      $sql2 = pg_query($conn, "SELECT COUNT(*) FROM faculty WHERE dept_id='".$_SESSION['dept2']."'")or die("Query failed with error: ".pg_last_error($conn));
    $count2 = pg_fetch_row($sql2);
    $values2[] = $count[0];
    $targs2[] = "#";
    $alts2[] = $row['status']." (".$count[0]."/".$count2[0].")";
  }
  
  // Create a second pie plot
  $p2 = new PiePlot($values2);
  $p2->SetCenter(0.75,0.6);
  //$p2->SetLegends($legends2);
  $p2->SetColor("white");
  $p2->ExplodeAll(1);

  // Set slice labels
  $p2->SetLabelPos(0.6);
  $p2->value->SetColor("white");
  $p2->value->SetFont(FF_ARIAL,FS_BOLD);
  $p2->value->SetFormat("%.2f%%");
  
  $p2->SetCSIMTargets($targs2,$alts2);

  $graph->Add($p1);
  $graph->Add($p2);

  // Send back the HTML page which will call this script again
  // to retrieve the image.
  $graph->StrokeCSIM();
?>