// JavaScript Document


/* USAGE:

function completedAJAX(response) {
	alert(response);
}

function testPass() {
	var parameters = {
		"uname" : document.getElementById("uname").value,
		"passwd" : document.getElementById("passwd").value
	};
	
	AjaxPost("authenticate.php", parameters, completedAJAX);
}
*/

function createAjaxRequestObject() {
	var xmlhttp;
	
	if(window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else { // code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	// Create the object
	return xmlhttp;
}

function AjaxPost(ajaxURL, parameters, onComplete) {
	var http3 = createAjaxRequestObject();
	
	http3.onreadystatechange = function() {
		if(http3.readyState == 4) {
			if(http3.status == 200) {
				if(onComplete) {
					onComplete(http3.responseText);
				}
			}
		}
	};
	
	// Create parameter string for application/x-www-form-urlencoded
//	var parameterString = "";
//	var isFirst = true;
//	for(var index in parameters) {
//		if(!isFirst) {
//			parameterString += "&";
//		} 
//		parameterString += encodeURIComponent(index) + "=" + encodeURIComponent(parameters[index]);
//		isFirst = false;
//	}
	
	// Make request
	http3.open("POST", ajaxURL, true);
	http3.setRequestHeader("Content-type", "application/json");
	http3.send(parameters);
}






















