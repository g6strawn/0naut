<?php # Boss menu helpers
if(count(get_included_files())==1)  exit; //direct access not permitted

//---------------------------------------------------------------------------
//DoTest - placeholder for debugging
function DoTest() {
	var_dump($_SERVER);
} //DoTest
	
	
//---------------------------------------------------------------------------
//ShowTodo - show help for Boss menu
function ShowTodo() { ?>
<article id="bossTodo" class="floatBox">
<h3>TODO</h3>
<ul>
  <li>interactive header</li>
  <li>footer secret - click to enter secret club</li>
  <li>log HTTP_REFERER for new users</li>
  <li>HTML editor (write your own code)</li>
  <li><a href="https://www.php.net/manual/en/features.file-upload.php">File uploads</a></li>
  <li>tic-tac-toe ^2</li>
  <li>rock-paper-scissors chase</li>
  <li>news feeds</li>
</ul>
</article>
<?php } //ShowTodo


//---------------------------------------------------------------------------
//ShowHelp - show help for Boss menu
function ShowHelp() {
	if(!IsBoss('Cashier'))  return;
?>
<article id="bossHelp" class="floatBox">
  <h3>What do all those buttons do?</h3>
  <ul class="tabs">
    <li data-tab_id="Intro" class="tab_active">Intro</li>
    <li data-tab_id="Orders">Orders</li>
    <li data-tab_id="Users">Users</li>
    <li data-tab_id="Prods">Prods</li>
  </ul>

  <!-- Intro -->
  <div id="Intro" class="tabContent">
    <p>The buttons are nothing but command shortcuts. For example, you can press <button class="tiny" onclick="BossCmd('USPS')" title="Anything after 'USPS' is ignored.">USPS</button> or type "USPS". You can press <button class="tiny" onclick="BossCmd()" title="This is the same as pressing the <ENTER> key.">&#x23CE;</button> or the &lt;ENTER&gt; key.</p>
    The big buttons are search commands:<br>
    <dl>
      <dt><b>Command</b></dt><dd><b>Result</b></dd>
      <dt>order smith</dt><dd>orders billed/shipped to smith</dd>
      <dt>users smith</dt><dd>users with a name like smith</dd>
      <dt>wholesale</dt><dd>recent wholesale orders</dd>
      <dt>users wholesale</dt><dd>users with wholesale status</dd>
      <dt>prod wholesale</dt><dd>wholesale products</dd>
      <dt>1234</dt><dd>order #1234</dd>
      <dt>user 1234</dt><dd>user #1234</dd>
      <dt>2 months</dt><dd>orders in past 2 months</dd>
      <dt>Jan 2020</dt><dd>orders in January 2020</dd>
    </dl>
    <p>Note that orders is the default search. Typing "green" will search orders billed/shipped to "Green". To see all unroasted green products, type "green" then press <button class="tiny" onclick="BossCmd('Prods')" title="This button may be tiny but it still works.">Prods</button> or type "prod green".</p>
  </div>

  <!-- Orders -->
  <div id="Orders" class="tabContent">
    <div class="ctxt">Type search string then press
      <button class="tiny" onclick="BossCmd('Orders')"
        title="Search for orders matching the search string.">Orders</button> or 
      <button class="tiny" onclick="BossCmd()"
        title="This is the same as pressing the <ENTER> key.">&#x23CE;</button><br>
      or prepend search string with "order" or "o".
    </div>
    <dl>
      <dt><i>&lt;blank&gt;</i></dt><dd>Recently shipped orders</dd>
      <dt><i>&lt;num&gt;</i></dt><dd>Edit order #<i>&lt;num&gt;</i></dd>
      <dt><i>&lt;name&gt;</i></dt><dd>billed/shipped to <i>&lt;name&gt;</i></dd>
      <dt><i>&lt;date&gt;</i></dt><dd>orders since <i>&lt;date&gt;</i></dd>
      <dt>2 months</dt><dd>orders in past 2 months</dd>
      <dt>Jan 2020</dt><dd>orders in January 2020</dd>
      <dt>new</dt><dd>New orders ready for shipping</dd>
      <dt>unpaid</dt><dd>Incomplete/declined orders</dd>
      <dt>wholesale</dt><dd>orders with wholesale products</dd>
      <dt>totals</dt><dd>calendar with monthly totals</dd>
      <dt><i>&lt;field&gt;</i> = <i>&lt;value&gt;</i></dt><dd>bill, ship, company, city, state, zip, email, remarks, notes, status<br>Ex: state=CA, company=Bean</dd>
    </dl>
  </div>

  <!-- Users -->
  <div id="Users" class="tabContent">
    <div class="ctxt">Type search string then press
      <button class="tiny" onclick="BossCmd('Users')"
        title="Search for users matching the search string.">Users</button><br>
      or prepend search strings with "users" or "u".
    </div>
    <dl>
      <dt><i>&lt;blank&gt;</i></dt><dd>Recently active users</dd>
      <dt><i>&lt;num&gt;</i></dt><dd>Edit user #<i>&lt;num&gt;</i></dd>
      <dt><i>&lt;name&gt;</i></dt><dd>User names like <i>&lt;name&gt;</i></dd>
      <dt>new</dt><dd>New users</dd>
      <dt>wholesale</dt><dd>Wholesalers</dd>
      <dt>boss</dt><dd>Boss access</dd>
      <dt>[<i>min</i>]-[<i>max</i>]</dt><dd>user# range</dd>
      <dt>[visited] <i>&lt;date&gt;</i></dt><dd>active since <i>&lt;date&gt;</i></dd>
      <dt>[created] <i>&lt;date&gt;</i></dt><dd>new users since <i>&lt;date&gt;</i></dd>
      <dt><i>&lt;field&gt;</i> = <i>&lt;value&gt;</i></dt><dd>email, flags, company, city, state, zip, phone, remarks, notes<br>Ex: state=CA, flags=banned</dd>
    </dl>
  </div>

  <!-- Products -->
  <div id="Prods" class="tabContent">
    <div class="ctxt">Type search string then press
      <button class="tiny" onclick="BossCmd('Prods')"
        title="Search for products matching the search string.">Prods</button><br>
      or prepend with "product", "prod", or "p".
    </div>
    <dl>
      <dt><i>&lt;blank&gt;</i></dt><dd>Show all products</dd>
      <dt><i>&lt;SKU&gt;</i></dt><dd>Find matching SKU</dd>
      <dt><i>&lt;desc&gt;</i></dt><dd>title like <i>&lt;desc&gt;</i></dd>
      <dt><i>&lt;num&gt;</i></dt><dd>weight: 8=half pound, etc.</dd>
      <dt>w|wholesale</dt><dd>wholesale products</dd>
      <dt>p|m|d|g</dt><dd>peaberry, medium, dark, green...</dd>
    </dl>
  </div>
</article>
<?php } //ShowHelp


//---------------------------------------------------------------------------
//DateSelectMonth - ['1-year'=>'January' ... '12-year'=>'December']
function DateSelectMonth($year) {
	$aMonths = [];
	for($month = 1;  $month <= 12;  $month++)
		$aMonths["$year-$month"] = DateTime::createFromFormat('!m', $month)->format('F');
	return $aMonths;
} //DateSelectMonth


//---------------------------------------------------------------------------
//DateSelectYear - ['month-2007'=>2007 ... 'month-2020'=>2020]
function DateSelectYear($month) {
	$aYears = [];
	for($year = 2007;  $year <= (int)date('Y');  $year++)
		$aYears["$year-$month"] = $year;
	return $aYears;
} //DateSelectYear


//---------------------------------------------------------------------------
//SelectBox - <select> filter of Orders, Users, etc.
// cmd - query command  Ex: orders:status, user:state, etc.
// val - currently selected value
// aOptions - array of <option value=$key>$val  Ex: [HI=>Hawaii, ...] or [created=>created, ...]
function SelectBox($cmd, $val, $aOptions) {
	$select = "<select required onChange=\"window.location='?$cmd='+ this.value\">";
	if(!isset($aOptions[$val]))
		$select .= "<option value=\"\" disabled selected>Select $cmd</option>";
	foreach($aOptions as $k=>$v)
		$select .= "<option value=\"$k\"". (($k === $val) ? ' selected' : '') .">$v</option>";
	$select .= '</select>';
	return $select;
} //SelectBox


//---------------------------------------------------------------------------
//ShowEvents - show scheduled events
function ShowEvents() {
	$limit = isset($_GET['moreEvents'])  ? 200 : 20;
	$aEvents = DB::Run("SELECT * FROM events ORDER BY due_date LIMIT $limit")->fetchAll();
	if(!$aEvents)  return; //silent; don't even show 'No upcoming events.'

	echo "<article id=\"showEvents\" class=\"floatBox\">\n";
	echo "  <table class=\"list\"><caption>Scheduled Events</caption>\n";
	echo "  <thead><tr><th>#</th><th>Type</th><th>Due Date</th><th>Repeat</th><th>Num</th><th>Name</th><th>Order#</th><th>SKUs</th></tr></thead><tbody>\n";
	foreach($aEvents as $event) {
		echo "<tr>\n";
		echo "  <td>{$event['event_id']}</td>\n";
		echo "  <td>{$event['event_type']}</td>\n";
		echo "  <td class=\"date\">". DateHTML($event['due_date']) ."</td>\n";
		echo "  <td>{$event['period']}</td>\n";
		echo "  <td>{$event['ordinality']}</td>\n";

		//name
		$name = $nameURL = '';
		if($event['user_id']) {
			//get name from user_id
			$name = DB::Run('SELECT CONCAT(first_name,\' \',last_name) AS name FROM users WHERE user_id=?', 
							[$event['user_id']])->fetchColumn();
			if(!$name)  $name = $event['user_id'];
			else $name = htmlspecialchars($name);
			if(IsBoss('Cashier'))        $nameURL = "/account.php?{$event['user_id']}";
			else if($event['order_id'])  $nameURL = "/order.php?{$event['order_id']}";
		} else if($event['order_id']) {
			//use shipping or billing name
			$aName = DB::Run('SELECT bill_name, ship_name FROM orders WHERE order_id=?', 
							[$event['order_id']])->fetch();
			if(!$aName)  $name = $event['order_id'];
			else $name = htmlspecialchars($aName['ship_name'] ?: $aName['bill_name']);
			$nameURL = "/order.php?{$event['order_id']}";
		}
		if(empty($name))
			echo "  <td>&horbar;</td>\n";
		else
			echo "  <td class=\"name\" title=\"$name\"><a href=\"$nameURL\">$name</a></td>\n";

		//order# and product SKUs
		if(!$event['order_id']) {
			echo "  <td>&horbar;</td><td></td>\n";
		} else {
			echo "  <td><a href=\"/order.php?{$event['order_id']}\">{$event['order_id']}</a></td>\n";
			$skus = '';
			$aCart = DB::Run('SELECT sku, qty FROM order_prods WHERE order_id=?', 
							[$event['order_id']])->fetchAll(PDO::FETCH_KEY_PAIR);
			foreach($aCart as $sku=>$qty) {
				if($sku[0] === 'D')  continue; //discount items are no longer used
				if($sku[0] === 'R')  continue; //order's repeat info is already shown
				$skus .= "$qty:$sku, ";
			}
			$skus = substr($skus, 0, -2); //remove trailing ', '
			echo "  <td class=\"desc\" title=\"$skus\">$skus</td>\n";
		}
		echo "</tr>\n";
	} //foreach
	echo "  </tbody></table>\n";

	//show "More" or "Less" link at bottom of table
	if(isset($_GET['moreEvents']))
		echo '  <a class="more" href="'. RemoveQuery('moreEvents') ."\">Less</a>\n"; //link = show less
	else if(count($aEvents) === $limit) //if hit LIMIT
		echo '  <a class="more" href="'. AddQuery('moreEvents') ."\">More</a>\n"; //link = show more

	echo "</article>\n";
} //ShowEvents


//---------------------------------------------------------------------------
//AddThrottleTriggers - output one <tr> row per throttle trigger
//  Called only from /server/throttle.php via JS UpdateThrottle()
function AddThrottleTriggers($since=0) {
	$prevHit = date('Y-m-d H:i:s', $since); //mySQL format
	$aHits = DB::Run('SELECT hit_time, INET6_NTOA(ip) AS ip, delay, msg FROM throttle '
					."WHERE hit_time > CAST('$prevHit' AS DATETIME) ORDER BY hit_time DESC LIMIT 50")->fetchAll();
	foreach($aHits as $hit) {
		$msg = filter_var($hit['msg'], FILTER_SANITIZE_SPECIAL_CHARS);
		echo "<tr>\n";
		echo "  <td class=\"date\">". date('Y-m-d H:i:s', strtotime($hit['hit_time'])) ."</td>\n";
		echo "  <td>{$hit['ip']}</td>\n";
		echo "  <td>{$hit['delay']}</td>\n";
		echo "  <td class=\"desc\" title=\"$msg\">$msg</td>\n";
		echo "</tr>\n";
	} //foreach
} //AddThrottleTriggers


//---------------------------------------------------------------------------
//ShowThrottle - called only from ShowHits
function ShowThrottle() { ?>
<!-- login throttle -->
<article id="throttleList" class="floatBox">
<table class="list"><caption style="white-space:nowrap">Throttle
  <button class="round" title="Refresh" onclick="ThrottleRefreshToggle(this)">&#8635;</button><br>
  <small title="The next throttle trigger will be delayed this long before receiving a response.">
    <b>Current delay:</b> <span id="throttleDelay"><?=CalcThrottleDelay()?></span> sec</small></caption>
<?php
if(!DB::Run('SELECT EXISTS (SELECT 1 FROM throttle)')->fetchColumn()) {
	//no triggers, don't show table
	echo "<tr><td style=\"text-align:center\">No triggers</td></tr></table>";
	echo "</article>\n";
	return;
} ?>
<thead><tr><th>Time</th><th>IP</th><th>Delay</th><th>Msg</th></tr></thead><tbody>
<?php AddThrottleTriggers(); ?>
</tbody></table>
<br>
<form method="post" action="/server/throttle.php" 
     onsubmit="return confirm('Permanently delete all throttle events?\nAre you sure?')">
  <button type="submit" name="resetThrottle"
    title="Reset throttle by deleting all prior triggers.">Delete All &amp; Reset</button>
</form>
<?php
	echo "</article>\n";
} //ShowThrottle


//---------------------------------------------------------------------------
//ShowHits - recent website activity
function ShowHits() {
	ShowThrottle();
?>
<article id="showHits" class="floatBox">
<table class="list"><caption>Site Hits
  <button class="round" onclick="location='/boss/?hits'" title="Refresh">&#8635;</button></caption>
  <thead><tr><th>Time</th><th>IP</th><th>User</th><th>Name</th><th>Page</th><th>Msg</th></tr></thead><tbody>
<?php
	$aHits = DB::Run('SELECT hit_time, INET6_NTOA(ip) AS ip, hits.user_id, page, msg,
				CONCAT(first_name,\' \',last_name) AS name 
				FROM hits LEFT JOIN users ON hits.user_id = users.user_id 
				ORDER BY hit_time DESC LIMIT 50')->fetchAll();
	foreach($aHits as $hit) {
		$msg = filter_var($hit['msg'], FILTER_SANITIZE_SPECIAL_CHARS);
		echo "<tr>\n";
		echo "  <td class=\"date\">". date('Y-m-d H:i:s', strtotime($hit['hit_time'])) ."</td>\n";
		echo "  <td>{$hit['ip']}</td>\n";
		echo "  <td>{$hit['user_id']}</td>\n";
		echo "  <td class=\"name\" title=\"{$hit['name']}\">{$hit['name']}</td>\n";
		echo "  <td class=\"name\" title=\"{$hit['page']}\">{$hit['page']}</td>\n";
		echo "  <td class=\"desc\" title=\"$msg\">$msg</td>\n";
		echo "</tr>\n";
	} //foreach
	echo "</tbody></table></article>\n";
} //ShowHits


//---------------------------------------------------------------------------
//ImportPhone - import new call/text activity from .csv file
//call format:  Date Time,Number,"Destination",Minutes,Type
//  Ex: 02/18/2022 09:47 AM,(808) 339-2297,"HILO, HI",1 Min,--
//text format:  Date Time,Number,"Destination",Direction,Type
//  Ex: 02/18/2022 03:29 PM,(785) 218-4463,"LAWRENCE, KS",Incoming,Text
//
//old voice format:  Date,Time,Destination,Number,Minutes,Type
//old text format:   Date,Time,Destination,Number,Direction,Type
function ImportPhone($fn, $isCall) {
	if(!file_exists($fn))  return;
	$fp = fopen($fn, "r");
	if(!$fp)  return;

	echo "<article id=\"importPhone\" class=\"floatBox\"><pre>\n";
	echo "Parsing ". ($isCall ? 'call' : 'text') ." file: $fn\n";
	$line = 0;
	while(($data = fgetcsv($fp, 1000)) !== FALSE) {
		//parse line
		$line++;
		if(count($data) != 5  ||  strncmp($data[0], 'Date', 4) == 0)
			continue;  //silently skip non-data lines (i.e. header)

		$timestamp = strtotime($data[0]);
		if($timestamp === FALSE) {
			echo "Line $line skipped, parse error: ". implode(',', $data) ."\n";
			continue;
		}
		$timestamp = date('Y-m-d H:i:s', $timestamp);

		//dir
		if($isCall) {
			$dir = 'Outgoing';
			if(strtoupper($data[2]) == 'INCOMING') {
				$dir = 'Incoming';
				$data[2] = '';
			}
		} else {
			$dir = $data[3];
			if(!in_array($data[3], ['Incoming','Outgoing'])) {
				$dir = '';
				echo "Line $line unknown direction: ". implode(',', $data) ."\n";
			}
		}

		//type
		$aTypes = array('--'=>'Call', 'T-Mobile to T-Mobile'=>'TMobile', 'Wi-Fi call'=>'WiFi', 'Call Waiting'=>'Call waiting', 'Text'=>'Text', 'Picture'=>'Picture');
		$type = $aTypes[$data[4]] ?? 'Unknown';
		if($type === 'Unknown')
			echo "Line $line unknown type: ". implode(',', $data) ."\n";

		//insert into database
		if(DB::Run('SELECT EXISTS (SELECT 1 FROM phone, phone_names WHERE phone.time=? AND phone.type=? AND phone.num_id=phone_names.id AND phone_names.num=?)', [$timestamp, $type, $data[1]])->fetchColumn())
			continue;  //already added

		$numId = DB::Run('SELECT id FROM phone_names WHERE num=?', [$data[1]])->fetchColumn();
		if(!$numId) {
			//insert new number
			if(!DB::Run('INSERT INTO phone_names SET num=?', [$data[1]]))
				echo "Line $line error inserting new number: ". implode(',', $data) ."\n";
			$numId = DB::lastInsertId();
		}

		if(!DB::Run('INSERT INTO phone (num_id, time, dest, min, dir, type) VALUES (?,?,?,?,?,?)', [$numId, $timestamp, $data[2], ($isCall ? $data[3] : 0), $dir, $type]))
			echo "Line $line error inserting: ". implode(',', $data) ."\n";
	} //while
	fclose($fp);
	echo "Done parsing $line lines.";
	echo "</pre></article>\n";
} //ImportPhone

//---------------------------------------------------------------------------
//ShowPhoneNumber - show all activity for specified number
//id - index into
function ShowPhoneNumber($id, $name) {
	if($name) {
		DB::Run('UPDATE phone_names SET name=? WHERE id=?', [$name, $id]);
		//fail silently and continue
	}

	$info = DB::Run('SELECT * FROM phone_names WHERE id=?', [$id])->fetch();
	if(!$info)  return; //fail silenty (don't display anything)

	$caption = "Activity for {$info['num']} ";
	if(empty($info['name'])) {
		$caption .= " Name:<input onchange=\"window.location='?phone+$id='+ this.value\">";
	} else {
		$caption .= 'asdf '. $info['name'];
	}

	echo "<article id=\"showPhoneNumber\" class=\"floatBox\">\n";
	echo "<table class=\"list\"><caption>Activity for {$info['num']}<br>\n";
	echo "<input type=\"text\" value=\"{$info['name']}\" ";
	echo   "onchange=\"window.location='?phone+$id='+ this.value\"></caption>";
	echo "  <thead><tr><th>Time</th><th>Min</th><th>Destination</th><th>Type</th></tr></thead><tbody>\n";

	$aCalls = DB::Run('SELECT time, dest, min, dir, type FROM phone WHERE num_id=? ORDER BY time DESC LIMIT 500', [$id])->fetchAll();
	foreach($aCalls as $call) {
		//direction arrow
		if($call['dir'] == "Incoming") {
			$color = 'green';
			$arrow = '&#129052;';  //left arrow
		} else {
			$color = 'red';
			$arrow = '&#129054;';  //right arrow
		}

		//timestamp & arrow
		echo "<tr>\n";
		echo "  <td class=\"date\" style=\"font-family:monospace\">". 
			date('D m/d/y h:ia', strtotime($call['time'])) .
			" <span style=\"color:$color\">$arrow</span></td>\n";

		//minutes (phone icon = '&#128222;'   text icon = '&#128172;'
		$aTypeIcons = [
			'Call'         =>'&#128172;', //phone handset
			'TMobile'      =>'&#128172;', 
			'WiFi'         =>'&#128172;', 
			'Call waiting' =>'&#128172;', 
			'Text'         =>'&#128172;', //speech bubble
			'Picture'      =>'&#128248;', //camera with flash
			'Unknown'      =>'?'];
		echo "  <td>". ($call['min'] ?: $aTypeIcons[$call['type']]) ."</td>\n";

		//destination, type
		echo "  <td class=\"name\" title=\"{$call['dest']}\">{$call['dest']}</td>\n";
		echo "  <td>{$call['type']}</td>\n";
		echo "</tr>\n";
	} //foreach
	echo "</tbody></table></article>\n";
} //ShowPhoneNumber

//---------------------------------------------------------------------------
//ShowPhone - show phone activity
function ShowPhone($qry='') {
	if(strtoupper($qry) === 'IMPORT') {
		ImportPhone('CSV_Calls_8083191809.csv', true);
		ImportPhone('CSV_Messages_8083191809.csv', false);
	}

	if(preg_match('/^(\d+)(?:=(.+))?$/i', $qry, $aMatches))  //<id>=<name>
		ShowPhoneNumber($aMatches[1], $aMatches[2] ?? '');
?>
<article id="showPhone" class="floatBox">
<table class="list"><caption>Recent Activity
  <button class="round" onclick="location='/boss/?phone%20import'" title="Refresh">&#8635;</button></caption>
  <thead><tr><th>Time</th><th>Min</th><th>Number</th><th>Destination</th><th>Type</th></tr></thead><tbody>
<?php
	$aCalls = DB::Run('SELECT phone.num_id as id, phone.time as time, phone_names.num as num, phone_names.name as name, phone.dest as dest, phone.min as min, phone.dir as dir, phone.type as type FROM phone, phone_names WHERE phone.num_id = phone_names.id ORDER BY time DESC LIMIT 1000')->fetchAll();
	foreach($aCalls as $call) {
		//direction arrow
		if($call['dir'] == "Incoming") {
			$color = 'green';
			$arrow = '&#129052;';  //left arrow
		} else {
			$color = 'red';
			$arrow = '&#129054;';  //right arrow
		}

		//timestamp & arrow
		echo "<tr>\n";
		echo "  <td class=\"date\" style=\"font-family:monospace\">". 
			date('D m/d/y h:ia', strtotime($call['time'])) .
			" <span style=\"color:$color\">$arrow</span></td>\n";

		//minutes (phone icon = '&#128222;'   text icon = '&#128172;'
		$aTypeIcons = [
			'Call'         =>'&#128172;', //phone handset
			'TMobile'      =>'&#128172;', 
			'WiFi'         =>'&#128172;', 
			'Call waiting' =>'&#128172;', 
			'Text'         =>'&#128172;', //speech bubble
			'Picture'      =>'&#128248;', //camera with flash
			'Unknown'      =>'?'];
		echo "  <td>". ($call['min'] ?: $aTypeIcons[$call['type']]) ."</td>\n";

		//name / number
		$href = "<a href=\"/boss/?phone+{$call['id']}\">";
		if(empty($call['name']))
			echo "  <td>$href{$call['num']}</a></td>\n";
		else
			echo "  <td class=\"name\" title=\"{$call['num']} {$call['name']}\">$href{$call['name']}</a></td>\n";

		//destination, type
		echo "  <td class=\"name\" title=\"{$call['dest']}\">{$call['dest']}</td>\n";
		echo "  <td>{$call['type']}</td>\n";
		echo "</tr>\n";
	} //foreach
	echo "</tbody></table></article>\n";
} //ShowPhone


//---------------------------------------------------------------------------
//UpdateBlog - search for new blog entries, update database, rebuild rss.xml
function UpdateBlog() {
	echo '<article id="updateBlog" class="floatBox">'. GenerateBlog() .'</article>';
} //UpdateBlog


//---------------------------------------------------------------------------
//ExportDB - output database as .sql file (same as phpMyAdmin export)
// exportTables - list (string) of table names, Ex: "users, orders, order_prods"
function ExportDB($exportTables='all') {
	if(!IsBoss('Admin'))  return;

	//get list of tables to be exported; also validates parameter
	$aTables = DB::Run('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN); //all tables in database
	if(!empty($exportTables)  &&  strtolower($exportTables) !== 'all')
		$aTables = array_intersect(preg_split('/[,\s]+/', $exportTables), $aTables);
	if(empty($aTables)) {
		echo "<div class=\"errBox\">Unable to export invalid table(s): $exportTables <br>\n"
			."Valid tables: ". implode(', ', DB::Run('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN)) . "</div>\n";
		return;
	}

	//filename with date  Ex: 'KonaEarth 210401'
	$fn = (count($aTables) === 1  ?  $aTables[0]  :  DB_DATABASE) .' '. date("ymd") .'.sql';

	//start file output
	ob_end_clean(); //flush HTML that was already sent
	header('Content-Type: application/sql');
	header("Content-Transfer-Encoding: Binary");
	header("Content-disposition: attachment; filename=\"$fn\"");

	global $g_siteHost;
	echo '-- Database: `'. DB_DATABASE ."` on $g_siteHost\n";
	echo '-- '. date('r') ."\n";
	echo '-- '. count($aTables) .' tables: '. implode(', ', $aTables) ."\n";
	echo "START TRANSACTION;\n";
	echo "\n";

	//convert column type (decimal(6,2), varchar(20), etc) to output style
	$aStyleLookup = [
		//0: default = string output; add slashes, \n
		//1: numeric output, no quotes
		'INT'=>1, 'INTEGER'=>1, 'TINYINT'=>1, 'SMALLINT'=>1, 'MEDIUMINT'=>1, 'BIGINT'=>1, 
		'DECIMAL'=>1, 'NUMERIC'=>1, 'FLOAT'=>1, 'DOUBLE'=>1, 'REAL'=>1, 'SERIAL'=>1, 'YEAR'=>1,
		'BOOLEAN'=>2, 'BOOL'=>2,      //2: true or false
		'BIT'=>3,                     //3: binary string, b'010101'
		'BINARY'=>4, 'VARBINARY'=>4,  //4: hexidecimal
		'BLOB'=>5, 'TINYBLOB'=>5, 'MEDIUMBLOB'=>5, 'LONGBLOB'=>5, //5: base64_encode
		'DATE'=>6, 'TIME'=>6, 'DATETIME'=>6, 'TIMESTAMP'=>6, //6: unmodified string
	];

	//export each table
	foreach($aTables as $table) {
		//Note: $table is safe because it was validated by array_intersect
		//get column type names, convert to output type
		$aColDesc = DB::Run("SHOW COLUMNS FROM `$table`")->fetchAll(); //name, type, null, etc.
		$aColStyle  = []; //output style (see $aStyleLookup)
		foreach($aColDesc as $desc) {
			$type = strtoupper(preg_split('/[(\s]/', $desc['Type'])[0]); //no length(n): int, decimal, varchar
			$aColStyle[] = $aStyleLookup[$type] ?? 0;
		}

		$aRows = DB::Run("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_NUM); //data
		echo "-- ------------------------------------------------------------\n";
		echo "-- $table - ". count($aRows) ." rows\n";
		echo "DROP TABLE IF EXISTS `$table`;\n";
		echo DB::Run("SHOW CREATE TABLE `$table`")->fetchColumn(1) .";\n\n";
		for($i = 0;  $i < count($aRows);  $i++) {
			if($i % 100 === 0)  echo "INSERT INTO `$table` VALUES\n";
			echo "(";
			for($j = 0;  $j < count($aRows[$i]);  $j++) {
				if($aRows[$i][$j] === NULL)  echo 'NULL';
				else {
					switch($aColStyle[$j]) {
						case 1: echo $aRows[$i][$j];  break; //numeric (no quotes): INT, DECIMAL, FLOAT...
						case 2: echo $aRows[$i][$j] ? 'TRUE' : 'FALSE';  break; //BOOL
						case 3: echo "b'". base_convert($aRows[$i][$j], 10, 2) ."'";  break; //BIT
						case 4: echo '0x'. bin2hex($aRows[$i][$j]);  break; //BINARY
						case 5: echo base64_encode($aRows[$i][$j]);  break; //BLOB
						case 6: echo "'{$aRows[$i][$j]}'";  break; //DATE
						default: echo '"'. str_replace("\n", "\\n", addslashes($aRows[$i][$j])) .'"'; //string
					} //switch
				}
				if($j+1 < count($aRows[$i]))  echo ", ";
			} //for j
			echo ((($i+1) % 100 === 0  &&  $i > 0)  ||  $i+1 === count($aRows))  ?  ");\n" : "),\n";
		} //for i
		echo "\n";
	} //foreach table
	echo "-- end transaction\nCOMMIT;\n";
	exit(); //stop output (don't output footer.php)
} //ExportDB


//---------------------------------------------------------------------------
//ImportDB - import database from .sql file
function ImportDB() {
	if(!IsLocalHost()  ||  !IsBoss('Admin')  ||  !isset($_FILES['sqlFile']))  return;
	if($_FILES['sqlFile']['error'] !== UPLOAD_ERR_OK) {
		$errLookup = [
			UPLOAD_ERR_INI_SIZE => 'Uploaded file exceeds upload_max_filesize directive in php.ini',
			UPLOAD_ERR_FORM_SIZE => 'Uploaded file exceeds MAX_FILE_SIZE specified in HTML form',
			UPLOAD_ERR_PARTIAL => 'Uploaded file was only partially uploaded',
			UPLOAD_ERR_NO_FILE => 'No file was uploaded',
			UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
			UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
			UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.'
		];
		if($_FILES['sqlFile']['error'] === UPLOAD_ERR_INI_SIZE) {
			echo "<div class=\"errBox\">\n";
			echo "<h2>{$_FILES['sqlFile']['name']} is too large</h2>\n";
			echo "Try exporting tables separately.<br>\n";
			$aTables = DB::Run('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN); //all tables in database
			foreach($aTables as $table)
				echo "$table, ";
			echo "\n</div>\n";
			return;
		}
		return ErrMsg($errLookup[$_FILES['sqlFile']['error']]);
	} //if error

	echo "<h2>Import SQL from {$_FILES['sqlFile']['name']}</h2>\n";
	echo "<pre>\n";
	$qry = ''; //concat lines for a full SQL query
	$aLines = file($_FILES['sqlFile']['tmp_name']);
	foreach($aLines as $line) {
		echo $line;
		if(empty($line)  ||  strncmp($line, '--', 2) === 0)  continue;
		$qry .= $line;
		if(substr(trim($line), -1, 1) === ';') { //if end of query (line end with semicolon)
			if(!DB::Run($qry))  ErrMsg("SQL error: $qry<br>\n");
			$qry = '';
		}
	} //foreach line
	echo "</pre>\n";
} //ImportDB
