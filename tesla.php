<?php //Copyright (c) 2022 Gary Strawn, All rights reserved
$g_siteName = 'Gary Strawn';
$g_pageDesc = 'Tesla application';
require_once "{$_SERVER['DOCUMENT_ROOT']}/inc/header.php";
?>
<section id="tesla">
<details open>
  <summary><h1>Contact</h1></summary>
    <label>Resume: <a href="resume.pdf" title="Resume">resume.pdf</a></label>
    <label>Email: <a href="mailto:g6strawn@gmail.com">g6strawn@gmail.com</a></label>
</details>

<details open>
  <summary><h1>Evidence of Excellence</h1></summary>
  <p>Some people are lucky enough to find their passion in life. I am one of those people. I have been writing games since before I knew how but skilled coders are in demand and I quickly started getting paid. My career leaped from military to game coder to senior network engineer. I shipped many products, patented technology for giant server farms, and co-founded a startup with the CEO who replaced Musk at Zip2. Over the years I have experienced both huge successes and obscure failures.</p>
  <p>Programming always has been and always will be a big part of my life. I have worked with C/C++, JavaScript, PHP, SQL, REST, CSS, Three.js, and countless others. Learning new tech has become routine. My primary strength is front-end and 3D but I have worked across the full-stack.</p>
  <p>My biggest success was building my own Kona coffee farm business with no prior experience. I did everything from web development and tractor maintenance to customer relations and political lobbyist. I changed the governor's mind when none of the other executives could. I've managed large teams and I've worked alone. While my passion is heads-down coding, that is certainly not my only skill.</p>
  <p>After selling my e-commerce business, I'm lucky enough that I can be picky about what I do next. I want to work with talented people on a challenging project with big potential. I'm not looking for just a paycheck, I want to help move the world forward. Is Tesla the correct place for me?</p>
</details>

<embed id="resume" type='application/pdf' src="https://drive.google.com/viewerng/viewer?embedded=true&url=https://thunkonaut.com/resume.pdf" />
</section>
<?php include "{$_SERVER['DOCUMENT_ROOT']}/inc/footer.php"; ?>
