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
   	
   <script type="text/javascript" charset="utf-8">   
     table = [];
     table2 = {};

     $( window ).load(function() {
       console.log( "window loaded" );
     });

     $(document).ready(function(){
     <?php
       if (!$custom) {
     ?>

     var API = new LMS_API({url: 'http://' + location.host 
        + '/gede', api_key: '1234', api_version: 1});

     /*
     var rows = API.get_data('<?php echo $table; ?>', '', '0', '5', 
        success, failure);
     */
     var rows = API.get_data('<?php echo $table; ?>', '', '0', 
      $("#iDisplayLength").val(), success, failure);

     $("#iDisplayStart").text("0");
     //$("#iDisplayLength").val("5");
     $("#iDisplayEnd").text(parseInt($("#iDisplayStart").text()) 
        + parseInt($("#iDisplayLength").val()));

     <?php } ?>

     $('#load').hide();

     $("#search").keyup(function(event){
       var iDisplayLength = $("#iDisplayLength").val();

       //if ($("#search").val().length == 0) {
       API.get_data('<?php echo $table; ?>', $(this).val(), 
          0, iDisplayLength, success, failure);

       $("#iDisplayStart").text("0"); 
       $("#iDisplayEnd").text(parseInt($("#iDisplayStart").text()) 
         + parseInt($("#iDisplayLength").val()));
     });
   
     $("#iDisplayLength").change(function(event){
       var iDisplayLength = $("#iDisplayLength").val();

       API.get_data('<?php echo $table; ?>', $(this).val(),
          0, iDisplayLength, success, failure);

        $("#iDisplayStart").text("0");
        $("#iDisplayEnd").text(parseInt($("#iDisplayStart").text())
          + parseInt($("#iDisplayLength").val()));
     });

    /*
     $("#Delete").click(function(event) {
     $('#load').fadeIn();
     var commentContainer = $(this).parent();
     var id = $(this).attr("id");
     var string = 'id='+ id ;
        $.ajax({
         type: "POST",
	 url: "delete.php",
	 data: string,
	 cache: false,
	 success: function(){
	 commentContainer.slideUp('slow', function() {$(this).remove();});
	 $('#load').fadeOut();
     }	   
     });
     return false;
     });
	
	 
	$("#New").click(function(event) {
	
    table_data = {
            id: '-1',
			table: '<?php echo $table; ?>',
			action: "create"
        }
	    event.preventDefault();	
		 
		var loginForm = $('form').serializeArray();
		var x = 0;
		$.each(loginForm,
		  function(i, v) {
           table_data[v.name] = v.value;
		  
		});
	   API.create_row(table_data, success, failure);
	   $("#myModal").modal('hide');

	});
	
  
  $("#Edit").click(function(event) {
    
    var row_id = $("#id").text();
    var json_data = {
      id: row_id,
      table: '<?php echo $table; ?>',
      action: "edit"
    }
    event.preventDefault(); 

    var values = '';
    var tds = $('#' + row_id).find('td');
    $.each(tds, function(index, item) {
        values = values + 'td' + (index + 1) + ':' + item.innerHTML + '<br/>';
        json_data[table[index]] = item.innerHTML
    });
    alert(JSON.stringify(json_data));
    $("#cru").val(json_data['cru']);
    //$("#client_id").val(json_data['client_id']);
    //$(‘#client_id :selected’).text();
    $("#client_id option[value=2]").attr("selected",true);
    $("#myModal").modal('show');

  });
	   function success_create(json) {
	     alert("Successfully Added");
	     var text = $('#example1');
		 text.empty();
		 var str = JSON.stringify(json);
		 var i = 0;
         for (var data in json.aaData) {
		   if (($i % 2) == 0)
	         text.append("<tr style='background-color:#bbbbff'>");
		    else
			  text.append("<tr>");
	       var klass = json.aaData[data];
		
	       for (var row in klass) {
		     text.append("<td>" + klass[row] + "</td>");
		   }
		
		   text.append("</tr>");
		   //$("tr:odd").css("background-color", "#bbbbff");
		  $i = $i + 1;
	    }
		$("#iTotalRecords").text(json.iTotalRecords);
	  }
	
	*/
	$("#First").click(function(event){
	  event.preventDefault();	

	  API.get_data('<?php echo $table; ?>', 
	  $("#search").val(), '0', '5', success, failure);

	  $("#iDisplayStart").text("0");
	  $("#iDisplayLength").val("5");
	  $("#iDisplayEnd").text(parseInt($("#iDisplayStart").text()) 
           + parseInt($("#iDisplayLength").val()));
        });
   
        $("#Previous").click(function(event){
	  event.preventDefault();	
	  var iDisplayStart = parseInt($("#iDisplayStart").text()) 
             - parseInt($("#iDisplayLength").text());
	  var iDisplayLength = $("#iDisplayLength").val()

	  API.get_data('<?php echo $table; ?>', 
            $("#search").val(), iDisplayStart, 
            iDisplayLength, success, failure);

	  $("#iDisplayStart").text(iDisplayStart);
	  $("#iDisplayEnd").text(parseInt($("#iDisplayStart").text()) 
            + parseInt($("#iDisplayLength").val()));	
        });

        $("#Next").click(function(event){
          event.preventDefault();	
	  var iDisplayStart = parseInt($("#iDisplayStart").text()) 
            + parseInt($("#iDisplayLength").text());
	  var iDisplayLength = $("#iDisplayLength").val()

          API.get_data('<?php echo $table; ?>', 
          $("#search").val(), iDisplayStart, iDisplayLength, success, failure);

	  $("#iDisplayStart").text(iDisplayStart);
	  $("#iDisplayEnd").text(parseInt($("#iDisplayStart").text()) 
           + parseInt($("#iDisplayLength").val()));
       });
   
       $("#Last").click(function(event){
         event.preventDefault();	
		
	 start = parseInt($("#iTotalRecords").text()) 
           - parseInt($("#iDisplayLength").text())

	 API.get_data('<?php echo $table; ?>', 
           $("#search").val(), start, $("#iDisplayLength").val(), 
           success, failure);

	  $("#iDisplayStart").text(start);
	  $("#iDisplayEnd").text(parseInt($("#iDisplayStart").text()) 
           + parseInt($("#iDisplayLength").val()));
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
               if (row == 1) {
                 output += "<td><a href='add_edit.php?";
                 output += "table=<?php echo $table;?>&id=";
                 output += json.aaData[i][0];
                 output += "&action=Edit'>";
                 output += json.aaData[i][row] + "</td>";
               } else 
	         output += "<td>" + json.aaData[i][row] + "</td>";
             }
	   }
	   text.append(header + output + "</tr>");
	 }
	 $("#iTotalRecords").text(json.iTotalRecords);
	 $('#load').fadeOut();
    
	 //$("#example tr:even").css("background-color", "#f5f5f5");
	 //No need bootstrap already does this
	  	
	 $("#example tr").click(function(event) {
	   var $this = $( this );
	   //alert($this.attr("id"));
           $("#id").text($this.attr("id"));
           console.log($this.attr("id"));

           var values = '';
           //$this.removeClass('row-highlight');
           //var tds = $this.addClass('row-highlight').find('td');        
           table = get_table();
      
           $.each(tds, function(index, item) {
             values = values + 'td' + (index + 1) + ':' 
               + item.innerHTML + '<br/>';
             table2[table[index]] = item.innerHTML
             //table2[table[index]] = "xx"
           });
           console.log(JSON.stringify(table2));

	   //$this.addClass( "row-highlight" ); 
	   //alert($(this).html());
         });
       }
	
       function failure(status, message) {
         var text = $('#example1');
	 text.append("<p>Failure: " + status + ", " + message + "</p>");
       }
     });
   </script>
  </head>
  <body>
 
  <div id="load" align="center">
   <img src="images/loading.gif" width="28" height="28" align="absmiddle"/> 
     Loading...
  </div>
  <div class="container-fluid">
   <div class="row-fluid">
    <div class="navbar navbar-inverse navbar-fixed-top">
     <div class="navbar-inner">
      <div class="container">
       <a class="btn btn-navbar" data-toggle="collapse" 
         data-target=".navbar-inverse-collapse">
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
        </a>
        <a class="brand" href="#">Gede Foundation</a>
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

          $arr2 = array('Client'=>'form.php?table=client', 
           'Daily Income' => 'form.php?table=daily_income', 
           'Credit Lab Income' => 'form.php?table=credit_lab_income',
           'Gngf Supplements' => 'form.php?table=gngf_supplements',
           'Clinical Exam' => 'form.php?table=clinical_exam',
           'Counselling' => 'form.php?table=counselling',
           'Gdr Cru' => 'form.php?table=gdr_cru',
           'Lab Cd4 Viral Load Drug Resistance' => 
             'form.php?table=lab_cd4_viral_load_drug_resistance',
           'Lab Chemistry' => 'form.php?table=lab_chemistry',
           'Lab Heamatology' => 'form.php?table=lab_heamatology',
           'Lab Other Tests' => 'form.php?table=lab_other_tests',
           'Lab Serology' => 'form.php?table=lab_serology',
           'Pharmacy Drugs' => 'form.php?table=pharmacy_drugs',
           'Nurse M And E' => 'form.php?table=nurse_m_and_e',
           'Arv' => 'form.php?table=arv',
           'Non Arv' => 'form.php?table=non_arv',
           'Pediatrics' => 'form.php?table=pediatrics',
           'Pitt' => 'form.php?table=pitt');

          if(in_array_i($arr1, $arr2)) {
            $menu = in_user_perm($arr1, $arr2);
            generate_menu("Manage", $menu);
            unset($menu);
          }
   

          $arr2 = array('Clinical Monthly' 
                   => 'choose_clinical_monthly_report.php', 
            'Nurse Report' 
                   => 'choose_date.php?url=nurse_report',  
            'Consultation Report' => 'choose_consultation_year.php',
            'Pharmacy'            => 'choose_pharmacy_report.php', 
            'Daily Cash Collection Summary' 
                   => 'choose_date.php?url=cash_collection_summary',
            'Daily Analysis Of Credit Lab Income' 
                   => 'choose_date.php?url=credit_lab_income',
            'Daily Analysis Of GNGF/Supplements'
                   => 'choose_date.php?url=gngf_analysis',
            'PITT Report'         => 'form.php?table=pitt',
            'Dhis Art'            => 'form.php?table=dhis_art',
            'Dhis Hct'            => 'form.php?table=dhis_hct', 
            'M and E Monthly Summary' => 'form.php?table=m_e',
            'Summary Statistics Nomis' 
                   => 'form.php?table=summary_statistics_nomis');


          if(in_array_i($arr1, $arr2)) {
            $menu = in_user_perm($arr1, $arr2);
            generate_menu("Reports", $menu);
            unset($menu);
          }

          $arr2 = array('User' => 'user.php?x=x',   
            'Entity' => 'entity.php?x=x', 
            'Backup and Restore' => 'backup_restore.php');

          if(in_array_i($arr1, $arr2)) {
            $menu = in_user_perm($arr1, $arr2);
            generate_menu("Settings", $menu);
            unset($menu);
          }
          ?>
                    </ul>
                    <ul class="nav pull-right">
                      <li>
                        <p class="navbar-text pull-right">
              		 	 Logged in as <a href="#" class="navbar-link">
               		     <?php echo $username; ?></a>	&nbsp;
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

<?php 
 }
?>

