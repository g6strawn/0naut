<?php //Copyright (c) 2022 Gary Strawn, All rights reserved
if(isset($_GET['tesla'])  ||  isset($_GET['Tesla']))  $g_redirectURL = '/tesla.php';
$g_pageDesc = 'A random collection of content and minigames.';
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/header.php";

//force the SignIn box
if(!IsSignedIn()) {
	$resetId = $_GET['reset'] ?? null; //GET[reset] = forgot password  i.e. /?reset=<hex digits>
	if(!ctype_xdigit($resetId)  ||  strlen($resetId) != FORGOT_NUMBYTES*2)  $resetId = null;
	echo "<script>addEventListener('load', ()=>{OpenLogin($resetId)})</script>";
}
?>

<section class="boxes">

<!-- Blinking Lights -->
<div id="blix"></div>

<!-- Cistercian Numerals -->
<div id="cistercian" title="Cistercian">
  <canvas width="120" height="160">Update your browser</canvas>
  <div>
    <input type="number" min=0 max=9999 oninput="Cistercian(this.value)">
    <span class="popup">?
      <div>What is this?<br><br>
        Take a <em>*couple minutes*</em> to see if you can figure it out.<br><br>
        <a href="https://en.wikipedia.org/wiki/Cistercian_numerals" target="_blank">Answer</a>
      </div>
    </span>
  </div>
</div>

<!-- Circuit Board Vending Machine -->
<a href="/vendor/" title="Custom Circuit Boards"><img src="/vendor/art/cb.png"></a>

<!-- Powers of 2 game  i.e. 1024 -->
<div id="pow2"><div id="pow2Board" onclick="location='/content/pow2/'"></div></div>
<script src="/content/pow2/pow2.js"></script>

<!-- Dungeon entrance -->
<a id="dungeon" href="/dungeon/" title="Enter the dungeon.">
  <h1 id="dungeonText">Dungeon of Thunk</h1>
  <img id="dungeonDoor" src="/dungeon/entry.jpg" width=300 height=250>
  <img id="dungeonEyes" src="/dungeon/eyes.png" style="display:none">
</a>
</section>


<!-- Interactive Articles -->
<section class="boxes" id="blogBoxes">
<details>
  <summary>Interactive Articles<span class="wide_only"> & Minigames</span></summary>
    The long-form articles below are as simple as possible but some subjects are simply not simple. In addition to the giant piles of words, each article includes interactive elements and usually a mini-game which is why it's necessary to sign in. You're being scored but the points don't matter.
</details>

<!-- Book -->
<article id="book" class="blogBox" onclick="location='/content/book/'" tabindex="0">
  <h1>Book: Aisudo</h1>
  <h2>1 Apr 2022</h2>
</article>

<!-- Investing -->
<article id="invest" class="blogBox" onclick="location='/content/invest/'" tabindex="0">
  <h1>Dad's Guide<br>to Investing</h1>
  <h2>1 Jan 2021</h2>
</article>

<!-- Lunar Lander -->
<article id="lander" class="blogBox" onclick="location='/content/lander/'" tabindex="0">
  <h1>Landing on the Moon</h1>
  <h2>20 May 2017</h2>
</article>

<!-- Rights - Crypto, Bitcoin, Cicada -->
<article id="rights" class="blogBox" onclick="location='/content/rights/'" tabindex="0">
  <h1>Cryptography, Bitcoin, Cicada</h1>
  <h2>1 June 2016</h2>
  <div>
    <a href="/content/rights/cryptography/" title="Crytopgraphy"><img src="/content/rights/crypto.png"></a>
    <a href="/content/rights/bitcoin/" title="Bitcoin"><img src="/content/rights/bitcoin.png"></a>
    <a href="/content/rights/cicada/" title="Cicada 3301"><img src="/content/rights/cicada.png"></a>
  </div>
</article>

</section>


<!-- xkcd RSS Feed (not shown until after server fetches it) -->
<template id="xkcdTemplate">
<article id="xkcd">
  <header id="xkcd_hdr">
    <a href="\content\xkcd1190\"><img src="/content/xkcd1190/thumb/1.png" id="xkcd1190_thumb" width=72 height=50></a>
    <h1 title="Copyright xkcd">
      <a href="https://xkcd.com/" target="_blank" title="&copy; xkcd">xkcd</a><br>
      <span id="xkcd_title"></span>
    </h1>
  </header>
  <section>
    <a href="https://xkcd.com/" target="_blank" class="center"><img src="/content/xkcd1190/frames/0001.png" id="xkcd_img"></a>
  </section>
  <details title="xkc... what?">
    <summary>What is xkcd?</summary>
      What is xkcd? Only the best web comic ever! The rule is that there's a relevant xkcd for everything. Never heard of it? Cool, today you're one of the <a href="https://xkcd.com/1053/" target="_blank">lucky 10,000</a>. Seriously, xkcd is a deep rabbit hole.
  </details>
</article>
</template>

<?php //fetch xkcd
if(isset($_GET['xkcd_rss'])) {
	$xml = @simpleXML_load_file('https://xkcd.com/rss.xml');
	ob_end_clean(); //ignore any error
	if($xml === false)
		exit(json_encode(['title'=>'xkcd',
						  'img'=>'<img src="/content/xkcd1190/frames/0001.png">']));
	else
		exit(json_encode(['title'=>(string)$xml->channel->item[0]->title,
						  'img'=>(string)$xml->channel->item[0]->description]));
} //if xkcd
?>

<?php include "{$_SERVER['DOCUMENT_ROOT']}/inc/footer.php"; ?>
