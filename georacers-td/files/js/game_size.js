// JavaScript Document



window.addEventListener('DOMContentLoaded', function() {
	let el_16x9 = document.getElementById("main");
	let map = document.getElementById("map");
	function fit_game() {
		let w_16x9 = vw(100) / 16;
		let h_16x9 = vh(100) / 9;
		if(w_16x9 > h_16x9) { //if width bigger => wider than 16x9 => fit by height
			el_16x9.classList.add("fit_height");
			el_16x9.classList.remove("fit_width");
		}
		else { //else fit by width
			el_16x9.classList.add("fit_width");
			el_16x9.classList.remove("fit_height");
		}
		document.getElementsByTagName("html")[0].style.fontSize = el_16x9.clientWidth / 100 + "px";
	}
	fit_game();
	window.addEventListener('resize', function() {
		fit_game();
	}, true)
}, true)
