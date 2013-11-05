<?php
  require_once "form.inc";
  function main_menu($uid, $username, $table, $custom, $con) {
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
   
   <script src="assets/js/dhtmlxchart.js" type="text/javascript"></script>
<link rel="STYLESHEET" type="text/css" href="assets/js/dhtmlxchart.css">

   <script type="text/javascript" charset="utf-8">   

     table = [];
     table2 = {};

     $( window ).load(function() {
       //console.log( "window loaded" );
     });

     $(document).ready(function(){
     <?php
       if (!$custom) {
     ?>

     var API = new LMS_API({url: 'http://' + location.host 
        + '/gede', api_key: '1234', api_version: 1});

     var rows = API.get_data('<?php echo $table; ?>', '', '0', 
      $("#iDisplayLength").val(), success, failure);

     $("#iDisplayStart").text("0");
     $("#iDisplayEnd").text(parseInt($("#iDisplayStart").text()) 
        + parseInt($("#iDisplayLength").val()));

     if (parseInt($("#iTotalDisplayRecords").text()) < 
       parseInt($("#iDisplayLength").val())) {
       $("#Prev").attr('disabled', 'disabled');
       $("#Next").attr('disabled', 'disabled');
       $("#Last").attr('disabled', 'disabled');
     }
         
     $("#Prev").attr('disabled', 'disabled');

     <?php } ?>

     $('#load').hide();
 
     $( "#search" ).autocomplete({
        source: "search.php&table=<?php echo $table;?>",
        minLength: 1,
        select: function( event, ui ) {
          log( ui.item ?
          "Selected: " + ui.item.value + " aka " + ui.item.id :
          "Nothing selected, input was " + this.value );
        }
     });



     $("#search").keyup(function(event){
       var iDisplayLength = $("#iDisplayLength").val();

       //if ($("#search").val().length == 0) {
       API.get_data('<?php echo $table; ?>', $(this).val(), 
          0, iDisplayLength, success, failure);

       $("#iDisplayStart").text("0"); 
       $("#iDisplayEnd").text(parseInt($("#iDisplayStart").text()) 
         + parseInt($("#iDisplayLength").val()));

       $("#First").removeAttr('disabled');
       $("#Prev").removeAttr('disabled');
       $("#Next").removeAttr('disabled');
       $("#Last").removeAttr('disabled');

     });
   
     $("#iDisplayLength").change(function(event){

       API.get_data('<?php echo $table; ?>', 
           $("#search").val(), $("#iDisplayStart").text(), 
           $("#iDisplayLength").val(), success, failure);

       $("#iDisplayEnd").text(parseInt($("#iDisplayStart").text()) 
         + parseInt($("#iDisplayLength").val()));

     });

     $("#First").click(function(event){
       event.preventDefault();	

       API.get_data('<?php echo $table; ?>', 
         $("#search").val(), '0', $("#iDisplayLength").val(), success, failure);

       $("#iDisplayStart").text("0");
       $("#iDisplayEnd").text(parseInt($("#iDisplayStart").text()) 
           + parseInt($("#iDisplayLength").val()));

       $("#Next").removeAttr('disabled');
       $("#Last").removeAttr('disabled');
       $("#Prev").attr('disabled', 'disabled');
     });
   
     $("#Prev").click(function(event){
       event.preventDefault();	

       var iDisplayStart = parseInt($("#iDisplayStart").text()) 
             - parseInt($("#iDisplayLength").val());
       var iDisplayLength = $("#iDisplayLength").val()

       if (iDisplayStart < 0)
         iDisplayStart = 0;

       API.get_data('<?php echo $table; ?>', 
          $("#search").val(), iDisplayStart, 
          iDisplayLength, success, failure);

       $("#iDisplayStart").text(iDisplayStart);
       $("#iDisplayEnd").text(parseInt($("#iDisplayStart").text()) 
            + parseInt($("#iDisplayLength").val()));	

       $("#Next").removeAttr('disabled');
       $("#Last").removeAttr('disabled');
     });

     $("#Next").click(function(event){
       event.preventDefault();	

       var iDisplayStart = parseInt($("#iDisplayStart").text()) 
            + parseInt($("#iDisplayLength").val());
       var iDisplayLength = $("#iDisplayLength").val()

       var iTotalDisplayRecords = parseInt($("#iTotalDisplayRecords").text()) 

       $("#iDisplayStart").text(iDisplayStart); 

       if ((iDisplayStart + parseInt(iDisplayLength)) > iTotalDisplayRecords) {
         $("#iDisplayEnd").text(iTotalDisplayRecords);
         $("#Next").attr('disabled', 'disabled');
         $("#Last").attr('disabled', 'disabled');
         $("#iDisplayStart").text(iDisplayStart);

       } else {
         $("#iDisplayEnd").text(parseInt($("#iDisplayStart").text()) 
           + parseInt($("#iDisplayLength").val()));
       } 

       API.get_data('<?php echo $table; ?>', 
          $("#search").val(), iDisplayStart, iDisplayLength, success, failure);

       $("#Prev").removeAttr('disabled');
       $("#First").removeAttr('disabled');
     });
   
     $("#Last").click(function(event){
       event.preventDefault();	
		
       var start = parseInt($("#iTotalDisplayRecords").text()) 
           - parseInt($("#iDisplayLength").val())

       //start -= 1; //Corrective entry

       API.get_data('<?php echo $table; ?>', 
           $("#search").val(), start, $("#iDisplayLength").val(), 
           success, failure);

       $("#iDisplayStart").text(start);
       $("#iDisplayEnd").text(parseInt($("#iDisplayStart").text()) 
           + parseInt($("#iDisplayLength").val()));

       $("#Next").attr('disabled', 'disabled');
       $("#Prev").removeAttr('disabled');
       $("#First").removeAttr('disabled');
     });
	
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
               if (row == 18) 
                 continue;

               if (row == 1) {
                 output += "<td";

                 if (json.aaData[i][18] == 'unregistered')
                   output += " style='color:red;'"

                 output += "><a href='add_edit.php?";
                 output += "table=<?php echo $table;?>&id=";
                 output += json.aaData[i][0];
                 output += "&action=Edit'>";
                 output += json.aaData[i][row] + "</td>";
               } else { 
                 output += "<td";   

                 if (json.aaData[i][18] == 'unregistered')
                   output += " style='color:red;'"

	         output += ">" + json.aaData[i][row] + "</td>";
               }
             }
	   }
	   text.append(header + output + "</tr>");
	 }
	 //$("#iTotalRecords").text(json.iTotalRecords);
	 $("#iTotalDisplayRecords").text(json.iTotalDisplayRecords);
	 $('#load').fadeOut();
    
         /*
	 $("#example tr").click(function(event) {
	   var $this = $( this );
           $("#id").text($this.attr("id"));
           console.log($this.attr("id"));

           var values = '';
           table = get_table();

           var tds; 
           $.each(tds, function(index, item) {
             values = values + 'td' + (index + 1) + ':' 
               + item.innerHTML + '<br/>';
             table2[table[index]] = item.innerHTML
           });
           console.log(JSON.stringify(table2));

         });
         */
         if (parseInt($("#iDisplayStart").text()) == 0)  {
           $("#Prev").attr('disabled', 'disabled');
           $("#First").attr('disabled', 'disabled');
         }
          
         if ((parseInt($("#iDisplayStart").text()) +
           parseInt($("#iDisplayLength").val())) >= 
             parseInt($("#iTotalDisplayRecords").text())) {
           $("#Next").attr('disabled', 'disabled');
           $("#Last").attr('disabled', 'disabled');
           $("#Prev").removeAttr('disabled');
         }

         if ((parseInt($("#iDisplayStart").text()) == 0) && 
           ((parseInt($("#iDisplayStart").text()) +
             parseInt($("#iDisplayLength").val())) >= 
             parseInt($("#iTotalDisplayRecords").text()))) {
           $("#Next").attr('disabled', 'disabled');
           $("#Last").attr('disabled', 'disabled');
           $("#Prev").attr('disabled', 'disabled');
         }
     }

     function failure(status, message) {
       alert("<p>Failure: " + status + ", " + message + "</p>");
     }
   });
   </script>
  </head>
  <!--<body>-->
  <body class="preview" id="top" data-spy="scroll" data-target=".subnav" data-offset="80">

 
  <div id="load" align="center">
   <img src="images/loading.gif" width="28" height="28" align="absmiddle"/> 
     Loading...
  </div>
  <div class="container-fluid">
   <div class="row-fluid">
    <div class="navbar navbar-fixed-top">
    <!--<div class="navbar navbar-inverse navbar-fixed-top">-->
     <div class="navbar-inner">
      <!--<div class="container" style='background-color:rgb(76,69,56);'>-->
      <div class="container" >
       <a class="btn btn-navbar" data-toggle="collapse" 
         data-target=".navbar-inverse-collapse">
         <span class="icon-bar"><img src='images/Untitled.png' /></span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
        </a>
        <a class="brand" href="#">
         <img src='images/Untitled.png' style='margin:-5px; padding:0;
          border: 1px solid white;'></a>
         <div class="nav-collapse collapse navbar-inverse-collapse">
          <ul class="nav">

          <!--<li class="divider"></li>
           <li class="nav-header">Pharmacy</li>
           <a data-toggle="modal" href="remote.html" 
             data-target="#modal">click me</a>
          'choose_date.php?x=x&url=nurse_report',  
          -->

          <?php
          $arr1 = get_user_perm($uid, $con);

          $arr2 = array('gdr'=>'form.php?table=gdr', 
           'client visit'=>'form.php?table=client_visit', 
           'daily income' => 'form.php?table=daily_income', 
           'credit lab income' => 'form.php?table=credit_lab_income',
           'gngf supplements' => 'form.php?table=gngf_supplements',
           'registration' => 'form.php?table=registration',
           'clinical exam' => 'form.php?table=clinical_exam',
           'counselling' => 'form.php?table=counselling',
           'gdr cru' => 'form.php?table=gdr_cru',
           'lab cd4 viral load drug resistance' => 
             'form.php?table=lab_cd4_viral_load_drug_resistance',
           'lab chemistry' => 'form.php?table=lab_chemistry',
           'lab heamatology' => 'form.php?table=lab_heamatology',
           'lab other tests' => 'form.php?table=lab_other_tests',
           'lab serology' => 'form.php?table=lab_serology',
           'pharmacy drugs' => 'form.php?table=pharmacy_drugs',
           'nurse m and e' => 'form.php?table=nurse_m_and_e',
           'naca'          => 'form.php?table=naca',
           'arv' => 'form.php?table=arv',
           'non arv' => 'form.php?table=non_arv',
           'pediatrics' => 'form.php?table=pediatrics',
           'pitt' => 'form.php?table=pitt');

          if(in_array_i($arr1, $arr2)) {
            $menu = in_user_perm($arr1, $arr2);
            generate_menu("Manage", $menu);
            unset($menu);
          }
   

          $arr2 = array('clinical monthly' 
                   => 'choose_clinical_monthly_report.php', 
            'nurse report' 
                   => 'choose_date.php?url=nurse_report',  
            'consultation report' => 'choose_consultation_year.php',
            'pharmacy report'            => 'choose_pharmacy_report.php', 
            'cash collection summary' 
                   => 'choose_date.php?url=cash_collection_summary',
            'analysis of credit lab income' 
                   => 'choose_date.php?url=analysis_of_credit_lab_income',
            'analysis of gngf supplements'
                   => 'choose_date.php?url=analysis_of_gngf_supplements',
            'pitt report1'         => 'choose_pitt.php?url=pitt_report1',
            'pitt report2'        => 'choose_pitt.php?url=pitt_report2',
            'dhis art'            => 'form.php?table=dhis_art',
            'dhis hct'            => 'form.php?table=dhis_hct', 
            'm and e monthly summary' 
               => 'form.php?table=m_and_e_monthly_summary',
            'summary statistics nomis' 
                   => 'form.php?table=summary_statistics_nomis');


          if(in_array_i($arr1, $arr2)) {
            $menu = in_user_perm($arr1, $arr2);
            generate_menu("Reports", $menu);
            unset($menu);
          }

          $arr2 = array('user' => 'user.php',   
            'org info' => 'org_info.php', 
            'backup and restore' => 'backup_restore.php');

          if(in_array_i($arr1, $arr2)) {
            $menu = in_user_perm($arr1, $arr2);
            generate_menu("Settings", $menu);
            unset($menu);
          }
          ?>
          <li class="dropdown">
           <a href="#" class="dropdown-toggle" data-toggle="dropdown">
             <span style='color:#664104;'>Help</span>
            <b class="caret"></b></a>
           <ul class="dropdown-menu">
            <li> 
             <a href="download.php?file=user_manual.pdf">
               User Manual</a>
            </li>
            <li>
             <a href="download.php?file=admin_manual.pdf">Admin Manual</a>
            </li>
           </ul>
          </li>  
                    </ul>
                    <ul class="nav pull-right">
                      <li>
                        <p class="navbar-text pull-right">
                             <span style='color:#664104;'>
              		    	 Logged in as 
               		     <?php echo $username; ?>&nbsp;
                             </span>
             		    </p>     
             		  </li>
             		  <li>
             		  	<p class="navbar-text pull-right">
             			<a href='index.php?action=logout'>Logout</a></p>
             		  </li>
          
             		</ul>
                  </div><!-- /.nav-collapse -->
                </div>
              </div><!-- /navbar-inner -->
            </div><!-- /navbar -->
          <!--</div>-->

<!--
<div class="ui-widget">
  <label for="birds">Birds: </label>
  <input id="birds">
</div>

<div class="ui-widget" style="margin-top:2em; font-family:Arial">
  Result:
  <div id="log" style="height: 200px; width: 300px; overflow: auto;" class="ui-widget-content"></div>
</div>
-->

        <div class="span9">
          <div id="myUniversalModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-header">    
          	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>    
          	<h3 id="myModalLabel">Modal header</h3>
          </div>
          <div class="modal-body">
          	<p>One fine body</p>
          </div>
          <div class="modal-footer">
           <button class="btn btn-primary">OK</button>
           <button class="btn" data-dismiss="modal" aria-hidden="true">CANCEL</button>
          </div>
         </div>
		
		
<?php 
 }
 function main_footer() {
?>
      <hr>
      <footer style='background-color: #f5f5f5;
        border-top: 1px solid #ddd; text-align:center;'>
        <br
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

<?php 
 }
 function print_menu($con) {
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
      <script type="text/javascript" charset="utf-8"
     language="javascript" src="assets/js/jquery.js"></script>

   </head>
  <body>
 <?php
   $sql = "select * from org_info";
   $result = mysql_query($sql, $con) or die(mysql_error());
   $row = mysql_fetch_array($result);
   echo "<div style='text-align:center;'> <h3>{$row['name']}</h3>
         <p>{$row['address']}, {$row['email']}, {$row['phone']}
         {$row['web']}</p><br /><br /></div>";   
 }
 function print_footer() {
?>
      <hr>

      <footer>
        <p>&copy; Gede Foundation 2013</p>
      </footer>

    </div><!--/.fluid-container-->
    </body>
   </html>
 <?php }
?>

