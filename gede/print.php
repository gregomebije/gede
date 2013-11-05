<?php
session_start();

if (!isset($_SESSION['uid'])) {
  header('Location: index.php');
  exit;
}
require_once "menu.inc";
require_once "form.inc";

$con = connect();

$temp = get_user_perm($_SESSION['uid'], $con);

if (!in_array(format_label2($_REQUEST['table']), $temp)) {
  main_menu($_SESSION['uid'],
    $_SESSION['firstname'] . " " . $_SESSION['lastname'],
    $_REQUEST['table'], true, $con);

  msg_box('Access Denied', 'index.php?action=logout', 'Logout');
  main_footer();
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Gede Foundation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="assets/css/smoothness/jquery-ui-1.10.3.custom.css" 
      rel="stylesheet">
    <link href="assets/css/gede.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }
      .row-highlight {
        background-color: Yellow;
      }
      #load {
        position:absolute;
	left:500px;
	background-image:url(images/loading-bg.png);
	background-position:center;
	background-repeat:no-repeat;
	width:159px;
	color:#999;
	font-size:18px;
	font-family:Arial, Helvetica, sans-serif;
	height:40px;
	font-weight:300;
	padding-top:14px;
	top: 200px;
      }
    </style>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="assets/js/html5shiv.js"></script>
    <![endif]-->

   <script type="text/javascript" charset="utf-8" 
     language="javascript" src="assets/js/jquery.js"></script>

   <script type="text/javascript" charset="utf-8" 
     language="javascript" src="dm_api.js"></script>

   <script src="assets/js/jquery-ui-1.10.3.custom.min.js"></script>
   <script type="text/javascript" charset="utf-8" 
     language="javascript" src="gede.js"></script>
   	
   <script type="text/javascript" charset="utf-8">   
     table = [];
     table2 = {};

     $( window ).load(function() {
       console.log( "window loaded" );
     });

     $(document).ready(function(){
       var API = new LMS_API({url: 'http://' + location.host 
        + '/gede', api_key: '1234', api_version: 1});

       var rows = API.get_data('<?php echo $_REQUEST['table']; ?>', 
        '<?php echo $_REQUEST['search'];?>', 
        <?php echo $_REQUEST['iDisplayStart']; ?>, 
        <?php echo $_REQUEST['iDisplayLength'];?>, success, failure);

       $('#load').hide();


       function success(json) {   
         var text = $('#example1');
	 text.empty();
         var str = JSON.stringify(json);

	 for (var i = 0; i < json.aaData.length; i++) {
	   header = "";
	   output = "";

	   for (var row=0; row < json.aaData[i].length; row++) {
	     if (row == 0) {
	       header = "<tr id=\"" + json.aaData[i][0] + "\">";
	       continue;
	     }
	     else {
               if (row == 1) {
                 output += "<td>";
                 output += json.aaData[i][row] + "</td>";
               } else 
	         output += "<td>" + json.aaData[i][row] + "</td>";
             }
	   }
	   text.append(header + output + "</tr>");
	 }
	 $('#load').fadeOut();
    
	 $("#example tr").click(function(event) {
	   var $this = $( this );
           $("#id").text($this.attr("id"));

           var values = '';
           table = get_table();
      
           $.each(tds, function(index, item) {
             values = values + 'td' + (index + 1) + ':' 
               + item.innerHTML + '<br/>';
             table2[table[index]] = item.innerHTML
           });

         });
       }
       function failure(status, message) {
         alert("<p>Failure: " + status + ", " + message + "</p>");
       }
     });
   </script>
 </head>
 <body>
 
  <div id="load" align="center">
   <img src="images/loading.gif" width="28" height="28" align="absmiddle"/> 
     Loading...
  </div>
  <!--<div class="container-fluid">-->
   
  <div class="container" style="margin-top: 10px">
   <div class="page-header text-center" style='margin-bottom:5px;
    margin-top:-10px;'>

   <?php
   $sql = "select * from org_info";
   $result = mysql_query($sql, $con) or die(mysql_error());
   $row = mysql_fetch_array($result);
   echo "<div style='text-align:center;'> <h3>{$row['name']}</h3>
         <p>{$row['address']}, {$row['email']}, {$row['phone']}
         {$row['web']}</p></div>";
   ?>
    
    <h3 style='margin-bottom:-15px;'>
     <?php echo format_label($_REQUEST['table']); ?> Report
    </h3>
    </div>

    <table cellpadding="0" cellspacing="0" border="0" 
      class="table table-striped table-bordered table-hover" 
      style='table-layout: auto;' id="example" >
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
           echo "<th width=\"" . strlen($field[0]) . "px\">"
                 . format_label($field[0]) . "</th>";
         }
       }
       ?>
       </tr>
      </thead>
      <tbody id="example1">
      </tbody>

      <script>
         table = [];
      </script>

      <tfoot>
       <tr>
       <?php
       $skip = array('id');
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
      <hr>

      <footer>
        <p>&copy; Gede Foundation 2013</p>
      </footer>

    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="accounting.js"></script>
    <script src="assets/js/bootstrap-transition.js"></script>
    <script src="assets/js/bootstrap-alert.js"></script>
    <script src="assets/js/bootstrap-modal.js"></script>
    <script src="assets/js/bootstrap-dropdown.js"></script>
    <script src="assets/js/bootstrap-scrollspy.js"></script>
    <script src="assets/js/bootstrap-tab.js"></script>
    <script src="assets/js/bootstrap-tooltip.js"></script>
    <script src="assets/js/bootstrap-popover.js"></script>
    <script src="assets/js/bootstrap-button.js"></script>
    <script src="assets/js/bootstrap-collapse.js"></script>
    <script src="assets/js/bootstrap-carousel.js"></script>
    <script src="assets/js/bootstrap-typeahead.js"></script>
	

  </body>
</html>


