
$( document ).ready(function() {
   if (document.getElementById('tfq').value != ''){
	   ajaxrequestAll('phpfiles/processrec.php');
   }
});

// create the XMLHttpRequest object, according browser
function get_XmlHttp() {
  // create the variable that will contain the instance of the XMLHttpRequest object (initially with null value)
  var xmlHttp = null;

  if(window.XMLHttpRequest) {		// for Forefox, IE7+, Opera, Safari, ...
    xmlHttp = new XMLHttpRequest();
  }
  else if(window.ActiveXObject) {	// for Internet Explorer 5 or 6
    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
  }

  return xmlHttp;
}

//To change button value
function changeButtonVal(rowNum){
	if(rowNum == 1){
		var myButton1 = document.getElementById("myButton1");
		if (myButton1.value == 'Trusted'){
			myButton1.value = 'Rated';
			$("button#myButton1").attr("data-original-title","Highly Voted Question");
			myButton1.innerHTML = 'Rated';
		
		}else {
			myButton1.value = 'Trusted';
			$("button#myButton1").attr("data-original-title","Highly Reputed User");
			myButton1.innerHTML = 'Trusted';
		}
	ajaxrequestRowWise('phpfiles/processrec.php', 'row1');
	} else {
		var myButton2 = document.getElementById("myButton2");
		if (myButton2.value == 'Verbatim'){
			myButton2.value = 'Popular';
			$("button#myButton2").attr("data-original-title","Many Answers");
			myButton2.innerHTML = 'Popular';
		
		}else {
			myButton2.value = 'Verbatim';
			$("button#myButton2").attr("data-original-title","Maximal String Match");
			myButton2.innerHTML = 'Verbatim';
		}
	ajaxrequestRowWise('phpfiles/processrec.php', 'row2');
	}
}


//To initialize tooltip
$( window ).load(function () {
  $('[data-toggle="tooltip"]').tooltip({container: 'body'})
});

//To initialize toggle button
$( window ).load(function() {
	$('#anscheck').bootstrapToggle();
});

function ajaxrequestAll(php_file){
	ajaxrequestRowWise(php_file ,'row1');
	ajaxrequestRowWise(php_file ,'row2');
}

function ajaxrequestRowWise(php_file, row){
	if(row == 'row1'){
		ajaxrequest(php_file, 'max-rec1');
		ajaxrequest(php_file, 'max-rec2');
	} else {
		ajaxrequest(php_file, 'max-rec3');
		ajaxrequest(php_file, 'max-rec4');		
	}
}

// sends data to a php file, via POST, and displays the received answer
function ajaxrequest(php_file, tagID) {
  var request =  get_XmlHttp();		// calls the function for the XMLHttpRequest instance

  // gets data from form fields, using their ID
  //var nume = document.getElementById('nume').value;
  var tfq = document.getElementById('tfq').value;
  
  if(tfq == "")
	  return;
  var strUser = '';
  // get the attribute value that is selected
  if ((tagID === 'max-rec1') || (tagID === 'max-rec2')){
	  var e = document.getElementById("myButton1");
	  strUser = e.innerHTML;
  }else {
	  var e = document.getElementById("myButton2");
	  strUser = e.innerHTML;
  }

  // Check weather answer checkbox is checked or not
  var answer = '';
  if(document.getElementById('anscheck').checked){
	  answer = ' AND ans_count > 0 ';
  }
  // create pairs index=value with data that must be sent to server
  //var  the_data = 'nume='+nume+'&tfq='+tfq;
  var  the_data ='tagID='+tagID+'&tfq='+tfq+'&strUser='+strUser+'&answer='+answer;

  request.open("POST", php_file, true);			// sets the request

  // adds a header to tell the PHP script to recognize the data as is sent via POST
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.send(the_data);		// sends the request

  // Check request status
  // If the response is received completely, will be transferred to the HTML tag with tagID
  request.onreadystatechange = function() {
    if (request.readyState == 4 && request.status==200) {
      document.getElementById(tagID).innerHTML = request.responseText;
	  
	  // media query event handler
	if (matchMedia) {
		var mq = window.matchMedia("(min-width: 500px)");
		mq.addListener(WidthChange);
		WidthChange(mq);
	}

	// media query change
	function WidthChange(mq) {

		if (mq.matches) {
			// window width is at least 500px
			document.getElementById(tagID).style.fontSize = '13px';
		}
		else {
			// window width is less than 500px
			document.getElementById(tagID).style.fontSize = '11px';
		}

	}
    }
  }
}

function Hide(arrTag, tagID) {
  var request =  get_XmlHttp();		// calls the function for the XMLHttpRequest instance
  // create pairs index=value with data that must be sent to server
  //var  the_data = 'nume='+nume+'&tfq='+tfq;
  var  the_data ='title='+arrTag;

  request.open("POST", "phpfiles/hide.php", true);			// sets the request

  // adds a header to tell the PHP script to recognize the data as is sent via POST
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.send(the_data);		// sends the request

  // Check request status
  // If the response is received completely, will be transferred to the HTML tag with tagID
  request.onreadystatechange = function() {
    if (request.readyState == 4 && request.status==200) {
		ajaxrequest("phpfiles/processrec.php", "max-rec2");
		ajaxrequest("phpfiles/processrec.php", "max-rec4");
		//document.getElementById(tagID).innerHTML = request.responseText;
    }
  }
}

// To submit query on Enter

$(function() {
    $('form').each(function() {
        $(this).find('input').keypress(function(e) {
            // Enter pressed?
            if(e.which == 10 || e.which == 13) {
                ajaxrequestAll("phpfiles/processrec.php");
            }
        });

        //$(this).find('input[type=submit]').hide();
    });
});


