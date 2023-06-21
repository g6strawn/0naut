<?php
$g_pageTitle = "Will Bitcoin make money obsolete?";
$g_pubDate = new DateTime('2016-02-01');
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/header.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/content/rights/puzzle.inc.php";
?>
<article>
<header>
  <h1><?=$g_pageTitle?></h1>
  <div class="byline">
    <time pubdate datetime="<?=$g_pubDate->format('Y-m-d')?>"><?=$g_pubDate->format('j F Y')?></time>, 
    <a href="/about" rel="author"><?=$g_pageAuthor?></a><br>
    See also: Part 1 - <a href="/content/rights/cryptography/" title="Privacy, security, freedom: Part 1 of 3">Cryptography? I have nothing to hide.</a>
  </div>
</header>

<div class="eli5" onclick="ToggleELI5(this)">
  <span title="Explain Like I'm Five - Article Summary">&#9650 ELI5</span>
  <div>Is Bitcoin only for nerds and bad guys? That's what I thought at first. Then I learned more about it and realized that even normal grown-up people are using it. Some people use Bitcoin because they don't trust Mr. Greedy McBanker. I don't like him much either, he doesn't always share nicely. Other people use Bitcoin because it lets them trade with anybody in the whole wide world even if they don't know each others' names. That sounds good to me! Who knows, maybe some day everyone will use bitcoins instead of pennies. Probably not but nobody knows for sure.</div>
</div>

<div class="disclaimer"><strong>Disclaimer</strong>: I am neither an economist nor a Bitcoin expert. In fact, I am not particularly good with my own money so don't look to me for financial advise. At the time of this writing I don't own any bitcoin, though I may in the future, or not, I don't know yet. This article is the result of my own attempt to understand Bitcoin better. Before buying or selling any bitcoins, you should do your own research.
</div>

<figure class="right">
  <span class="toggle2024"><img src="isisflag.jpg" alt="Isis terrorists" width="340" height="210"
    title="Isis terrorists, 2014"></span>
  <span class="toggle1924"><img src="archduke.jpg" alt="Archduke of Austria" width="340" height="210"
    title="Archduke of Austria, 1914"></span>
  <figcaption>
    <span class="toggle2024">Isis terrorists, 2014</span>
    <span class="toggle1924">The Archduke of Austria, 1914</span>
  </figcaption>
</figure>
<p>Pretend that it is the year 
<span class="toggle2024">2014</span> 
<span class="toggle1924">1914</span> 
and you live in the 
<span class="toggle2024">United States of America.&nbsp;</span> 
<span class="toggle1924">Weimar Republic (Germany).</span> 
The economy is relatively stable and prosperous. The 
<span class="toggle2024">U.S. dollar</span> 
<span class="toggle1924">German mark</span> 
has nearly the same value as other important currencies such as the 
<span class="toggle2024">Euro, British pound, Australian and Canadian dollar.</span> 
<span class="toggle1924">British shilling, French franc and the Italian lira.</span> 
A gallon of gas is about $3, a decent beer is $5 and a modest meal is $10. Economically, life is pleasant.</p>

<p>You wake up one morning to news that a small group of six 
<span class="toggle2024">al-Qaeda &nbsp;terrorists</span> 
<span class="toggle1924">Black Hand assassins</span> 
have killed 
<span class="toggle2024">an important politician.</span> 
<span class="toggle1924">the Archduke of Austria.</span> 
This makes a lot of people very angry. Fingers are pointed, threats are made and armies are mobilized. Things escalate quickly and within weeks several countries are shooting at each other.</p>

<p>At first the press reports that the conflict is not expected to last long. It started quickly and should be over quickly. Some politicians are calling it a war and want to raise taxes to pay for it but that idea is quickly abandoned because nobody wants higher taxes. Instead it is decided that the conflict will be financed with a few government bonds (i.e. public debt).</p>

<p>As these things often do, the conflict gets nastier and lasts longer than expected. It is 
<span class="toggle2024">2019</span> 
<span class="toggle1924">1919</span> 
before things finally settle down again but by then the government debt has piled up and the economy is really struggling. Consumer prices have more than doubled. Gas is $6.50 per gallon, a beer is $11 and a meal to go with it is about $25 per person. Your paycheck received a slight cost-of-living increase but it's not enough. Employers are struggling too and you're lucky to have a job at all.</p>

<p>The actual fighting occurred elsewhere so 
<span class="toggle2024">the U.S.</span> 
<span class="toggle1924">Germany&nbsp;</span> 
didn't sustain much damage but other countries sure did. As a result, an international treaty demands that 
<span class="toggle2024">the U.S.</span> 
<span class="toggle1924">Germany&nbsp;</span> 
use its still-intact industries, natural resources and workforce to pay for its war crimes. This added economic burden causes some serious problems for the already struggling economy. By the end of the year, prices nearly quadruple. By February 
<span class="toggle2024">2020,</span> 
<span class="toggle1924">1920,</span> 
gas hits $70 per gallon and a single can of beer costs more than $100. On the bright side, drinking and driving is no longer a problem.</p>

<figure class="left">
  <span class="toggle2024"><img src="money2014.jpg" alt="Money as toilet paper" width="300" height="200"
    title="Which is more valuable?"></span>
  <span class="toggle1924"><img src="money1914.jpg" alt="money in wheelbarrow" width="300" height="200"
    title="Gonna need another wheelbarrow"></span>
  <figcaption>What would you do with all that money?</figcaption>
</figure>
<p>The next year is a tough time. Prices drop a little, giving everyone some hope, then climb right back up again, yanking that hope away. In 
<span class="toggle2024">2021</span> 
<span class="toggle1924">1921</span> 
the first war-crime payments are due and 
<span class="toggle2024">China and Russia &nbsp;</span> 
<span class="toggle1924">France and Britain</span> 
make it clear that they intend to collect. With the entire economy falling apart, the government is forced to pay its debt by printing more money. With nothing to back the value of that money it quickly becomes worth even less than it already was. Gas reaches $150 per gallon, beer is $200 per can and meals are a whopping $500 per plate. Imagine trying to feed a family at those prices. Skipping meals becomes disturbingly common.</p>

<figure class="right">
  <span class="toggle2024"><img src="inflation2014.png" alt="imaginary hyperinflation" width="220" height="257"></span>
  <span class="toggle1924"><img src="inflation1914.png" alt="hyperinflation 1914" width="220" height="257"></span>
  <figcaption>
    <span class="toggle2024">Hyperinflation sucks</span>
    <span class="toggle1924"><a href="https://en.wikipedia.org/wiki/Hyperinflation_in_the_Weimar_Republic#Hyperinflation" title="Yup, this graph is real">Hyperinflation sucks</a></span>
  <figcaption>
</figure>
<p>Then, in 
<span class="toggle2024">2022,</span> 
<span class="toggle1924">1922,</span> 
a popular and powerful politician, 
<span class="toggle2024">Abe Lincoln XVII,</span> 
<span class="toggle1924">Walther Rathenau,</span> 
is assassinated by a group of right-wing, anti-Semite extremists who don't want to pay for the war crimes. This throws things into complete chaos. The country needs help but other countries refuse to loan any more money. With few choices remaining, the government decides to keep printing money. All the sane people know this is a bad idea but that doesn't stop the government, they just print money faster and faster until inflation is out of control. The price of a decent meal increases to $1000, then $2000, then $5000, then $10,000. Seriously, $10,000 for a hamburger.</p>

<p>By 
<span class="toggle2024">2023</span> 
<span class="toggle1924">1923</span> 
this whole money thing just isn't working any more. Nobody wants a 
<span class="toggle2024">U.S. dollar.</span> 
<span class="toggle1924">German mark.</span> 
At one point, it gets as ridiculous as requiring $1 trillion 
<span class="toggle2024">U.S. dollars</span> 
<span class="toggle1924">German marks</span> 
to equal a single dollar from other countries. The 
<span class="toggle2024">U.S. dollar</span> 
<span class="toggle1924">German mark</span> 
is worthless.</p>

<p>What happens next? If you know some history, or if you're German, then this little story probably didn't fool you. Simply subtract 100 from all the dates and you have the story of hyperinflation in the Weimar Republic, or as we call it now, Germany. The story is real, the converted dollar amounts are accurate, only the dates and names have been changed. Flip the switch and see for yourself.</p>

<div id="switch1924">
  <div>2024<br>Fiction</div>
  <div>
    <input type="checkbox" class="toggle" id="WWI" onclick="Toggle1924()">
    <label for="WWI"></label>
  </div>
  <div>1924<br>Germany</div>
</div>

<p>As you can imagine, the Weimar citizens were not too happy with their government after hyperinflation destroyed the economy. When a new government came along with promises of recovery and revenge, it was quickly embraced. That new government eventually became the Nazi Party, led by Adolf Hitler. This was the start of World War II. Imagine if that happened again now, with today's massive weaponry.</p>

<p>Did we humans learn our lesson? I'd sure like to think so. What kind of government thinks it can pay off its debt simply by printing more money? Surely modern day banks and governments know better.</p>

<figure class="right">
  <img src="zimbabwe.jpg" alt="Zimbabwe trillion dollars" width="300" height="158" title="$100,000,000,000,000.00">
  <figcaption>One Hundred Trillion Dollars<br>This happened in 2008</figcaption>
</figure>
<p>Nope. In 2008 the world economy was precariously close to a major collapse. How did the governments respond? By incurring massive debt in order to bail out the failing banks. Luckily their scheme seems to have worked this time but it shows how easily history could repeat itself.</p>

<p>Iceland didn't fair so well in 2008, all three of its major banks failed, resulting in a severe economic depression. Zimbabwe had an even tougher time, their economy experienced hyperinflation and a complete collapse. In late 2015, with their currency still worthless, they officially switched to the U.S. dollar instead. To be fair, Zimbabwe wasn't exactly a stable country to begin with. Surely hyperinflation and complete economic collapse couldn't happen in the United States, Europe or other first-world countries.</p>

<p>As of 2015, Greece has been bailed out three times in five years and is still at risk of defaulting on its loans. The primary bank doing the lending is the European Central Bank (ECB). This isn't just any bank, this is the bank that controls the Euro, prints the money and makes loans to governments. The ECB is based in Berlin, German and should remember their history because it's easy to see how today's situation with Greece has similarities to the Weimar Republic situation 100 years ago. But similarities are not the same as guarantees and it will probably all be fine. Probably. As long as we aren't affected by the problems in Greece, Venezuela, Ukraine, Russia, Brazil, Argentina, Mayanmar or any other country with an economy that is currently flirting with disaster.</p>

<aside class="blue center" style="width:90%">
<h1 style="text-align:center">What could possibly go wrong?</h1>
<figure class="right">
  <img src="coyote.png" alt="Wile E. Coyote" width="300" height="228" title="Wile E. Coyote">
  <figcaption>Who knows, maybe things will go well for the coyote this time.</figcaption>
</figure>
<p>Most of us think $1 million is a lot of money but that's a tiny amount compared to the wealth of someone like Bill Gates. His wealth is tiny compared to the world's total gold reserves, which is tiny compared to the world's stock markets, which is tiny compared to the world's debt, which is <a href="http://money.visualcapitalist.com/all-of-the-worlds-money-and-markets-in-one-visualization/" title="A nice graphic showing relative values">tiny compared to the derivatives market</a>.</p>

<p>What is a derivative? Basically it's a contract, a promise to buy or sell something when certain conditions are met. The value of a derivative is <i>derived</i> from an underlying asset. That asset could be a stock, a physical item or maybe an event such as a good or bad harvest season. For example, a farmer might want to ensure the minimum price of next year's harvest while a buyer wants to limit the maximum price. Some call it gambling (speculating), others call it insurance (hedging).</p>

<p>The derivative market is nothing new. The only reason it is scary is because of its overwhelming size and tremendous complexity. Most of us only vaguely understand that any of this is happening. We think the world's debt is dangerously immense but that's nothing compared to the derivatives market. With something so vast and complex, even a small mishap could easily crush all of us. Let's just hope the experts are smarter than the coyote.</p>
</aside>
<br>

<p>The problem is that economic disaster is often like driving on the freeway in heavy traffic, everything seems fine until suddenly someone slams on their brakes and everyone panics at once. The heavier the traffic, the worse the panic. Even if you're careful, the carelessness of others could still ruin your day. History is filled with stories of hyperinflation and other economic disasters. It's not a question of if it will happen again, it's a matter of when.</p>

<figure class="left">
  <img src="walbeer.jpg" alt="Walbeer" width="300" height="220" title="Hold up, I gotta get me a picture of this">
  <figcaption>Cheap beer at Walmart<br><small>(Photographer unknown)</small></figcaption>
</figure>
<p>Currently the U.S. economy appears relatively stable. Gas costs less than it used to, Walmart sells plenty of cheap beer, and Americans aren't known for having to skip meals. Consumer confidence is moderate, neither too low nor too high. For the most part, the authorities seem to have everything under control.</p>

<p>I trust our economy and I keep my money in a bank account. Several accounts actually. I have the normal savings, checking and credit card accounts for storing cash and paying bills. I use another account for wiring International funds and paying employees. I tell myself that I own my home but really it's owned by the bank. My retirement savings is kept with yet another bank. I use a bank with a payment gateway so I can accept credit cards and sell coffee on the Internet. I also have a brokerage account so I can pretend to be an expert while I make and lose money in the stock market. I feel like I have more bank accounts than I have money but conventional wisdom says that banks are the best way to handle my money and by golly I agree.</p>

<p>We can all agree that banks and Wall Street are not perfect. Interest rates and ATM fees suck. Don't even think about paying your credit card late. I find it amusing when my bank acts like cashing a check from another bank is some sort of big ordeal. Really guys? If stock brokers can get busted for <a href="https://www.washingtonpost.com/news/wonk/wp/2013/09/24/traders-may-have-gotten-last-weeks-fed-news-7-milliseconds-early/" title="WashingtonPost.com">trading faster than the speed of light</a> then I think you can cash my check in less than 3-5 business days.</p>

<p>Every time I sell a bag of coffee, not only does the government take a cut for taxes, another cut goes to the credit card companies. Even worse, if a customer claims fraud then the bank will reverse the transaction and the burden of proof is on me, the merchant. These expenses are simply the cost of doing business. If I want to make a profit then I have to figure these expenses into the cost of the product. That means it doesn't matter if you're a consumer or a merchant, business expenses such as credit card fees affect you either way.</p>

<p>Bank fees are annoying but generally tolerable. Government regulations are burdensome but generally effective. Still, sometimes I wonder, what if things did go wrong? What if the 2008 financial crisis had ended in disaster? Is America's economy really too big to fail? If the economy did fail, is my money safe in a bank? Governments have frozen accounts and seized assets before, could that ever happen to me? What if, for whatever unforseeable reason, I couldn't get at my money? I don't want to be paranoid but I also don't want to blindly trust a system that might not always work in my favor.</p>

<p>Even if nothing goes wrong, wouldn't it be nice if we could cut out out all the fees, paperwork and regulations? I'd love to be able to trade with customers directly, no expensive credit card service required. If only there was a way to do business with anybody, anywhere in the world, without needing to trust elite bankers and short-sighted governments. Of course this magic trading technology should also be completely secure, accessible to all, practically free and nearly instantaneous. If only such a thing existed...</p>

<aside class="tan shadow right" style="max-width:18em">
  <h1>Fiat currency</h1>
  <p>Historically, governments minted coins from precious metals such as gold, silver or copper. These had intrinsic value so there was less need to trust the government that minted them. The U.S. dollar is printed on paper which has almost no intrinsic value.</p>

  <p>Until 1971, the value of the dollar was backed by gold. Now the U.S. dollar is a fiat currency, meaning it is legal tender by government decree. The value of the dollar comes from nothing but supply and demand. It relies on faith in the U.S. economy.</p>
</aside>

<h2>Bitcoin</h2>
<p>Bitcoin is not a coin. Bitcoin is not a bank. Bitcoin is technically not even a fiat currency. Bitcoin is a shared digital ledger system. In other words, it is simply a list of transactions. It can be valued and used like a currency but in reality it is nothing but a protocol for tracking individual transactions without needing a central authority.</p>

<p>Unlike gold, <a href="http://www.nytimes.com/2005/06/05/magazine/monkey-business.html" title="Chimps use food and sex as money.">food and sex</a>, Bitcoin has no intrinsic value. Unlike traditional currencies such as dollars, euros, yen or yuan, Bitcoin is not backed by any government or controlled by any bank. Unlike the stock market, there is no central authority, no list of regulations and nobody to oversee transactions or stop fraud.</p>

<p>So if bitcoin has no value and nobody controls it then what is all the hype about?</p>

<p>It's about security, transparency and independence. It's also, at least partially, about dreams, fears and crime. Bitcoin is not the only currency of its kind and the initial hype seems to be fading some but it looks like it's here to stay and the lasting ramifications are only beginning to be understood.</p>

<p>The original paper describing <a href="https://bitcoin.org/bitcoin.pdf" title="Bitcoin white paper">Bitcoin</a> was posted in 2008 by Satoshi Nakamoto. Nobody knows if Satoshi is a real person or a group of people. After posting the original paper, he/she/they collaborated with others for a couple years then handed over control and disappeared. There are plenty of rumors and speculation about the real identity and motivations of Satoshi Nakamoto but due to the anonymity of the Internet we will likely never know the truth. Although it is probably safe to say that Bitcoin was not created by government officials or entrenched bankers.</p>

<h2>Block Chain</h2>
<p>Bitcoin works on something called the block chain. Even if Bitcoin disappears, it's the block chain that is the real magic and that invention is here to stay. You can think of the block chain as a growing list of transactions, like a world-wide bank ledger that everyone shares. Every time someone transfers money, it is recorded in a transaction. These transactions are collected into blocks which are added to the end of the chain. Anybody that wants to can verify a transaction and a transaction is kept in the global block chain only after enough people agree that it is authentic. As more transactions are added, previous transactions become increasingly difficult to change which makes the overall chain very secure.</p>

<aside class="tan shadow left" style="max-width:18em">
  <h1>Public-key cryptography</h1>
  <p>Modern cryptography uses public and private key pairs. The public-key is used to encrypt a message, providing confidentiality. The private-key is used to sign a message, providing authetication. The result is communication that is easy to use, available to everyone, and very secure.</p>

  <p>For more details, see:<br>
  <a href="/content/rights/cryptography/" title="Privacy, security, freedom: Part 1 of 3">Cryptography? I have nothing to hide.</a></p>
</aside>

<p>Each transaction has four basic parts. First is the transaction itself which is nothing but some data, usually the amount being transferred. Next is a <dfn title="What is a hash? I'm glad you asked. Check out the blue box for a quick explanation.">hash</dfn> value that allows all transactions to be linked together and easily verified. The transaction is then tagged with the recipient's public-key (only the recipient can spend it again) and signed using the sender's private-key (proof that the sender created the transaction). Since these encryption keys are anonymous numbers, and the transaction itself is nothing but numbers, there is no need for any identifying information. Therefore all bitcoin transactions are public but anonymous.</p>

<p>To prevent double-spending and limit the number of bitcoins in circulation, adding a block to the block chain requires proof-of-work. Think of this as randomly rolling dice until you get a certain value except instead of dice it's computers computing zillions of hash values per second. When a valid hash value is found, the corresponding block and all its transactions can be added to the block chain. The difficulty is adjusted automatically so that a new block is added about once every 10 minutes.</p>

<p>The block chain can only be modified in two ways, by spending existing bitcoins or by discovering new blocks. The reward for discovering (mining) a new block is currently 25 bitcoins but is <a href="http://www.bitcoinblockhalf.com/" title="How long until the next halving?">predicted to halve</a> soon and again every four years. Miners also get a transaction fee for each transaction they include in a block, the exact amount depends on what Bitcoin owners are willing to pay. Simple transactions (value doesn't matter) are often processed for free while more complex or high-priority transactions typically include a fee of a few cents. Transactions with no fee included might require a day or more before they are processed.</p>

<p>The entire process is performed by a loose network of computers in much the same way the Internet is a collection of computers. Individual computers can come and go, just like individual websites can come and go. It's the overall collection that makes it all work and no single entity owns or controls it. Bitcoin is nothing but a generally agreed upon concept, a way of doing things, much like we communicate with a common language but nobody owns or controls that language.</p>

<aside class="blue center" style="width:90%">
<h1 style="text-align:center">Checksums, Hashes and Bitcoin Mining</h1>
<p>On the front of your credit card is a big number, probably 16 digits. The first few digits are the card type. For example Visas start with a 4 and Mastercards usually start with a 5. The remaining digits, except the last one, are the account number. All those digits are necessary because there are a lot of credit cards in the world.</p>

<aside class="bluer right" style="width:330px; font-size:small">
  <h1 style="text-align:center">Luhn's Algorithm</h1>
  <img src="luhn.png" alt="Luhn algorithm" class="center" width="300" height="189" title="Luhn's Algorith, step 1">
  <p><b>Step 1</b>: From right to left, starting with the second to last digit, double the value of every other digit. If the doubled value is two digits, add the digits together.</p>
  <p><b>Step 2</b>: Add all the digits together.<br>
    <span style="font-size:smaller">8+0+0+0 + 2+2+6+4 + 1+6+5+8 + 9+0+2+7 = 60</span></p>
  <p><b>Checksum</b>: The number is valid if the sum is a multiple of 10 (ends in zero). If not, then someone copied the number wrong (transcription error).</p>
</aside>

<p>The problem with big numbers like this is that they're difficult for humans to handle. It's easy to accidentally skip a digit or maybe swap two digits. To help detect such mistakes, the last digit is a checksum. The checksum is computed by adding up all the digits with a slight twist to make the math more reliable. If any of the digits change, so does the checksum.</p>

<p>A hash works in a similar manner. It takes all the information in a message and computes a corresponding hash value, like a name tag. If any bit in the message changes, the hash value changes too. The chances of two different messages having the same hash value is exceedingly rare. More importantly, it is practically impossible to intentionally modify a message without also changing its hash value. This makes a hash an excellent way to verify that the data has not been tampered with.</p>

<p>Let's say that for some reason you wanted a specific hash value, for example only a hash value beginning with two zeros. Given a set of data, the corresponding hash value has a 1 in 100 chance of beginning in two zeros. If you change one byte in the data and try again there's another 1 in 100 chance. It's like rolling dice until you get the value you're looking for.</p>

<p>Repeatedly computing hash values like this is the essence of bitcoin mining. A single value in the data, called a nonce, is changed until a hash value with the required number of leading zeros is found. This can require trillions and trillions of attempts.</p>

<p>When Bitcoin was first introduced mining could be done on a regular desktop computer. As Bitcoin gained in popularity and miners started using fancier equipment, the difficulty increased automatically. It's to the point now where the cost of electricity to run the equipment is usually higher than the reward from mining. Imagine giant warehouses filled with thousands of specialized computers designed to do nothing but compute bitcoin hashes. These warehouses are usually located somewhere with a cold climate and cheap electricity. Here's a <a href="https://www.youtube.com/watch?v=K8kua5B5K3I" title="YouTube video, 2015">bitcoin mine</a> in rural China.</p>
</aside>
<br>

<h2>Decentralized</h2>
<p>As history has repeatedly shown us, trusting banks and governments is great when it works but catastrophic when it doesn't work. The bitcoin protocol removes the need to trust a centralized authority and replaces it with distributed cryptographic proof. Instead of relying on politicians, bankers and corporations, Bitcoin relies on the distributed power of the Internet and the same type of intractable math used in public-key cryptography. The lack of a central authority is both fabulous and horrible.</p>

<p><img src="thumbup.png" alt="thumb up" class="left" title="Having no one in charge is fabulous">On the bright side, no central authority means the entire system is less susceptible to high-level corruption, greed and stupidity. Bad government decisions can't destroy the entire system and corporate greed can't control the system. With no central authority to control access, Bitcoin is truly available to everyone. It doesn't matter if you're in New York, Tokyo, Greece or North Korea, all you need is Internet access and a computer (or smartphone) with the appropriate software. Bitcoin has no borders, no bureaucracy and no bank holidays.</p>

<p><img src="thumbdown.png" alt="thumb down" class="left" title="Having no one in charge is horrible">On the down side, no central authority means nobody to mediate disputes, bolster a failing economy or stop criminal activity. There is no bank to take care of your money for you, no law-enforcement to protect you from crooks, and no government to complain to when things need changing. Catching a thief at the local convenience store is hard enough, stopping International credit card fraud is even more difficult, and preventing Bitcoin crime may prove completely impossible.</p>

<p>Banks and governments can control spending on an individual level by controlling anybody that uses their services or lives inside their borders but they can't control the overall system. Even if countries try to control or outlaw Bitcoin, doing so will be difficult because of its encrypted and distributed nature.</p>

<figure class="noborder right" style="width:9em; caption-side:top">
  <figcaption>Click here if you don't keep your finances private.</figcaption>
  <img src="peephole.png" alt="peephole" id="peephole" width="90" height="90" onclick="PeekClick()" title="This will be used for official purposes only">
  <audio src="shutter.wav" preload="auto" id="shutter"></audio>
</figure>

<h2>Privacy</h2>
<p>While bitcoin transactions are public record, the information in them is anonymous. That privacy is nice. Most of us don't like it when neighbors stare in our windows, or Facebook tells everyone what we just purchased from Amazon, or criminals get access to our financial records</p>

<p>Bitcoin has a multi-signature feature that can be useful when a group of partners or board of directors needs shared access and consensus on all transactions. Organizations that require complete transparency, such as some non-profits or political groups, can easily share their bitcoin transactions with the world. For the rest of us, we can help guarantee privacy by generating a new key pair for every transaction. The sender and recipient might know each other's identities but nobody else will. Unless of course one of the parties divulges the information.</p>

<p>The privacy of bitcoin is very similar to cash, it's only private if neither party reports the transaction. If you're doing business with a legitimate company, the transaction is almost certainly recorded. It doesn't matter if I pay with cash or a credit card, Walmart keeps records of everything I purchase there. If Walmart starts accepting bitcoins, those transactions will most likely be recorded too. Businesses have to keep records so the government can tax their income.</p>

<p>Speaking of taxes, there is some concern that Bitcoin encourages tax evasion. This is partially true. Individuals, small businesses and criminals can potentially use Bitcoin to hide their income and spending, just like they do with cash. The problem is, just like cash, it only works if the money never produces a paper trail. If the IRS ever comes knocking on your door, they'll want to know how you paid for that giant mortgage, the new car in your driveway and your monthly credit card bills. When it comes to requiring paperwork and collecting taxes, the power of the government should not be underestimated. Governments may not be able to decrypt all Bitcoin transactions but it's a safe bet that they'll figure out how to collect taxes no matter what currency is being used.</p>

<aside class="left" style="width:320px; text-align:center">
  <div class="shadow" style="margin-bottom: 1em">
  <figure class="noborder center" style="width:270px">
    <img src="FBI.png" alt="FBI warning" id="FBI" width="200" height="200" title="The unauthorized reproduction or distribution of a copyrighted work is illegal. Criminal copyright infringement, including infringement without monetary gain, is investigated by the FBI and is punishable by fines and federal imprisonment.">
    <figcaption>FBI Anti-Piracy Warning: Unauthorized copying is punishable under federal law.</figcaption>
  </figure></div>
  The FBI says you may use this seal as long as you don't claim to have their approval or that you're entitled to their protection.<br>
  <a href="https://www.gpo.gov/fdsys/pkg/FR-2012-07-13/pdf/2012-16506.pdf" title="Warning: Reading this document may cause drousiness, headaches and/or vomiting">41 CFR &#167;128-1.5009(f)(4)(i-ii)</a>
</aside>
<h2>Security</h2>
<p>Similar to privacy, the security of Bitcoin depends more on external factors rather than inherent flaws. While it is theoretically possible to attack the bitcoin block chain by controlling more CPU power than everyone else combined, doing so is extremely unlikely and difficult to accomplish, even for large governments. Besides, the most it would accomplish is allowing the attacker to spend their own money twice, it would not create value out of thin air nor allow the attacker to steal money from others.</p>

<p>The underlying technology of Bitcoin is provably secure but the daily use of it most definitely is not. Silk Road is a well-known example of the dangers of Bitcoin. It was the largest black market TOR site before being shut down by the FBI who seized $33.6 million worth of bitcoin (and in turn <a href="https://www.fbi.gov/sanfrancisco/press-releases/2015/former-silk-road-task-force-agent-pleads-guilty-to-money-laundering-and-obstruction" title="The authorities are people too, and sometimes they get caught.">stole nearly a million</a> of that). The site sold some legitimate items but was mostly known for drugs and other illegal stuff. That makes it difficult to feel sympathy for those who lost their money because, after all, they were voluntarily dealing with an online black market.</p>

<p><abbr title="Magic The Gathering Online eXchange">Mt. Gox</abbr> was different, it was originally designed for a trading-card game before warping into the most popular bitcoin exchange. It was never a bank, since there is no such thing as a bitcoin bank, but it was common for users to store their bitcoins there anyways. After several stories of theft, fraud and mismanagement, the exchange finally collapsed in February 2014. More than $450 million worth of bitcoin went missing. Some of it has reappeared but most is probably gone forever.</p>

<p>In 2013, James Howells reported 
<a href="http://www.theguardian.com/technology/2013/nov/27/hard-drive-bitcoin-landfill-site" title="Guardian article about James Howells' mistake">losing $7.5 million worth of bitcoin</a> when he discarded the hard drive containing his private-keys. Unless that hard drive is recovered, nobody will ever be able to use that money again. It's probably safe to say that Mr. Howells regrets his careless mistake. The rest of us might be tempted to think that we'd be more careful but with a currency as different and complex as Bitcoin, there are plenty of unexpected ways to lose your money. The question is, is it better to rely on the authorities for protection or to rely on yourself to understand everything and not make any mistakes?</p>

<aside id="privkey" class="green inset right" style="width:30em">
  <h1>Bitcoin Private Keys</h1>
  <p>Bitcoins can only be spent if you have the corresponding private-key. These private-keys are usually 256-bit numbers. Here's an example:<br>
  <nobr><label><input type="radio" name="privkey"
    onchange="document.getElementById('pkAnswer').innerHTML = 'Are you sure you wrote that down correctly?';">
    <samp>0C 28 FC A3 86 C7 A2 27 60 0B 2F E5 0B 7C AE 11<br>
          EC 86 D3 BF 1F BE 47 1B E8 98 27 E1 9D 72 AA 1D</samp>
  </label></nobr>
  </p>

  <p>There is a <span title="The Wallet Interchange Format is simply the SHA256 hash, with an SHA256 checksum appended, all converted to Base58.">shorter format</span> that includes a check code to help detect typos, similar to the checksum on credit card numbers. Here's the above example in the shorter format:<br>
  <nobr><label><input type="radio" name="privkey"
    onchange="document.getElementById('pkAnswer').innerHTML = 'Can you memorize that without writing it down?';">
    <samp>5HueCGU8rMjxEXxiPuD5BDku4MkFqeZyd4dZ1jvhTVqvbTLvyTJ</samp>
  </label></nobr>
  </p>

  <p>There is an <span title="It's called the mini private key format and can fit in a QR code.">even shorter format</span> that is only slightly less secure:<br>
  <nobr><label><input type="radio" name="privkey"
    onchange="document.getElementById('pkAnswer').innerHTML = 'Maybe it would be easier to use a password.';">
    <samp>S6c56bnXQiBjk9mqSYE7ykVQ7NzrRy</samp>
  </label></nobr>
  </p>

  <p>Instead of any of the above, many people store their private-keys in wallet software and access it with an easy to remember password:<br>
  <nobr><label><input type="radio" name="privkey"
    onchange="document.getElementById('pkAnswer').innerHTML = 'Are you sure nobody can guess your password?';">
    <samp>correct horse battery staple</samp>
  </label></nobr>
  </p>

  <div style="text-align:center">Which format do you prefer?<br>
    <span id="pkAnswer" class="answer">&nbsp;</span>
  </div>
</aside>

<h2>Wallets</h2>
<p>When someone transfers you some bitcoins, all they really did is use their private-key (their signature) to transfer money to your public-key (your unique address). That money can't be spend again without your corresponding private-key (your signature). These keys are unwieldy numbers that are difficult to memorize or write down correctly. To make matters worse, almost every transaction produces new keys, sometimes several keys. If you forget or misplace any of these keys, the associated money is lost forever. If you accidentally transfer money to an incorrect key, the money is lost forever. If someone manages to discover your private-key, the money is lost forever. In short, there are several ways to lose your money forever and no central authority to undo your mistake.</p>

<p>To help prevent mistakes, all of these unwieldy keys are usually managed by digital wallet software. But when was the last time you found software that was easy to understand, bug free and impossible to screw up? Now how much of your net worth are you willing to hand over to that software? Remember, if that software has a hidden back door or ever gets hacked, there's no bank or government to complain to. So be sure to choose wisely.</p>

<p>One website, called Brainwallet, provided a service that helped generate keys that could be easily remembered without the need for fancy software. Since cryptography keys start with a random number and that random number can be anything, the idea is to use an easy to remember password instead of a ridiculously obscure base-64 number.</p>

<figure class="left noborder" id="horse" style="width:16em">
  <a href="http://xkcd.com/936/" id="horse_a">
    <img src="xkcd.png" alt="xkcd" width="220" height="200" title="Correct Horse, Battery Staple">
  </a>
  <figcaption>
    Password advice from <a href="http://xkcd.com/936/" title="The best web comic ever">XKCD</a><br>
    Even better <a href="https://www.schneier.com/blog/archives/2014/03/choosing_secure_1.html" title="How to choose a secure password, 2014">password advice</a>
  </figcaption>
</figure>
<p>This might sound like a great idea but is actually a very bad idea. The problem is that it's relatively easy for a hacker to make a list of all the common passwords and their corresponding key pairs. This is called a dictionary attack. Since the bitcoin block chain is public, any time one of the public-keys in the list is used, the corresponding private-key can be looked up and used to transfer that money to a different account. It's like a burglar having a master-key for all common door locks.</p>

<p>Brainwallet did exactly this. While it's possible that Brainwallet was innocent and someone else performed the hack, much of the stolen money was transferred to the Bitcoin address: <a href="https://blockchain.info/address/1brain7kAZxPagLt2HRLxqyc3VgGSa1GR" title="All transactions for 1brain...">1brain7kAZxPagLt2HRLxqyc3VgGSa1GR</a>. That sure does look like the signature of a smug thief.</p>

<p>Hackers aren't the only ones to take advantage of this random number trick. The NSA used a similar back-door to break RSA security. It was called project BULLRUN and was leaked by Edward Snowden in 2013. The back-door works by manipulating the random number generator used to create key pairs. Instead of completely random numbers, it generated numbers that the NSA could predict. This allowed them to generate the corresponding private-key which gave them complete access to the encrypted data.</p>

<p>The moral of these stories is that even provably secure cryptography can fail when used in an insecure manner. The only way to know if wallet software is secure is to carefully study every line of code. Since this is completely impractical for the average person, and only slightly less impractical for an experienced programmer with plenty of free time, we're back to having to guess who can be trusted and who cannot.</p>

<h1>Deflation</h1>
<p>We're all familiar with inflation. That's the bothersome trend that our money is worth less today than it was yesterday. Being a millionaire isn't what it used to be. If I put $1 million under my mattress and save it until I retire, when I take it out it will be worth less than when I started. The only solution is to find a savings tool that pays a compound interest rate higher than inflation. That's not always easy to do.</p>

<p>Inflation is such a normal part of the economy that we simply assume it will always happen. The inflation rate is normally only a few percent and it's a big deal if it reaches double digits. Inflation is never zero or negative. A negative inflation is called deflation. If that happened, your money would get more valuable the longer you held onto it. Bitcoin does exactly that. Or it will, eventually.</p>

<table id="inflation" class="tan shadow left" title="This is based on the rate at which bitcoins are generated, it does not account for loss nor value fluctuations.">
  <caption>Bitcoin's Inflation Rate<br>(does not include loss)</caption>
  <tr><th>Year</th><th>Bitcoins</th><th>Inflation</th></tr>
  <tr><td>2009</td><td>1,624,250</td><td>-</td></tr>
  <tr><td>2010</td><td> 5,020,250</td><td>209.1%</td></tr>
  <tr><td>2011</td><td> 8,001,400</td><td> 59.4%</td></tr>
  <tr><td>2012</td><td>10,733,825</td><td> 34.1%</td></tr>
  <tr><td>2013</td><td>12,199,725</td><td> 13.7%</td></tr>
  <tr><td>2014</td><td>13,671,200</td><td> 12.1%</td></tr>
  <tr><td>2015</td><td>15,021,200</td><td>9.9%</td></tr>
  <tr><td>2016</td><td>16,060,600</td><td>6.9%</td></tr>
  <tr><td>2017</td><td>16,735,600</td><td>4.2%</td></tr>
  <tr><td>2018</td><td>17,410,600</td><td>4.0%</td></tr>
  <tr><td>2019</td><td>18,085,600</td><td>3.9%</td></tr>
  <tr><td>2020</td><td>18,563,944</td><td>2.6%</td></tr>
  <tr><td>2021</td><td>18,894,694</td><td>1.8%</td></tr>
  <tr><td>2022</td><td>19,225,444</td><td>1.8%</td></tr>
  <tr><td>2023</td><td>19,556,194</td><td>1.7%</td></tr>
  <tr><td>2024</td><td>19,774,097</td><td>1.1%</td></tr>
  <tr><td>2025</td><td>19,939,472</td><td>0.8%</td></tr>
  <tr><td>2026</td><td>20,104,847</td><td>0.8%</td></tr>
</table>

<p>Bitcoins are currently being mined (created) at a very specific rate and that rate decreases over time. When 21 million bitcoins have been mined, no more will be created. Keep in mind, that's the number of coins, not their total net worth. The value of an individual coin fluctuates, just like the value of a share of stock fluctuates.</p>

<p>It's also important to realize that the <em>usable</em> number of bitcoins will never reach 21 million. Remember Mr. Howells who lost $7.5 million? $7.5 million was the estimated (and slightly exaggerated) value reported by the press in 2013. What he actually lost was the private keys for 7,500 bitcoins which he had mined in 2009. Without the private keys, those bitcoins can never be spent again. Thanks to Mr. Howells, there are now only 21,000,000 - 7,500 = 20,992,500 bitcoins that can be spent.</p>

<p>Mr. Howells' misfortune isn't the only such incident, bitcoins are lost all the time. Most are lost through mistakes but some are thrown away intentionally. Why would someone intentionally throw away money? Well, it's usually only a small amount here or there but it all adds up.</p>

<p>For awhile it was popular to tip people in bitcoins. Bitcoin enthusiast saw tipping as a friendly way to popularize the currency. Of course it didn't hurt that an increase in popularity would also increase Bitcoin's price which could offset the cost of tipping. Many of the recipients had never heard of Bitcoin. Some may have stopped to learn what it is all about but others never bothered to reclaim their tip and and have long since lost the corresponding private key. Those bitcoins are gone forever.</p>

<p>Some not-so-friendly types try to trick people into sending bitcoins to invalid addresses where it will be lost forever. Some nerdier types like to hide data in the blockchain. To do this, small amounts of bitcoin are spent with fake public keys that have no corresponding private keys. It's usually only a few cents worth of bitcoin but in the long run it all adds up.</p>

<p>A certain amount of loss is inevitable with all currencies. The difference with Bitcoin is that eventually the rate of loss will exceed the rate of creation. This will cause deflation. Every time some else loses some bitcoin, that makes your pile slightly more valuable.</p>

<p>At first glance this deflation thing sounds awesome. Instead of that million dollars under your mattress losing value, it would gain value. Imagine your stockpile of money growing faster than you can spend it. You could live the life of a multimillionaire simply by letting your money sit under your mattress.</p>

<p>Sound too good to be true? It probably is but nobody knows for sure because permanent deflation has never happened before. Some experts, usually those with a vested interest in Bitcoin, will cite statistics, create graphs and argue esoteric minutiae to prove that Bitcoin's deflation is a good thing. Other experts, usually those with a vested interest in currencies other than Bitcoin, will present just as many statistics, graphs and minutiae demonstrating that Bitcoin's deflation is a bad thing. History shows us that long-term deflation is usually a bad thing because it increases the value of debt and exacerbates a recession by discouraging spending. That's what history says but past performance does not always indicate future results.</p>

<p>Personally, I don't know if Bitcoin's deflation is good or bad, all I know for sure is that nobody knows what will happen next. The world has never seen a currency that experienced permanent deflation. We can guess what might happen but the last time I checked, <span title="I'm not going to let that stop me from trying.">nobody can predict the future</span>.</p>

<aside id="Cares" class="green inset right" style="max-width:22em">
  <h1>Do you care?</h1>
  <p>Check all that apply:</p>
  <label><input type="checkbox">I don't care about privacy,<br> I have nothing to hide.</label><br>
  <label><input type="checkbox">I don't care about security,<br> I have nothing to lose.</label><br>
  <label><input type="checkbox">I don't care about free speech,<br> I have nothing to say.</label><br>
  <label><input type="checkbox">I don't care about free trade,<br> I have nothing to buy or sell.</label><br>
  <label><input type="checkbox">I don't care about the economy,<br> I have no need for money.</label><br>
  <label><input type="checkbox">I don't care about freedom,<br> I trust the authorities.</label><br>
  <label><input type="checkbox">I don't care about fraud,<br> I trust everyone.</label><br>
  You have <span class="answer" id="cares">7</span> reasons<br> to care about Bitcoin.
</aside>

<h1>The Future</h1>
<p>Like it or not, Bitcoin seems like it is here to stay. It's not the only cryptocurrency but it was the first and still the most popular. As a U.S. citizen with multiple bank accounts and a vested interest in the existing economy, I'm not sure the benefits of bitcoin outweigh the drawbacks for me. That's for me, others feel differently. Bitcoin has some very devout followers as well as some very outspoken critics. There are just as many news articles claiming its demise as there are claiming it will revolutionize the world. Overall, the general trend seems to be increasing acceptance, stability and value.</p>

<p>Most of Wall Street may not think Bitcoin is ready for prime time but the underlying blockchain technology definitely has their attention. While the old guard might be slow to adapt this newfangled currency, the new guard is stepping up fast. The Winklevoss twins of Facebook fame have started a Bitcoin exchange called Gemini. They're calling it a "next generation" exchange, probably to distance themselves from exchanges like Mt. Gox and cater to more mainstream speculators.</p>

<p>Bitcoin will have to overcome its past ties to Internet black markets. Early adopters were often criminals, conspiracy theorists or techno-speculators looking for a quick buck. That reputation will be difficult to shake. Modern usage is becoming more main stream and even large banks are slowly starting to see the benefits of block chain technology and cryptocurrencies. Still, there's one major dilemma remaining for Bitoin: merchants.</p>

<p>There's no arguing that the U.S. dollar is by far the world's most widely accepted currency. A crisp new Benjamin is accepted just about everywhere. U.S. dollars are the currency of choice for professional investors, International businessmen, yuppies, hipsters, rednecks, warlords, kingpins and anybody else that cares about keeping their net worth secure. If you have a stack of U.S. dollars in your pocket, it doesn't matter much who or where you are, you'll probably be able to find someone willing to accept your money.</p>

<p>Bitcoin is completely the opposite. The number of merchants who accept bitcoin is growing but it's still exceedingly difficult to buy a cold beer, a fish taco, a designer handbag, a luxury yacht or pay your rent if all you own are bitcoins. I'm sure there are Bitcoin enthusiasts who would happily show me how to spend bitcoins but the point is, most of us won't consider it a mainstream currency until Walmart, Amazon, Chevron, Bank of America and the IRS all accept bitcoin as readily as they accept U.S. dollars.</p>

<p>Imagine all those enthusiasts out there who are sitting on a pile of bitcoins with nowhere to spend it. As a merchant myself, tapping into that market sounds tempting. As an added benefit, I wouldn't have to worry about credit card fees or disputed transactions. Maybe some day I'll give Bitcoin a try. For now though, after a quick cost/benefit analysis, I think I'll stick with U.S. dollars which, we can all agree, are far too easy to spend.</p>

<p>Will Bitcoin be replacing our credit cards any time soon? My guess is no but of course that is my opinion, you'll need to make up your own mind. On the one hand, it's nice to imagine a world with no greedy bankers and no short-sighted politicians controlling our money. On the other hand, it's scary to imagine a world without any authorities to stop the criminals or fix a broken economy.</p>

<p>What happens next is anybody's guess. All we know for certain is that the block chain, and the underlying public-key cryptography, are technologies that are here to stay. Like the invention of gun powder, it will likely change our world in ways we can't even imagine. For better or worse, there's no stopping it now.</p>

<p>Is Bitcoin too mysterious and unconventional for your taste? If so, then maybe you shouldn't read <a href="/content/rights/cicada/" title="Who is Cicada 3301?">Part 3</a> of this series. If the invention of cryptography is like gun powder then whether or not it is a good thing depends on which side of the gun you're standing on. The Internet has a safe side and a dangerous side. After reading Part 3, you might not be so sure which side you're on.</p>

</article>

<?php
include "{$_SERVER['DOCUMENT_ROOT']}/content/rights/menu.inc.php";
MakeMenu($g_aMenuFreedom, 1);
include "{$_SERVER['DOCUMENT_ROOT']}/inc/footer.php";
?>
