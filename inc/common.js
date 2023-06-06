//--------------------------------------------------------------------------
//RandInt(), RandInt(max), RandInt(min,max) - [min, max] are inclusive
// min - lower bound (or the next integer greater than min if min isn't an integer)
// max - upper bound (or the next integer lower than max if max isn't an integer)
//Note: using Math.round() makes a non-uniform distribution!
//This is similar to lodash.random;  Why doesn't native Javascript have this?
function RandInt(min, max) {
	if(min === undefined  &&  max === undefined) {
		//RandInt()
		min = 0;
		max = 1;
	} else {
		min = min ? Math.ceil(min) : 0;
		if(max === undefined) {
			//RandInt(max)
			max = min;
			min = 0;
		} else {
			//RandInt(min, max)
			max = Math.floor(max);
		}
	}
	if(min > max) {
		//RandInt(max, min)
		var temp = min;
		min = max;
		max = temp;
	}
	return Math.floor(Math.random() * (max - min + 1) + min);
} //RandInt


//-----------------------------------------------------------------------------
//ToggleELI5 - show/hide ELI5 summary
function ToggleELI5(eli5) {
	var span = eli5.getElementsByTagName('span')[0];
	var div  = eli5.getElementsByTagName('div')[0];
	if(div.style.display == 'none') {  //is hidden
		//show
		span.innerHTML = "&#9650 ELI5";  //&#9650 = 0x25B2 = up arrow
		div.style.display = '';
	} else {
		//hide
		span.innerHTML = "&#9660 ELI5";  //&#9660 = 0x25BC = down arrow
		div.style.display = 'none';
	}
} //ToggleELI5


/* //x
//---------------------------------------------------------------------------
//WaitRedirect - show message, wait icon, and progress bar ...then redirect
function WaitRedirect(msg='', ms=5000, redirectURL='/', parent=undefined) {
	if(!parent)  parent = document.getElementById('main');
	parent.insertAdjacentHTML('afterbegin', 
		  '<div class="errBox">'
		+ '  <h2>'+msg+'</h2>'
		+ '  <img src="/img/wait.gif" class="center">'
		+ '  <progress id="redirectProgress" value=0></progress>'
		+ '</div>');
	setInterval(()=>{document.getElementById('redirectProgress').value += 0.01}, ms/100);
	setTimeout(()=>{window.location=redirectURL}, ms);
} //WaitRedirect
*/

//---------------------------------------------------------------------------
//OpenLogin - open modal login dialog; window background greyed
//box closes on ESC or close (x) button
//RECEIVES: resetID - password reset ID for "forgot password";  default=NULL=normal login
function OpenLogin(resetID) {
	const tmpDlg = document.getElementById('loginTemplate').content;
	if(resetID) {
		//check for valid resetID
		const xhr = new XMLHttpRequest();
		const data = new FormData();
		data.set('isValidId', resetID);
		xhr.onload = function() {
			if(this.responseText) {
				window.alert("That password reset link is no longer valid. To reset your password, click on Sign In then Forgot Password.");
				window.location.replace(location.pathname); //reload page without POST or GET data, no back button
				return;
			}
		}
		xhr.open('POST', '/server/login.php');
		xhr.send(data);

		//reset password; user clicked on "forgot password" email link
		tmpDlg.getElementById('loginHdr').textContent = "Enter a new password";
		tmpDlg.getElementById('loginEmail').type = 'hidden';
		tmpDlg.getElementById('loginEmail').value = resetID;
		tmpDlg.getElementById('loginPswd').setAttribute('autocomplete', 'new-password');
		tmpDlg.getElementById('loginSubmit').innerHTML = 'Update&nbsp;Password';
		tmpDlg.getElementById('loginCreate').remove();
		tmpDlg.getElementById('loginForgot').remove();
	}
	document.querySelector('body').appendChild(document.importNode(tmpDlg, true));
	document.getElementById('loginEmail').focus();

	//close on ESC
	document.addEventListener('keyup', ev => {
		if(ev.key == 'Escape'  &&  document.getElementById('loginBox'))
			SkipLogin();
	});
	return false; //return false to override default <a href onClick> action
} //OpenLogin


//---------------------------------------------------------------------------
//SkipLogin - User doesn't want to sign in, pressed ESC or close (X) button
function SkipLogin() {
	//Note: web crawlers don't run Javascript, press ESC, or click on buttons
	// so if those happened, we know it's an anonymous human
//	fetch('/server/login.php?anon'); //tell server this is an anonymous human
	fetch('/server/login.php?anon').then(resp => resp.text()).then(txt =>
		console.log(txt) );
	document.getElementById('loginBox').remove();
} //SkipLogin


//---------------------------------------------------------------------------
//SubmitLogin - Login dialog, process form
function SubmitLogin(form) {
	const xhr = new XMLHttpRequest();
	const data = new FormData(form);
	if(!data.has('email')  ||  !data.get('email')) { //is forgot password?
		//Step 1 of 2: user clicked "forgot password" link in Login box
		data.set('reset', '1');
		data.set('email', document.getElementById('loginEmail').value);
		if(!data.get('email')) {
			document.getElementById('loginHdr').textContent = 'Email address required';
			document.getElementById('loginHdr').classList.add('error');
			document.getElementById('loginEmail').reportValidity();
			return false;
		}
		document.getElementById('loginHdr').textContent = 'Resetting password...';
	} else if(document.getElementById('loginEmail').type == 'hidden') {
		//Step 2 of 2: user clicked email link with resetID (password reset Id)
		data.set('reset', '2');
		document.getElementById('loginHdr').textContent = 'Updating password...';
	}
	document.getElementById('loginWait').style.display = ''; //show wait icon
	xhr.onload = function() {
		if(!this.responseText) { //if success (no reponse error message)
			if(window.location.search.substring(0,6) === '?reset')
				window.location.replace(location.pathname); //reload page without GET
			else
				window.location.reload(); //keep GET data (orders page)
			return;
		}

		//show error message
		document.getElementById('loginWait').style.display = 'none'; //hide wait icon
		document.getElementById('loginHdr').textContent = this.responseText; //show error message
		document.getElementById('loginHdr').classList.add('error');
		if(this.responseText.includes('try again')) {
			document.getElementById('loginEmail').value = '';
			document.getElementById('loginPswd').value = '';
		}
	} //onload
	xhr.open('POST', '/server/login.php');
	xhr.send(data);
	return false; //always return false to override default onSubmit action
} //SubmitLogin


/*
//---------------------------------------------------------------------------
//ObfuscateContacts - Replace email & phone images with obfuscated links to try to fool spambots
function ObfuscateContacts() {
	//replace emails:  <img class="email" alt="...">  =>  <a onclick="href="mailto:...">...NOSPAM...</a>
	//Old: <img src="/img/email.png" class="email" alt="info﹫KonaEarth&period;com">
	//New: <a onclick="this.href="mailto:info@KonaEarth.com">info<span style="display:none">NOSPAM</span>&commat;KonaEarth&period;com</a>
	document.querySelectorAll('img.email').forEach(imgNode => {
		const newNode = document.createElement('a');
		newNode.href = '';

		//retrieve email address from <img alt>
		const regex = /[@\uFE6B\uFF20]/; //match any @, \uFE6B=﹫small, \uFF20=＠ large
		let aEmail = imgNode.alt.split(regex); //split name (info) and domain (KonaEarth.com)
		if(aEmail.length != 2)  aEmail = imgNode.alt.split('﹫'); //&#65131; = &#xFE6B; = ﹫ = small @
		if(aEmail.length != 2)  aEmail = imgNode.alt.split('＠'); //&#65312; = &#xFF20; = ＠ = large @
		if(aEmail.length != 2)  aEmail = imgNode.alt.split('@'); //&#64; = &#x0040; = &commat; = @
		if(aEmail.length != 2)  return; //skip this image

		//Trick 1: no href until clicked
		newNode.onclick = function() { this.href = 'mailto:'+ aEmail[0] +'@'+ aEmail[1]; };

		//Trick 2: insert invisible NOSPAM
		newNode.innerHTML = aEmail[0] + '<span style="display:none">NOSPAM</span>&commat;' + aEmail[1];

		//Trick 3: replace <img> with <a href>
		imgNode.parentNode.replaceChild(newNode, imgNode);
	});

	//replace phone numbers:  <img class="phone" alt="...">  =>  <a onclick="href="tel:...">...555...</a>
	//Old: <img src="/img/phone.png" class="phone" alt="808&minus;324&minus;1725">
	//New: <a onclick="this.href="tel:808-324-1725">808&minus;<span style="display:none">555</span>324&minus;<span style="display:none">555</span>1725</a>
	document.querySelectorAll('img.phone').forEach(imgNode => {
		const newNode = document.createElement('a');
		newNode.href = '';

		//retrieve phone number from <img alt>
		const regex = /\D+/; //match non-digits
		let aPhone = imgNode.alt.split(regex); //split on non-digits
		if(aPhone.length < 2)  aPhone = imgNode.alt.split('-');

		//Trick 1: href invalid until clicked
		newNode.onclick = function() { this.href = 'tel:'+ aPhone.join('-'); };

		//Trick 2:  insert &minus; (unicode − vs regular -) and invisible 555
		newNode.innerHTML = aPhone.join('&minus;<span style="display:none">555</span>');

		//Trick 3: replace <img> with <a href>
		imgNode.parentNode.replaceChild(newNode, imgNode);
	});
} //ObfuscateContacts


//---------------------------------------------------------------------------
//InitClocks - display current time at specific GMT offset
//  data-mode = (12 | 24 | GMT);  default=12,  GMT=show GMT offset
//  data-gmt = difference from GMT (in hours), default=local time;  Ex: Hawaii = -10
//Note: uses client's local clock so may not be accurate on some computers
//Example:  It is <time class="clock" data-gmt="-10">GMT -10</time> in Hawaii.
function InitClocks() {
	const now = new Date();
	const min = ('00' + now.getUTCMinutes()).slice(-2); //prefix leading zero: 01-59
	document.querySelectorAll('.clock').forEach(clock => {
		const mode = (clock.dataset.mode  ||  '12');
		const zone = parseInt(clock.dataset.gmt  ||  (now.getTimezoneOffset()/-60));
		let hrs = (parseInt(now.getUTCHours()) + zone + 24) % 24;
		let suffix = '';
		if(mode.toUpperCase() == 'GMT')
			suffix = ' GMT' + (zone >= 0 ? '+' : '') + zone;
		if(mode == '12') {
			suffix = (hrs >= 12) ? 'pm' : 'am';
			hrs = ((hrs % 12)  ||  12); //1-12
		}

		const timeStr = hrs + ':' + min + suffix; //Ex: 1:00am
		clock.textContent = timeStr;
		if(clock.tagName == 'time')
			clock.setAttribute('datetime', timeStr);
	});

	//update clock at the end of every minute
	const msleft = (60 - now.getUTCSeconds()) * 1000;
	setTimeout(InitClocks, msleft);
} //InitClocks
*/

//---------------------------------------------------------------------------
//Init - this script is loaded with defer so this runs after DOM is ready
{
//x	ObfuscateContacts(); //obfuscate emails and phone numbers
//x	InitClocks(); //show current time

	//open/close popup boxes
	document.querySelectorAll('.popup').forEach(elem => 
		elem.addEventListener('click', ev => 
			ev.currentTarget.toggleAttribute('open')));

	//init <select class="select-placeholder"> elements
	//  Used to diplay placeholder text for optional (not required) <select>
	document.querySelectorAll('.select-placeholder').forEach(elem => 
		elem.addEventListener('change', ev => 
			ev.currentTarget.classList.remove('select-placeholder')));
} //Init
