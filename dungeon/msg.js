//Copyright (c) 2022 Gary Strawn, All rights reserved
export { msgBox, errBox, log, clearLog, logShowHide };


//---------------------------------------------------------------------------
//msgBox - message dialog box in center of screen
//errBox - same as msgBox but red
//Receives:
//  msg: message to be displayed; truncated to 64k; may be HTML
//    CAUTION: user input is not sanitized!!!  Ex: msg = "<img src='x' onerror='alert(1)'>"
//  params (optional): single element or array
//    <string>: button to be added; ex: Ok, Cancel, Yes, No, I don't know
//    <number>: auto-close delay IN SECONDS
//   <boolean>: true = only-once, never be shown again (return onClose(''))
//Returns: a promise with a string parameter:
//  <string>: text of the button used to close dialog
//   'Close': user pressed ESC or default close X button
//        '': only-once msgBox (true specified in params) was called again
//
//Duplicates: multiple message boxes opened similtaneously appear on top of each other, 
//  most recent one on top. However, the same message box is only displayed once.
//
/* Usage Example:
msgBox("I like ice cream")
.then(() => errBox("<h1>Uh oh!</h1>No more ice cream.", '&#x1F612'))
.then(() => msgBox("Get more ice cream?", 'Yes', 'No'))
.then(answer => { if(answer == 'Yes')  msgBox("Going to the store...", 'OK', 'Hurry', 5)
.then(speed => {
if(speed == 'Hurry')  setTimeout(msgBox, 15*1000, "Yay! More ice cream.", 5);
else                  setTimeout(msgBox, 30*1000, "They were out of ice cream.", 5);
})});
errBox('*burp*', 3, true); //true = shown only once, repeat calls return onClose('')
*/
const _aDupMsgs = []; //all msgBoxes currently open or only-once
function msgBox(msg, ...params) {
	console.assert(typeof msg === 'string'  &&  msg.length < 65536);

	//don't open duplicate boxes (multiples ok, duplicates bad)
	const sThisMsgBox = JSON.stringify({msg, ...params});
	const sPrevMsgBox = _aDupMsgs.find(e => e === sThisMsgBox);
	if(sPrevMsgBox) {
		return new Promise((resolve, reject) => {
			if(/"\d+":true,/i.test(sPrevMsgBox)) //if has param=true
				return resolve(''); //don't open, exit silently: onClose('')
			else
				return reject('Previous instance of msgBox is still open. Duplicate ignored.');
		});
	}
	_aDupMsgs.push(sThisMsgBox);

	//parse parameters
	let aBtns     = [];    //ex: ['Ok', 'Cancel']
	let autoClose = 0;     //auto-close delay, in seconds;  0 = no auto-close
	let onlyOnce  = false; //true if this msgBox should only be displayed once
	let isErr     = false; //true = class .error = red
	params.forEach(param => {
		switch(typeof param) {
			case 'undefined':                   break;
			case 'boolean': onlyOnce = param;   break;
			case 'string':  aBtns.push(param);  break;
			case 'number':  autoClose = param;  break;
			case 'symbol':  isErr = param;      break;
			default: console.assert(false, "Invalid MsgBox param: ", param);
		}
	});
	console.assert(autoClose < 1000  ||  autoClose % 1000 != 0, //60*60 is ok, 3000 is not
		`msgBox autoClose delay = ${autoClose}, should be whole seconds, not milliseconds`);

	//closeDlg - called when dialog is closed (pressed button or ESC)
	function closeDlg(ev) {
		if(ev  &&  ev.type == 'keydown'  &&  ev.key != 'Escape')  return;
		removeEventListener('keydown', closeDlg);
		dlg.remove(); //remove from DOM
		if(!onlyOnce) //don't remove onlyOnce boxes (they won't be shown again)
			_aDupMsgs.splice(_aDupMsgs.indexOf(sThisMsgBox), 1); //remove
		if(!ev  ||  ev.type == 'keydown'  ||  ev.target.classList.contains('closeBtn'))
			onClose('Close'); //if autoTimeout, ESC, or closeBtn
		else
			onClose(ev.target.textContent);
	}
	addEventListener('keydown', closeDlg);
	if(autoClose > 0)  setTimeout(closeDlg, autoClose*1000);

	//create dialog box
	const dlg = document.body.appendChild(document.createElement('dialog'));
	dlg.classList.add(isErr ? 'errBox' : 'msgBox');

	//CAUTION: user input is not sanitized!!!
	//ex: msg = "<img src='x' onerror='alert(1)'>"
	dlg.innerHTML = msg;

	//add buttons
	if(aBtns.length > 0) {
		const btnDiv = dlg.appendChild(document.createElement('div'));
		btnDiv.classList.add('msgBoxBtns');
		aBtns.forEach((btnTxt, i) => {
			const btn = btnDiv.appendChild(document.createElement('button'));
			if(i == 0)  btn.autofocus = true; //first button gets autofocus
			btn.innerHTML = btnTxt;
			btn.addEventListener('click', closeDlg);
		});
	}

	//add close button (added to dlg, not btnDiv, in case there are no other buttons)
	const btnClose = dlg.appendChild(document.createElement('button'));
	btnClose.classList.add('closeBtn');
	btnClose.innerHTML = '&times;'; //X or &times; or &#10005; or &#10006;
	btnClose.addEventListener('click', closeDlg);

	dlg.show();

	let onClose;
	return new Promise(resolve => onClose = resolve);
} //msgBox

//---------------------------------------------------------------------------
//errBox - same as msgBox but red
function errBox(errMsg, ...params)  { return msgBox(errMsg, Symbol(), ...params); }



//---------------------------------------------------------------------------
//-- log
let log_fadeStart    = 5*1000; //old messages start to fade away (defined in .css)
let log_fadeDuration = 2*1000; //fade duration (defined in .css)
const log_maxAge = 60*60*1000; //old log messages are deleted after one hour
const log_maxLen = 256; //max character length of a single message
const log_maxNum = 256; //max messages kept (256*256=64k)
const log_aloneMaxNum = 10; //max logAlone messages (arrive fast? disappear fast)


//---------------------------------------------------------------------------
//log init
{	//fetch values from .css; convert '5s' to 5000
	const css = getComputedStyle(document.documentElement);
	const cssStart = css.getPropertyValue('--msgLogFadeStart');
	const cssDuration = css.getPropertyValue('--msgLogFadeDuration');
	console.assert(/^\s*\d+m?s$/i.test(cssStart)  &&  /^\s*\d+m?s$/i.test(cssDuration),
		'.css file must include:\n'+
		'  :root {\n    --msgLogFadeStart: 5s;\n    --msgLogFadeDuration: 2s;\n  }');
	log_fadeStart    = parseInt(cssStart)    * (/^\s*\d+s$/i.test(cssStart)     ?  1000 : 1);
	log_fadeDuration = parseInt(cssDuration) * (/^\s*\d+s$/i.test(cssDuration)  ?  1000 : 1);

	//start listening
	document.getElementById('logTime').addEventListener('click', logTime);
	document.getElementById('logClear').addEventListener('click', clearLog);
	document.getElementById('logMax').addEventListener('click', logMax);
	document.getElementById('logCmd').addEventListener('input', cmdInput);
	document.getElementById('logCmd').addEventListener('change', cmdChange);
	clearLog();
}


//---------------------------------------------------------------------------
//logShowHide - show/hide message log
//force - true=show | false=hide | undefined=toggle
function logShowHide(force) {
	const dlg = document.getElementById('msgLog');
	const isShow = (force !== undefined)  ?  force  :  !dlg.open;
	if(isShow) {
		dlg.show();
		document.getElementById('msgLogAlone').style.display = 'none';
	} else {
		dlg.close();
		delete document.getElementById('msgLogAlone').style.display;
	}
} //logShowHide


//---------------------------------------------------------------------------
//log - add message to dialog#msgLog at bottom of screen
// if dialog#msgLog is closed, messages are shown in #msgAlone until they fade
function log(msg) {
	console.assert(typeof msg === 'string'  &&  msg.length < log_maxLen);
	if(msg.length > log_maxLen)  msg = msg.substing(0, log_maxLen);
	const logMsgs = document.getElementById('logMsgs');
	const logAlone = document.getElementById('msgLogAlone');
	const now = new Date();

	//create new <p> element
	const p = document.createElement('p');
		p.classList.add('msgLogNew');
		p.dataset.time = now.getTime(); //unix timestamp
		p.append(msg);
	logAlone.append(p.cloneNode(true)); //make a copy, add to bottom of logAlone

	//add timestamp
	const span = document.createElement('span');
		span.classList.add('logTimestamp');
		span.append(now.toTimeString().substring(0,8) +' ');
	p.prepend(span);

	//add <p> to bottom of msgLog
	const topMax = logMsgs.clientHeight - logMsgs.scrollHeight;
	const isBottom = topMax - logMsgs.scrollTop < 10; //within 10px of bottom
	const newLogNode = logMsgs.appendChild(p);
	if(isBottom)  newLogNode.scrollIntoView();

	//remove excess <p> elements (oldest ones are at the top)
	while(logMsgs.childElementCount > log_maxNum)
		logMsgs.firstChild.remove();
	while(logAlone.childElementCount > log_aloneMaxNum)
		logAlone.firstChild.remove();

	//remove old <p> elements (oldest ones are at the top)
	const maxAge = now.getTime() - log_maxAge;
	while(logMsgs.firstChild.dataset.time < maxAge)
		logMsgs.firstChild.remove();
	//note: logAlone elements remove themselves after they fade

	//set fade timer
	setTimeout(startFade, log_fadeStart, newLogNode, logAlone.lastChild);
}


//---------------------------------------------------------------------------
//clearLog - erase all messages (doesn't clear msgLogAlone, those clear themselves)
function clearLog() {
	document.getElementById('logMsgs').replaceChildren();
}

//toggle minimize (5 lines visible) or maximize (10 lines visible)
function logMax() { document.getElementById('msgLog').classList.toggle('logMaximize'); }

//logTime - toggle timestamps
function logTime() {
	document.getElementById('msgLog').classList.toggle('logShowTime');
	console.log('TODO: toggle timestamps');
}

//slowly fade away
function startFade(logNode, aloneNode) {
	logNode.classList.remove('msgLogNew');
	aloneNode.classList.remove('msgLogNew');
	setTimeout(() => aloneNode.remove(), log_fadeDuration);
}

//done fading, message is no longer new
function endFade(aloneNode) {
	logNode.classList.remove('msgLogFading');
//	aloneNode.remove();
}


function cmdInput(ev) { //user is actively typing
	//TODO: auto-completion
}

function cmdChange(ev) { //all done
	console.log(ev.target.value);
	this.add(ev.target.value);
}
