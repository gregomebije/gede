<!--Interestingly, if your spreadsheet cells contain formulae, 
 PHP-ExcelReader will return the final result of the formulae 
 as the corresponding cell value (instead of the raw formula string). 
 However, when it comes to cell formatting, 
 PHP-ExcelReader returns the raw value of each cell, rather than the formatted cell value.
-->
<html>
  <head>
    <style type="text/css">
    table {
    	border-collapse: collapse;
    }        
    td {
    	border: 1px solid black;
    	padding: 0 0.5em;
    }        
    </style>
  </head>
  <body>
    <table>
	<?php
	error_reporting(E_ALL ^ E_NOTICE);
	require_once 'excel_reader2.php';
	//$excel = new Spreadsheet_Excel_Reader("Book3.xls");
    
    // initialize reader object
    $excel = new Spreadsheet_Excel_Reader();
    
    // read spreadsheet data
    $excel->read('Book3.xls');    
    
    // iterate over spreadsheet cells and print as HTML table
    $x = 1;
    while($x <= $excel->sheets[0]['numRows']) {
      echo "\t<tr>\n";
      $y = 1;
      while($y <= $excel->sheets[0]['numCols']) {
        $cell = isset($excel->sheets[0]['cells'][$x][$y]) ? $excel->sheets[0]['cells'][$x][$y] : '';
        echo "\t\t<td>$cell</td>\n";  
        $y++;
      }  
      echo "\t</tr>\n";
      $x++;
    }
    ?>    
    </table>
  </body>
</html>