// JavaScript Document



let health = 100;
let money = 2000;
let score = 0;
let selected_tower = 0;
let tower_array = new Array();
let tower_count = 0;
let tower_types = 4;

let closest_death = 100;
let ai_data_cd = 0;

let lat_inc = 0.0015;
let lng_inc = 0.002;

class tower_p {
	constructor(price)
	{
		this.price = price;
	}
}
const tower_clasic = new tower_p(100);
const tower_damage = new tower_p(200);
const tower_fire = new tower_p(250);
const tower_electric = new tower_p(500);

class tower {
	constructor(id, lat, lng)
	{
		this.lat = lat;
		this.lng = lng;
		this.id = id;
		if(id==1)
		{
			this.type = "tower_clasic"
			this.damage = 2;
			this.range = 0.0055;
			this.speed = 1;
			this.sell = 50;
			this.active = true;
			this.circle = L.circle([lat, lng],
						{
							color: 'blue',
							fillColor: 'lightblue',
							fillOpacity: 0.3,
							radius: 500
						}).addTo(map);
			this.object = L.imageOverlay('./files/ico/tower_clasic.png',[
							[lat-(lat_inc/2), lng-(lng_inc/2)],
							[lat+(lat_inc/2), lng+(lng_inc/2)]
						]).addTo(map);
			this.popup = L.popup().setLatLng([lat, lng])
						.setContent("<b>Clasic Tower</b>"
									+ "<br>damage: " + this.damage
									+ "<br>range: " + (this.range * 1000)
								    + "<br>speed: " + 1 / this.speed
								    + "<br><span style='cursor: pointer; color: red;' "
									+ "onclick='tower_sell(" + this.lat + "," + this.lng + ");'>"
									+ "Sell for " + this.sell + "$</span>");
		}
		if(id==2)
		{
			this.type = "tower_damage"
			this.damage = 5;
			this.range = 0.003;
			this.speed = 1;
			this.sell = 100;
			this.active = true;
			this.circle = L.circle([lat, lng],
						{
							color: 'red',
							fillColor: '#f03',
							fillOpacity: 0.3,
							radius: 300
						}).addTo(map);
			this.object = L.imageOverlay('./files/ico/tower_damage.png',[
							[lat-(lat_inc/2), lng-(lng_inc/2)],
							[lat+(lat_inc/2), lng+(lng_inc/2)]
						]).addTo(map);
			this.popup = L.popup().setLatLng([lat, lng])
						.setContent("<b>Damage Tower</b>"
									+ "<br>damage: " + this.damage
									+ "<br>range: " + (this.range * 1000)
								    + "<br>speed: " + 1 / this.speed
								    + "<br><span style='cursor: pointer; color: red;' "
									+ "onclick='tower_sell(" + this.lat + "," + this.lng + ");'>"
									+ "Sell for " + this.sell + "$</span>");
		}
		if(id==3)
		{
			this.type = "tower_fire"
			this.damage = 10;
			this.range = 0.0015;
			this.speed = 1;
			this.sell = 100;
			this.active = true;
			this.circle = L.circle([lat, lng],
						{
							color: 'orange',
							fillColor: 'orange',
							fillOpacity: 0.3,
							radius: 150
						}).addTo(map);
			this.object = L.imageOverlay('./files/ico/tower_fire.png',[
							[lat-(lat_inc/2), lng-(lng_inc/2)],
							[lat+(lat_inc/2), lng+(lng_inc/2)]
						]).addTo(map);
			this.popup = L.popup().setLatLng([lat, lng])
						.setContent("<b>Fire Tower</b>"
									+ "<br>damage: " + this.damage
									+ "<br>range: " + (this.range * 1000)
								    + "<br>speed: " + 1 / this.speed
								    + "<br><span style='cursor: pointer; color: red;' "
									+ "onclick='tower_sell(" + this.lat + "," + this.lng + ");'>"
									+ "Sell for " + this.sell + "$</span>");
		}
		if(id==4)
		{
			this.type = "tower_electric"
			this.damage = 4;
			this.range = 0.007;
			this.speed = 1;
			this.sell = 200;
			this.active = true;
			this.circle = L.circle([lat, lng],
						{
							color: 'purple',
							fillColor: 'violet',
							fillOpacity: 0.3,
							radius: 700
						}).addTo(map);
			this.object = L.imageOverlay('./files/ico/tower_electric.png',[
							[lat-(lat_inc/2), lng-(lng_inc/2)],
							[lat+(lat_inc/2), lng+(lng_inc/2)]
						]).addTo(map);
			this.popup = L.popup().setLatLng([lat, lng])
						.setContent("<b>Electric Tower</b>"
									+ "<br>damage: " + this.damage
									+ "<br>range: " + (this.range * 1000)
								    + "<br>speed: " + 1 / this.speed
								    + "<br><span style='cursor: pointer; color: red;' "
									+ "onclick='tower_sell(" + this.lat + "," + this.lng + ");'>"
									+ "Sell for " + this.sell + "$</span>");
		}
	}
	sell_and_kill()
	{
		map.removeLayer(this.circle);
		map.removeLayer(this.object);
		map.removeLayer(this.popup);
		this.active = false;
		money += this.sell;
		document.getElementById("money").innerHTML = money;
	}
	game_end()
	{
		tower_kill(this.lat,this.lng);
	}
}

let enemy_id = 0;
class enemy {
	constructor(id, lat, lng)
	{
		this.lat = lat;
		this.lng = lng;
		this.uid = enemy_id;
		enemy_id++;
		if(id==1)
		{
			this.type = "basic_enemy"
			this.damage = 10;
			this.health = 20;
			this.speed = 0.0004;
			this.object = L.imageOverlay('./files/ico/enemy_right.png',[
							[lat-(lat_inc/2), lng-(lng_inc/2)],
							[lat+(lat_inc/2), lng+(lng_inc/2)]
						]).addTo(map);
		}
	}
	take_damage(damage)
	{
		this.health -= damage;
		if(this.health<=0)
		{
			map.removeLayer(this.object);
			enemy_kill(this.uid);
			money += 10;
			document.getElementById("money").innerHTML = money;
			score++;
			document.getElementById("score").innerHTML = score;
		}
	}
	hit_car()
	{
		health -= this.damage;
		document.getElementById("health").innerHTML = health;
		if(health <= 0)game_over();
		map.removeLayer(this.object);
		enemy_kill(this.uid);
	}
	update_path(path)
	{
		this.path = path;
		this.index = 0;
	}
	move()
	{
		if(!this.path) return;
		this.index++;
		if(this.path[this.index]==null)this.index=0;
		this.lat_old = this.lat;
		this.lng_old = this.lng;
		this.lat = Number(this.path[this.index][1].toFixed(6));
		this.lng = Number(this.path[this.index][0].toFixed(6));
		
		if(this.lng>this.lng_old)
			this.enemy_img = './files/ico/enemy_right.png';
		else
			this.enemy_img = './files/ico/enemy_left.png';
		
		map.removeLayer(this.object);
		this.object = L.imageOverlay(this.enemy_img ,[
					[this.lat-(lat_inc/2), this.lng-(lng_inc/2)],
					[this.lat+(lat_inc/2), this.lng+(lng_inc/2)]
				]).addTo(map);
	}
	game_end()
	{
		enemy_kill(this.lat,this.lng);
	}
}

class spawner {
	constructor(lat, lng)
	{
		this.lat = lat;
		this.lng = lng;
		console.log("Spawner placed at: " + this.lat + " " + this.lng);
		this.active = true;
		this.object = L.imageOverlay('./files/ico/base.png',[
						[lat-(lat_inc/2), lng-(lng_inc/2)],
						[lat+(lat_inc/2), lng+(lng_inc/2)]
					]).addTo(map);
	}
	kill()
	{
		map.removeLayer(this.object);
		this.active = false;
		spawner_kill(this.lat,this.lng);
	}
	spawn(enemy_array)
	{
		if(enemy_array.length<5) enemy_array.push(new enemy(1, this.lat, this.lng));
	}
	game_end()
	{
		spawner_kill(this.lat,this.lng);
	}
}







function tower_sell(lat, lng)
{
	console.log(lat + " " + lng);
	for(let i=0; i<tower_array.length; i++)
	{
		if(tower_array[i].lat==lat && tower_array[i].lng==lng)
		{
			tower_array[i].sell_and_kill();
			tower_array[i]=null;
		}
	}
	
	tower_array = tower_array.filter(el => el !== null);
}
function tower_kill(lat, lng)
{
	for(let i=0; i<tower_array.length; i++)
	{
		if(tower_array[i].lat==lat && tower_array[i].lng==lng)
		{
			tower_array[i]=null;
		}
	}
	
	tower_array = tower_array.filter(el => el !== null);
}
function spawner_kill(lat, lng)
{
	for(let i=0; i<spawner_array.length; i++)
	{
		if(spawner_array[i].lat==lat && spawner_array[i].lng==lng)
		{
			spawner_array[i]=null;
		}
	}
	
	spawner_array = spawner_array.filter(el => el !== null);
}
function enemy_kill(uid)
{
	for(let i=0; i<enemy_array.length; i++)
	{
		if(enemy_array[i].uid == uid)
		{
			ai_data_cd = 0;
			
			ai_data_cd += (c_lat - enemy_array[i].lat) * (c_lat - enemy_array[i].lat);
			ai_data_cd += (c_lng - enemy_array[i].lng) * (c_lng - enemy_array[i].lng);
			ai_data_cd = Math.sqrt(ai_data_cd);
			
			if(ai_data_cd < closest_death) closest_death = ai_data_cd;
			
			enemy_array[i]=null;
		}
	}
	
	enemy_array = enemy_array.filter(el => el !== null);
}







function game_over()
{
	for(let i=0; i<enemy_array.length; i++)
	{
		enemy_array[i].game_end();
	}
	for(let i=0; i<tower_array.length; i++)
	{
		tower_array[i].game_end();
	}
	for(let i=0; i<spawner_array.length; i++)
	{
		spawner_array[i].game_end();
	}
	alert("Game Over");
}