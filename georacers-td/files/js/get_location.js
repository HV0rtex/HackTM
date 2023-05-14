// JavaScript Document



let c_lat;
let c_lng;

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(setPosition);
  } else { 
    c_lat = "-";
	c_lng = "-";
    document.getElementById("show_lat").innerHTML = "Location";
    document.getElementById("show_lng").innerHTML = "Disabled";
  }
}

function setPosition(position) {
  c_lat = position.coords.latitude; 
  c_lng = position.coords.longitude;
  document.getElementById("show_lat").innerHTML = "Lat: " + c_lat;
  document.getElementById("show_lng").innerHTML = "Lng: " + c_lng;
}

window.addEventListener('load', function() {
	setInterval(getLocation(), 500);
}, true)