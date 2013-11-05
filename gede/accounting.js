function get_sub_accounts(id) {
  var sIndex = document.form1.account_id.selectedIndex;
  var account_id = document.form1.account_id.options[sIndex].value;
  var host = window.location.hostname;
  var url = "http://" + host + "/profile_accounting/get_sub_accounts.php?account_id=" + account_id + "&rand=" + Math.random();
  get_objects(url, id);
}
function get_objects(url, target_id) {
   if (window.XMLHttpRequest) {
    agax = new XMLHttpRequest();
   } else if (window.ActiveXObject) {
    agax = new ActiveXObject('Microsoft.XMLHTTP');
   }
   if (agax) {
     agax.open('GET', url, true);
     agax.onreadystatechange = function () {
       if (agax.readyState == 4 && agax.status == 200) {
         var agaxText = agax.responseText;
         document.getElementById(target_id).innerHTML = agaxText;
       }};
     agax.send(null);
   } else {
    alert("Error in Connecting to server");
  }
}

function get_permissions_in_u_permissions() {
  var element = document.form1.u_permissions;
  var value="";
  for(var i = 0; i < element.options.length; i++) {
    //if (element.options[i].selected) 
    if (i == (element.options.length - 1)) {
      value += element.options[i].value;
    } else {  
      value += element.options[i].value + "|";
    }
  }
  document.form1.u_permissions_members.value = value;
  //alert(document.form1.u_permissions_members.value);
}

function transfer() {
   //Get the subject selected
   var sIndex = document.form1.pid.selectedIndex;
   var len = document.form1.pid.options.length;
   if ((sIndex < 0) || (sIndex >= len)) {
     alert('Please choose a permission to add');
     return;
   }
   var ptext = document.form1.pid.options[sIndex].text;
   var pvalue = document.form1.pid.options[sIndex].value;

   //Create a new Option Object
   var new_pid = new Option(ptext, //The text property
                               pvalue, //The value property
                               false,   // The defaultSelected property 
                               false);  // The selected property

   //Display it in s_permissions element by appending it to the options array
   var u_permissions = document.form1.u_permissions;
   u_permissions.options[u_permissions.options.length]=new_pid;

   //Remove the subject from class_subject element
   document.form1.pid.options[sIndex] = null;
  
   get_permissions_in_u_permissions();
}


function transfer2() {
   //Get the student in the class 
   var sIndex = document.form1.u_permissions.selectedIndex;
   var len = document.form1.u_permissions.options.length;
   if ((sIndex < 0) || (sIndex >= len)) {
     alert('Please choose a permission to remove');
     return;
   }
   var ptext = document.form1.u_permissions.options[sIndex].text;
   var pvalue = document.form1.u_permissions.options[sIndex].value;

   //Create a new Option Object
   var new_p = new Option(ptext, //The text property
                          pvalue, //The value property
                               false,   // The defaultSelected property 
                               false);  // The selected property

   //Display it in class_subject element by appending it to the options array
   var pid = document.form1.pid
   pid.options[pid.options.length]=new_p;

   //Remove the student from student element
   document.form1.u_permissions.options[sIndex] = null;

   get_permissions_in_u_permissions();
}

