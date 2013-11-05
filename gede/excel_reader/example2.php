<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once 'excel_reader2.php';
$excel = new Spreadsheet_Excel_Reader("Book2.xls");

// print number of rows, columns and sheets
echo "Number of sheets: " . sizeof($excel->sheets) . "<br />";

for ($x=0; $x < sizeof($excel->sheets); $x++) {
  echo "Number of rows in sheet " . ($x+1) . ": " . $excel->sheets[$x]["numRows"] . "<br/>";
  echo "Number of columns in sheet " . ($x+1) . ": " . $excel->sheets[$x]["numCols"] . "<br />";
}

?>

