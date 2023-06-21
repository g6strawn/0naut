//---------------------------------------------------------------------------
//TickToStr - convert tick count (ms) to string
function TickToStr(ms) {
	const t = new Date(parseInt(ms));
	return (ms < 60*60*1000)
		? t.toISOString().substring(14, 19)   //convert to mm:ss
		: t.toISOString().substring(12, 19);  //convert to h:mm:ss
} //TickToStr


//---------------------------------------------------------------------------
//PausePlay - pause / play
var g_isPaused = false; //true: paused (stop animation)
var g_isPauseGo = false; //false: still not time to display next frame (wait for timeout)
                         //true: unpause advances to next frame immediately
function PausePlay(isPaused) {
	g_isPaused = (typeof(isPaused) != 'undefined')  ?  isPaused  :  !g_isPaused;
	if(g_isPaused) {
		//pause
		if(document.getElementById('editDelay')) {
			//show editDelay input instead of pause button
			document.getElementById('editDelay').removeAttribute('disabled');
			//Note: Pressing <space> will pause/play but that space will 
			//  be sent to <input editDelay> which will delete the selected value.
			//  To prevent this, don't set focus until after <space> is processed.
			setTimeout(()=>{document.getElementById('editDelay').select();}, 0);
		} else { //show pause button, but not when editing
			document.getElementById('playBtn').style.display = 'block';
			document.getElementById('xkcd1190img').style.opacity = 0.2;
		}
	} else {
		//play
		document.getElementById('editDelay')?.setAttribute('disabled', '');
		document.getElementById('playBtn').style.display = 'none';
		document.getElementById('xkcd1190img').style.opacity = 1.0;
		if(g_isPauseGo) { //has current frame been displayed long enough?
			GotoFrame(parseInt(document.getElementById('progBar').value) + 1); //next frame
		}
	} //else
} //PausePlay


//---------------------------------------------------------------------------
//GotoFrame - go to specified frame or (if !iFrame) advance to next frame
var g_nextFrameTimeout = null; //timer (setTimeout) for displaying next frame
var g_aTickTimeouts = []; //if frame > 1sec, keep updating timer element
var g_aCache = []; //preloaded images (help mitigate network lag)
function GotoFrame(iFrame=0) { //default = 0 = next frame
	if(g_isPaused  &&  !iFrame) { //if paused and not jumping to a specific frame
		g_isPauseGo = true; //ready for next frame
		return;
	}
	if(!iFrame)  iFrame = parseInt(document.getElementById('progBar').value) + 1
	if(iFrame < 1  ||  iFrame > 3099)  iFrame = 1;

	//show frame
	const url = '/content/xkcd1190/frames/' + (('0000'+iFrame).slice(-4)) + '.png';
	document.getElementById('xkcd1190img').src = url;
	document.getElementById('iFrame').textContent = iFrame;
	document.getElementById('progBar').value = iFrame;
	document.getElementById('currTime').textContent = TickToStr(g_aTicks[iFrame-1]);
	if(document.getElementById('editDelay')) {
		//Note: editor shows actual delay, not default
		let ms = (iFrame in g_aDelays) ? (g_aDelays[iFrame] / 1000) : '';
		document.getElementById('editDelay').value = ms;
	}

	//advance to next frame
	const delay = (iFrame in g_aDelays) ? g_aDelays[iFrame] : 1000; //default = 1 sec
	clearTimeout(g_nextFrameTimeout);
	g_nextFrameTimeout = setTimeout(GotoFrame, delay);
	g_isPauseGo = false; //next frame isn't ready yet

	//keep updating timer for frames longer than 1 second
	if(delay > 1000  &&  iFrame < 3099) {
		g_aTickTimeouts.forEach(i => clearTimeout(i));
		g_aTickTimeouts = [];
		const elem = document.getElementById('currTime');
		let tick = g_aTicks[iFrame-1] + 1000;
		for(let i=1000;  i < delay;  i += 1000) {
			g_aTickTimeouts.push(setTimeout(s => elem.textContent = s, i, TickToStr(tick)));
			tick += 1000;
		} //for
	} //if delay

	//preload future frames so they're in browser cache
	const iStart = iFrame + 1;
	const iEnd = Math.min(iStart + g_aCache.length, 3099);
	for(let i = iStart;  i < iEnd;  i++) {
		const url = '/content/xkcd1190/frames/' + (('0000'+i).slice(-4)) + '.png';
		g_aCache[i % g_aCache.length].src = url;
	}
} //GotoFrame


//---------------------------------------------------------------------------
//Init - start the movie
function Init() {
	//edit frame delay
	const edit = document.getElementById('editDelay');
	if(edit) {
		edit.addEventListener('change', (e)=>{
			//frame's delay has been edited, send new value to server
			if(edit.hasAttribute('disabled'))  return; //don't edit if disabled (user pressed ESC)

			//update new frame delay (send POST[iFrame]=delay)
			const delay = Math.round(document.getElementById('editDelay').value * 1000);
			const iFrame = parseInt(document.getElementById('progBar').value); //current frame
			const opt = {
				method: 'POST',
				headers: {'Content-Type':'application/x-www-form-urlencoded'},
				body: iFrame +"="+ delay
			};
			fetch('/content/xkcd1190/index.php', opt)
			.then(response => response.text())
			.then(text => console.log(text));
			PausePlay(false);
		}); //edit change

		//discard edit
		edit.addEventListener('keydown', (e)=>{
			if(e.code == 'Escape') {
				edit.setAttribute('disabled', ''); //discard changes
				PausePlay(false);
			}
		}); //edit ESC
	} //if edit

	//pause/play (click on image)
	document.getElementById('imgBox').addEventListener('click', ()=>{PausePlay()});

	//progress bar
	document.getElementById('progBar').addEventListener('change', (e)=>{
		GotoFrame(e.currentTarget.value);
	});
	document.getElementById('progBar').addEventListener('keydown', (e)=>{
		//keys are handled below, ignore all progBar key presses
		e.preventDefault();
	});

	//key press <space>=pause/play, <left>=back 1 frame, ...
	document.addEventListener('keydown', (e)=>{
		if(document.activeElement == edit)  return; //hotkeys disabled during edit
		const iFrame = parseInt(document.getElementById('progBar').value); //current frame
		switch(e.code) {
		case 'Space':      PausePlay();           break; //pause / resume
		case 'ArrowLeft':  GotoFrame(iFrame-1);   break; //previous frame
		case 'ArrowRight': GotoFrame(iFrame+1);   break; //next frame
		case 'PageUp':     GotoFrame(iFrame-10);  break; //back 10 frames
		case 'PageDown':   GotoFrame(iFrame+10);  break; //forward 10 frames
		case 'Home':       GotoFrame(1);          break; //first frame
		case 'End':        GotoFrame(3099);       break; //last frame
		} //switch
		return false;
	});

	//allocate prefetch cache
	g_aCache = new Array(10);
	for(let i = 0;  i < g_aCache.length;  i++)
		g_aCache[i] = new Image();

	GotoFrame(parseInt(document.getElementById('progBar').value)); //start animation
} //Init

Init();
