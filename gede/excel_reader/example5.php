<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once 'excel_reader2.php';
$excel = new Spreadsheet_Excel_Reader("Book2.xls");

// attempt a connection
try {
   $pdo = new PDO('mysql:dbname=gede;host=localhost', 'gede', 'password');
} catch (PDOException $e) {
   die("ERROR: Could not connect: " . $e->getMessage());
}

// iterate over spreadsheet rows and columns
// convert into INSERT query
$sql = "INSERT INTO data (country, sales) VALUES (?, ?)";
if ($stmt = $pdo->prepare($sql)) {
  $x=2;
  while($x<=$excel->sheets[0]['numRows']) {
    $stmt->bindParam(1, $excel->sheets[0]['cells'][$x][1]);
    $stmt->bindParam(2, $excel->sheets[0]['cells'][$x][2]);
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

