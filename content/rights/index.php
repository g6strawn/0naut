<?php
$g_pageTitle = "Privacy, Security, Freedom";
$g_pubDate = new DateTime('2016-06-01');
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/header.php";
?>
<article>
<header>
  <h1><?=$g_pageTitle?></h1>
  <div class="byline">
    <time pubdate datetime="<?=$g_pubDate->format('Y-m-d')?>"><?=$g_pubDate->format('j F Y')?></time>, 
    <a href="/about" rel="author"><?=$g_pageAuthor?></a>
  </div>
</header>

<div class="eli5" onclick="ToggleELI5(this)">
  <span title="Explain Like I'm Five - Article Summary">&#9650 ELI5</span>
  <div>
    <p><b>Privacy (Cryptography)</b> - We're the good guys. Some people think good guys have nothing to hide so we don't need complicated spy stuff like cryptography. But cryptography is not complicated and it keeps the bad guys from spying on us. That's important. You don't want someone to steal your money do you? So how do we protect our privacy while keeping the bad guys from doing bad things?</p>

    <p><b>Security (Bitcoin)</b> - The world has Mommies and Daddies, we call them trusted public authorities. These people are supposed to make sure bad things don't happens. Sometimes though, the Mommies and Daddies make mistakes and sometimes they do things we don't want them to do, like take our money. Should we always trust the Mommies and Daddies or should we protect ourselves? That's what Bitcoin is, it's money that doesn't rely on trusted public authorities.</p>

    <p><b>Freedom (Cicada 3301)</b> - Freedom is often called an inherent right which means everyone is free and nobody can take that away. That sure does sound nice. Unless of course you're a bad guy, then we will take away your freedom. So don't do naughty things. To be free you have to follow all the rules. Sometimes the government makes rules we don't like but we have to follow them anyway. Hmm... maybe freedom isn't such an inherent right. What if the rules are wrong? What if a bad guy makes the rules? How do we make sure we stay free even if other people don't like us?</p>

    <p><b>Privacy, security, and freedom</b> all sound important. It would be bad if someone took them away. Luckily, all we have to do is watch out for the tricky bad guys. To stop the tricky bad guys, we have to be smarter than they are. So let's get smarter!</p>
  </div>
</div>

<a href="/content/rights/bitcoin/" title="Bitcoin"><img src="/content/rights/bitcoin.png" class="inset right"></a>
<p>This is a three part series about life, liberty, and the pursuit of happiness. Sort of. It didn't start as a three part series, it started when a friend asked "What is this Bitcoin stuff all about?" Without really knowing what I was saying, I confidently replied "It's a fad. It's interesting technology but not particularly useful in everyday life." I was both horribly wrong and completely correct at the same time. That was many years ago and I'm still amazed at how poorly Bitcoin and blockchain technology are understood. Are they important? I've laid out the basics so you can decide for yourself.</p>

<a href="/content/rights/cicada/" title="Cicada 3301"><img src="/content/rights/cicada.png" class="inset left"></a>
<p>Around the same time, I also discovered a mysterious Internet puzzle called Cicada 3301. At first it looked like a ridiculous conspiracy hoax but the more I looked into it, the more complex it became. It's a good example of the darker side of the Internet and also a terrific example of how personal freedom is sometimes more important than security.</p>

<p><a href="/content/rights/cryptography/" title="Crytopgraphy"><img src="/content/rights/crypto.png" class="inset right"></a>
Trying to explain all this, I realized how few people understand the concept of public-key encryption. It sounds esoteric but it is actually very simple. Not only is public-key encryption easy to understand, it's also an extremely important part of modern society. The scary part is that the powers-that-be are trying to take this away from us. In my opinion, that would be a horrible mistake.</p>

<p>Should you trust a bank to keep your money for you? Will our politicians keep us safe during the next war or recession? Are we really free to choose our own religion, speak out against the government, or do whatever we want to do on the Internet? Let's find out.</p>
</article>

<?php
include "{$_SERVER['DOCUMENT_ROOT']}/content/rights/menu.inc.php";
MakeMenu($g_aMenuFreedom, 1);
include "{$_SERVER['DOCUMENT_ROOT']}/inc/footer.php";
?>
