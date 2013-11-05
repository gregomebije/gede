<?php
  /*
  * Script:    DataTables server-side script for PHP and MySQL
  * Copyright: 2010 - Allan Jardine
  * License:   GPL v2 or BSD (3-point)
  * Modified by: Greg Omebije
  */
	
  /*  Easy set variables*/
  /* Array of database columns which should be read and sent back to 
    DataTables. Use a space where
   * you want to insert a non-database field 
  (for example a counter or static image)
  */
  //$aColumns = array( 'engine', 'browser', 'platform', 'version', 'grade' );
  $aColumns = array();
  $bColumns = array();
  $cColumns = array();
  $dColumns = array();
	
  /* Indexed column (used for fast and accurate table cardinality) */
    $sIndexColumn = "id";
	
  /* DB table to use */
  $sTable = $_REQUEST['table'];
	
  /* Database connection information */
  $gaSql['user']       = "gede";
  $gaSql['password']   = "password";
  $gaSql['db']         = "gede";
  $gaSql['server']     = "localhost";
	
	
  /* * * * * * * * * * * * * * * * * * * * * * * * * 
   * * * * * * * * * * * * * * * * * * * * * *
   * If you just want to use the basic configuration for DataTables with 
   * PHP server-side, there is
   * no need to edit below this line
  */
	
  /* 
  * MySQL connection
  */
  $gaSql['link'] =  mysql_pconnect( $gaSql['server'], 
     $gaSql['user'], $gaSql['password']  ) or
     die( 'Could not open connection to server' );
	
  mysql_select_db( $gaSql['db'], $gaSql['link'] ) or 
    die( 'Could not select database '. $gaSql['db'] );
	
  $skip = array();
  $sql="describe {$sTable}";
  $result = mysql_query($sql) or die(mysql_error());
  while($field = mysql_fetch_array($result)) {
    if (in_array($field[0], $skip))
      continue;
    $aColumns[] = $field[0];
    $dColumns[] = "{$sTable}.{$field[0]}";

    if (strpos($field[0], "_id") !== false) { //Found a key 
      $t = substr($field[0], 0, stripos($field[0], "_id"));

      $temp = array();
      $sql = "describe $t";
      $result_k = mysql_query($sql) or die(mysql_error());
      while($row_k = mysql_fetch_array($result_k))
        $temp[] = $row_k[0];
      
      $bColumns[$t] = array("{$sTable}.{$field[0]} = {$t}.id", $temp[1]);
      $cColumns[] = $t;
    }
    
  }
	
  /* 
   * Paging
   */
  $sLimit = "";
   if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
   {
     $sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
  	mysql_real_escape_string( $_GET['iDisplayLength'] );
   }
	
   /*
   * Ordering
   */
   if ( isset( $_GET['iSortCol_0'] ) )
   {
     $sOrder = "ORDER BY  ";
     for ( $i=0 ; $i < intval( $_GET['iSortingCols'] ) ; $i++ )
     {
       if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
       {
         $sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
	  ".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
       }
     }
     $sOrder = substr_replace( $sOrder, "", -2 );
     if ( $sOrder == "ORDER BY" )
     {
       $sOrder = "";
     }
   }
	
   /* 
    * Filtering
    * NOTE this does not match the built-in DataTables filtering which does it
    * word by word on any field. It's possible to do here, 
    * but concerned about efficiency
    * on very large tables, and MySQL's regex functionality is very limited
    */
   $sWhere = "";
   $sWhere2 = "";

   if ( $_GET['sSearch'] != "" )
   {
     $sWhere = "WHERE (";
     $sWhere2 = "WHERE (";

     for ( $i=0 ; $i < count($aColumns) ; $i++ )
     {
       if ($aColumns == 'id')
         continue;
       else if (strpos($aColumns[$i], "_id") !== false) { 
         $t = substr($aColumns[$i], 0, stripos($aColumns[$i], "_id"));
         $sWhere2 .= "{$t}.{$bColumns[$t][1]} LIKE '%"
            . mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
       } else {
         $sWhere .= $aColumns[$i]." LIKE '%" 
          . mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";

         $sWhere2 .= "{$sTable}.{$aColumns[$i]} LIKE '%" 
          . mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
       } 
     }
     $sWhere = substr_replace( $sWhere, "", -3 );
     $sWhere .= ')';

     $sWhere2 = substr_replace( $sWhere2, "", -3 );
     $sWhere2 .= ')';
     
   }
	
   /* Individual column filtering */
   for ( $i=0 ; $i<count($aColumns) ; $i++ )
   {
     if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
     {
       if ( $sWhere == "" )
       {
         $sWhere = "WHERE ";
       }
       else
       {
         $sWhere .= " AND ";
       }
       $sWhere .= $aColumns[$i]." LIKE '%" 
        . mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
     }
   }
	
	
   /*
    * SQL queries
    * Get data to display
   */
   //$sQuery = "select SQL_CALC_FOUND_ROWS ";
   
   $sQuery = "
     SELECT SQL_CALC_FOUND_ROWS " 
     . str_replace(" , ", " ", implode(", ", $aColumns))."
	FROM   $sTable
      	       $sWhere
	       $sOrder
	       $sLimit
	";

   $sQuery2 = "
     SELECT SQL_CALC_FOUND_ROWS "
     . str_replace(" , ", " ", implode(", ", $dColumns))."
	FROM   $sTable ";


   if (count($cColumns) > 0) {
     $sQuery2 .= "
      	JOIN  (" 
        . str_replace(" , ", " ", implode(", ", $cColumns))."
        ) on (  ";

     $i = 0;
     foreach ($bColumns as $x => $y) {
       $sQuery2 .= "{$y[0]} ";
       $i = $i + 1;
       if (count($bColumns) > $i)
         $sQuery2 .= " and ";
     }

     $sQuery2 .= ") $sWhere2
               $sOrder
	       $sLimit
       ";
   } else {
     $sQuery2 .= "$sWhere
	       $sOrder
	       $sLimit";
   }
   //echo "{$sQuery}<br>";
   //echo "{$sQuery2} <br>";

   $rResult = mysql_query( $sQuery2, $gaSql['link'] ) or die(mysql_error());
	
   /* Data set length after filtering */
   $sQuery = "
	SELECT FOUND_ROWS()
   ";
   $rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) 
     or die(mysql_error());

   $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
   $iFilteredTotal = $aResultFilterTotal[0];
	
   /* Total data set length */
   $sQuery = "
     SELECT COUNT(".$sIndexColumn.")
	FROM   $sTable
   ";
   $rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
   $aResultTotal = mysql_fetch_array($rResultTotal);
   $iTotal = $aResultTotal[0];
	
	
   /*
    * Output
   */
   $output = array(
     "sEcho" => intval($_GET['sEcho']),
     "iTotalRecords" => $iTotal,
     "iTotalDisplayRecords" => $iFilteredTotal,
      "aaData" => array()
   );
   while ( $aRow = mysql_fetch_array( $rResult ) ) 
   {
     $row = array();
     for ( $i=0 ; $i<count($aColumns) ; $i++ )
     {
       if ( $aColumns[$i] == "version" )
       {
         /* Special output formatting for 'version' column */
        $row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
       }
       else if (strpos($aColumns[$i], "_id") !== false) { //Automatic join
         $t = substr($aColumns[$i], 0, stripos($aColumns[$i], "_id"));
		 
         //Ignore any empty key or null keys
	 if (strlen($aRow[$aColumns[$i]]) == 0) 
           $row[] = "";
	 else {
           //echo "<h1>" . strlen($aRow[$aColumns[$i]]) . "</h1>";
	   $sql = "select * from $t where id={$aRow[$aColumns[$i]]} 
             order by id";
           //echo "{$sql}<br/>";
	   $result_join = mysql_query($sql) or die(mysql_error()); 
	   $row_join = mysql_fetch_array($result_join);
	   //$row[] = $row_join[1] . " | " . $row_join[2];  

	   $row[] = $row_join[1];
	 }
       }
       else if ( $aColumns[$i] != ' ' )
       {
	/* General output */
        if (($aRow[$aColumns[$i]] == null) || ($aRow[$aColumns[$i]] == ''))
          $row[] = "";
	else
          $row[] = $aRow[ $aColumns[$i] ];
       }
     }
     if ($sTable == 'gdr') {
       $sql = "select * from registration where 
        gdr_id = {$aRow[$aColumns[1]]} and 
        (date_of_registration between '".date("Y"). "-01-01' and '" 
           . date("Y")."-12-31')";
       $rr = mysql_query($sql) or die(mysql_error());
       if (mysql_num_rows($rr) > 0) 
         $row[] = 'registered'; 
       else
         $row[] = 'unregistered';
     }

     $output['aaData'][] = $row;
   }
	
   echo json_encode( $output );
?>
