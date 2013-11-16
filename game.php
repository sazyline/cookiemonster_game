<!DOCTYPE HTML>
<html>
<head>
<title>Kakmonstret i gottefabriken</title>
<meta charset="UTF-8" />
</head>
<body onload="oneTimeTasks()" onKeyPressed="keyFunction(e)">

<script type="text/javascript">

function oneTimeTasks() {
	gameInterval = setInterval(main, 1);
	started = false;
	musicOn = true;
	music.play();
}

function toggleMusic() {
	if (musicOn) {
	musicOn = false;
	music.pause();
	document.getElementById('toggle-btn').value = "Sätt på musik";
	} else {
	musicOn = true;
	music.play();
	document.getElementById('toggle-btn').value = "Stäng av musik";
	}
}

// Create the canvas
var canvas = document.createElement("canvas");
var ctx = canvas.getContext("2d");
canvas.width = 600;
canvas.height = 700;
document.body.appendChild(canvas);

// Background image
var bgReady = false;
var bgImage = new Image();
bgImage.onload = function () {
	bgReady = true;
};
bgImage.src = "images/background.png";

// Monster image
var monsterReady = false;
var monsterImage = new Image();
monsterImage.onload = function () {
	monsterReady = true;
};
monsterImage.src = "images/player.png";

// Cookie image
var cookieReady = false;
var cookieImage = new Image();
cookieImage.onload = function () {
	cookieReady = true;
};
cookieImage.src = "images/enemy.png";

//Adding sounds
var music = new Audio("audio/music.ogg");
music.volume = 0.2;
music.addEventListener('ended', function() {
        this.currentTime = 0;
        this.play();
    }, false);
var nasty = new Audio("audio/nasty.wav");
var lose = new Audio("audio/lose.wav");
lose.addEventListener('ended', function() {
        if (musicOn) {
        music.play();
        }
    }, false);

// Game objects
var monster = {
	speed: 300 // movement in pixels per second
};
var cookie = {
  speed: 100
};
var cookiesEaten = 0;

	monster.x = canvas.width / 2;
	monster.y = canvas.height - 100;

// Handle keyboard controls
var keysDown = {};

addEventListener("keydown", function (e) {
	started = true;
	keysDown[e.keyCode] = true;
}, false);

addEventListener("keyup", function (e) {
	delete keysDown[e.keyCode];
}, false);

var keys = [];
window.addEventListener("keydown",
function(e){
keys[e.keyCode] = true;
switch(e.keyCode){
case 37: case 39: case 38: case 40: // Arrow keys
case 32: e.preventDefault(); break; // Space
default: break; // do not block other keys
}
},
false);
window.addEventListener('keyup',
function(e){
keys[e.keyCode] = false;
},
false);






















// Reset the game when the player catches a cookie
var reset = function () {
	// Throw the cookie somewhere on the screen randomly
	cookie.x = 32 + (Math.random() * (canvas.width - 64));
	cookie.y = -50;
};



// Update game objects
var update = function (modifier) {
	if (38 in keysDown) { // Player holding up
		if (!(monster.y < 10)) {
      		monster.y -= monster.speed * modifier;
      		}
	}
	if (40 in keysDown) { // Player holding down
		if (!(monster.y > canvas.height-70)) {
      		monster.y += monster.speed * modifier;
      		}
	}
	if (37 in keysDown) { // Player holding left
		if (!(monster.x < 10)) {
		monster.x -= monster.speed * modifier;
		}
	}
	if (39 in keysDown) { // Player holding right
		if (!(monster.x > canvas.width-40)) {
			monster.x += monster.speed * modifier;
		}
	}
	if (77 in keysDown) { // Player holding M
		//toggleMusic();
	}
		if (started) {cookie.y += cookie.speed * modifier;};

	// Are they touching?
	if (
		monster.x <= (cookie.x + 40)
		&& cookie.x <= (monster.x + 40)
		&& monster.y <= (cookie.y + 50)
		&& cookie.y <= (monster.y + 50)
	) {
		nasty.currentTime = 0;
		nasty.play();
		++cookiesEaten;
    cookie.speed = cookie.speed+5;
		reset();
	}

    // Have you lost the game?
  if (cookie.y >= canvas.height-40) {
  	music.pause();
  	lose.play();
  	keysDown = [];
  	clearInterval(gameInterval);
  	started = false;
  	alert("GAME OVER!\n\nStyggt kakmonster! Du lät en kaka undslippa!\n\nDu åt " + cookiesEaten + " kakor denna omgång. Tryck på OK för att spela igen!");
  	reset();
  	monster.x = canvas.width / 2;
	monster.y = canvas.height - 100;
	cookiesEaten = 0;
	cookie.speed = 100;
	//started = true;
	gameInterval = setInterval(main, 1);
  }
};

// Draw everything
var render = function () {

	if (bgReady) {
		ctx.drawImage(bgImage, 0, 0);
	}

	if (monsterReady) {
		ctx.drawImage(monsterImage, monster.x, monster.y);
	}

	if (cookieReady) {
		ctx.drawImage(cookieImage, cookie.x, cookie.y);
	}

	// Score
	if (started) {
	//ctx.fillStyle = "rgb(51, 120, 71)";
	ctx.font = "24px Helvetica";
	ctx.textAlign = "left";
	ctx.textBaseline = "bottom";
	ctx.fillText("Kakor ätna:" + cookiesEaten, 10, 650);
	} else {
	ctx.fillStyle = "rgb(0, 0, 0)";
	ctx.font = "26px Arial";
	ctx.textAlign = "left";
	ctx.textBaseline="bottom"; 
	ctx.fillText("Tryck på pilarna för att starta spelet!",100,400);
	}
};

// The main game loop
var main = function () {
	var now = Date.now();
	var delta = now - then;

	update(delta / 1000);
	render();

	then = now;
};

// Lets play this game!
reset();
var then = Date.now();
//setInterval(main, 1); // Execute as fast as possible


</script>
<br />
<input type="button" onclick="toggleMusic()" value="Stäng av musik" id="toggle-btn">
<br />
</body>
</html>