//---------------------------------------------------------------------------
//AnimateXkcd - The little stick figures above the xkcd comic, make them look around
//iFrame = [1,8] there are 8 thumbnails
function AnimateXkcd(iFrame) {
	document.getElementById('xkcd1190_thumb').src = '/content/xkcd1190/thumb/'+ (iFrame+1) +'.png';
	setTimeout(AnimateXkcd, RandInt(10,50)*1000, (iFrame+1) % 8);
} //AnimateXkcd


//---------------------------------------------------------------------------
//FetchXkcd - fetch xkcd RSS feed: https://xkcd.com/rss.xml
// Let server fetch it to avoid (CORS) cross-origin resource sharing
// i.e. servers can GET cross-site but browser scripts are not allowed
function FetchXkcd() {
	fetch('/index.php?xkcd_rss') //simple GET request
	.then((response) => {
		if(response.ok)  return response.json(); //HTTP OK (2xx) - assume response is JSON
		throw new Error(response.status +' '+ response.statusText); //treat 4xx/5xx as errors
	}).then((data) => {
		if(!data  ||  !data.title  ||  !data.img)  return; //don't update

		//create <img> from data.img text
		const tmpImg = document.createElement('template');
		tmpImg.innerHTML = data.img.trim(); //ex: <img src...>

		//replace xkcdTemplate
		const tmp = document.getElementById('xkcdTemplate');
		if(!tmp)  return; //xkcdTemplate not found
		tmp.content.getElementById('xkcd_title').textContent = data.title;
		tmp.content.getElementById('xkcd_img')?.replaceWith(tmpImg.content.firstChild);
		tmp.replaceWith(tmp.content);

		AnimateXkcd(0); //animate xkcd 1190 stick-figures
	}).catch((err) => {
		console.error(err);
	});
} //FetchXkcd


//---------------------------------------------------------------------------
//Blix - set "blix" (i.e. blinking lights clock) to current/specified time
// It is a 3x9 array of (28) lights
function Blix(time=null, blix=null) {
	if(!(time instanceof Date))  time = new Date();
	if(!(blix instanceof HTMLElement))  blix = document.getElementById('blix');
	if(!time  ||  !blix)  return;

	//BlixGroup - return Array[max] of <num> random colors
	// Ex: BlixGroup(3,2) = [3, 0, 1]
	function BlixGroup(max, num) {
		var arr = [];
		for(let i = 0;  i < num;  i++) //choose <num> random colors
			arr.push(RandInt(1, 5)); //[1..5] see index.css .blix1 - .blix5
		arr.push(...Array(max - num).fill(0)); //fill remainder of array with 0
		return arr.sort(() => Math.random() - 0.5); //shuffle array
	} //BlixGroup

	//create randomized groups
	var aLights =   BlixGroup(3, Math.floor(time.getHours() / 10)); //0-2
	aLights.push(...BlixGroup(9, time.getHours() % 10)); //0-9
	aLights.push(...BlixGroup(6, Math.floor(time.getMinutes() / 10))); //0-6
	aLights.push(...BlixGroup(9, time.getMinutes() % 10)); //0-9

	//create <div> elements
	blix.replaceChildren();
	for(const color of aLights) {
		const div = document.createElement('div');
		div.classList.add('blix'+ color);
		blix.append(div);
	}
} //Blix


//---------------------------------------------------------------------------
//Cistercian - draw Cistercian numerals (0-9999)
var g_cistercianTimeoutID = 0; //update time display
function Cistercian(num) {
	num = parseInt(num);
	if(isNaN(num)  ||  num < 0  ||  num > 9999) {
		//default = time = 4 digits = hhmm
		const now = new Date();
		num = (now.getHours() * 100) + now.getMinutes();
		const secRemain = ((60-now.getSeconds()) ?? 60); //seconds until next minute
		g_cistercianTimeoutID = setTimeout(Cistercian, secRemain * 1000, 'time');
	} else if(g_cistercianTimeoutID) {
		clearTimeout(g_cistercianTimeoutID); //stop updating time
		g_cistercianTimeoutID = 0;
	}

	//get canvas context
	const eCanvas = document.querySelector('#cistercian>canvas');
	const ctx = eCanvas?.getContext('2d');
	if(!eCanvas || !ctx)  return;

	//line boundaries
	const pad = 5;
	const xMid = eCanvas.width / 2;
	const yMin = pad;
	const yMax = eCanvas.height - pad;
	const third = Math.round((yMax - yMin) / 3);
	const yMid1 = yMin + third;
	const yMid2 = yMax - third;
	ctx.lineWidth = pad;
	ctx.strokeStyle = 'whitesmoke';
	ctx.lineCap = ctx.lineJoin = 'round';
	console.assert(eCanvas.width >= ((third*2)+(pad*3)), 
		'Cistercian canvas is too narrow. width should be >= '+ ((third*2)+(pad*3)));

	//center staff
	ctx.clearRect(0, 0, eCanvas.width, eCanvas.height);
	ctx.beginPath();
	ctx.moveTo(xMid, yMin);
	ctx.lineTo(xMid, yMax);

	//digits
	function DrawDigit(i, x0, x1, y0, y1) {
		switch(Math.floor(i)) {
			case 0:  return;
			case 1:  ctx.moveTo(x0,y0);  ctx.lineTo(x1,y0);  return;
			case 2:  ctx.moveTo(x0,y1);  ctx.lineTo(x1,y1);  return;
			case 3:  ctx.moveTo(x0,y0);  ctx.lineTo(x1,y1);  return;
			case 4:  ctx.moveTo(x0,y1);  ctx.lineTo(x1,y0);  return;
			case 5:  ctx.moveTo(x0,y1);  ctx.lineTo(x1,y0);  ctx.lineTo(x0,y0);  return;
			case 6:  ctx.moveTo(x1,y0);  ctx.lineTo(x1,y1);  return;
			case 7:  ctx.moveTo(x0,y0);  ctx.lineTo(x1,y0);  ctx.lineTo(x1,y1);  return;
			case 8:  ctx.moveTo(x0,y1);  ctx.lineTo(x1,y1);  ctx.lineTo(x1,y0);  return;
			case 9:  ctx.moveTo(x0,y0);  ctx.lineTo(x1,y0);  ctx.lineTo(x1,y1);  ctx.lineTo(x0,y1);  return;
		}
	}
	DrawDigit(num%10,        xMid, xMid+third, yMin, yMid1); //top right
	DrawDigit((num/10)%10,   xMid, xMid-third, yMin, yMid1); //top left
	DrawDigit((num/100)%10,  xMid, xMid+third, yMax, yMid2); //bottom right
	DrawDigit((num/1000)%10, xMid, xMid-third, yMax, yMid2); //bottom left
	ctx.stroke();
} //Cistercian


//---------------------------------------------------------------------------
//SpookyEyes - they blink and disappear on mouseOver
function SpookyEyes() {
	const door = document.getElementById('dungeonDoor');
	const eyes = document.getElementById('dungeonEyes');
	if(!door || !eyes)  return;

	let eyesTimer = undefined; //timer before something happens
	function NewEyes() { //new position
		eyes.style.top  = RandInt(130, 150)+'px';
		eyes.style.left = RandInt( 82,  95)+'px';
		OpenEyes();
	}
	function OpenEyes() {
		eyes.style.display = ''; //open eyes
		eyesTimer = setTimeout(CloseEyes, RandInt(1,10)*1000); //blink
	}
	function CloseEyes() {
		eyes.style.display = 'none'; //close eyes
		if(Math.random() < 0.20)
			eyesTimer = setTimeout(NewEyes, RandInt(3, 60)*1000); //go away for awhile
		else
			eyesTimer = setTimeout(OpenEyes, RandInt(100, 200)); //fast blink
	}
	door.addEventListener('mouseenter', ()=>{
		eyes.style.display = 'none'; //close eyes
		clearTimeout(timerEyesOpen); //stay closed
		timerEyesOpen = undefined; //nothing happens until mouseleave
	});
	door.addEventListener('mouseleave', ()=>{
		timerEyesOpen = setTimeout(NewEyes, RandInt(10,30)*1000);
	});
	timerEyesOpen = setTimeout(NewEyes, RandInt(5,20)*1000);
} //SpookyEyes


//---------------------------------------------------------------------------
//DungeonText - faulty "Coming Soon" text
function DungeonText() {
	const flicker = Math.floor(Math.random() * Math.random() * 12)+1; //[1..12]
	document.getElementById('dungeonText').style.animationName = 'flicker'+ flicker;
	setTimeout(DungeonText, RandInt(1, 8) * 1000);
} //DungeonText


//Init
if(document.getElementById('pow2Board'))  setTimeout(pow2.Autoplay, 2*1000);
if(document.getElementById('xkcdTemplate'))  FetchXkcd();
if(document.getElementById('blix'))  { Blix();  setInterval(Blix, 5*1000); }
if(document.getElementById('cistercian'))  Cistercian();
if(document.getElementById('dungeon'))  SpookyEyes();
if(document.getElementById('dungeonText'))  setTimeout(DungeonText, 8*1000);
