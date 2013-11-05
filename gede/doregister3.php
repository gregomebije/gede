<?php
require_once "booking_admin/form.inc";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr">
<head>
<link href='http://fonts.googleapis.com/css?family=Terminal+Dosis:600' rel='stylesheet' type='text/css'>
  <base href="http://localhost/joomla/index.php/booking" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="author" content="Super User" />
  <meta name="generator" content="Joomla! - Open Source Content Management" />
  <title>Booking</title>
  <link href="/joomla/templates/businesslines-tg/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
  <link href="http://localhost/joomla/index.php/component/search/?Itemid=294&amp;format=opensearch" rel="search" title="Search Denoyab's Place" type="application/opensearchdescription+xml" />
  <script src="/joomla/media/system/js/mootools-core.js" type="text/javascript"></script>
  <script src="/joomla/media/system/js/core.js" type="text/javascript"></script>
  <script src="/joomla/media/system/js/caption.js" type="text/javascript"></script>
  <script src="/joomla/media/system/js/mootools-more.js" type="text/javascript"></script>
  <script type="text/javascript">
window.addEvent('load', function() {
				new JCaption('img.caption');
			});
function keepAlive() {	var myAjax = new Request({method: "get", url: "index.php"}).send();} window.addEvent("domready", function(){ keepAlive.periodical(840000); });
  </script>


<link rel="stylesheet" href="/joomla/templates/businesslines-tg/css/styles.css" type="text/css" />
<link rel="stylesheet" href="/joomla/templates/businesslines-tg/css/NivooSlider.css" type="text/css" />
<script type="text/javascript" src="/joomla/templates/businesslines-tg/slideshow/NivooSlider.js"></script>
    <script type="text/javascript">
        window.addEvent('domready', function () {
            // initialize Nivoo-Slider
            new NivooSlider($('Slider'), {
				effect: 'random',
				interval: 5000,
				orientation: 'random'
			});
        }); 
    </script>
</head>

<body class="background">
<div class="shadow">
<div id="main">
<div id="header-w">
    	<div id="header">
		<div class="topmenu">
		<div class="topleft"></div><div class="topright">
			
		</div><div class="topright2"></div>
		</div>
        	        
            	<a href="/joomla/">
			<img src="/joomla/templates/businesslines-tg/images/logo.png" border="0" class="logo">
			</a>
            		<div class="slogan"></div>
                                     
	</div> 
</div>

<div id="wrapper">
        	<div id="navr">
			<div class="searchbutton"><form action="/joomla/index.php/booking" method="post">
	<div class="search">
		<input name="searchword" id="mod-search-searchword" maxlength="20"  class="searchbox" type="text" size="20" value="Search..."  onblur="if (this.value=='') this.value='Search...';" onfocus="if (this.value=='Search...') this.value='';" />	<input type="hidden" name="task" value="search" />
	<input type="hidden" name="option" value="com_search" />
	<input type="hidden" name="Itemid" value="294" />
	</div>
</form>
</div>
		<div id="navl">
		<div id="nav">
				<div id="nav-left">
<ul class="menu">
<li class="item-435"><a href="/joomla/" >Home</a></li><li class="item-294 current active"><a href="/joomla/index.php/booking" >Booking</a></li><li class="item-238 deeper parent"><a href="/joomla/index.php/accommodation" >Accommodation</a><ul><li class="item-469"><a href="/joomla/index.php/accommodation/standard-dp-arena" >Standard DP Arena</a></li><li class="item-474"><a href="/joomla/index.php/accommodation/standard-main" >Standard Main</a></li><li class="item-470"><a href="/joomla/index.php/accommodation/executive-rooms" >Executive Rooms</a></li><li class="item-471"><a href="/joomla/index.php/accommodation/royal-suites" >Royal Suites</a></li><li class="item-472"><a href="/joomla/index.php/accommodation/corporate-suites" >Corporate Suites</a></li></ul></li><li class="item-448"><a href="/joomla/index.php/our-services" >Our Services</a></li><li class="item-473"><a href="/joomla/index.php/our-facilities" >Our Facilities</a></li><li class="item-455"><a href="/joomla/index.php/about-us" >About Us</a></li><li class="item-468"><a href="/joomla/index.php/contactusss" >Contact Us</a></li><li class="item-233 deeper parent"><a href="/joomla/index.php/admin" >Admin</a><ul><li class="item-438"><a href="http://denoyabsplace.com/webmail" target="_blank" >Web Mail</a></li></ul></li></ul>
</div>
	<div id="nav-right">
	</div></div></div></div><div id="main-content">		<div class="clearpad"></div>
	<div id="message">
	    
<div id="system-message-container">
</div>
	</div>    
            <div id="leftbar-w">
    <div id="sidebar">
        	<div class="module">
        <div class="inner">
				<div class="h3c"><div class="h3r"><div class="h3l"><h3 class="module-title">Login</h3></div></div></div>
			    <div class="module-body">
	        <form action="/joomla/index.php/booking" method="post" id="login-form" >
		<fieldset class="userdata">
	<p id="form-login-username">
		<label for="modlgn-username">User Name</label>
		<input id="modlgn-username" type="text" name="username" class="inputbox"  size="18" />
	</p>
	<p id="form-login-password">
		<label for="modlgn-passwd">Password</label>
		<input id="modlgn-passwd" type="password" name="password" class="inputbox" size="18"  />
	</p>
		<p id="form-login-remember">
		<label for="modlgn-remember">Remember Me</label>
		<input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
	</p>
		<input type="submit" name="Submit" class="button" value="Log in" />
	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" value="user.login" />
	<input type="hidden" name="return" value="aW5kZXgucGhwP0l0ZW1pZD0yODA=" />
	<input type="hidden" name="a9db536a2a3f90dad3d3145d6199c120" value="1" />	</fieldset>
	<ul>
		<li>
			<a href="/joomla/index.php/using-joomla/extensions/components/users-component/password-reset">
			Forgot your password?</a>
		</li>
		<li>
			<a href="/joomla/index.php/using-joomla/extensions/components/users-component/username-reminder">
			Forgot your username?</a>
		</li>
				<li>
			<a href="/joomla/index.php/using-joomla/extensions/components/users-component/registration-form">
				Create an account</a>
		</li>
			</ul>
	</form>
        </div>
        </div>
	</div>
		<div class="module">
        <div class="inner">
			    <div class="module-body">
	        

<div class="custom"  >
	<h3> </h3>
<h3>Denoyab's Video</h3>
<p><iframe src="http://www.youtube.com/embed/YZdRlCXpmlM" frameborder="0" width="200" height="150"></iframe></p>
<p> </p></div>
        </div>
        </div>
	</div>
	</div>
<!-- MODIFY social buttons here (add yours from addthis.com) -->
   
<div id="bookmark"><div id="addthis">
<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
<a class="addthis_button_preferred_1"></a>
<a class="addthis_button_preferred_2"></a>
<a class="addthis_button_preferred_3"></a>
<a class="addthis_button_preferred_4"></a>
<a class="addthis_button_compact"></a>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4dd788572198c717"></script>
</div></div>
<!-- END of social script -->		
</div>
        	
<div id="centercontent">
<!-- Slideshow -->
		
<!-- END Slideshow -->
<div class="clearpad">
<div class="item-page">

<script src="booking_admin/assets/js/jquery.js"></script>
<script src="booking_admin/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
<link href="booking_admin/assets/css/smoothness/jquery-ui-1.10.3.custom.min.css" rel="stylesheet"> 
<script>
  $(function() {
    $( "#checkin").datepicker({ dateFormat: "yy-mm-dd" });
  });
</script>

<table style="width: 100%;">
<tbody>
<tr>
<td class="normalfont">
 <span style="font-family: arial,verdana; font-size: 10pt;">
  <span style="font-family: arial,verdana; font-size: 10pt;">
   <?php
    if (empty($_REQUEST['message'])) 
	  echo "Please fill out the form below to register yourself:";
	else
	  echo $_REQUEST['message'];
   ?>
   <br /> <br /></span></span>
<div><form action="../doregister.php" method="post"><input type="hidden" name="expid" value="%% EXPID %%" />
<table border="0" cellspacing="1" cellpadding="3">
<tbody>
<tr>
<td class="normalfont">Checkin Date:</td>
<td><input class="formInput" type="text" id="checkin" name="checkin" 
  value = '<?php echo (empty($_REQUEST['checkin'])) ? "" : $_REQUEST['checkin']; ?>' /></td>
</tr>

<tr>
<td class="normalfont">Number Of Nights:</td>
<td>
<?php
  $temp = array();
  for($i = 1; $i <= 30; $i++)
    $temp[$i] = $i;
	
  $default_id = empty($_REQUEST['number_of_nights']) ? 1 : $_REQUEST['number_of_nights'];
  echo selectfield($temp, "number_of_nights", $default_id);
  ?>
</td>
</tr>
<tr>
<td class="normalfont">Number Of People:</td>
<td>
 <?php
  $default_id = empty($_REQUEST['number_of_people']) ? 1 : $_REQUEST['number_of_people'];
  echo selectfield(array(1 => 1, 2 => 2), "number_of_people", $default_id);?>
</td>
</tr>
<tr>
<td class="normalfont">Full Name:</td>
<td><input class="formInput" type="text" name="full_name" 
 value = '<?php echo (!empty($_REQUEST['full_name'])) ? $_REQUEST['full_name'] : ""?>' /></td>
</tr>
<tr>
<td class="normalfont">Telephone:</td>
<td><input class="formInput" type="text" name="telephone" 
  value = '<?php echo (!empty($_REQUEST['telephone'])) ? $_REQUEST['telephone'] : ""?>' /></td>
</tr>
<tr>
<td class="normalfont">Email:</td>
<td><input class="formInput" type="text" name="email" 
  value = '<?php echo (!empty($_REQUEST['email'])) ? $_REQUEST['email'] : ""?>' /></td>
</tr>
<tr>
<td class="normalfont">Address:</td>
<td><textarea class="formInput" name="address">
 <?php echo (!empty($_REQUEST['address'])) ? $_REQUEST['address'] : ""?>
  </textarea></td>
</tr>
<tr>
<td class="normalfont">Country:</td>
<td>
<?php
 $arr = array('Nigeria'=>'Nigeria','United Kingdom'=>'United Kingdom','United States'=>'United States', 'Afghanistan'=>'Afghanistan','Aland Islands'=>'Aland Islands','Albania'=>'Albania','Algeria'=>'Algeria','American Samoa'=>'American Samoa','Andorra'=>'Andorra','Angola'=>'Angola','Anguilla'=>'Anguilla','Antarctica'=>'Antarctica','Antigua And Barbuda'=>'Antigua And Barbuda','Argentina'=>'Argentina','Armenia'=>'Armenia','Aruba'=>'Aruba','Australia'=>'Australia','Austria'=>'Austria','Azerbaijan'=>'Azerbaijan','Bahamas'=>'Bahamas','Bahrain'=>'Bahrain','Bangladesh'=>'Bangladesh','Barbados'=>'Barbados','Belarus'=>'Belarus','Belgium'=>'Belgium','Belize'=>'Belize','Benin'=>'Benin','Bermuda'=>'Bermuda','Bhutan'=>'Bhutan','Bolivia'=>'Bolivia','Bosnia and Herzegowina'=>'Bosnia and Herzegowina','Botswana'=>'Botswana','Bouvet Island'=>'Bouvet Island','Brazil'=>'Brazil','British Indian Ocean Territory'=>'British Indian Ocean Territory','Brunei Darussalam'=>'Brunei Darussalam','Bulgaria'=>'Bulgaria','Burkina Faso'=>'Burkina Faso','Burundi'=>'Burundi','Cambodia'=>'Cambodia','Cameroon'=>'Cameroon','Canada'=>'Canada','Cape Verde'=>'Cape Verde','Cayman Islands'=>'Cayman Islands','Central African Republic'=>'Central African Republic','Chad'=>'Chad','Chile'=>'Chile','China'=>'China','Christmas Island'=>'Christmas Island','Cocos Keeling Islands'=>'Cocos (Keeling) Islands','Colombia'=>'Colombia','Comoros'=>'Comoros','Congo'=>'Congo','Congo, the Democratic Republic of the'=>'Congo, the Democratic Republic of the','Cook Islands'=>'Cook Islands','Costa Rica'=>'Costa Rica','Cote d Ivoire'=>'Cote d Ivoire','Croatia'=>'Croatia','Cuba'=>'Cuba','Cyprus'=>'Cyprus','Czech Republic'=>'Czech Republic','Denmark'=>'Denmark','Djibouti'=>'Djibouti','Dominica'=>'Dominica','Dominican Republic'=>'Dominican Republic','Ecuador'=>'Ecuador','Egypt'=>'Egypt','El Salvador'=>'El Salvador','Equatorial Guinea'=>'Equatorial Guinea','Eritrea'=>'Eritrea','Estonia'=>'Estonia','Ethiopia'=>'Ethiopia','Falkland Islands (Malvinas)'=>'Falkland Islands (Malvinas)','Faroe Islands'=>'Faroe Islands','Fiji'=>'Fiji','Finland'=>'Finland','France'=>'France','French Guiana'=>'French Guiana','French Polynesia'=>'French Polynesia','French Southern Territories'=>'French Southern Territories','Gabon'=>'Gabon','Gambia'=>'Gambia','Georgia'=>'Georgia','Germany'=>'Germany','Ghana'=>'Ghana','Gibraltar'=>'Gibraltar','Greece'=>'Greece','Greenland'=>'Greenland','Grenada'=>'Grenada','Guadeloupe'=>'Guadeloupe','Guam'=>'Guam','Guatemala'=>'Guatemala','Guernsey'=>'Guernsey','Guinea'=>'Guinea','Guinea-Bissau'=>'Guinea-Bissau','Guyana'=>'Guyana','Haiti'=>'Haiti','Heard and McDonald Islands'=>'Heard and McDonald Islands','Holy See (Vatican City State)'=>'Holy See (Vatican City State)','Honduras'=>'Honduras','Hong Kong'=>'Hong Kong','Hungary'=>'Hungary','Iceland'=>'Iceland','India'=>'India','Indonesia'=>'Indonesia','Iran, Islamic Republic of'=>'Iran, Islamic Republic of','Iraq'=>'Iraq','Ireland'=>'Ireland','Isle of Man'=>'Isle of Man','Israel'=>'Israel','Italy'=>'Italy','Jamaica'=>'Jamaica','Japan'=>'Japan','Jersey'=>'Jersey','Jordan'=>'Jordan','Kazakhstan'=>'Kazakhstan','Kenya'=>'Kenya','Kiribati'=>'Kiribati','Korea, Democratic People's Republic of'=>'Korea, Democratic People's Republic of','Korea, Republic of'=>'Korea, Republic of','Kuwait'=>'Kuwait','Kyrgyzstan'=>'Kyrgyzstan','Lao People's Democratic Republic'=>'Lao People's Democratic Republic','Latvia'=>'Latvia','Lebanon'=>'Lebanon','Lesotho'=>'Lesotho','Liberia'=>'Liberia','Libyan Arab Jamahiriya'=>'Libyan Arab Jamahiriya','Liechtenstein'=>'Liechtenstein','Lithuania'=>'Lithuania','Luxembourg'=>'Luxembourg','Macao'=>'Macao','Macedonia, The Former Yugoslav Republic Of'=>'Macedonia, The Former Yugoslav Republic Of','Madagascar'=>'Madagascar','Malawi'=>'Malawi','Malaysia'=>'Malaysia','Maldives'=>'Maldives','Mali'=>'Mali','Malta'=>'Malta','Marshall Islands'=>'Marshall Islands','Martinique'=>'Martinique','Mauritania'=>'Mauritania','Mauritius'=>'Mauritius','Mayotte'=>'Mayotte','Mexico'=>'Mexico','Micronesia, Federated States of'=>'Micronesia, Federated States of','Moldova, Republic of'=>'Moldova, Republic of','Monaco'=>'Monaco','Mongolia'=>'Mongolia','Montenegro'=>'Montenegro','Montserrat'=>'Montserrat','Morocco'=>'Morocco','Mozambique'=>'Mozambique','Myanmar'=>'Myanmar','Namibia'=>'Namibia','Nauru'=>'Nauru','Nepal'=>'Nepal','Netherlands'=>'Netherlands','Netherlands Antilles'=>'Netherlands Antilles','New Caledonia'=>'New Caledonia','New Zealand'=>'New Zealand','Nicaragua'=>'Nicaragua','Niger'=>'Niger','Nigeria'=>'Nigeria','Niue'=>'Niue','Norfolk Island'=>'Norfolk Island','Northern Mariana Islands'=>'Northern Mariana Islands','Norway'=>'Norway','Oman'=>'Oman','Pakistan'=>'Pakistan','Palau'=>'Palau','Palestinian Territory, Occupied'=>'Palestinian Territory, Occupied','Panama'=>'Panama','Papua New Guinea'=>'Papua New Guinea','Paraguay'=>'Paraguay','Peru'=>'Peru','Philippines'=>'Philippines','Pitcairn'=>'Pitcairn','Poland'=>'Poland','Portugal'=>'Portugal','Puerto Rico'=>'Puerto Rico','Qatar'=>'Qatar','Reunion'=>'Reunion','Romania'=>'Romania','Russian Federation'=>'Russian Federation','Rwanda'=>'Rwanda','Saint Barthelemy'=>'Saint Barthelemy','Saint Helena'=>'Saint Helena','Saint Kitts and Nevis'=>'Saint Kitts and Nevis','Saint Lucia'=>'Saint Lucia','Saint Pierre and Miquelon'=>'Saint Pierre and Miquelon','Saint Vincent and the Grenadines'=>'Saint Vincent and the Grenadines','Samoa'=>'Samoa','San Marino'=>'San Marino','Sao Tome and Principe'=>'Sao Tome and Principe','Saudi Arabia'=>'Saudi Arabia','Senegal'=>'Senegal','Serbia'=>'Serbia','Seychelles'=>'Seychelles','Sierra Leone'=>'Sierra Leone','Singapore'=>'Singapore','Slovakia'=>'Slovakia','Slovenia'=>'Slovenia','Solomon Islands'=>'Solomon Islands','Somalia'=>'Somalia','South Africa'=>'South Africa','South Georgia and the South Sandwich Islands'=>'South Georgia and the South Sandwich Islands','Spain'=>'Spain','Sri Lanka'=>'Sri Lanka','Sudan'=>'Sudan','Suriname'=>'Suriname','Svalbard and Jan Mayen'=>'Svalbard and Jan Mayen','Swaziland'=>'Swaziland','Sweden'=>'Sweden','Switzerland'=>'Switzerland','Syrian Arab Republic'=>'Syrian Arab Republic','Taiwan'=>'Taiwan','Tajikistan'=>'Tajikistan','Tanzania, United Republic of'=>'Tanzania, United Republic of','Thailand'=>'Thailand','Timor-Leste'=>'Timor-Leste','Togo'=>'Togo','Tokelau'=>'Tokelau','Tonga'=>'Tonga','Trinidad and Tobago'=>'Trinidad and Tobago','Tunisia'=>'Tunisia','Turkey'=>'Turkey','Turkmenistan'=>'Turkmenistan','Turks and Caicos Islands'=>'Turks and Caicos Islands','Tuvalu'=>'Tuvalu','Uganda'=>'Uganda','Ukraine'=>'Ukraine','United Arab Emirates'=>'United Arab Emirates','United Kingdom'=>'United Kingdom','United States'=>'United States','United States Minor Outlying Islands'=>'United States Minor Outlying Islands','Uruguay'=>'Uruguay','Uzbekistan'=>'Uzbekistan','Vanuatu'=>'Vanuatu','Venezuela'=>'Venezuela','Viet Nam'=>'Viet Nam','Virgin Islands, British'=>'Virgin Islands, British','Virgin Islands, U.S.'=>'Virgin Islands, U.S.','Wallis and Futuna'=>'Wallis and Futuna','Western Sahara'=>'Western Sahara','Yemen'=>'Yemen','Zambia'=>'Zambia','Zimbabwe'=>'Zimbabwe'); 
 $default_id = empty($_REQUEST['country']) ? "Nigeria" : $_REQUEST['country'];
 echo selectfield($arr, "country", $default_id);
?>
 <!--
 <select class="formInput" id="country" name="country">
<option value="Nigeria">Nigeria</option>
<option value="United Kingdom">United Kingdom</option>
<option value="United States">United States</option><option value="" disabled="disabled">-------------</option>
<option value="Afghanistan">Afghanistan</option>
<option value="Aland Islands">Aland Islands</option>
<option value="Albania">Albania</option>
<option value="Algeria">Algeria</option>
<option value="American Samoa">American Samoa</option>
<option value="Andorra">Andorra</option>
<option value="Angola">Angola</option>
<option value="Anguilla">Anguilla</option>
<option value="Antarctica">Antarctica</option>
<option value="Antigua And Barbuda">Antigua And Barbuda</option>
<option value="Argentina">Argentina</option>
<option value="Armenia">Armenia</option>
<option value="Aruba">Aruba</option>
<option value="Australia">Australia</option>
<option value="Austria">Austria</option>
<option value="Azerbaijan">Azerbaijan</option>
<option value="Bahamas">Bahamas</option>
<option value="Bahrain">Bahrain</option>
<option value="Bangladesh">Bangladesh</option>
<option value="Barbados">Barbados</option>
<option value="Belarus">Belarus</option>
<option value="Belgium">Belgium</option>
<option value="Belize">Belize</option>
<option value="Benin">Benin</option>
<option value="Bermuda">Bermuda</option>
<option value="Bhutan">Bhutan</option>
<option value="Bolivia">Bolivia</option>
<option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option>
<option value="Botswana">Botswana</option>
<option value="Bouvet Island">Bouvet Island</option>
<option value="Brazil">Brazil</option>
<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
<option value="Brunei Darussalam">Brunei Darussalam</option>
<option value="Bulgaria">Bulgaria</option>
<option value="Burkina Faso">Burkina Faso</option>
<option value="Burundi">Burundi</option>
<option value="Cambodia">Cambodia</option>
<option value="Cameroon">Cameroon</option>
<option value="Canada">Canada</option>
<option value="Cape Verde">Cape Verde</option>
<option value="Cayman Islands">Cayman Islands</option>
<option value="Central African Republic">Central African Republic</option>
<option value="Chad">Chad</option>
<option value="Chile">Chile</option>
<option value="China">China</option>
<option value="Christmas Island">Christmas Island</option>
<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
<option value="Colombia">Colombia</option>
<option value="Comoros">Comoros</option>
<option value="Congo">Congo</option>
<option value="Congo, the Democratic Republic of the">Congo, the Democratic Republic of the</option>
<option value="Cook Islands">Cook Islands</option>
<option value="Costa Rica">Costa Rica</option>
<option value="Cote d'Ivoire">Cote d'Ivoire</option>
<option value="Croatia">Croatia</option>
<option value="Cuba">Cuba</option>
<option value="Cyprus">Cyprus</option>
<option value="Czech Republic">Czech Republic</option>
<option value="Denmark">Denmark</option>
<option value="Djibouti">Djibouti</option>
<option value="Dominica">Dominica</option>
<option value="Dominican Republic">Dominican Republic</option>
<option value="Ecuador">Ecuador</option>
<option value="Egypt">Egypt</option>
<option value="El Salvador">El Salvador</option>
<option value="Equatorial Guinea">Equatorial Guinea</option>
<option value="Eritrea">Eritrea</option>
<option value="Estonia">Estonia</option>
<option value="Ethiopia">Ethiopia</option>
<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
<option value="Faroe Islands">Faroe Islands</option>
<option value="Fiji">Fiji</option>
<option value="Finland">Finland</option>
<option value="France">France</option>
<option value="French Guiana">French Guiana</option>
<option value="French Polynesia">French Polynesia</option>
<option value="French Southern Territories">French Southern Territories</option>
<option value="Gabon">Gabon</option>
<option value="Gambia">Gambia</option>
<option value="Georgia">Georgia</option>
<option value="Germany">Germany</option>
<option value="Ghana">Ghana</option>
<option value="Gibraltar">Gibraltar</option>
<option value="Greece">Greece</option>
<option value="Greenland">Greenland</option>
<option value="Grenada">Grenada</option>
<option value="Guadeloupe">Guadeloupe</option>
<option value="Guam">Guam</option>
<option value="Guatemala">Guatemala</option>
<option value="Guernsey">Guernsey</option>
<option value="Guinea">Guinea</option>
<option value="Guinea-Bissau">Guinea-Bissau</option>
<option value="Guyana">Guyana</option>
<option value="Haiti">Haiti</option>
<option value="Heard and McDonald Islands">Heard and McDonald Islands</option>
<option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
<option value="Honduras">Honduras</option>
<option value="Hong Kong">Hong Kong</option>
<option value="Hungary">Hungary</option>
<option value="Iceland">Iceland</option>
<option value="India">India</option>
<option value="Indonesia">Indonesia</option>
<option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
<option value="Iraq">Iraq</option>
<option value="Ireland">Ireland</option>
<option value="Isle of Man">Isle of Man</option>
<option value="Israel">Israel</option>
<option value="Italy">Italy</option>
<option value="Jamaica">Jamaica</option>
<option value="Japan">Japan</option>
<option value="Jersey">Jersey</option>
<option value="Jordan">Jordan</option>
<option value="Kazakhstan">Kazakhstan</option>
<option value="Kenya">Kenya</option>
<option value="Kiribati">Kiribati</option>
<option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
<option value="Korea, Republic of">Korea, Republic of</option>
<option value="Kuwait">Kuwait</option>
<option value="Kyrgyzstan">Kyrgyzstan</option>
<option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
<option value="Latvia">Latvia</option>
<option value="Lebanon">Lebanon</option>
<option value="Lesotho">Lesotho</option>
<option value="Liberia">Liberia</option>
<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
<option value="Liechtenstein">Liechtenstein</option>
<option value="Lithuania">Lithuania</option>
<option value="Luxembourg">Luxembourg</option>
<option value="Macao">Macao</option>
<option value="Macedonia, The Former Yugoslav Republic Of">Macedonia, The Former Yugoslav Republic Of</option>
<option value="Madagascar">Madagascar</option>
<option value="Malawi">Malawi</option>
<option value="Malaysia">Malaysia</option>
<option value="Maldives">Maldives</option>
<option value="Mali">Mali</option>
<option value="Malta">Malta</option>
<option value="Marshall Islands">Marshall Islands</option>
<option value="Martinique">Martinique</option>
<option value="Mauritania">Mauritania</option>
<option value="Mauritius">Mauritius</option>
<option value="Mayotte">Mayotte</option>
<option value="Mexico">Mexico</option>
<option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
<option value="Moldova, Republic of">Moldova, Republic of</option>
<option value="Monaco">Monaco</option>
<option value="Mongolia">Mongolia</option>
<option value="Montenegro">Montenegro</option>
<option value="Montserrat">Montserrat</option>
<option value="Morocco">Morocco</option>
<option value="Mozambique">Mozambique</option>
<option value="Myanmar">Myanmar</option>
<option value="Namibia">Namibia</option>
<option value="Nauru">Nauru</option>
<option value="Nepal">Nepal</option>
<option value="Netherlands">Netherlands</option>
<option value="Netherlands Antilles">Netherlands Antilles</option>
<option value="New Caledonia">New Caledonia</option>
<option value="New Zealand">New Zealand</option>
<option value="Nicaragua">Nicaragua</option>
<option value="Niger">Niger</option>
<option value="Nigeria">Nigeria</option>
<option value="Niue">Niue</option>
<option value="Norfolk Island">Norfolk Island</option>
<option value="Northern Mariana Islands">Northern Mariana Islands</option>
<option value="Norway">Norway</option>
<option value="Oman">Oman</option>
<option value="Pakistan">Pakistan</option>
<option value="Palau">Palau</option>
<option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
<option value="Panama">Panama</option>
<option value="Papua New Guinea">Papua New Guinea</option>
<option value="Paraguay">Paraguay</option>
<option value="Peru">Peru</option>
<option value="Philippines">Philippines</option>
<option value="Pitcairn">Pitcairn</option>
<option value="Poland">Poland</option>
<option value="Portugal">Portugal</option>
<option value="Puerto Rico">Puerto Rico</option>
<option value="Qatar">Qatar</option>
<option value="Reunion">Reunion</option>
<option value="Romania">Romania</option>
<option value="Russian Federation">Russian Federation</option>
<option value="Rwanda">Rwanda</option>
<option value="Saint Barthelemy">Saint Barthelemy</option>
<option value="Saint Helena">Saint Helena</option>
<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
<option value="Saint Lucia">Saint Lucia</option>
<option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
<option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
<option value="Samoa">Samoa</option>
<option value="San Marino">San Marino</option>
<option value="Sao Tome and Principe">Sao Tome and Principe</option>
<option value="Saudi Arabia">Saudi Arabia</option>
<option value="Senegal">Senegal</option>
<option value="Serbia">Serbia</option>
<option value="Seychelles">Seychelles</option>
<option value="Sierra Leone">Sierra Leone</option>
<option value="Singapore">Singapore</option>
<option value="Slovakia">Slovakia</option>
<option value="Slovenia">Slovenia</option>
<option value="Solomon Islands">Solomon Islands</option>
<option value="Somalia">Somalia</option>
<option value="South Africa">South Africa</option>
<option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
<option value="Spain">Spain</option>
<option value="Sri Lanka">Sri Lanka</option>
<option value="Sudan">Sudan</option>
<option value="Suriname">Suriname</option>
<option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
<option value="Swaziland">Swaziland</option>
<option value="Sweden">Sweden</option>
<option value="Switzerland">Switzerland</option>
<option value="Syrian Arab Republic">Syrian Arab Republic</option>
<option value="Taiwan">Taiwan</option>
<option value="Tajikistan">Tajikistan</option>
<option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
<option value="Thailand">Thailand</option>
<option value="Timor-Leste">Timor-Leste</option>
<option value="Togo">Togo</option>
<option value="Tokelau">Tokelau</option>
<option value="Tonga">Tonga</option>
<option value="Trinidad and Tobago">Trinidad and Tobago</option>
<option value="Tunisia">Tunisia</option>
<option value="Turkey">Turkey</option>
<option value="Turkmenistan">Turkmenistan</option>
<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
<option value="Tuvalu">Tuvalu</option>
<option value="Uganda">Uganda</option>
<option value="Ukraine">Ukraine</option>
<option value="United Arab Emirates">United Arab Emirates</option>
<option value="United Kingdom">United Kingdom</option>
<option value="United States">United States</option>
<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
<option value="Uruguay">Uruguay</option>
<option value="Uzbekistan">Uzbekistan</option>
<option value="Vanuatu">Vanuatu</option>
<option value="Venezuela">Venezuela</option>
<option value="Viet Nam">Viet Nam</option>
<option value="Virgin Islands, British">Virgin Islands, British</option>
<option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
<option value="Wallis and Futuna">Wallis and Futuna</option>
<option value="Western Sahara">Western Sahara</option>
<option value="Yemen">Yemen</option>
<option value="Zambia">Zambia</option>
<option value="Zimbabwe">Zimbabwe</option></select>
-->
 </td>
</tr>
<tr>
<td class="normalfont"> </td>
<td><input class="formButton" type="submit" value="Continue" /></td>
</tr>
</tbody>
</table>
</form></div>
</td>
</tr>
</tbody>
</table>


</div> </div></div>	
    <div id="rightbar-w">
    <div id="sidebar">
         	<div class="module">
        <div class="inner">
			    <div class="module-body">
	        <div class="random-image">
	<img src="/joomla/images/random_pix1/1.jpg" alt="1.jpg" width="180" height="135" /></div>
        </div>
        </div>
	</div>
		<div class="module">
        <div class="inner">
			    <div class="module-body">
	        <div class="random-image">
	<img src="/joomla/images/random_pix2/2.jpg" alt="2.jpg" width="180" height="135" /></div>
        </div>
        </div>
	</div>
		<div class="module">
        <div class="inner">
			    <div class="module-body">
	        

<div class="custom"  >
	<h3><span style="line-height: 15.59375px;">Our Vision</span></h3>
<p><span style="line-height: 15.59375px;">Our vision is to be the preferred choice for relaxation, lodging and accommodation in terms of comfort and cleanliness, prompt service, modern and functional facilities, convenience and security.</span></p>
<p><span style="line-height: 15.59375px;"> </span></p>
<h3><span style="line-height: 15.59375px;">Our Mission</span></h3>
<p><span style="line-height: 15.59375px;">To provide affordable quality service to everyone we come in contact with, using a core of lean, efficient, polite, and highly motivated and God fearing professionals.</span></p></div>
        </div>
        </div>
	</div>
	
    </div>
    </div>
    <div class="clr"></div>
        </div>   		
        </div>     
		<div id="user-bottom">
<div class="user1">		<div class="moduletable">
					

<div class="custom"  >
	<p><img src="/joomla/images/contact2.jpg" border="0" alt="" /></p>
<p><span style="line-height: 1.3em;">Landline Phone: 234 9 8713 048</span></p>
<p><span style="line-height: 1.3em;">Mobile Phone 234 803 2389 437 </span></p>
<p><span style="line-height: 1.3em;">Mobile Phone 234 706 6233 311</span></p></div>
		</div>
	</div>
<div class="user2">		<div class="moduletable">
					

<div class="custom"  >
	<p><img src="/joomla/images/ourlocation.jpg" border="0" alt="" /></p>
<p><span style="color: #333333; font-family: Tahoma, Helvetica, Arial, sans-serif; font-size: 12px; line-height: 15.59375px;">Denoyab's Place The Comfort Zone, Plot 89 Cadastral Zone 07 -05, Biajhin, Beside Hope Center (Daughter of Charity Special School – inside), Kubwa, Abuja.</span></p></div>
		</div>
	</div>
<div class="user3">		<div class="moduletable">
					

<div class="custom"  >
	<p><img src="/joomla/images/email1.jpg" border="0" alt="" /></p>
<p><span style="line-height: 15.59375px;">
 <script type='text/javascript'>
 <!--
 var prefix = '&#109;a' + 'i&#108;' + '&#116;o';
 var path = 'hr' + 'ef' + '=';
 var addy63865 = '&#105;nf&#111;' + '&#64;';
 addy63865 = addy63865 + 'd&#101;n&#111;y&#97;bspl&#97;c&#101;' + '&#46;' + 'c&#111;m';
 document.write('<a ' + path + '\'' + prefix + ':' + addy63865 + '\'>');
 document.write(addy63865);
 document.write('<\/a>');
 //-->\n </script><script type='text/javascript'>
 <!--
 document.write('<span style=\'display: none;\'>');
 //-->
 </script>This email address is being protected from spambots. You need JavaScript enabled to view it.
 <script type='text/javascript'>
 <!--
 document.write('</');
 document.write('span>');
 //-->
 </script></span></p></div>
		</div>
	</div>
</div>

<div id="bottom">
            <div class="tg">
            Copyright 2012. 
			<a href="http://www.qualityjoomlatemplates.com" title="qjt">QualityJoomlaTemplates</a>.
</div></div></div></div>
<div class="back-bottom"><!--shadow top--></div>
</div>
</div>
</body>
</html>
