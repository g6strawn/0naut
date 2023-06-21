//---------------------------------------------------------------------------
//InitTabs - initialize all tabs
function InitTabs() {
	//initialize all <section.tab> elements
	document.querySelectorAll('ul.tabs li').forEach(tab => {
		const tabContent = document.getElementById(tab.dataset.tab_id);
		if(!tabContent)
			console.log('Cannot find tab '+ tab.textContent);
		else {
			tab.addEventListener('click', ev => SelectTab(ev.currentTarget));
			tabContent.hidden = true;
		}
	});
	document.querySelectorAll('ul.tabs li.tab_active').forEach(SelectTab);
} //InitTabs


//---------------------------------------------------------------------------
//SelectTab - User clicked on a new tab
function SelectTab(tab) {
	//deactivate old level
	tab.parentNode.querySelectorAll('li.tab_active').forEach(oldTab => {
		oldTab.classList.remove('tab_active');
		document.getElementById(oldTab.dataset.tab_id).hidden = true;
	});

	//activate new level
	tab.classList.add('tab_active');
	document.getElementById(tab.dataset.tab_id).hidden = false;
} //SelectTab


//---------------------------------------------------------------------------
//USPSEdit - debounce onInput; update after typing stops for 500ms
var uspsInputTimeout = null;
function USPSEdit(input) {
	clearTimeout(uspsInputTimeout);
	uspsInputTimeout = setTimeout(()=>{
		const xhr = new XMLHttpRequest();
		const data = new FormData();
		data.set('usps_sku', input.name);
		data.set('usps_cost', input.value);
		xhr.open('POST', '/server/submit.php');
		xhr.send(data);
	}, 500);
} //USPSEdit


//---------------------------------------------------------------------------
//ProdStylize - set styled value (input.value) from the actual value (td.dataset.val)
function ProdStylize(td) {
	if(td.querySelector('input'))  return; //don't style if editing (i.e. has <input>)
	if(!td.dataset.val)  td.dataset.val = td.innerText;  //data-val holds actual, unstyled value
	if(td.classList.contains('qty')) {
		//qty - change symbol, add color
		switch(td.dataset.val) {
		case  '0': td.innerText = '-0-';      td.style.color = 'red';   break; //0 = out of stock
		case '-1': td.innerHTML = '&infin;';  td.style.color = 'green'; break; //-1 = always in stock
		case '-2': td.innerHTML = '&horbar;'; td.style.color = 'grey';  break; //-2 = discontinued
		default: td.style.color = (td.innerText < 10) ? 'yellow' : 'green';
		}
		td.parentElement.style.color = (td.style.color == 'grey') ? 'grey' : '';

	} else if(td.classList.contains('price')) {
		//price - add $, remove trailing .00
		td.innerText = PriceStr(td.dataset.val);

	} else if(td.classList.contains('net_lbs')) {
		//net_lbs - remove trailing zeros
		td.innerText = parseFloat(td.dataset.val);

	} else if(td.classList.contains('icon')) {
		//icon - remove '/img/cart'
		td.innerText = td.innerText.replace('/img/cart/', '');
	}
} //ProdStylize


//---------------------------------------------------------------------------
//ProdInputDebounce - debounce onInput; update after typing stops for 500ms
var prodInputTimeout = null;
function ProdInputDebounce(input) {
	clearTimeout(prodInputTimeout);
	prodInputTimeout = setTimeout(()=>ProdUpdate(input), 500);
} //ProdInputDebounce


//---------------------------------------------------------------------------
//ProdUpdate - save product changes to database
function ProdUpdate(input) {
	if(!input  ||  !input.checkValidity())  return; //if !exist or !valid
	const xhr = new XMLHttpRequest();
	const data = new FormData();
	data.set('prod_sku', input.parentElement.parentElement.id);  //tr.id > td > input
	data.set('prod_key', input.id);
	data.set('prod_val', input.value);
	xhr.open('POST', '/server/submit.php');
	xhr.send(data);
	clearTimeout(prodInputTimeout);
} //ProdUpdate


//---------------------------------------------------------------------------
//ProdEdit - start editing a product (i.e. user double-clicked on a row)
function ProdEdit(row) {
	//template contains <input> for fields that are editable (according to IsBoss access)
	const aInput = document.getElementById('prodRowTemplate').content.children;
	for(input of aInput) {
		const td = row.querySelector('td.'+ input.id);  //corresponding <td>
		input.value = td.hasAttribute('data-val')  ?  td.dataset.val  :  td.innerText;
		while(td.firstChild)  td.removeChild(td.firstChild);  //remove <td> innerText
		td.appendChild(document.importNode(input, false));  //insert <input>
		td.style.padding = '0';
	} //for
	row.querySelector('td.qty input').select(); //select all text and set focus

	//add listeners
	row.querySelectorAll('td input').forEach((input)=>{
		input.addEventListener('input', ()=>ProdInputDebounce(input));
		input.addEventListener('blur', ev=>{ //blur = <tab> or click to another element = stop editing
			ProdUpdate(input); //save any changes
			//relatedTarget = new element gaining focus
			if(!ev.relatedTarget  ||  !row.contains(ev.relatedTarget)) { //if focus not on this row
				//stop editing entire row (remove <input>, reset td.data-val)
				row.querySelectorAll('td').forEach((td)=>{
					td.innerText = td.dataset.val = td.firstElementChild.value;
					td.style.padding = null;
					ProdStylize(td);
				});
			}
		});
		input.addEventListener('keyup', ev=>{ //if pressed <ENTER>
			if(ev.key == 'Enter')  document.activeElement.blur();
		});
	});
} //ProdEdit


//---------------------------------------------------------------------------
//ToggleListDesc - toggle expanded view of 'desc' field
function ToggleListDesc(td) {
	if(td.style['white-space'] == '') { //if default style (parent style is nowrap)
		//let long text wrap (multi-line)
		td.style['white-space'] = 'normal';  //allow multi-line
		td.style['max-width'] = '25em';
		td.style['border'] = '1px solid black';

		//select all text
		const range = document.createRange();
		range.selectNodeContents(td);
		const selection = window.getSelection();
		selection.removeAllRanges();
		selection.addRange(range);
	} else //go back to nowrap (single line...)
		td.style['white-space'] = td.style['max-width'] = td.style['border'] = '';
} //ToggleListDesc


//---------------------------------------------------------------------------
//Throttle Refresh checkbox/button;  called only from throttle "Refresh" button
function ThrottleRefresh(btn) {
	btn.classList.toggle('pressed');
	btn.classList.toggle('spin');
	if(btn.classList.contains('pressed'))  UpdateThrottle();
} //ThrottleRefresh


//---------------------------------------------------------------------------
//UpdateThrottle - fetch recent throttle triggers, update current delay
function UpdateThrottle() {
	const xhr = new XMLHttpRequest();
	const data = new FormData();
	const prevHit = document.querySelector('#throttleList td.date time'); //most recent throttle event
	data.set('prevHit', prevHit ? prevHit.dateTime : '');
	xhr.onload = function() {
		//automatic updates?
		const btnRefresh = document.querySelector('#throttleList button');
		if(btnRefresh  &&  btnRefresh.classList.contains('pressed'))
			setTimeout(UpdateThrottle, 5000); //update again in 5 seconds

		if(this.responseText) {
			//insert new rows to top of table
			const tbody = document.querySelector('#throttleList table tbody');
			tbody.innerHTML = this.responseText + tbody.innerHTML;

			//row limit 50
			const aRows = document.querySelectorAll('#throttleList table tbody tr');
			for(let i = 50;  i < aRows.length;  i++)
				tbody.removeChild(aRows[i]);
		} //responseText
	} //onload
	xhr.open('POST', '/server/throttle.php');
	xhr.send(data);

	//also update current delay
	const ajax2 = new XMLHttpRequest();
	const data2 = new FormData();
	data2.set('currDelay', '');
	ajax2.onload = function() { document.getElementById('throttleDelay').textContent = this.responseText; }
	ajax2.open('POST', '/server/throttle.php');
	ajax2.send(data2);
	return false; //always return false to override default onSubmit action
} //UpdateThrottle


//---------------------------------------------------------------------------
//BossCmd - submit appropriate command:  ?cmd, ?qry, or ?cmd=qry
function BossCmd(cmd) {
	const qry = document.getElementById('bossCmd').value;
	if(cmd == '⏎')  cmd = '';
	if(cmd && qry)  cmd += '=';
	window.location = '?' + cmd + encodeURIComponent(qry);
	//Note: Boss menu uses GET to send commands (button presses) and queries. This technique
	//might return cached results. To avoid this problem (besides pressing <SHIFT>+F5) it may 
	//be necessary to randomize the URI:
	//  <url>?<qry> +'&'+ Math.random(); //add random number to force page refresh
} //BossCmd


//---------------------------------------------------------------------------
//init BossMenu
window.addEventListener("load", function() {
	if(!document.getElementById('bossMenu'))  return;

	//all bossMenu buttons.onClick = BossCmd
	document.querySelectorAll('#bossMenu button').forEach(elem => {
		if(elem.nextElementSibling  &&  elem.nextElementSibling.nodeName == 'FORM')
			return; //skip - proxy buttons handle their own onClick
		elem.addEventListener('click',ev=>BossCmd(ev.currentTarget.textContent));
	});

	//add bossMenu <ENTER> key
	document.getElementById('bossCmd').addEventListener('keyup', ev => {
		if(ev.key == 'Enter'  &&  ev.currentTarget.value)  //ignore <ENTER> for empty command
			document.getElementById('bossSubmit').click();
	});

	//click to toggle expanded view of 'desc' fields
	document.querySelectorAll('table.list td.desc').forEach(
		td=>td.addEventListener('click', ()=>ToggleListDesc(td)));

	InitTabs();   //initialize tab menus
}); //onLoad
