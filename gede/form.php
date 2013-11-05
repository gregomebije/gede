<?php
session_start();

if (!isset($_SESSION['uid'])) {
  header('Location: index.php');
  exit;
}
require_once "form.inc";
require_once "menu.inc";

$con = connect();

$temp = get_user_perm($_SESSION['uid'], $con);

if (!in_array(format_label2($_REQUEST['table']), $temp)) {
  main_menu($_SESSION['uid'], 
    $_SESSION['firstname'] . " " . $_SESSION['lastname'], 
    $_REQUEST['table'], false, $con);

  msg_box('Access Denied', 'index.php?action=logout', 'Logout');
  main_footer();
  exit;
}

main_menu($_SESSION['uid'], 
   $_SESSION['firstname'] . " " . $_SESSION['lastname'], 
   $_REQUEST['table'], false, $con);

?>  
<div class="container" style="margin-top: -15px;">
 <div class="page-header text-center" 
   style='margin-bottom:5px; margin-top:-10px;'>
  <h3 style='margin-bottom:-15px;'>
   <?php echo format_label3($_REQUEST['table']); ?>
 
  <script>
   function get_length() {
     return $("#iDisplayLength").val(); 
   }
   function get_start() {
     return $("#iDisplayStart").text(); 
   }
   function gen_url() {

     var url='print.php?action=Preview&table=<?php echo $_REQUEST['table'];?>&iDisplayLength=' + get_length() + '&iDisplayStart=' + get_start() + '&search=' +
   $("#search").val();
     window.open(url, "smallwin",
        "width=800,height=600,status=yes,resizable=yes,menubar=yes,toolbar=yes,scrollbars=yes");
   }
   </script>
  
  <span>
     <a
   onClick='gen_url();'>
   <img src='images/icon_printer.gif'></a>
  </span>
  </h3>
 </div>
 <div class="span6">
  <select id="iDisplayLength" name="iDisplayLength">
   <!--<option value="3" selected="selected">3</option>
   <option value="4" >4</option>-->
   <option value="5">5</option>
   <option value="10">10</option>
   <option value="25">25</option>
   <option value="50">50</option>
   <option value="100">100</option>
  </select>
 </div>
 <div class="btn-group"> 
 <?php
 $arr = array('dhis_art', 'dhis_hct', 'm_and_e_monthly_summary',
   'summary_statistics_nomis');

 if (can_create_update($_SESSION['uid'], format_label($_REQUEST['table']))) {
   if (in_array($_REQUEST['table'], $arr)) {
     $url = "<a href=\"upload.php?table={$_REQUEST['table']}\""; 
     $url .= "role=\"button\" class=\"btn\" data-toggle=\"modal\">Upload</a>";
     echo $url;
   } else {
     $url = "<a href=\"add_edit.php?action=New&table={$_REQUEST['table']}\"";
     $url .= "role=\"button\" class=\"btn\" data-toggle=\"modal\">New</a>";
     echo $url;
   }
 }
 ?> 
 </div>
	

   <form class="navbar-search pull-right">
	   Search: <input type="text" class="search-query" placeholder="Search" id="search" name="search">
	  </form>
	  
	   
       <table cellpadding="0" cellspacing="0" border="0" 
           class="table table-striped table-bordered table-hover" style='table-layout: auto;' id="example" >
         <thead>
          <tr> 

          <?php
          $skip = array('id');
          $sql="describe {$_REQUEST['table']}";
          $result = mysql_query($sql) or die(mysql_error());
          while($field = mysql_fetch_array($result)) {
            if (in_array($field[0], $skip))
              continue;
            else {
              if (strpos($field[0], "_id") !== false)
                $label = substr($field[0], 0, stripos($field[0], "_id"));
              else
                $label = $field[0];

              echo "<th width=\"" . strlen($field[0]) . "px\">"
                 . ucwords(str_replace('_', '&nbsp;', $label)) . "</th>";

                 //. format_label($field[0]) . "</th>";

            }
          }
          
          ?>
         </tr>
        </thead>
        <tbody id="example1">
		<!--
         <tr>
          <td colspan="5" class="dataTables_empty">Loading data from server</td>
         </tr>
		 -->
        </tbody>

        <script>
         table = [];
        </script>

        <tfoot>
         <tr>
         <?php
         $skip = array('id');
         $arr = array();
         $sql="describe {$_REQUEST['table']}";
         $result = mysql_query($sql) or die(mysql_error());
         while($field = mysql_fetch_array($result)) {
           if (in_array($field[0], $skip))
             continue;
           echo "<th>" . format_label($field[0]) . "</th>";
           $arr[] = "'" . $field[0] . "'";
         }
         ?>
		 </tr>
        </tfoot>
       </table>
       <?php
         $comma_separated = implode(",", $arr);
		 echo "<script>\nfunction get_table() { table =[{$comma_separated}];\n return table; } \n</script>\n"
        ?>
         
	   <span id="id"></span>
       <div class="row-fluid">
	     <p class='span23'>Showing <span id="iDisplayStart"></span> 
	     	to <span id="iDisplayEnd"></span> 
	     	of <span id="iTotalDisplayRecords"></span> entries
	     </p>

              <div class="btn-group">
               <button class="btn" id="First">First</button>
               <button class="btn" id="Prev">Prev</button>
               <button class="btn" id="Next">Next</button>
               <button class="btn" id="Last">Last</button>
              </div>
              <!--
		 <div class="pagination" style='display:inline;'>
		  <ul>
		   <li><a id="First" href="#">First</a></li>
		   <li><a id="Prev" href="#">Prev</a></li>
		   <li><a id="Next2" href="#">Next</a></li>
		   <li><a id="Last" href="#">Last</a></li>
		  </ul>
		 </div>
               -->
	   </div>
 </div>
        </div><!--/span-->
      </div><!--/row-->

<?php main_footer(); ?>
