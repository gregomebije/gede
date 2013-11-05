<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once 'excel_reader2.php';
$excel = new Spreadsheet_Excel_Reader("Gridxls_DHIS_ART.xls");


// attempt a connection
try {
   $pdo = new PDO('mysql:dbname=gede;host=localhost', 'gede', 'password');
} catch (PDOException $e) {
   die("ERROR: Could not connect: " . $e->getMessage());
}

// iterate over spreadsheet rows and columns
// convert into INSERT query
$sql = "INSERT INTO dhis_art (coverage_and_usage, indicator, 
  males, D, E, females, G, H, grand_total, J, K, 
  source_of_information) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
if ($stmt = $pdo->prepare($sql)) {
  $x=8;
  while($x<=$excel->sheets[0]['numRows']) {
    $stmt->bindParam(1, $excel->sheets[0]['cells'][$x][1]);
    $stmt->bindParam(2, $excel->sheets[0]['cells'][$x][2]);
    $stmt->bindParam(3, $excel->sheets[0]['cells'][$x][3]);
    $stmt->bindParam(4, $excel->sheets[0]['cells'][$x][4]);
    $stmt->bindParam(5, $excel->sheets[0]['cells'][$x][5]);
    $stmt->bindParam(6, $excel->sheets[0]['cells'][$x][6]);
    $stmt->bindParam(7, $excel->sheets[0]['cells'][$x][7]);
    $stmt->bindParam(8, $excel->sheets[0]['cells'][$x][8]);
    $stmt->bindParam(9, $excel->sheets[0]['cells'][$x][9]);
    $stmt->bindParam(10, $excel->sheets[0]['cells'][$x][10]);
    $stmt->bindParam(11, $excel->sheets[0]['cells'][$x][11]);
    $stmt->bindParam(12, $excel->sheets[0]['cells'][$x][12]);
    if (!$stmt->execute()) {
      echo "ERROR: Could not execute query: $sql. " . print_r($pdo->errorInfo());
    }  
    $x++;
  }
} else {
  echo "ERROR: Could not prepare query: $sql. " . print_r($pdo->errorInfo());
}

// close connection
unset($pdo);

echo "Database updated";
?>

