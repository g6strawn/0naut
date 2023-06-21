<?php # Boss page
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/header.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/form.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/boss/boss.php"; //misc commands
require_once "{$_SERVER['DOCUMENT_ROOT']}/boss/showusers.php";

//Authorized Personnel Only
if(!IsBoss()) {
	//no warning, redirect to home page
	ob_end_clean(); //make sure no HTTP has been sent yet
	header('Location: /'); //redirect to home page
	exit;
}

if(IsBoss('Cashier')) { //Intern/Shipper can't see boss menu ?>
<!-- Boss Menu -->
<section id="bossMenu" class="floatBox">
  <input id="bossCmd" placeholder="Name, order#, date..." autofocus>
  <button id="bossSubmit" title="This is the same as pressing the <ENTER> key">&#x23CE;</button>
  <button title="Recent website traffic">Hits</button>
  <button title="Enter user name, #, company=, city=, wholesale, boss...">Users</button>
<?php if(IsBoss('Dev')) { ?>  <button class="small" title="Phone records">Phone</button><?php } ?>
  <button title="Used for testing">Test</button>
  <button title="Upload a file"
      onclick="document.getElementById('uploadFile').click()">Upload</button>
    <form action="" method="post" encType="multipart/form-data">
      <input type="file" id="uploadFile" name="aUploads[]" accept=".txt" multiple 
          onChange="this.form.submit()" style="display:none">
    </form>

<?php //database import/export
	if(IsBoss('Admin')) {
		if(IsLocalHost()) { //Import ?>
  <!-- use a proxy button instead of trying to style <input file> -->
  <button title="Import database from .sql file" 
      onclick="document.getElementById('sqlFile').click()">Import</button>
    <form action="" method="post" encType="multipart/form-data">
      <input type="file" id="sqlFile" name="sqlFile" accept=".sql" 
          onChange="this.form.submit()" style="display:none">
    </form>
<?php } else { //Export ?>
  <button title="Export database table(s) to .sql file">Export</button>
<?php } 
	} //if IsBoss(Admin)
?>
  <button title="Tips and tricks">Help</button>
</section>
<?php } //BossMenu

echo "<section class=\"boxes\">\n";

const BOSS_CMDS = [
	'user'    =>'/^(u(?:sers?)?)\b\s*[=:]?\s*/i',         //(u, user, users) [[=]qry]
	'hits'    =>'/^hits$/i',                   //hits
	'phone'   =>'/^phone\b\s*[=:]?\s*/i',      //phone [import | <id> | <id>=<name>]
	'export'  =>'/^export\b\s*/i',             //export [all|<table>[, <table>...]]
	'test'    =>'/^test$/i',                   //test
	'help'    =>'/^h(?:elp)?$|^\?$/i',         //help|h|?
	'phpinfo' =>'/^php(?:info)?$/i',           //phpinfo
]; //BOSS_CMDS


if(isset($_FILES['sqlFile']))  ImportDB();  //handle database Import button

//process GET (i.e. process menu input)
else if(empty($_GET)  ||  !IsBoss('Cashier')) { //if no input or not a cashier
	//no input = page default
//	ShowOrders('new');
//	if(IsBoss('Cashier'))  ShowOrders('unpaid');
//	if(IsBoss('Shipper'))  ShowEvents();
	if(IsBoss('Dev'))  ShowTodo();
} else {
	//Example URL: "/boss/?ship+to+smith+%26+wesson&limit=50"
	//  $full = "ship to smith & wesson"
	//  $cmd  = "ship"
	//  $qry  = "smith & wesson"
	$full = $cmd = $qry = '';
	if(count($_GET) === 1)
		$full = urldecode($_SERVER['QUERY_STRING']);
	else {
		//remove all other GET parameters
		$pos = strpos($_SERVER['QUERY_STRING'], '&'); //start of second paramenter
		$full = urldecode(substr($_SERVER['QUERY_STRING'], 0, $pos));
	}

	//find corresponding BOSS_CMD
	foreach(BOSS_CMDS as $key=>$regex) {
		if(preg_match($regex, $full)) {
			$cmd = $key; //replace with formal command from BOSS_CMDS
			$qry = preg_replace($regex, '', $full); //remove command; Ex: 'orders new' => 'new'
			break;
		} //if preg_match
	} //foreach

	//process cmd
	switch($cmd) {
		case '':        ShowHits();         break; //empty/unknown command = default = order <str>
//		case '':        ShowOrders($full);  break; //empty/unknown command = default = order <str>
//		case 'order':   ShowOrders($qry);   break;
		case 'user':    ShowUsers($qry);    break;
//		case 'prod':    ShowProds($qry);    break;
//		case 'sku':     echo "sku"; ShowProds($full);   break;
//		case 'pick':    ob_end_clean();  header('Location: /pick/');  exit;
//		case 'pulp':    ob_end_clean();  header('Location: /boss/pulp.php');  exit;
		case 'phone':   ShowPhone($qry);    break;
//		case 'usps':    ShowUSPS();         break;
//		case 'blog':    UpdateBlog();       break;
//		case 'spam':    ShowSpam();         break;
		case 'hits':    ShowHits();         break;
		case 'export':  ExportDB($qry);     break;
		case 'test':    DoTest();           break;
		case 'help':    ShowHelp();         break;
//		case 'daily':   DailyCron();        break;
		case 'phpinfo': if(IsBoss('Dev')) phpinfo();  break;
		default: echo "<div class=\"errBox\">$cmd not yet implemented</div>";
	} //switch
} //if $_GET
?>
</section>

<?php include_once "{$_SERVER['DOCUMENT_ROOT']}/inc/footer.php"; ?>
