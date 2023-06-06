<?php //Copyright (c) 2022 Gary Strawn, All rights reserved
$g_pageTitle = "Dungeon of Thunk";
$g_pageDesc = "Where is the exit?";
$g_pageImage = '/dungeon/entry.png';
$g_noHdrFtr = true; //don't show siteHdr, siteFtr, or Login
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/header.php";

$threeRoot = IsLocalHost() ? '/three' : 'https://threejs.org';
if(IsLocalHost())  echo '<script>window.isLocalHost = true;</script>';
?>
<!-- es-module-shims.js = importmap polyfill - Remove when importmap widely supported -->
<script async src="https://unpkg.com/es-module-shims@1.3.6/dist/es-module-shims.js"></script>
<script type="importmap">{"imports":{
  "three": "<?=$threeRoot?>/build/three.module.js",
  "three/addons/": "<?=$threeRoot?>/examples/jsm/"
}}</script>
<script type="module" src="dungeon.js"></script>

<dialog id="topInfo" class="posTopInfo" data-hotkey="F2" <?= IsLocalHost() ? 'open' : '' ?>>
  <button class="closeBtn" title="Open/Close">F2</button>
  <pre id="infoPlayer"></pre>
  <pre id="infoPlayerdxz"></pre>
<?php if(IsLocalHost()) { ?>
  <select id="entSelect" data-ext="<?= isset($_GET['fbx']) ? 'fbx' : 'gltf' ?>">
    <option value="" disabled selected hidden>Choose an ent...</option>
<?php //get all file names
$path = isset($_GET['fbx']) ? './assets/modular/fbx/*.fbx' : './assets/modular/*.gltf';
$aNames = glob($path, GLOB_NOSORT);
foreach($aNames as $filename) {
	$name = pathinfo($filename, PATHINFO_FILENAME); //no path or .ext
	echo "    <option value=\"$name\">$name</option>\n";
}
}?>
  </select>
</dialog>

<dialog id="msgLog" data-hotkey="F3" class="logMaximize logShowTime">
  <button id="logClose" class="closeBtn" title="Open/Close">F3</button>
  <button id="logTime"  class="topBtn" title="Show timestamps">&#9716;</button>
  <button id="logClear" class="topBtn" title="Clear log">&#11199;</button>
  <button id="logMax"   class="topBtn" title="Maximize">&#9650;</button>
  <pre id="logMsgs" class="logShowTimes"></pre>
  <input id="logCmd" placeholder="Type ? for help" autofocus></input>
</dialog>
<div id="msgLogAlone"></div>

<div id="loading">
  <h1>Dungeon of Thunk</h1>
  <div>
    <span>&#x1F612;</span>
    <progress id="loadProgress">Loading...</progress>
    <span>&#x1F603;</span>
  </div>
  <p id="loadStatus">Adjusting fun...</p>
</div>

<template id="templateHotkeys">
  <table id="hotkeys">
    <tr><td>&lt;arrows&gt;</td><td>Move forward/left/back/right</td></tr>
    <tr><td>&lt;space&gt;</td><td>Fight / interact with object</td></tr>
    <tr><td>&lt;shift&gt;</td><td>Run</td></tr>
    <tr><td>C</td><td>Crouch</td></tr>
    <tr><td>F2</td><td>Show/hide top info box</td></tr>
    <tr><td>F3</td><td>Show/hide bottom message log</td></tr>
    <tr><td>Ctrl+L</td><td>Lock/unlock mouse-look mode</td></tr>
  </table>
</template>

<div id="citations">
  Copyright &copy; 2022-<?=date('Y')?> Gary Strawn, All rights reserved<br>
  <ul>
<?php
$aArt = [
['Dungeon Entrance', 'https://artstation.com/artwork/8QZQw', 'Denis Popov'],
['Modular Dungeon Kit 2', 'https://opengameart.org/content/modular-dungeon-2-3d-models', 'Keith at Fertile Soil Productions', 'CC0'],
['Knight', 'https://opengameart.org/content/lowpoly-animated-knight', 'quaternius', 'CC0'],
['RPG Characters', 'https://opengameart.org/content/lowpoly-rpg-characters', 'quaternius', 'CC0'],
['Cute monsters', 'https://opengameart.org/content/textured-cute-monster-pack', 'quaternius', 'CC0'],
['Farm animals', 'https://opengameart.org/content/lowpoly-animated-farm-animal-pack', 'quaternius', 'CC0'],
['Sky Home Dioroma', 'https://skfb.ly/P6nF', 'Sander Vander Meiren', 'CCA'],
];
$aCC = [
	'CC0' => 'https://creativecommons.org/publicdomain/zero/1.0/',
	'CCA' => 'https://creativecommons.org/licenses/by/4.0/',
];
function href($url, $txt) { return "<a href=\"$url\" rel=\"nofollow\">$txt</a>"; }
foreach($aArt as $art) {
	if(count($art) < 2)  continue;
	echo '<li>'. href($art[1], $art[0]);
	if($art[1])  echo ' by '. $art[2];
	if(isset($art[3])  &&  $art[3]  &&  isset($aCC[$art[3]]))
		echo ' ['. href($aCC[$art[3]], $art[3]) .']';
	echo "</li>\n";
}
?>
  </ul>
</div>

<?php include "{$_SERVER['DOCUMENT_ROOT']}/inc/footer.php"; ?>
