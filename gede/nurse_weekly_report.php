<?php
session_start();

if (!isset($_SESSION['uid'])) {
  header('Location: index.php');
  exit;
}
require_once "form.inc";
require_once "menu.inc";

$con = connect();

main_menu($_SESSION['uid'], 
   $_SESSION['firstname'] . " " . $_SESSION['lastname'], $_REQUEST['table'], true, $con);

?>

	<div class="container" style="margin-top: 10px">
		
	 <div class="span6">
	   <select id="iDisplayLength" name="iDisplayLength">
	    <option value="5" selected="selected">5</option>
	    <option value="10">10</option>
		<option value="25">25</option>
		<option value="50">50</option>
		<option value="100">100</option>
	   </select>
	 </div>
	  <form class="navbar-search pull-right">
	   Search: <input type="text" class="search-query" placeholder="Search">
	  </form>
       <table cellpadding="0" cellspacing="0" border="0" 
           class="table table-striped table-bordered table-hover"" id="example" >
         <thead>
          <tr> 
		   <th>S/N</th>
		   <th>GDR</th>
		   <th>CRU</th>
		   <th>GNGF</th>
		   <th>SEX</th>
		   <th>AGE</th>
		   <th>NAME</th>
		   <th>BP</th>
		   <th>WT</th>
		   <th>HGT</th>
		   <th>TEMP</th>
		   <th>REGIMEN</th>
		   <th>PHONE NUMBER</th>
		   <th>DATE OF VISIT</th>
		   <th>DATE OF NEXT APPOINTMENT</th>
		   <th>NEW CLIENT IN CARE</th>
		   <th>NEW CLIENT UNDER TREATMENT</th>
		   <th>CLIENT UNDER TREATMENT</th>
         </tr>
		 <tr>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <td>
		    <table>
			 <tr><td>Sex</td><td>Age</td></tr>
			</table>
		   </td>
		   <td>
		    <table>
			 <tr><td>Sex</td><td>Age</td></tr>
			</table>
		   </td>
		   <td>
		    <table>
			 <tr><td>Sex</td><td>Age</td></tr>
			</table>
		   </td>
		 </tr>
        </thead>
        <tbody id="example1">
		<!--
         <tr>
          <td colspan="5" class="dataTables_empty">Loading data from server</td>
         </tr>
		 -->
		 <tr>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <th></th>
		   <td>
		    <table>
			 <tr><td></td><td></td></tr>
			</table>
		   </td>
		   <td>
		    <table>
			 <tr><td></td><td></td></tr>
			</table>
		   </td>
		   <td>
		    <table>
			 <tr><td></td><td></td></tr>
			</table>
		   </td>
		 </tr>
        </tbody>
        <tfoot>
         <tr>
         </tr>
        </tfoot>
       </table>
       <div class="row">
	     <p style='display:inline;'>Showing <span id="iDisplayStart"></span> to <span id="iDisplayEnd"></span> of <span id="iTotalRecords"></span> entries</p>
		 <div class="pagination" style='display:inline;'>
		  <ul>
		   <li><a id="First" href="#">First</a></li>
		   <li><a id="Prev" href="#">Prev</a></li>
		   <li><a id="Next" href="#">Next</a></li>
		   <li><a id="Last" href="#">Last</a></li>
		  </ul>
		 </div>
	   </div>
 </div>
        </div><!--/span-->
      </div><!--/row-->

<?php main_footer(); ?>
