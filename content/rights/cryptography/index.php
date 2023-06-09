<?php
$g_pageTitle = "Cryptography? I have nothing to hide.";
$g_pubDate = new DateTime('2016-01-01');
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/header.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/content/rights/puzzle.inc.php";
?>
<article>
<header>
  <h1><?=$g_pageTitle?></h1>
  <div class="byline">
    <time pubdate datetime="<?=$g_pubDate->format('Y-m-d')?>"><?=$g_pubDate->format('j F Y')?></time>, 
    <a href="/about" rel="author"><?=$g_pageAuthor?></a><br>
  </div>
</header>

<div class="eli5" onclick="ToggleELI5(this)">
  <span title="Explain Like I'm Five - Article Summary">&#9650 ELI5</span>
  <div>Cryptography is a big, scary word but that doesn't mean only scary people use it. Everyone uses cryptography. So do you! Your computer and phone already use cryptography for you? Isn't that cool! Your computer does the hard parts but you should still learn the easy parts. Don't worry, it's so easy that even five-year-olds can figure it out. Maybe we should get some five-year-olds to explain it to the important people who make the rules because those people seem confused and a little afraid of this big word cryptography.</div>
</div>

<div class="word right" style="max-width:23em">
  <h1>cryp·tog·ra·phy</h1>
  <h2>/kripˈtäɡr<b>ə</b>fē/</h2>
  <h3>noun</h3>
  <ul>
    <li>the art of writing or solving codes, especially secret codes, ciphers, etc.</li>
    <li>encryption and decryption</li>
  </ul>
  <p class="word_example">"Cryptography isn't just for spies, we all rely on it nearly every day."</p>
</div>

<p>Cletus is a farmer, ask him if he uses cryptography and he'd probably say "Crypto what? That 'bout graveyards or some such?"</p>

<p>"No Cletus," you'd politely correct him, "cryptography is about protecting your privacy. It is a critical component of e-commerce. It provides protection from criminals as well as invasive data collection. Not only does cryptography create security, it also prevents censorship and safeguards individual freedom from overbearing governments."</p>

<p>After a moment of intense contemplation, Cletus might respond "I don't know nothin 'bout all that. Alls I know is I ain't got nothin to hide and this here shotgun protects my freedom. Ain't no commie spies gonna censor me none. Murica!"</p>

<figure class="left overlays">
  <img src="Cletus.jpg" alt="Cletus" width="300" height="400" title="Murica!">
  <span class="overlay" id="cletus">I sure could use a homebrew sixer.</span>
  <figcaption>Does Cletus care about cryptography?</figcaption>
</figure>
<p>Trying not to sound too exasperated, you'd continue "That's great Cletus but haven't you ever shopped online, like maybe at Walmart.com or GunsAmerica.com? If you have nothing to hide, how do you keep thieves from stealing your credit card information?"</p>

<p>"I don't use no credit cards and I sure as heck don't use none of that Internets hooey. The Good Lord willing, I got all I need right here on my farm."</p>

<p>Indeed, Cletus is lucky enough to own a coffee farm on a tropical island with a year-round growing season. In addition to coffee, his farm also has a plethora of fruit trees, a garden overflowing with vegetables, a noisy flock of chickens and plenty of delicious wild pigs to eat. With enough hard work, he can grow all the food he needs. He doesn't ever have to leave the farm, at least not until he needs more diesel for his pickup truck, runs out of ammunition for the shotgun or drinks his last beer.</p>

<p>Cletus might not be much for book learning but he's a friendly guy and quite resourceful. When he runs out of ammo he simply trades some coffee with his friends at the local gun shop. When he runs out of alcohol to drink or diesel for his pickup, he knows a guy up the mountain who built himself a moonshine still.</p>

<p>"Problem is," Cletus explains, "that feller's a cranky old bastard who don't like coffee none. What kind of backwards hillbilly don't drink no coffee?! I tell you what. I reckon that boy's two fries short of a Happy Meal."</p>

<p>To get around this minor alcohol procurement setback, Cletus trades his coffee with a neighbor who grows tea, then has to find a tea drinker who grows hops, then trades the hops with the cranky, non-coffee-drinking, home-brew aficionado up the mountain. It requires a little extra leg work but Cletus reckons it's better than having to deal with unwanted complexities such as e-commerce or needing a "city-type job so's I can shop at the Walmarts."</p>

<div class="center overlays">
  <img src="Beer0.jpg" alt="coffee, tea, hops, beer" id="beer" class="center" width="660" height="165" title="If you have enough friends, turning coffee into beer is easy.">
  <span class="overlay" style="top:1em; left:38px;">Coffee</span>
  <span class="overlay" style="top:1em; left:218px;">Tea</span>
  <span class="overlay" style="top:1em; left:375px;">Hops</span>
<?php if(!$g_cpLvl) { ?>  <span class="overlay" style="top:1em; left:540px;">Beer!</span>
<?php } else { ?>  <button class="overlay" style="top:1em; left:540px;" onclick="Beer()">Beer!</button> <?php } ?>
</div>
<br>

<p>Not having regular employment makes it relatively easy for Cletus to avoid cryptography. All he has to do is avoid the Internet, cell phones, credit cards, gas stations, Walmart, hospitals, the welfare office and any part of society that tracks, stores or otherwise uses any personal information of any kind.</p>

<p>If, like Cletus, you stay completely off the grid and barter for everything instead of using money then you may not need cryptography either. For the rest of us, it's an important part of our lives. Unfortunately, Cletus isn't the only one that lacks a clear understanding of cryptography. He's also not the only one that's a little scared of it. Nearly every politician in the world has expressed concern that cryptography makes it difficult for government security agencies and law enforcement to protect us from terrorists and criminals.</p>

<blockquote>It doesn't do anybody any good if terrorists can move toward encrypted communication that no law enforcement agency can break into before or after. There must be some way. I don't know enough about the technology to be able to say what it is ... but I have a lot of confidence in our tech experts.
<cite>Hillary Clinton, 19 Dec 2015, <a href="http://www.cbsnews.com/news/democratic-debate-transcript-clinton-sanders-omalley-in-new-hampshire/" title="CBS transcript">3rd Democratic National Debate</a></cite>
</blockquote>

<p>This isn't just one politician's view, it's nearly all of them, Democrat and Republican. On the bright side, yea, finally something that all they can agree on. Unfortunately, they're all wrong and their lack of understanding is disturbing. No politician, no government agency, no International coalition, no collection of geniuses can prevent the bad guys from using cryptography. That's not a pleasant thought but it's true.</p>

<p>With the possible exception of Cletus, the rest of us rely on cryptography every day. It is an important and ubiquitous part of our society and it affects your daily life in ways you may not even realize. Luckily, as you'll see, it's not that complicated.</p>

<p>
<h2>Secret-key cryptography</h2>
<aside id="caesar" class="green inset left" style="width:18em">
  <h1>Shift Cipher</h1>
  <div>
    <button type="button" onclick="document.getElementById('caesarRotN').value--;  UpdateCaesar()"> << </button>
    <input id="caesarRotN" value="13" type="number" min="0" max="25" style="width:3em" oninput="UpdateCaesar()">
    <button type="button" onclick="document.getElementById('caesarRotN').value++;  UpdateCaesar()"> >> </button>
  </div>
  <div id="caesarABC">ABCDEFGHIJKLMNOPQRSTUVWXYZ</div>
  <div id="caesarKey">NOPQRSTUVWXYZABCDEFGHIJKLM</div>
  <textarea id="caesarIn" rows="3" oninput="UpdateCaesar()">Type a secret message here.</textarea>
  <textarea id="caesarOut" rows="3">Glcr n frperg zrffntr urer.</textarea>
</aside>
Cryptography has been around for a very long time. One of the earliest and simplest examples is a shift cipher, sometimes called Caesar cipher because it was supposedly used way back in ancient Rome. Fancy-pants cryptography experts like to call it a rot-N substitution cipher because it rotates each letter by N places in the alphabet. Whatever you want to call it, it's a very simple code to break. It worked for Caesar because most of his enemies were illiterate or simply assumed the encoded message was in a foreign language they didn't understand. If you're literate, and the secret message is in English, you can break it by knowing that the most common letter or symbol is <i>e</i>, the second most common is <i>t</i>, and so on.</p>

<p>Simple substitution and transposition ciphers work fine if you're passing a note to a friend at school or afraid your little brother might find your diary but if your needs are more serious, like communicating across enemy lines or protecting bank records, then you'll probably want something slightly more secure. When military commanders started relying on long distance radio communications during the World Wars, suddenly all sorts of complex cryptography was invented. The famous German Enigma machine enciphered messages that were nearly impossible for the Allies to break. Likewise, the Japanese were completely stumped by the Navajo Code Talkers. Even today, such methods can be nearly impossible to break if you don't know the secret.</p>

<p>All the above methods are known as secret-key cryptography, or symmetric cryptography. That means that whatever the secret is, whether a password, algorithm or code book, it must be known by both the sender and receiver while kept secret from everyone else. The secret can't be changed unless both the sender and receive know the new secret. If the enemy ever manages to get ahold of the secret, whether by espionage, bribery, burglary, trickery, defection, brute force, or pure luck, then every message sent can be decrypted. Even worse, if the good guys don't know that the bad guys have the secret then the bad guys can pretend to be the good guys and create their own messages.</p>

<img src="Trooper.jpg" alt="Storm Trooper" class="right" width="170" height="300" title="I always do as I am told.">
<div style="margin-left: 2em">
<p>"Command Base to Alpha Team, initiate the attack."</p>
<p>"... um, Command Base to Alpha Team, we changed our mind, you should drop your weapons and run away."</p>
</div>

<p>The problem with a secret-key cryptography is that both parties have to know the secret. It would be far more secure if the secret didn't have to be shared at all. If there's no shared secret then there's no code book to be lost, stolen or copied; nobody to be bribed, tortured or lured to the dark side; no vulnerable cipher for the enemy's clever team of code breakers to decipher. If only there was some type of cryptography that didn't require a shared secret key. While at it, this magic cryptography should also be easy to use and impossible to break.</p>

<p>Well, in 1977, three <span title="Proof that nerds are awesome.">nerds</span> at MIT (Rivest, Shamir, and Aldeman) managed to create just such a method. They had been working on the problem for more than a year when Rivest, after a night with a little too much Passover wine, was laying on his couch and thinking about math, as nerds sometimes do. That's when he finally devised the algorithm that would change the world forever. That algorithm became known as RSA security and it is still widely used today.</p>

<p>The formal name for this newfangled magic is public-key cryptography, or asymmetric cryptography. It's asymmetric because there are two keys, one that is secret and the other which can be made public. There's no need for a shared secret, the algorithm is straightforward, it can be used by anyone, and it is guaranteed unbreakable! With all this magic in a single package, it's no wonder governments are afraid of it.</p>

<p>Just in case you don't see how astoundingly awesome this is, I'm going to say it again. So, like, one person uses this magic math formula to encrypt a secret message. Then the person receiving the message can use the magic math formula to decrypt the secret message. Nobody else on the planet can decrypt the secret message even though the magic math formula isn't a secret. This magic math formula is so powerful that governments are afraid of it yet so easy that anybody can use it.</p>

<p>That all sounds too good to be true, right? If the magic math formula is really that powerful, there's no way it can be so easy that anybody can use it, right? What could this magic math formula possibly be? Here it is:</p>

<div id="npq" class="tan center">N = P * Q</div>

<p>Yup, that's it, simple multiplication. Well, the details are a bit more complicated but that's the basic idea. P and Q are the private key and N is the public-key. The trick is that you have to choose p and q very carefully. Here's why.

<h2>Prime factors</h2>
<p>Some math problems are very difficult to find an answer to even if we know how to solve them. The fancy word for this is <dfn title="intractable - difficult to manage, manipulate or cure; obstinate, defiant, unruly: 'He is stubborn when sober and intractable after a few drinks'">intractable</dfn>. A less fancy description is trap-door. A trap-door problem is easy to solve one direction but not the other. Prime factorization is an example of this. It's easy to multiply two prime numbers together but if all you have is the answer, it's far more difficult to figure out which two numbers were multiplied together to get there. It's like a one-way trap-door.</p>

<p>To understand this better we have to first remember that a prime number is any number that is only divisible by one and itself.</p>

<!-- Prime numbers: Sieve of Eratosthenes -->
<div>
<script>
function IsPrime(x) {
  for(var i = 2; i < x; i++)
    if(x % i === 0) return false;
  return true;
}

function CalcPrimes(num) {
  var aPrimes = [2];
  for(var i = 3; i < num; i += 2)
    if(IsPrime(i))  aPrimes.push(i);
  document.getElementById("primes").value = aPrimes;
}
</script>
<textarea id="primes" readonly rows="4">2,3,5,7,11,13,17,19,23,29,31,37,41,43,47,53,59,61,67,71,73,79,83,89,97...</textarea>
<form>
  <input type="radio" name="NumPrimes" onclick="CalcPrimes(100)" checked />100<br>
  <input type="radio" name="NumPrimes" onclick="CalcPrimes(1000)" />1000<br>
  <input type="radio" name="NumPrimes" onclick="CalcPrimes(10000)" />10,000<br>
  <input type="radio" name="NumPrimes" onclick="CalcPrimes(100000)" />100,000 <small>(slow)</small><br>
</form>
</div>
<br>

<img src="Factors.png" alt="prime factors" width="370" height="370" class="right" title="The prime factors of 1 to 36, arranged into shapes.">
<p>Once we have our list of prime numbers, we can factor any other number into primes by finding the prime numbers that multiply together to make the original number. For example:</p>

<p>&nbsp; 6 = 2 &times; 3<br>
12 = 2 &times; 2 &times; 3<br>
30 = 2 &times; 3 &times; 5<br>
60 = 2 &times; 2 &times; 3 &times; 5.<br>
... <span title=" 2 = prime
 3 = prime
 4 = 2 &times; 2
 5 = prime
 6 = 2 &times; 3
 7 = prime
 8 = 2 &times; 2 &times; 2
 9 = 3 &times; 3
10 = 2 &times; 5
11 = prime
12 = 2 &times; 2 &times; 3
...">and so on</span>.</p>

<p>Most of us learned about prime factors in math class then quickly proceeded to forget about it. No big deal, factorization wasn't a hugely important part of society until public-key cryptography was invented and even now computers do all the work for us. The important thing to remember is that computing prime factors is very time consuming while verifying the answer is easy. Or, said another way, multiplying is easy but prime factorization is hard.</p>

<p>Here's another example. Is 5149 prime? If not, what are its factors? It's not divisible by 2, nor 3, nor 5, nor 7...</p>
<p>Keep going, I'll wait.</p>
<p>It takes awhile.</p>
<p>Ok, here's a hint, what is 19 &times; 271?</p>

<p>Once you have the answer it is trivial to verify but finding the answer is difficult. This is an example of P vs NP and it is an important computer science problem with some interesting ramifications. Even really smart math geniuses agree that computing prime factors of large numbers is so time consuming that it is basically impossible. The experts call this type of math hard. You might feel that all math is hard but the experts disagree, some problems are hard while others are easy. The trick that makes public-key cryptography work is using math that is hard, such as prime factorization.</p>

<p>Is prime factorization really so difficult that it makes cryptography secure? Computers are good at math, why can't they can just solve it? What about future computers that are even faster, will they be able to break today's cryptography? Let's find out in this blue box...</p>

<br>
<aside class="blue center" style="width:95%">
  <h1 style="text-align:center">P vs NP</h1>
  <span class="left bigchar">P = fast</span>
  <p>Let P be the set of "fast" problems that can be solved relatively quickly by a computer. This doesn't mean they're easy to solve, it means that the algorithm used to solve them will execute in polynomial time (ex: n^2) as opposed to exponential time (ex: 2^n). For example, multiplying numbers, sorting objects by size, finding the shortest path, and computing the digits of PI are all solvable in polynomial time. Larger problem sets require more time to solve, just not exponentially more time.</p>

  <span class="left bigchar">NP = slow</span>
  <p>Let NP be the set of "slow" problems with a complexity that is solvable in non-deterministic polynomial (NP) time. In other words, the difficulty of solving them grows exponentially. Multi-body collision detection is NP, it's difficult to compute but easy to verify. Prime factoring is also a slow NP problem but once you have an answer, verifying its correctness is a fast P problem.</p>

  <p>Computing the prime factors of 5149 requires dividing by every possible prime until all factors are found. While this can seem daunting for a human, a computer can brute force its way through every possibility rather quickly. At least for small numbers.</p>

  <p>The issue is that the number of possibilities grows exponentially as the number being factored increases in size. Factoring a four digit number like 5149 is relatively fast but a 40 digit or 400 digit number takes much, much longer. To see how long, let's pretend we have a computer that can compute one million factorizations per second. That's reasonable. Now let's network together one million of these computers so we can compute 10^12 (a million times a million) factorizations per second. That's a whole lot of math to do in a single second, even for very fast computers.</p>

  <aside class="bluer right" style="width:16em; text-align:center;">
    <p>This <a href="http://www.emc.com/emc-plus/rsa-labs/historical/the-rsa-factoring-challenge.htm" title="RSA factoring challenge">400-digit number</a> has two prime factors. Nobody knows what they are. Can you be the first?</p>
    <div style="font-family:monospace; font-size:smaller;">
    2014096878945207511726700
    4857834425479153217820727
    0435610303912900996679339
    6141985086509455102260403
    2086955587930913903404388
    6751376612341894284530160
    3261911930567685648626153
    2125663001026834647174783
    6597131398943140685464051
    6317519403149294308737302
    3216848409563951832221174
    6844357850984794711999537
    3645360710979599471328761
    0750434646825511120586422
    9937059807870281060330089
    0715874500584758146849481
    </div>
  </aside>

  <p>The square root of a 400-digit number is a 200-digits number so that's 10^200 factors we have to work through. The universe is approximately 13.82 billion years old which is 10^17 seconds. That means our giant computer network would require <span title="171 = 200 - (17 + 12)">10^171</span> universe lifetimes to get the job done.</p>

  <p>Instead of a million, what if we had a billion computers doing a billion factorizations per second? Well, that would shorten the time to only 10^165 universe lifetimes. You can add more -illions but it won't help much because I can just add a few more digits. In other words, prime factorization of large numbers is so difficult that we can consider it impossible. We know how to factor large numbers but we don't know how to do it fast enough to matter. At least not yet.</p>

  <h2>Is P = NP?</h2>
  <p>Is it possible that maybe some day, someone will invent a clever way to compute prime factors? There are plenty of other problems that we thought were unsolvable until someone came along and solved them. Just because we haven't found a solution yet doesn't mean that nobody ever will. Prime factorization is currently consider an NP problem but is it possible we'll find a solution that turns it into a P problem?</p>

  <p>Prime factors are just the beginning. There is another class of problems called NP-Complete that includes things like Soduko puzzles and the Traveling Salesman problem. It turns out that if anybody ever manages to find a way to quickly solve any of these NP-Complete problems then that solution will also work for all NP problems. In other words, if you figure out a clever way to compute prime factors that doesn't involve brute force trial and error, let someone know because you also just figured out how to solve every Soduko puzzle ever created, how to solve protein folding which could cure cancer, how to break every bank's security and decrypted every government's secrets, and you won a <a href="http://www.claymath.org/millennium-problems/p-vs-np-problem" title="The Clay Mathematics Institute Millenium Prize Problems">$1 million prize</a>.</p>

  <p>Is it possible that someday we'll show that P = NP? The consensus among math geniuses seems to be that it is not possible. That's the general consensus but so far nobody has been able to prove it either way. There is a lot of speculation about the <a href="https://en.wikipedia.org/wiki/Quantum_computing#Potential" title="Wikipedia article about effects of quantum computers on cryptography">potential of quantum computers</a> being able to solve these problems but they're not there yet. For now (2016) it's probably safe to assume that intractable problems such as prime factorization are difficult to solve. I hope it's a safe assumption because modern society relies on it.<br>
</aside>

<br>
<h2>Public-Key Cryptography</h2>
<p>Now that we understand how math such as prime factorization is easy to do in one direction but impossible in the other direction, we can use it for cryptography. Start by randomly choosing two very large prime numbers, preferably a couple hundred digits each. Let's call these numbers your private-key. Now multiply them together and that is your public-key. Your private-key stays secret while your public-key can be published to the world. It's safe to give away your public-key because the one-way math makes it practically impossible to decrypt your private-key.</p>

<figure class="right">
<pre class="console" title="Who's signature is this?">
-----BEGIN PGP SIGNATURE-----
Version: GnuPG v1.4.11 (GNU/Linux)

iQIcBAEBAgAGBQJPBRz7AAoJEBgfAeV6NQkP1UIQALFcO8DyZkecTK5pAIcGez7k
ewjGBoCfjfO2NlRROuQm5CteXiH3Te5G+5ebsdRmGWVcah8QzN4UjxpKcTQRPB9e
/ehVI5BiBJq8GlOnaSRZpzsYobwKH6Jy6haAr3kPFK1lOXXyHSiNnQbydGw9BFRI
fSr//DY86BUILE8sGJR6FA8Vzjiifcv6mmXkk3ICrT8z0qY7m/wFOYjgiSohvYpg
x5biG6TBwxfmXQOaITdO5rO8+4mtLnP//qN7E9zjTYj4Z4gBhdf6hPSuOqjh1s+6
/C6IehRChpx8gwpdhIlNf1coz/ZiggPiqdj75Tyqg88lEr66fVVB2d7PGObSyYSp
HJl8llrt8Gnk1UaZUS6/eCjnBniV/BLfZPVD2VFKH2Vvvty8sL+S8hCxsuLCjydh
skpshcjMVV9xPIEYzwSEaqBq0ZMdNFEPxJzC0XISlWSfxROm85r3NYvbrx9lwVbP
mUpLKFn8ZcMbf7UX18frgOtujmqqUvDQ2dQhmCUywPdtsKHFLc1xIqdrnRWUS3CD
eejUzGYDB5lSflujTjLPgGvtlCBW5ap00cfIHUZPOzmJWoEzgFgdNc9iIkcUUlke
e2WbYwCCuwSlLsdQRMA//PJN+a1h2ZMSzzMbZsr/YXQDUWvEaYI8MckmXEkZmDoA
RL0xkbHEFVGBmoMPVzeC
=fRcg
-----END PGP SIGNATURE-----
</pre>
<figcaption>Digital signatures, such as this one, cannot be forged.</figcaption>
</figure>

<p>Now for the really cool part. With <a href="http://mathcircle.berkeley.edu/BMC3/rsa/node6.html" title="It looks icky but isn't that bad for a computer.">a little more math</a>, anybody that has your public-key can send you messages that only you can decrypt with your private-key. Likewise, you can use anybody else's public-key to send them messages that only they can decrypt with their private-key. This means private information can be sent right across the Internet because even if someone is eavesdropping, the only way to decrypt the intercepted information is with your private-key and you're the only person in the world who knows what it is. <span title="Ensure confidentiality by using the recipient's public key."><mark>Confidentiality</mark></span> is guaranteed. Even if Chuck is listening in, he can't understand what Alice is saying to Bob.</p>

<p>Another neat trick happens if the process is done the other way around. If I use my private-key to encrypt a message then you or anybody else can use my public-key to decrypt the message. The world knows the message came from me because I'm the only one with my private-key. This is called <span title="Digitally sign your messages with your private key."><mark>authentication</mark></span> and it's a great way to create a digital signature. This prevents man-in-the-middle attacks such as Chuck pretending to be Bob and sending fake messages to Alice.<br>

<p>The RSA guys invented public-key cryptography in 1977 and in 1991 another clever fellow, Phil Zimmermann, published a popular piece of software called <abbr title="Pretty Good Privacy">PGP</abbr>. It hides all the math of RSA behind an easy to use interface. While that software is still used, it is currently owned by Symantec. No problem, the protocol is public domain and there is a popular, free, open-source alternative called <a href="https://gnupg.org/" title="GNU Privacy Guard">GPG</a> that works just as good.</p>

<p>The one-way nature of prime factorization is easy to understand and considered unbreakable but it's not the only security algorithm. Other math geniuses have discovered even more intractable algorithms such as <span title="Are you really interested in learning more about Elliptic Curve Cryptography? The math is more intense (i.e. boring) than relatively simple prime factors. It is kind of interesting but I don't pretend to fully comprehend it and I certainly can't explain it properly. If you really want to learn more, I'm sure Google will be happy to find plenty of links for you.">Elliptic Curve Cryptogrophy.</span> ECC produces more security with smaller keys. For example, a 20 digit ECC key is more secure than a 200 digit RSA key. Even though the math is more difficult for most humans, the smaller key size makes it faster for computers. That means less powerful computers, such as mobile phones, can still stay ahead of more powerful computers, such as giant networks of eavesdropping super computers. Whether using prime numbers or funny curves, almost all Internet security that we use today is built on top of this intractable mathematical wizardry.</p>

<aside class="shadow left" style="width:335px; padding:0; font-size: 10pt">
  <img src="SSL.png" alt="SSL icon" width="335" height="62" title="The lock icon indicates a secure connection.">
  <div style="padding: 1em">
  <abbr title="Secure Sockets Layer">SSL</abbr>/<abbr title="Transport Layer Security">TLS</abbr> (used by <abbr title="HyperText Transport Protocol - Secure">HTTPS</abbr>) provides both authentication and confidentiality for information sent across the Internet. The authentication is done with public-key cryptography then, once a secure connection is established, the protocol switches to a faster symmetric cryptography. This allows it to be used on slower computers while still providing strong security.
  </div>
</aside>

<p>The only remaining issue is publishing the public keys. If all you're doing is exchanging email with friends then your public-key can be published anywhere. Once your friends have your public key then it doesn't matter if someone else makes a bogus key with your name on it because your friends already have the real key. On the other hand, if you're doing business with the public in general then you don't want anybody else publishing fake keys with your name.</p>

<p>To manage this public-key publication process there is a complex hierarchy of Certificate Authorities. Symantec (owners of VeriSign), Comodo and GoDaddy are the top three. For a small fee, about $100 per year, they're happy to issue an SSL certificate to anybody that wants one. All you have to do is provide some ID so they can verify your real-world identity. For example, you can't pretend to be Microsoft.com because they already have an SSL certificate with a trusted Certificate Authority.</p>

<p>If you have ever purchased anything on the Internet, visited your bank's website, or logged into any website that requires a password, then you have used public-key cryptography. If the website address starts with <code>https://</code>, that <i>s</i> (and often a lock icon ) indicates that the website is using an SSL certification. An eavesdropper can see that you visited the website but only you and the website know what information was transferred. Of course this security only applies to the communication, whether or not the website keeps the data on their servers secure is a whole different issue.</p>

<figure class="right noborder" style="caption-side:top">
  <img src="spy0Hole.gif" alt="Spy hole" width="200" height="200" title="Don't poke me" id="spyHole" onclick="SpyPoke()" onmouseover="SpyPeek()">
  <figcaption id="spyCap">Are you sure you have nothing to hide?</figcaption>
</figure>

<h2>Good or Bad?</h2>
<p>It's easy to see why those who are concerned with security, such as governments, banks, and online merchants, use public-key cryptography. It is far better than an envelope with a wax seal or a code book that might fall into the wrong hands. The mathematical wizardry of public-key cryptography is far more secure than a messenger boy could ever be. How secure? Let's put it this way, the U.S. government considers cryptographic software a munition. Shortly after Zimmerman published PGP in 1991 he was investigated for violating the Arms Export Control Act. PGP allows anybody to communicate in a way that even the authorities can't listen in and that's not a power we want the bad guys to have.</p>

<figure class="left noborder">
  <img src="NSA.png" alt="NSA" width="128" height="128" title="We're from the government and we're here to help">
  <figcaption>Don't worry, the government will protect your privacy.</figcaption>
</figure>

<p>As much as governments would like to control cryptography, doing so is about as easy as controlling the motions of the planets, <a href="https://www.agecon.purdue.edu/crd/localgov/Second%20Level%20pages/indiana_pi_bill.htm" title="1897 Indiana PI Bill">changing the value of pi</a>, or eradicating email spam. This won't stop the authorities from trying though. The Edward Snowden leaks revealed that the <a href="https://en.wikipedia.org/wiki/Bullrun_(decryption_program)" title="Wikipedia article on NSA's project BULLRUN">NSA inserted back doors</a> into commercial software. It was also shown that large corporations such as Google and Apple could be legally compelled to decrypt smartphone data. In response, these companies changed their encryption software to something that even they can't break. In response to that, <a href="http://blogs.wsj.com/digits/2015/01/16/obama-sides-with-cameron-in-encryption-fight/" title="Wall Street Journal article, January 2015">President Obama, following British Prime Minister Cameron's lead</a>, stated that law enforcement and intelligence agencies should have the ability to decrypt anything necessary for preventing crime or fighting terrorism. This isn't an issue of Democrats versus Republicans, and it's not an issue of criminal versus innocent, this is an issue that affects the privacy, security, and freedom of everyone, everywhere.</p>

<p>It's not likely that this controversy will end any time soon. There's no question about it, encryption does indeed make it more difficult for security agencies and law enforcement to monitor the bad guys. In a recent (2016) court case, the FBI once again demanded that Apple needs to provide an encryption backdoor. Apple's CEO recognized the danger of this demand but refusing the demands of the U.S. government is not easy. The FBI sited a law from 1789 to justify giving them access. They claimed that this access would only be used as necessary. The problem is, if the FBI's backdoor key was ever leaked, the entire world would instantly be vulnerable to attack. This risk might be justified if there was a good reason for it but a government sanctioned backdoor like this won't help stop the criminals. They can simply use their own encryption key rather than the one <a href="https://www.apple.com/business/docs/iOS_Security_Guide.pdf">hard-coded on their iPhone</a>, thereby rendering the backdoor pointless. A government sanctioned backdoor only hurts innocent citizens.</p>

<blockquote>weakening encryption ... would hurt only the well-meaning and law-abiding citizens who rely on companies like Apple to protect their data. Criminals and bad actors will still encrypt, using tools that are readily available...
<cite>Tim Cook, Apple CEO, <a href="http://www.apple.com/customer-letter/" title="Open letter to Apple customers">2016 Open Letter to Customers</a></cite>
</blockquote>

<p>You may have noticed over the past few years that many website addresses have changed from <code>http://</code> to <code>https://</code>, with the <i>s</i> meaning secure. Even websites with no obvious need for security, such as Google and Wikipedia, have switched to SSL for everything. The reasons for this are nuanced and complex. It is partially to give users more privacy but it also helps protect the website owners from unwanted liability. Using cryptography is like using window blinds, it keeps the nosey neighbors from watching everything you're doing. You might not care if the neighbors watch but Google, Wikipedia, and Facebook don't want to be responsible if something goes wrong. It's far easier and safer for them to simply encrypt everything.</p>

<figure class="right">
  <img src="Tiananmen.jpg" alt="Tiananmen" width="400" height="252" title="Tiananmen Square, China, 1989">
  <figcaption>On June 12, 1989, the day after Chinese troops violently dispersed pro-democracy protesters from Tiananmen Square, this man defiantly blocked a column of tanks. This is still censored in China.</figcaption>
</figure>

<p>When the general public starts using encryption, that causes a problem for governments that want to censor the Internet. For example China doesn't want its citizens looking up <a href="https://en.wikipedia.org/wiki/Internet_censorship_in_China#Specific_examples_of_internet_censorship" title="If you are in China, I'd love to see a screenshot of this page.">information on Wikipedia about Tiananmen Square</a>. At first they censored everything. When that proved too problematic, they censored just the offensive Wikipedia page. They even got clever and delivered their own fake pages instead, pages that looked authentic but had the government's prefered version of history. Now Wikipedia has switched to <code>https://</code> which means the Chinese censors have no way to know what page their citizens are looking at and no way to change those pages. As a result, they're back to censoring everything.</p>

<p>Whether it's censorship in China or fighting terrorism in the U.S. and Europe, it doesn't really matter what anybody wants because it's too late to put this genie back in the bottle. Not only is public-key cryptography widely published and accessible, it's already programmed into our computers for us. Clever criminals will continue finding ways to trick us and the authorities will continue finding ways to control us but nobody can break the encryption.</p>

<p>We live in a world where <a href="http://www.nytimes.com/2012/02/19/magazine/shopping-habits.html" title="2012 New York Times article">Target knows if your daughter is pregnant</a>, <a href="http://www.wired.com/2011/02/egypts-revolutionary-fire/" title="2011 Wired article on Egyptian Revolution">Facebook can spark a revolution</a>, and <a href="https://www.youtube.com/watch?v=_QdPW8JrYzQ" title="TED video &quot;This is what happens when you reply to spam email&quot;">Nigerian investors need your help</a>. It's scary to think that the authorities might not be able to protect us from the bad guys but cryptography helps us protect ourselves even if the authorities are the bad guys. Cryptography has taken some power away from those in charge and given it to the rest of us, both the good guys and the bad guys.</p>

<p>"So what's the answer," you might ask, "is cryptography good or bad?"</p>

<p>"Both" I would answer. "But it's not a matter of liking it or hating it. Cryptography is here to stay so what's important is understanding it because that's the only way we'll be able to make intelligent decisions. To see if you understand cryptography better than the politicians, let's see if you can pass this little test."</p>

<p>"<i>What?!</i> Nobody said there would be a test!?"</p>

<p>"Don't worry, it's an easy test. It's more of a game really."</p>

<p>Hint: If you get stuck, the important parts are highlighted above in yellow.</p>

<div id="KeyGame">
  <h1>Public-Key Cryptography Test</h1>

  <ul class="tabs">
    <li data-tab_id="Level1" class="tab_active">Level 1</li>
    <li data-tab_id="Level2">Level 2</li>
    <li data-tab_id="Level3">Level 3</li>
    <li data-tab_id="Level4">Level 4</li>
    <li data-tab_id="Level5">Level 5</li>
  </ul>

  <section id="Level1" class="tab">
    <div class="instructions">
      <h3>Level 1: Encryption</h3>
      <p>Help Bob decrypt Alice's secret message.<br>
         Alice used her secret key to encrypt the message,<br>
         drag and drop the key that will decrypt the message.</p>
    </div>
    <div class="keys">
      <figure class="pressable">
        <figcaption>Alice's Public Key</figcaption>
        <img src="APub.gif" class="drag" alt="APub" title="Alice's Public Key">
      </figure>
      <figure class="pressable">
        <figcaption>Bob's Public Key</figcaption>
        <img src="BPub.gif" class="drag" alt="BPub" title="Bob's Public Key">
      </figure>
    </div>
    <div class="dropzone">
      <div class="alice">
        <h4>Alice</h4>
        <input class="io" value="Hello World!">
        <img   class="io drag" src="APrv.gif" alt="APrv" title="Encrypted with Alice's Private Key">
        <input class="io">
      </div>
      <div class="cloud" style="width:28px"></div>
      <div class="bob">
        <h4>Bob</h4>
        <input class="io">
        <img   class="io drop" src="None.gif" alt="None" title="Place a key here">
        <input class="io">
      </div>
    </div>
    <div class="feedback">Javascript must enabled for this to work.</div>
    <button type="submit" class="pressable center" onclick="ShowLevel(2)">Next Level</button>
    <button type="reset" class="pressable center" onclick="ResetLevel(1)">Reset</button>
  </section>

  <section id="Level2" class="tab">
    <div class="instructions">
      <h3>Level 2: Confidential</h3>
      <p>Help Alice send a confidential message to Bob.<br>
         Prevent Eve from eavesdropping, even if she has<br>
         a copy of everyone's public keys.</p>
    </div>
    <div class="keys">
      <figure class="pressable">
        <figcaption>Alice's Public Key</figcaption>
        <img src="APub.gif" class="drag" alt="APub" title="Alice's Public Key">
      </figure>
      <figure class="pressable">
        <figcaption>Alice's Private Key</figcaption>
        <img src="APrv.gif" class="drag" alt="APrv" title="Alice's Private Key">
      </figure>
      <figure class="pressable">
        <figcaption>Bob's Public Key</figcaption>
        <img src="BPub.gif" class="drag" alt="BPub" title="Bob's Public Key">
      </figure>
    </div>
    <div class="dropzone">
      <div class="alice">
        <h4>Alice</h4>
        <input class="io" value="Hello World!">
        <img   class="io drop" src="None.gif" alt="None" title="Place a key here">
        <input class="io">
      </div>
      <div class="cloud">
        <h4>Eve</h4>
        <input class="io">
        <img   class="io drag" src="APubR.gif" alt="APub" title="Alice's public key can be used by anyone">
        <input class="io">
        <img   class="io drag" src="BPub.gif" alt="BPub" title="Bob's public key can be used by anyone">
        <input class="io">
      </div>
      <div class="bob">
        <h4>Bob</h4>
        <input class="io">
        <img   class="io" src="BPrv.gif" alt="BPrv" title="Bob's private key can only be used by Bob" draggable="false">
        <input class="io">
      </div>
    </div>
    <div class="feedback">Javascript must enabled for this to work.</div>
    <button type="submit" class="pressable center" onclick="ShowLevel(3)">Next Level</button>
    <button type="reset" class="pressable center" onclick="ResetLevel(2)">Reset</button>
  </section>

  <section id="Level3" class="tab">
    <div class="instructions">
      <h3>Level 3: Authenticated</h3>
      <p>Help Alice digitally sign her message.<br>
         Help Bob verify that the message came from Alice.<br>
         Don't let Chuck send a fake message.</p>
    </div>
    <div class="keys">
      <figure class="pressable">
        <figcaption>Alice's Public Key</figcaption>
        <img src="APub.gif" class="drag" alt="APub" title="Alice's Public Key">
      </figure>
      <figure class="pressable">
        <figcaption>Alice's Private Key</figcaption>
        <img src="APrv.gif" class="drag" alt="APrv" title="Alice's Private Key">
      </figure>
      <figure class="pressable">
        <figcaption>Bob's Public Key</figcaption>
        <img src="BPub.gif" class="drag" alt="BPub" title="Bob's Public Key">
      </figure>
    </div>
    <div class="dropzone">
      <div class="alice">
        <h4>Alice</h4>
        <input class="io" value="Hello World!">
        <img   class="io drop" src="None.gif" alt="None" title="Place a key here">
        <input class="io">
      </div>
      <div class="cloud">
        <h4>Chuck</h4>
        <input class="io" value="Hello Bob! This is Alice, honest.">
        <img   class="io drag" src="APub.gif" alt="APub" title="Alice's public key can be used by anyone">
        <input class="io">
        <img   class="io drag" src="BPubR.gif" alt="BPub" title="Bob's public key can be used by anyone">
        <input class="io" value="This is Alice, not Chuck, I promise.">
      </div>
      <div class="bob">
        <h4>Bob</h4>
        <input class="io">
        <img   class="io drop" src="None.gif" alt="None" title="Place a key here">
        <input class="io">
      </div>
    </div>
    <div class="feedback">Javascript must enabled for this to work.</div>
    <button type="submit" class="pressable center" onclick="ShowLevel(4)">Next Level</button>
    <button type="reset" class="pressable center" onclick="ResetLevel(3)">Reset</button>
  </section>

  <section id="Level4" class="tab">
    <div class="instructions">
      <h3>Level 4: Full Security</h3>
      <p>Help Alice send a signed and confidental message.<br>
         Help Bob authenticate and decrypt the message.<br>
         Don't let Chuck make changes, don't let Eve eavesdrop.</p>
    </div>
    <div class="keys">
      <figure class="pressable">
        <figcaption>Alice's Public Key</figcaption>
        <img src="APub.gif" class="drag" alt="APub" title="Alice's Public Key">
      </figure>
      <figure class="pressable">
        <figcaption>Alice's Private Key</figcaption>
        <img src="APrv.gif" class="drag" alt="APrv" title="Alice's Private Key">
      </figure>
      <figure class="pressable">
        <figcaption>Bob's Public Key</figcaption>
        <img src="BPub.gif" class="drag" alt="BPub" title="Bob's Public Key">
      </figure>
    </div>
    <div class="dropzone">
      <div class="alice">
        <h4>Alice</h4>
        <input class="io" value="Hello World!">
        <img   class="io drop" src="None.gif" alt="None" title="Place a key here">
        <input class="io">
        <img   class="io drop" src="None.gif" alt="None" title="Place a key here.">
        <input class="io">
      </div>
      <div class="cloud">
        <h4>Internet</h4>
        <input class="io">
        <img   class="io drag" src="APubR.gif" alt="APub" title="Alice's public key can be used by anyone">
        <input class="io">
        <img   class="io drag" src="BPub.gif" alt="BPub" title="Bob's public key can be used by anyone">
        <input class="io">
      </div>
      <div class="bob">
        <h4>Bob</h4>
        <input class="io">
        <img   class="io" src="BPrv.gif" alt="BPrv" title="Bob's private key can only be used by Bob" draggable="false">
        <input class="io">
        <img   class="io drop" src="None.gif" alt="None" title="Place a key here">
        <input class="io">
      </div>
    </div>
    <div class="feedback">Javascript must enabled for this to work.</div>
    <button type="submit" class="pressable center" onclick="ShowLevel(5)">Next Level</button>
    <button type="reset" class="pressable center" onclick="ResetLevel(4)">Reset</button>
  </section>
  <section id="Level5" class="tab">
    <div class="instructions">
      <h3>Level 5: Extra Credit</h3>
      <p>Help the Internet get a copy of Alice's message.<br>
         While private-key cryptography is very secure,<br>
         sometimes there are other ways to EDIT a message.</p>
    </div>
    <div class="keys">
      <figure class="pressable">
        <figcaption>Alice's Public Key</figcaption>
        <img src="APub.gif" class="drag" alt="APub" title="Alice's Public Key">
      </figure>
      <figure class="pressable">
        <figcaption>Bob's Public Key</figcaption>
        <img src="BPub.gif" class="drag" alt="BPub" title="Bob's Public Key">
      </figure>
    </div>
    <div class="dropzone">
      <div class="alice">
        <h4>Alice</h4>
        <input class="io" value="Hello World!">
        <img   class="io" src="APrv.gif" alt="APrv" title="Alice's used her private key to sign the message." draggable="false">
        <input class="io">
        <img   class="io drag" src="BPub.gif" alt="BPub" title="Bob's public key keeps the message confidential.">
        <input class="io">
      </div>
      <div class="cloud">
        <h4>Internet</h4>
        <input class="io">
        <img   class="io drop" src="None.gif" alt="None" title="Can you get a copy of Alice's message?">
        <input class="io">
        <img   class="io drop" src="None.gif" alt="None" title="Can you get a copy of Alice's message?">
        <input class="io">
      </div>
      <div class="bob">
        <h4>Bob</h4>
        <input class="io">
        <img   class="io" src="BPrv.gif" alt="BPrv" title="Bob's private key can only be used by Bob." draggable="false">
        <input class="io">
        <img   class="io drag" src="APub.gif" alt="APub" title="Alice's public key authenticates the message.">
        <input class="io">
      </div>
    </div>
    <div class="feedback">Javascript must enabled for this to work.</div>
    <button type="submit" class="pressable center" onclick="ResetAll()" style="display:none">Start Over</button>
    <button class="pressable center" onclick="ResetLevel(5)">Reset</button>
  </section>
</div><!-- KeyGame -->

<br>
<h2>Summary</h2>
Like language, public-key cryptography is used by everyone yet owned by noone. Also like language, authorities sometimes try to control usage they deem harmful. Ultimately, like language, cryptography can be used for good or bad.</p>

<p><strong>Authentic</strong>: The private-key provides a digital signature. If I'm the only one with my private-key then I'm the only one that can make my signature.</p>

<p><strong>Confidential</strong>: The public-key provides encryption. If I use your public-key to encrypt a message then you're the only one that can decrypt it.</p>

<p>Public-key cryptography is both easy to use and provably secure. Maybe some day someone will figure out how to break it but currently not even giant government supercomputers can break public-key cryptography. There is no denying that public-key cryptography is a powerful tool and an important part of our society. It can dramatically affect our privacy, security, and freedom. But that's just the beginning. Next, let's see how public-key cryptography could make our money obsolete.</p>

</article>

<script src="tea.min.js" defer></script>
<?php
include "{$_SERVER['DOCUMENT_ROOT']}/content/rights/menu.inc.php";
MakeMenu($g_aMenuFreedom, 1);
include "{$_SERVER['DOCUMENT_ROOT']}/inc/footer.php";
?>
