<?php //Copyright (c) 2021 Gary Strawn, All rights reserved
$g_pageTitle = "Dad's Guide to Investing";
$g_pageDesc = "How to get rich. Or maybe just not so poor.";
$g_pubDate = new DateTime('2021-01-01');
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/header.php";
?>
<article>
<header>
	<h1><?=$g_pageTitle?></h1>
	<h2><?=$g_pageDesc?></h2>
	<div class="byline">
		<time pubdate datetime="<?=$g_pubDate->format('Y-m-d')?>"><?=$g_pubDate->format('j F Y')?></time>,
		<a href="/about" rel="author"><?=$g_pageAuthor?></a>
	</div>
</header>

<div class="eli5" onclick="ToggleELI5(this)">
	<span title="Explain Like I'm Five - Article Summary">&#9650 ELI5</span>
	<div><p>I want to be rich. How do I do that by investing?</p>
	<p>Sorry, no fancy tricks here, only boring Dad advice.</p></div>
</div>

<nav class="rules green inset left"><ol>
	<li><a href="#rule1">Make a budget.</a></li>
	<li><a href="#rule2">Be prepared.</a></li>
	<li><a href="#rule3">Avoid debt.</a></li>
	<li><a href="#rule4">Save for retirement.</a></li>
	<li><a href="#rule5">Invest in index funds.</a></li>
	<li><a href="#rule6">Stick with it.</a></li>
	<li><a href="#rule7">Enjoy the 4% rule.</a></li>
	<li><a href="#game">Beat the Market</a></li>
</ol></nav>

<img src="HankHill.png" alt="Hank" class="right" width="210" height="500" title="Hank Hill wants to have a talk.">
<p>When I was in my 20's I had so many great plans. I knew I was going to be rich and I didn't need anyone's advice on how to do it. I was mostly correct too, except for that part about my great plans, getting rich, and not needing any advice.</p>

<p>I understood that debit is bad, compound interest is great, and index funds are better than timing the market. I understood all that yet somehow I managed to not quite understand it. I wish someone had sat me down and had <em>the talk</em>. So now, as a Dad, I feel obligated to have the talk. Here it is:</p>

<p class="tanbox">Dollar cost average into a no-load index fund.</p>

<p>That's it. It's not fast, it's not guaranteed, and there are zillions of investment schemes that might work better. But if you wait long enough, this simple strategy has lower risk and higher gains than almost anything else you can do. It's certainly better than trying to time the market. We'll time the market later but first let's start at the beginning.</p>

<h2><a name="rule1">Rule 1) Make a budget.</a></h2>

<p>I know this sounds obvious yet so many people, including myself, often get it wrong. It's so tempting to skip this step but if you don't count your money then you aren't in control of your money and nothing else you do will matter.</p>

<p class="tanbox">Money = Income - Expenses</p>

<p>You can make a million dollars per year but if you spend a million and one dollars then you're broke. You have two choices: earn more or spend less. Preferably both. Sounds obvious right? The catch is that money is slippery. You might think you know where it all went but unless you count it you don't know for sure. As soon as you're not watching, expenses have a way of taking all your money without you noticing. The best way to prevent that is with a budget.</p>

<p>When you make a budget you start to realize things like:</p>

<blockquote>Wow, $15 for a coffee and muffin every morning really adds up. I don't even like Starbucks that much. Breakfast at home is so much cheaper, I should spend that money on something else I like better.</blockquote>

<p>Or maybe</p>
<blockquote>I thought I was making more than that?! Oh, I see, the amount deducted from my paycheck for health insurance has increased. I'm glad I checked. And what's this FICA?</blockquote>

<p>Or how about</p>
<blockquote>These monthly subscriptions sounded cheap at the time but I rarely use that one any more and I totally forgot about this other one. Do I really need all of these streaming services? What if I invested that money every month instead?</blockquote>

<p>Seriously, you have to make a budget so you can count your money, every penny earned, every penny spent. You have to keep at it too because money will slip away unless you watch it constantly. You can't do it in your head either, you have to write it down. There is plenty of software that makes this easy. Here are a couple 2021 suggestions:</p>

<aside class="blue center" style="width:95%">
<span class="left bigchar">YNAB</span>
<p><a href="https://www.youneedabudget.com/">YNAB</a> (You Need A Budget) is super easy to learn and use. It has great tutorials that help teach budgeting concepts. This isn't accounting software, all it does is budgeting. My biggest complaint is the $89/year price. If you're just starting with a budget, like YNAB's target audience, every penny matters. Still, for anyone new to budgeting this can be life changing software. Who knows, if you ask nicely maybe Dad will help with the cost.</p>

<span class="left bigchar">Mint</span>
<p><a href="https://mint.intuit.com/">Mint</a> is very popular because it's free. It does more than YNAB but doesn't have the cool budgeting tutorials. Mint is entirely online which means all your finances are stored on their servers. It's owned by Intuit which is the same company that owns QuickBooks and TurboTax. Intuit makes money on Mint with intrusive ads, recommending third-party services, and selling the aggregate (anonymous, not your individual account) financial data such as spending trends, account balances, and consumer profiles. Of course in our modern world Big Brother is watching whether you use this software or not and it's hard to beat free.</p>

<span class="left bigchar">Quicken</span>
<p><a href="https://www.quicken.com/">Quicken</a> and <a href="https://quickbooks.intuit.com/">QuickBooks</a> are two separate products, two different companies, but both undoubtedly the most well-known financial software. They are also both relatively expensive and subscription based so you'll be paying again every year. They offer more features than almost any competitor which also makes them more difficult to learn. If you want to be a power user or run a small business, these may be the best choice for you.</p>

<span class="left bigchar">MoneyDance</span>
<p><a href="https://moneydance.com/">MoneyDance</a> is a decent alternative to Quicken Deluxe with a one-time price of $50. It's primarily designed for Mac/Windows, not for mobile devices. The interface is a bit dated and can be frustratingly unintuitive but most features are there. There are no ads, promotions, or online data storage. Local storage means more privacy but no cell phone access and lost data if your computer ever crashes.</p>
</aside>
<br>

<br>
<h2><a name="rule2">Rule 2) Be prepared.</a></h2>

<aside id="stash" class="green inset right" style="width:15em">
<h1>Emergency Stash</h1>
	<label title="Monthly rent/mortgage, taxes, maintenance, fees...">Housing: 
		<input type="number" id="stash_rent" value="1200"></label>
	<label title="Groceries and dining out">Food: 
		<input type="number" id="stash_food" value="800"></label>
	<label title="If your employer pays for health insurance, what if you lose your job?">Insurance: 
		<input type="number" id="stash_insurance" value="600"></label>
	<label title="Car payments, gasoline, parking, bus fare...">Transportation: 
		<input type="number" id="stash_trans" value="500"></label>
	<label title="Electricity, phone, Internet, gas, water, sewer, natural gas...">Utilities: 
		<input type="number" id="stash_util" value="400"></label>
	<label title="Furniture, clothes, linens, tools, anything that's not groceries">Household: 
		<input type="number" id="stash_goods" value="400"></label>
	<label title="Travel, movies, books, games, outtings...">Leisure: 
		<input type="number" id="stash_leisure" value="300"></label>
	<label title="Miscellanous, unexpected, and unscheduled expenses">Other: 
		<input type="number" id="stash_other" value="500"></label>
	<label title="Number of months to prepare for"><input type="number" id="stash_months" value="3"> months</label>
	<label title="Total amount kept in the savings account at all times">Total: 
		<span id="stash_total">$0</span></label>
</aside>

<p>Three months. No, maybe six months. Twelve months? Nah, that's too much, maybe three months. However many months you choose, you need a safety net. You need to be ready in case you lose your job, you get sick, your apartment burns down, you need to wait out a global pandemic, or some other unpredictable calamity strikes. Having an emergency fund set aside is super important.</p>

<p>The rule is that this stash of cash is for emergencies only. A vacation is not an emergency. A down payment on a house is not an emergency. Opening a new business that will make you rich is not an emergency. Investing is definitely not an emergency. Those things are all great but they come AFTER your emergency funds are in place.</p>

<p>You have a budget (if not, go back to Rule 1). Use your budget to determine how much you need every month. Don't forget health insurance, if you lose your job you'll need to pay for it. Take all that money and set it in a regular old savings account then never touch it. No, I take that back, over time your monthly expenses will increase (life is expensive) so you will need to increase the amount in your emergency account occasionally. Other than that, don't touch it.</p>

<p>Do it now. Three months minimum. I'll wait here.</p>

<br>
<h2><a name="rule3">Rule 3) Avoid debt. <span id="except">(Except maybe a mortgage)</span></a></h2>

<p>Debt is <span title="Of course there are exceptions but not many.">bad</span>. That means <span title="It would be more accurate to say 'Loans are expensive'">loans are bad</span>. This includes student loans and car loans. Credit cards are the worst. They're a devious trap designed by the evil banker overlords. Credit cards are convenient when paid off every month but if you ever miss a payment, even just one, the trap is sprung. Your debt will quickly spiral out of control and the bankers will get rich at your expense. It's like a drug dealer offering you a free hit of cocaine. Once you give in to the temptation, it's really hard to stop.</p>

<p>Sometimes you will feel like you need something and a loan is the only way to get it. Fine, as long as you're honest with yourself about "need" and how expensive the loan will be. Credit card rates are 15-20% or higher which is ridiculous. Even a low interest loan will nearly double the cost of whatever it is you're buying. Do you need it so much that you're willing to pay double? A good rule of thumb is that if you can't afford it, don't buy it.</p>

<aside class="blue left" style="width:22em">
	<h1>Good vs Bad Debt</h1>
	<p>There was a time when my house made more money than I did. The local housing market was going up and up. If I had sold my house I could have paid off my mortgage and had more money left over than I had made from my job. Of course then I would have no home and every other home in the area had gone up in value too.</p>
	<p>There were plenty of other times when my house lost value. It's foolish to think that housing prices do nothing but go up. Not only can real estate markets go down, there's also this annoying thing called depreciation. Basically, the older your house gets, the less valuable it is. A new home doesn't stay new forever.</p>
	<p>Calling a home mortgage "good debt" and credit cards "bad debt" is over-simplifying things. There are times when purchasing a home would not be wise or when using credit card debt might be warranted. Every situation is different.</p>
</aside>

<p>The most common exception is a home mortgage. Buying a home can be a good investment. Of course a mortgage is a loan so it is still more expensive than it seems. You can use Google's <a href="https://www.google.com/search?q=mortgage+calculator">mortgage calculator</a> to see for yourself.</p>

<p>Pretend you talked to your bank and you were approved for a $300,000 mortgage. That's a big accomplishment, you should be proud. The mortgage calculator shows that a 30-year mortgage at 4% interest has monthly payments of $1432. That doesn't sound so bad. If you find the right house, those payments are quite reasonable.</p>

<p>The deceptive part is the total cost. In addition to the $300,000 you borrowed you will have to pay $215,609 in interest. The bank loans you $300,000 and in exchange you have to pay them a total of $512,609. No wonder bankers are rich. And that's before things like points, fees, closing costs, and <span title="Private mortgage insurance (PMI) is often required if your down payment is less than 20%. PMI insures the lender against default. If you stop paying your mortgage, the insurance company pays the lender. You pay for it but it only insures the lender.">PMI</span>. Of course don't forget property taxes, those can be huge depending on where you live. A good rule of thumb is five years, if you expect to own the house for less than five years then it's cheaper to rent.</p>

<p>How would renting be cheaper than owning? Because loan payments are split into principal plus interest. Principal is the portion of the loan you've paid back and interest is the money you never see again. The thing is, interest is front-loaded. That means the early payments are almost all interest. In other words, the bankers get their money first. After the first several years of mortgage payments you still own almost none of the home because almost all of your payments went towards interest. Once you include property taxes, depreciation, and repairs then renting might be better.</p>

<p>There's a hidden cheat code with loans called prepayment. If you pay anything extra beyond the regular monthly payment, the extra goes towards the principal. Lower principal means less interest which lowers the cost of the loan. For example, if you could double your very first payment on a typical 30 year mortgage, you'd save so much interest the loan would be paid off in only 25 years.</p>

<p>There are a couple problems with this prepayment idea. First, if you can afford larger payments then you should have taken a smaller loan (shorter term, larger down payment). Second, once you make that extra payment the money is gone, you can't get it back without refinancing. Third, mortgage interest is usually lower than other loans. There's no hurry to pay off that 4% mortgage if your money can earn 7% elsewhere.</p>

<br>
<h2><a name="rule4">Rule 4) Save for retirement.</a></h2>

<blockquote id="buffet" class="right blue" style="width:16em">My wealth has come from a combination of living in America, some lucky genes, and compound interest.<cite>Warren Buffet</cite></blockquote>

<p>If you made it this far then you have no high-interest debt, your emergency funds are in place, and thanks to your budgeting skills you are spending less than you are earning which means you have extra cash laying around at the end of every month. Congratulations, you are ahead of the vast majority of your fellow humans. This is where the magic starts to happen.</p>

<p>The magic starts with an account balance of $0 because your father didn't give you a trust fund. No problem, your budget says you can save $100 per month. $200 would be better but if you can only afford $50 that works too. Whatever your budget says you can afford, that's what you put into your retirement fund. Make it a mandatory monthly expense the same as utilities, groceries, or rent. Put the money into a separate retirement fund, not a regular savings account. Do this for the next 30 years.</p>

<blockquote>But Dad, $100 is a lot of money for me and 30 years is soooo far away! Retirement is the last of my worries right now. You're asking me to cut down a giant tree when all I have is a pocket knife. Someday I'll be making more money and I'll have a chainsaw. I'll save for retirement then.</blockquote>

<aside id="compound" class="green inset right" style="width:20em">
	<h1>Compound Interest</h1>
	<label title="Amount of money you start with">Starting balance: 
		<input type="number" id="comp_starting" value="0"></label>
	<label title="Amount you add every month">Monthly contribution: 
		<input type="number" id="comp_contrib" value="100"></label>
	<label title="Estimated annual interest rate (compounded monthly)">Interest rate: 
		<input type="number" id="comp_rate" value="4"></label>
	<label title="How long you plan to save">Number of years: 
		<input type="number" id="comp_years" value="30"></label>
	<label title="Amount you added over the years">Principal: 
		<span id="comp_principal">$36,000.00</span></label>
	<label title="Amount of interest your account gained">Interest: 
		<span id="comp_interest">$33,404.94</span></label>
	<label title="Total ending amount in the account">Ending balance: 
		<span id="comp_ending">$69,404.94</span></label>
</aside>

<p>You're right, I totally believe in you and some day you will be hugely successful. Start saving now anyway, even if you only have a pocket knife. The magic is called compound interest and it only works if you start now. You can use the nifty Compound Interest calculator (in the green box) to see how the magic works.</p>

<p>Save $100 per month for 30 years and you'll have $36,000. Not bad, but it gets better. If you add 4% compound interest then the $36,000 you saved turns into $69,404.94. Magic! Compound interest has nearly doubled your money with almost no effort on your part.</p>

<p>Remember the expensive mortgage we talked about earlier? Instead of giving $2 to the bankers for every $1 you borrow you can pay yourself and get $2 for every $1 you save. That's some sweet magic. Now check out $1000 per month at 7% interest. Never mind, don't look at those numbers, that's too much magic. Maybe you don't care about being a millionaire anyway.</p>

<p>Saving $1000 per month might seem out of reach but there is a power-up that will help you unlock this achievement. Every time you get a raise, pay yourself first by taking half of the raise and adding it to your retirement savings. A $300 per month raise would increase your retirement savings from $100 to $250 per month and you'd still have $150 per month for yourself. If you do this before you get used to the extra money then you'll never miss it.</p>

<p>There's another power-up called 401k and its slightly weaker cousin called IRA (Individual Retirement Account). If your employer offers a 401k plan please, please max that sucker out. 401k's have this crazy thing called matching. For every dollar you put in, your employer adds another dollar. For free! There are limits and sometimes it's not a full dollar but it's still free money! Maximizing free money is a no-brainer.</p>

<p>Both 401K's and IRAs are tax-deferred. That means the money you put in them isn't taxed until later. There's this obscure thing called a Roth ladder but don't worry about that until you're older. For now, focus on the fact that tax-deferred means fewer taxes today. Everybody likes fewer taxes. Putting money into a retirement account gives you the magic of compound interest and lower taxes: double win.</p>

<p>It gets even better. When you're ready to buy your first home you can borrow from your retirement account. It's like you're saving for retirement and a house at the same time. Plus, when you pay interest on the money you borrow, that interest goes into your retirement account instead of to a bank. Nice.</p>

<p>If I could go back in time to 20 year-old me and talk to myself, here's what I would say:</p>

<p id="selfquote"><q>Self,</q> I would say, <q>I know you know how compound interest works. I know you know how to make a budget, avoid debt, and maximize your retirement fund. I also know you don't know how well this simple stuff works. This retirement savings stuff seems super boring and super far away but it works if you stick with it. Trust me, I've done this before. Boring retirement savings are way better than all those other investment ideas you will hear about.</q></p>

<br>
<h2><a name="rule5">Rule 5) Invest in index funds.</a></h2>

<p>I wouldn't have listened to me either. Besides, a retirement fund still has to be invested somewhere right? And what happens if you manage to max out your retirement funds and you STILL have extra cash. That extra cash should be invested right?</p>

<p>Yes and no. Don't focus on investing at the expense of enjoying life. Go spend some of your money. You've earned it, enjoy it. Once you have a healthy life balance and you're still managing to save extra money, that's when you can start with this investing nonsense.</p>

<p>Investing is a hugely complicated topic. It's way too complicated for me. Luckily there's a well known and super simple trick that outperforms the expert investors.</p>

<div class="tanbox">Dollar cost average into a no-load index fund.</div>

<p>This sounds complicated so we're going to break it down but we have to understand one little factoid before we begin.</p>

<div class="tanbox">More than half of all investments under-perform the market average.</div>

<p>What? The market average means the middle, right? That means half will do better and half will do worse, right? So how can more than half do worse than average? Wouldn't that just change the average? The answer is counter-intuitive so let me explain.</p>

<p>Buy low, sell high. That's how investing works. It's that simple. The problem is that us humans are emotional creatures. We understand the logic then we follow our emotions anyway.</p>

<p>Imagine a company whose stock price is going up and up. We hear this news and that's when we want to buy. When the market is falling apart and prices won't stop dropping, that's when we panic and sell everything. Instead of buying low and selling high we just bought high and sold low. That's the opposite of making money. We know better yet our instincts make us do irrational things anyway.</p>

<p>Trying to time the market is impossible over the long run. It's like gambling at a casino, the odds are against you. There are plenty of success stories but that's survivorship bias. Nobody brags about their huge failures or how they managed to break even, we only hear about the wins.</p>

<p>Why do most investments under-perform the market? Partially because it's impossible to time the market over the long run but also because of a four-letter word: fees. Any time you buy or sell, it costs a fee. Every mutual fund out there will showcase their awesome performance but to see the whole picture you need to account for the fees.</p>

<img src="expert.png" alt="expert" class="left" width="150" height="126" title="Experts have some of the best promises.">
<p>Some finanical advisors charge a flat fee while others charge a commission saying <q>We only make money if you make money.</q> Which one is better? Neither. None of them are working for the fun of it. They are all in the business of using your money to make money. Keeping some of that money for themselves is how they stay in business. I'm not saying professional investors are bad, I'm saying they have to do better than the market average plus their fees too. That's not easy to do year after year.</p>

<p>Let's look at it this way. Every investor out there wants to maximize their profits. As soon as anyone finds a good investment, others will want some of it too, diminishing the remaining profits until there are none left. In order to maximize your profit you have to outguess everyone else. This includes outguessing professional experts, ingenius computer algorithms, and ultra-rich investors with resources and information the rest of us simply don't have. The deck is stacked in their favor. Trying to compete against them is a losing game. Now add in fees and you can see why it's so difficult to out-perform the market average.</p>

<p>If you're super rich with super esoteric investments and super smart financial advisors then maybe this doesn't apply to you. For the rest of us, we can avoid the dilemma of under-performing the market average by using the magic of dollar cost averaging into a no-load index fund. Let's see what that means.</p>
<p>

<h4>Index Fund</h4>

<p>The stock market is super complicated and super risky. Diversity is a great way to make it safer. Owning lots of different stocks helps minimize the risk. In other words, don't put all your eggs in one basket.</p>

<p>A mutual fund is a group of investors who pool their money into an entire collection of investments. This large collection gives the fund managers more money to work with and a greater diversity to mitigate risk. The fund usually has a theme like only tech stocks, only the oil industry, anything but the oil industry, or whatever theme the fund managers think they can sell.</p>

<p>Of course the people managing the fund are professionals and they don't work for free. That's what an index fund is, it's a mutual fund with no managers (of course there are managers but they're mostly clerical staff instead of highly-paid investment gurus trying to beat the market). An index fund does nothing but follow a market index. If the market average goes up, the index fund goes up. If the market average goes down, the index fund goes down. Nobody is trying to outsmart the market.</p>

<p>Historically the stock market gains an average of 10% per year. That might not sound like much compared to the 20% or more advertised by some mutual funds. The difference is that the historical market average includes everything, even all the bad years. Mutual funds only advertise their good years. You never see mutual funds with bad years because those funds have died and gone away. Over the coarse of time, 10% is realistically the best anyone can expect. If any investment reliably made more than 10% then everyone would do it and that would be the new market average.</p>

<aside class="blue">
<h1>No-load Index Funds</h1>
<p>A no-load index fund is simply a mutual fund that follows a market index with no fees and no commission, i.e. no-load. There are tons of no-load index funds to choose from. A super popular one and one of my favorites is Vanguard Total Stock Market Index (<a href="https://www.google.com/finance/quote/VTSMX:MUTF">VTSMX</a>).</p>

<p><a href="https://www.google.com/finance/quote/VTSMX:MUTF">VTSMX</a> does nothing but follow the market average. Super boring, super powerful. If you are young and you have the discipline to not sell even if the market goes down, then this is probably your best bet. Of course be sure you don't need that money for anything else. Investing isn't a drive-thru, it's a parking lot. You put your money in and you leave it there.</p>

<p><a href="https://www.google.com/finance/quote/VFINX:MUTF">VFINX</a> is another popular Vanguard fund. It follows the market's 500 largest companies (S&amp;P 500) instead of the total market which includes thousands of smaller companies. The difference doesn't really matter. When it comes to performance and risk these two funds are nearly identical.</p>

<p>As you get older you should gradually reduce market exposure by adding some bonds to your investment portfolio. This is because as you get older you have less time to wait out bad years. Bonds grow slower but are safer than stocks. If a recession hits right when you plan to retire you'll be glad you had some bonds. Vanguard has a selection of funds that <a href="https://investor.vanguard.com/mutual-funds/target-retirement/">target retirement dates</a> or <a href="https://investor.vanguard.com/mutual-funds/lifestrategy/">lifestyle (risk) preferences</a>.</p>
</aside>
<br>

<h4>Dollar cost average</h4>

<p>Dollar cost averaging is a fancy name for something super simple. You know that $100 per month you're saving? That's it, that's all there is to it. All you have to do is set up a monthly payment to automatically move $100, or whatever your budget says you can afford, from your bank account into your favorite index fund. Contributing into a 401K plan counts too. You are now out-performing the average investor.</p>

<p>Technically dollar cost averaging refers to investing a lump sum of money over time instead of all at once. The result is the same whether that money is coming from a monthly paycheck or a lump sum. By investing a fixed amount at fixed intervals you are mitigating your risk over time.</p>

<p>It works because when the market is down your money will be buying more shares per dollar and when the market is up your money will buy fewer shares per dollar. In other words when things are cheap you buy more, when things are expensive you buy less. It helps balance out market fluctuations. Mostly it helps because it avoids the temptation to try to time the market.</p>

<br>
<h2><a name="rule6">Rule 6) Stick with it.</a></h2>


<p>The magic of compound interest seems so simple and so obvious. Setting aside a little bit extra every month shouldn't be that hard. Yet if it's so easy, why isn't every 50-year-old a retired millionaire?</p>

<p>Because we give up, that's why. Or we never started in the first place. Thirty years seems like forever when you're young. Buying a new iPhone, car, or house feels so important. Sometimes those things are important. It's not easy to find the right balance between enjoying today and saving for tomorrow.</p>

<a href="https://xkcd.com/" target="_blank"><img src="https://imgs.xkcd.com/comics/investing.png" alt="investing" class="center" title="But Einstein said it was the most powerful force in the universe, and I take all my investment advice from flippant remarks by theoretical physicists making small talk at dinner parties." width="604" height="266"></a><br>

<p>The magic of compound interest isn't perfect and it only works if you give it enough time. There will be some bad years and some great years. Since there's no way to predict what will happen next, betting on the 10% market average is about as sure of a thing as you can get. There are plenty of other alluring investment ideas that promise faster and bigger gains. Some of them might even work, most will not. Your meager savings will grow so slow at first it's super tempting to switch strategies and that's why most people never get there.</p>

<br>
<h2><a name="rule7">Rule 7) Enjoy the 4% rule.</a></h2>

<p>If you stick with it then some day you'll have a giant pile of money. The 4% rule will help you know when it is safe to start spending that pile of money. If your money is in index funds then your compound interest has been about 10%. Awesome. Your money grew but unfortunately it also shrank due to inflation. A million dollars isn't worth what it used to be.</p>

<p>Inflation will reduce the value of your money by about 3% every year. Historically the stock market goes up about 10% every year but we're going to use 7% to be safe and because your portfolio at retirement shouldn't be all stocks. A mixture of stocks and bonds plus a little padding means expected returns of about 7%.</p>

<div class="tanbox">7% - 3% = 4%</div>

<p>That's the 4% rule. You take your average expected gains, subtract the expected loss from inflation, and the result is how much money you can withdraw from your stockpile without it losing value. For example, if you have $1 million in your retirement account you can expect it to gain 7% ($70,0000) but also lose 3% ($30,000) due to inflation, that leaves 4% ($40,000) you can withdraw and still have the same buying power you started with. If your budget says you can live on $40,000 per year then you're done, you can stop working because you don't need any more income.</p>

<p>Of course 4% is just a guess. 5% used to be considered safe while some think 3% is a better bet. You also need to consider how much longer you expect to live. Running out of money in your 80's would be bad. On the other hand, dying with a giant pile of cash is rather pointless. <a href="https://www.firecalc.com/">FireCalc.com</a> is a cool calculator that uses actual past market performance to predict future results. It's just a guess but it's about the best guess anyone can make.</p>

<p>If I was 20 years old again this is what I'd do. I'd constantly watch my budget to make sure I was spending less than I was earning. I'd try my hardest to maximize my 401k then I'd open an IRA and maximize that too. If I was enjoying life and still had extra money I'd put it into VTSMX and leave it. I'd try really hard to ignore the stock market, bitcoin, Tesla, or whatever shiny investment opportunity comes along. If I had done those simple things I wouldn't be working today.</p>

<br>
<h2><a name="game">Beat the Market</a></h2>

<p>Still think you can beat the market? Buy low, sell high, how hard can it be? Simply look at the graph and if it's over-priced then sell, if the market is down then it's time to buy. That sounds easy enough.</p>

<img src="monopoly.png" alt="monopoly" class="right" width="200" height="150" title="Maybe the true secret to investing is having a mustache and fancy hat.">
<p>The challenge is to beat the market average. You don't have to get rich, just beat the market average. Every month another $1000 is added to your bank. Your opponent, <span title="Mr. Dollar Cost Average">Mr. Avg</span>, automatically invests his $1000 every month and never sells. You can buy, sell, or hold. Any money left in the bank earns 0% interest. It's safe from any loses but also misses out on any gains.</p>

<p>The market performance is actual historic data. The starting date is random and hidden because being able to predict 2008 or 1929 wouldn't be fair. With no news headlines, balance sheets, or mass hysteria to influence your decisions, you'll have to time the market using nothing but past performance to predict future results. Of course the point of this exercise is to demonstrate that timing the market is more difficult than it sounds. Here are some strategies that might or might not help:</p>

<p>Strategy 1: Keep your money in the bank until there's a dip in the market. Then go all in. If you get in at the bottom then there's nothing left but gains.</p>

<p>Strategy 2: If the market starts to crash, sell everything immediately. Historically, loses have been concentrated at a few small panic moments while gains are spread out over years. Avoid those panic cliffs and there's nothing left but gains.</p>

<p>Strategy 3: Find a pet monkey and have it flip a coin for you.</p>

<div class="center"><div id="market">
	<h1>Beat the Market</h1>
	<div>Time Elapsed: <span id="mktElapsed">0 years, 30 to go.</span></div>
	<canvas id="mktGraph" width="520" height="300">Browser not supported</canvas>
	<div id="mktOverlay">
		<label>Your name: <input id="mktName" maxlength="10" size="8"<?php
			if(!empty($_SESSION['name'])) echo "value=\"{$_SESSION['name']}\""?>></label><br>
		<button id="mktStart" onclick="StartMarket()">Start Investing</button>
	</div>
	<div>
		<label><input type="radio" name="zoom" value="4" onclick="ZoomMarket()">6 months</label>
		<label><input type="radio" name="zoom" value="2" onclick="ZoomMarket()">1 year</label>
		<label><input type="radio" name="zoom" value="1" onclick="ZoomMarket()" checked>2 years</label>
		<label><input type="radio" name="zoom" value="0.4" onclick="ZoomMarket()">5 years</label>
	</div>
	<div>
		<button id="buy" onclick="Buy()">Buy $1k</button><button id="buyall" onclick="BuyAll()">Buy All</button>
		<button id="sell" onclick="Sell()">Sell $1k</button><button id="sellall" onclick="SellAll()">Sell All</button>
	</div>
	<table>
		<tr><td></td><td>Bank</td><td>+</td><td>Market</td><td>=</td><td>Total</td></tr>
		<tr><th id="mktName">You</th><td id="youBnk">$0</td><td>+</td><td id="youMkt">$0</td><td>=</td><td id="youTtl">$0</td><td id="youGain"></td></tr>
		<tr><th>Mr. Avg</th><td id="avgBnk">$0</td><td>+</td><td id="avgMkt">$0</td><td>=</td><td id="avgTtl">$0</td><td id="avgGain"></td></tr>
	</table>
	<div>Principal: <span id="mktPrincipal">$0</span></div>
</div></div>

<table id="mktScores" class="brown center">
<caption>Market Scores</caption>
<thead id="mktScoreHdr">
	<td title="Player's name">Name</td>
	<td title="Date the game was played">Played</td>
	<td title="Start date of historic market data used">Market Start</td>
	<td title="Total time played">Time Elapsed</td>
	<td title="Final results of player" colspan="2">Player</td>
	<td title="Final results of Mr. Avg" colspan="2">Mr. Avg</td>
</thead>
<tbody></tbody>
</table>

</article>
<script src="invest.js" defer></script>
<?php include "{$_SERVER['DOCUMENT_ROOT']}/inc/footer.php"; ?>
