<!doctype html>
<html>
<head>
	<!-- META DATA -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php require './files/php/no_cache_link.php';?>
	
	<!-- IMPORT CSS FILES -->
	<link href="<?php no_cache_link('./files/css/default_tag_values.css');?>"
		  rel="stylesheet" type="text/css">
	<link href="<?php no_cache_link('./files/css/default_blocks.css');?>"
		  rel="stylesheet" type="text/css">
	<link href="<?php no_cache_link('./files/css/scrollbar.css');?>"
		  rel="stylesheet" type="text/css">
	<link href="<?php no_cache_link('./files/css/elements.css');?>"
		  rel="stylesheet" type="text/css">
	<!-- MAP CSS -->
	<link rel="stylesheet" href="https://npmcdn.com/leaflet@1.0.0-rc.2/dist/leaflet.css" />
	
	<!-- TITLE AND ICON -->
	<title>GeoRacers</title>
	<link rel="icon" href="./files/ico/icon2.png" type="image/x-icon">
	
	<!-- LOAD GOOGLE FONTS -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	
	<!-- SCRIPTS -->
	<script src="<?php no_cache_link('./files/js/viewscreen_units.js');?>"></script>
	<script src="<?php no_cache_link('./files/js/link_to.js');?>"></script>
	<script src="<?php no_cache_link('./files/js/game_size.js');?>"></script>
<!--<script src="<?php no_cache_link('./files/js/get_location.js');?>"></script>-->
	<script src="<?php no_cache_link('./files/js/data_init.js');?>"></script>
	<script src="<?php no_cache_link('./files/js/render_polyline.js');?>"></script>
	<script src="<?php no_cache_link('./files/js/decode.js');?>"></script>
	<script src="<?php no_cache_link('./files/js/ajax.js');?>"></script>
</head>

<body>
	<!-- MAIN 16x9 CONTAINER -->
	<main id="main">
		<!-- MAP ELEMENT -->
		<div id="map" style="min-width: 80%; max-width: 80%; height: 100%"></div>
		<!-- SET MAP ELEMENT WIDTH TO WIDTH OF 16x9 CONTAINER TO SOLVE MAP LOAD BUG -->
		<script>
			let el_temp = document.getElementById("main");
			let map_temp = document.getElementById("map");
			let tower_prices = [100, 200, 250, 500];
			let w_temp = vw(100) / 16;
			let h_temp = vh(100) / 9;
			if(w_temp > h_temp) { //if width bigger => wider than 16x9 => fit by height
				el_temp.classList.add("fit_height");
				el_temp.classList.remove("fit_width");
			}
			else { //else fit by width
				el_temp.classList.add("fit_width");
				el_temp.classList.remove("fit_height");
			}
			document.getElementsByTagName("html")[0].style.fontSize = el_temp.clientWidth / 100 + "px";
			map_temp.style.width = el_temp.clientWidth / 10 * 8;
		</script>
		<!-- MAP MODULES -->
		<script src="https://npmcdn.com/leaflet@1.0.0-rc.2/dist/leaflet.js"></script>
		<script src="https://code.jquery.com/jquery-2.2.4.min.js"
				integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
				crossorigin="anonymous"></script>
		<!-- CREATE MAP TILES -->
		<script>
			//make a map using osm tiles
			var map = L.map('map').setView([45.756483, 21.228704], 15);
			L.tileLayer('http://tile.openstreetmap.org/{z}/{x}/{y}.png', {
				maxZoom: 18,
				attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributers'
			}).addTo(map);
		</script>
		
		
		
		
		<!-- TOWERS -->
		<script>
			function onMapClick(e) {
				let str = e.latlng.toString().replace(/\s/g, '').slice(7);
					str = str.slice(0, -1);
				let str_lat = Number(str.split(',')[0]);
				let str_lng = Number(str.split(',')[1]);
				
				let is_tower = false;
				let is_tower_nr = 0;
				for(let i=0; i<tower_array.length; i++)
				{
					if(str_lat < (tower_array[i].lat + lat_inc) &&
					   str_lng < (tower_array[i].lng + lng_inc) &&
					   str_lat > (tower_array[i].lat - lat_inc) &&
					   str_lng > (tower_array[i].lng - lng_inc))
					{
						is_tower = true;
						is_tower_nr = i;
					}
				}
				
				console.log(selected_tower);
				if(is_tower == true)
				{
					tower_array[is_tower_nr].popup.openOn(map);
				}
				else if(selected_tower > 0 && money >= tower_prices[selected_tower-1])
				{
					tower_array.push(new tower(selected_tower, str_lat, str_lng));
					money -= tower_prices[selected_tower-1];
					document.getElementById("money").innerHTML = money;
				}
				else if(selected_tower > 0 && money < tower_prices[selected_tower-1]) {alert("You're too poor.");}
				else {alert("Please select a tower!");} //display no-tower-selected message ==========================
				
				
//				L.circle([str_lat + (str_lat_inc / 2), str_lng + (str_lng_inc / 2)], {
//					color: 'red',
//					fillColor: '#f03',
//					fillOpacity: 0.5,
//					radius: 500
//				}).addTo(map);
			}
			map.on('click', onMapClick);
		</script>
		<!-- SPAWNER -->
		<script>
			let spawner_array = new Array();
			let enemy_array = new Array();
			
			setInterval(function() {
				for(let i=0; i<spawner_array.length; i++)
				{
					if(spawner_array[i])
						if(spawner_array[i].active)
							spawner_array[i].spawn(enemy_array);
				}
			}, 5000);
		</script>
		<!-- CAR PATH -->
		<?php require './files/php/path.php';?><?php echo $path_out;?>
		<textarea id="txt_car" style="visibility: hidden;">mqevGors`CC@e@gBaFxCeEpB}AtA_@d@q@hAiAvCUx@i@|Cg@jFGTUr@wA`ByC`C}BbBi@PoBzA{DfDSHYDc@CaDy@oB{@WWeDiA_@KmAM{BImA?_Ao@KCa@NOT_BlD]bAw@nBS|@cA|Cg@nBIdAW~IO?MDMJINGRAR?TBTu@`@kM|FiCpAo@\y@F{@RoBRGFCVX|Bv@rEj@~D^nBj@pBd@hATt@f@dAt@nA~@rA|AzAfBhAbBt@xDrA`G|APCfIdCRPx@XTFT?`Bf@NNxCx@fBl@dAX\Bz@VdBPn@B|@Qf@Op@Sj@EJFd@AMoAU{Aq@{D}CsSPSjD{Hz@yAb@aAL_@z@eFJUNMnA]HGfDw@vCi@~AVPJhCb@hAFnABlDQ`WsBHBHJVn@`@rAJLf@CRKRUF[?YFg@\{Af@uBNy@T}BBgBEoBKsAUaBkBwHkAaEkDgJYaAeBgHc@kBGc@iB{GqCsKFa@?O_@gBKIM@</textarea>
		<textarea id="txt_enemy" style="visibility: hidden;"></textarea>
		<script>
			//spawn car
			let encoded_c_path = document.getElementById("txt_car").value;
			let decoded_c_path = decode(encoded_c_path, 1e5);
			render(encoded_c_path);
			let c_lat = decoded_c_path[0][1]; let c_lng = decoded_c_path[0][0];
			let c_lat_old = c_lat; let c_lng_old = c_lng;
			let car_img;
			c_lat = Number(c_lat.toFixed(6)); c_lng = Number(c_lng.toFixed(6));
			let car = L.imageOverlay('./files/ico/car_right.png',[
						[c_lat-(lat_inc/2), c_lng-(lng_inc/2)],
						[c_lat+(lat_inc/2), c_lng+(lng_inc/2)]
					]).addTo(map);
			
			
			let i = 0;
			// move car and enemies
			setInterval(function() {
				//car
				i++;
				if(decoded_c_path[i]==null)i=0;
				c_lat_old = c_lat;
				c_lng_old = c_lng;
				c_lat = Number(decoded_c_path[i][1].toFixed(6));
				c_lng = Number(decoded_c_path[i][0].toFixed(6));
				
				if(c_lng>c_lng_old)
					car_img = './files/ico/car_right.png';
				else
					car_img = './files/ico/car_left.png';
				
				map.removeLayer(car);
				car = L.imageOverlay(car_img, [
							[c_lat-(lat_inc/2), c_lng-(lng_inc/2)],
							[c_lat+(lat_inc/2), c_lng+(lng_inc/2)]
						]).addTo(map);
				
				
				//enemy
				for(let i=0; i<enemy_array.length; i++)
				{
					if(enemy_array[i])
					{
						enemy_array[i].move();
					}
				}
				
				//get shot by tower
				for(let i=0; i<tower_array.length; i++) {
					let distance = 0;
					let min_distance = 0;
					let min_id = -1;
					
					for(let j=0; j<enemy_array.length; j++) {
						distance = 0;
						distance += (tower_array[i].lat - enemy_array[j].lat) * (tower_array[i].lat - enemy_array[j].lat);
						distance += (tower_array[i].lng - enemy_array[j].lng) * (tower_array[i].lng - enemy_array[j].lng);
						distance = Math.sqrt(distance);
						
						if (min_id == -1 || distance < min_distance) {
							min_id = j;
							min_distance = distance;
						}
					}
					
					if (min_id > -1 && min_distance <= tower_array[i].range) {
						enemy_array[min_id].take_damage(tower_array[i].damage);
					}
				}
				
				//check if close to car
				for(let i=0; i<enemy_array.length; i++)
				{
					let distance = 0;
					
					distance += (c_lat - enemy_array[i].lat) * (c_lat - enemy_array[i].lat);
					distance += (c_lng - enemy_array[i].lng) * (c_lng - enemy_array[i].lng);
					distance = Math.sqrt(distance);
					
					if(distance <= 0.0015)
						enemy_array[i].hit_car();
				}
			}, 1000);
			//path for enemy
			setInterval(function() {
				for(let i=0; i<enemy_array.length; i++)
				{
					if(enemy_array[i])
					{
						function completedAJAX(response) {
							document.getElementById("txt_enemy").innerHTML = response;
							enemy_array[i].update_path(decode(document.getElementById("txt_enemy").value, 1e5));
						}
						
						function send_AJAX() {
							let data = new Array();
							data[0] = new Array();
							data[1] = new Array();
							
							data[0][0] = enemy_array[i].lat;
							data[0][1] = enemy_array[i].lng;
							data[1][0] = c_lat;
							data[1][1] = c_lng;
							
							let parameters = JSON.stringify(data);
							AjaxPost("./files/php/path.php", parameters, completedAJAX);
						}
						send_AJAX();
					}
				}
			}, 5000)
			
		</script>
		<script>
			// local python AI api call via sql
			
//[24:36, 5/14/2023] +40 773 310 928: CE TRIMITE AI:
//{
//"ACTION_TYPE": [-1, 0, 1]
//"LATITUDE": [-0.005, 0.005]
//"LONGITUDE": [-0.005, 0.005]
//}
//[24:37, 5/14/2023] +40 773 310 928: CE AM NEVOIE:
//{
//"CLOSEST_DEATH": 0.25
//"OBJECTS": [
//   { "TYPE" : [1,2,3,100], "LAT": 0.25, "LONG": 0.25 },
//   { "TYPE" : ... }
//]
			let ai_data_lat; let ai_data_lng;
			let j = 0;
				
			let ai_data_send = new Object();
			let ai_data_receive; let ai_data_receive_d;
			
			setInterval(function() {
				j = 0;
				ai_data_send["CLOSEST_DEATH"] = closest_death;
				closest_death = 100;
				ai_data_send["OBJECTS"] = new Array();
				for(let i=0; i<tower_array.length; i++)
				{
					if(tower_array[i].active)
					{
						ai_data_lat = tower_array[i].lat - c_lat;
						ai_data_lng = tower_array[i].lng - c_lng;
						
						if(ai_data_lat >= -0.02 &&
						   ai_data_lat <= 0.02 &&
						   ai_data_lng >= -0.02 &&
						   ai_data_lng <= 0.02)
						{
							ai_data_send["OBJECTS"][j] = new Object();
							ai_data_send["OBJECTS"][j]["TYPE"] = tower_array[i].id;
							ai_data_send["OBJECTS"][j]["LAT"] = Number(ai_data_lat.toFixed(4));
							ai_data_send["OBJECTS"][j]["LNG"] = Number(ai_data_lng.toFixed(4));
							j++;
						}
					}
				}
				for(let i=0; i<spawner_array.length; i++)
				{
					if(spawner_array[i].active)
					{
						ai_data_lat = spawner_array[i].lat - c_lat;
						ai_data_lng = spawner_array[i].lng - c_lng;
						
						if(ai_data_lat >= -0.02 &&
						   ai_data_lat <= 0.02 &&
						   ai_data_lng >= -0.02 &&
						   ai_data_lng <= 0.02)
						{
							ai_data_send["OBJECTS"][j] = new Object();
							ai_data_send["OBJECTS"][j]["TYPE"] = 100;
							ai_data_send["OBJECTS"][j]["LAT"] = Number(ai_data_lat.toFixed(4));
							ai_data_send["OBJECTS"][j]["LNG"] = Number(ai_data_lng.toFixed(4));
							j++;
						}
						else spawner_array[i].kill();
					}
				}
				
				function completedAJAX(response) {
					console.log("AI Response = " + response);
					if(response == "") return;
					ai_data_receive = JSON.parse(response);
					
					console.log(c_lat + " " + c_lng);
					console.log(ai_data_receive["LAT"] + " " + ai_data_receive["LNG"]);
					
					ai_data_receive["LAT"] = c_lat + ai_data_receive["LAT"];
					ai_data_receive["LNG"] = c_lng + ai_data_receive["LNG"];
					
					console.log("Result = " + ai_data_receive["LAT"] + " " + ai_data_receive["LNG"]);
					
					if(ai_data_receive["TYPE"]==1)
					{
						spawner_array.push(new spawner(ai_data_receive["LAT"],ai_data_receive["LNG"]));
					}
					if(ai_data_receive["TYPE"]==-1)
					{
						let min_distance = -1;
						let min_id = -1;
						
						for(let i=0; i<spawner_array.length; i++)
						{
							let distance = 0;
							
							distance += (ai_data_receive["LAT"] - spawner_array[i].lat)
									  * (ai_data_receive["LAT"] - spawner_array[i].lat);
							distance += (ai_data_receive["LNG"] - spawner_array[i].lng)
									  * (ai_data_receive["LNG"] - spawner_array[i].lng)
							distance = Math.sqrt(distance);
							
							if(min_distance == -1 || distance < min_distance)
							{
								min_distance = distance;
								min_id = i;
							}
						}
						
						if(min_id >= 0)
						{
							console.log("Spawner deleted at: " + spawner_array[min_id].lat + " " + spawner_array[min_id].lng);
							
							spawner_array[min_id].kill();
						}
					}
						
				}
				
				AjaxPost("./files/php/web_post.php", JSON.stringify(ai_data_send), completedAJAX);
				console.log("AI Request = " + JSON.stringify(ai_data_send));
				
				for(let i=0; i<spawner_array.length; i++)
				{
					
				}
			}, 5000);
		</script>
		<script>
			//tower select
			function select_tower(id)
			{
				selected_tower = id;
				let el = document.querySelector(".tower_shop");
				for(let i=0; i<tower_types; i++)
					el.children[i].style.backgroundColor = "";
				if(id)
					el.children[id-1].style.backgroundColor = "#E1D4BB";
			}
		</script>
		<!-- SIDE MENU -->
		<div id="pannel" onclick="select_tower(0); event.stopPropagation()">
			<div class="row centered">
				<p class="title">
					GeoRacer Defence
				</p>
				<img class="icon_title" src="./files/ico/icon2.png" alt="">
			</div>
			<div class="column centered">
				<p class="subtitle">
					Current Location:
				</p>
				<div class="row">
					<p class="text" id="show_lat">Location</p>
				</div>
				<div class="row">
					<p class="text" id="show_lng">Disabled</p>
				</div>
			</div>
			<div class="column centered">
				<p class="subtitle">
					Stats:
				</p>
				<div class="row centered">
					<img class="icon" src="./files/ico/heart.png" alt="">
					<p id="health" class="text">100</p>
					<script>document.getElementById("health").innerHTML = health;</script>
				</div>
				<div class="row centered">
					<img class="icon" src="./files/ico/money.png" alt="">
					<p id="money" class="text">100</p>
					<script>document.getElementById("money").innerHTML = money;</script>
				</div>
				<div class="row centered">
					<p class="text">Score:&nbsp;</p>
					<p id="score" class="text">0</p>
					<script>document.getElementById("score").innerHTML = score;</script>
				</div>
			</div>
			<div class="centered column">
				<p class="subtitle">
					Shop:
				</p>
				<div class="centered tower_shop">
					<div class="centered column" onclick="select_tower(1); event.stopPropagation()">
						<img class="icon" src="./files/ico/tower_clasic.png" alt="">
						<p class="text">100$</p>
					</div>
					<div class="centered column" onclick="select_tower(2); event.stopPropagation()">
						<img class="icon" src="./files/ico/tower_damage.png" alt="">
						<p class="text">200$</p>
					</div>
					<div class="centered column" onclick="select_tower(3); event.stopPropagation()">
						<img class="icon" src="./files/ico/tower_fire.png" alt="">
						<p class="text">250$</p>
					</div>
					<div class="centered column" onclick="select_tower(4); event.stopPropagation()">
						<img class="icon" src="./files/ico/tower_electric.png" alt="">
						<p class="text">500$</p>
					</div>
					<div class="centered column">
						<img class="icon" src="./files/ico/coming_soon.png" alt="">
						<p class="text">???</p>
					</div>
					<div class="centered column">
						<img class="icon" src="./files/ico/coming_soon.png" alt="">
						<p class="text">???</p>
					</div>
				</div>
			</div>
			
		</div>
		
		
		
		<!--  -->
		<script></script>
		
		
		

	</main>
</body>
</html>
