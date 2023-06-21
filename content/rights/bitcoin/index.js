// ==ClosureCompiler==
// @compilation_level SIMPLE_OPTIMIZATIONS
// @output_file_name index.min.js
// ==/ClosureCompiler==

//-----------------------------------------------------------------------------
//Toggle1924 - toggle visibility of all <span class="toggle1924"> and <span class="toggle2024">
//NOTE: Use "display:inline-block" so <span> can have a fixed width
var g_is2024 = true;
function Toggle1924() {
	g_is2024 = !g_is2024;
	var spans = document.querySelectorAll('.toggle1924');
	[].forEach.call(spans, function(span) {
		span.style.display = g_is2024 ? 'none' : 'inline';
	});

	var spans = document.querySelectorAll('.toggle2024');
	[].forEach.call(spans, function(span) {
		span.style.display = g_is2024 ? 'inline' : 'none';
	});
	Horse();
} //Toggle1924


//-----------------------------------------------------------------------------
//SetCares - #Cares checkboxes;  Do you care about Bitcoin?
var g_cares = 7;
function SetCares() {
	g_cares = 7 - document.querySelectorAll("#Cares input:checked").length;
	document.getElementById("cares").textContent = g_cares;
	Horse();
} //SetCares


//-----------------------------------------------------------------------------
//Horse - Cicada puzzle
function Horse() {
	if(!g_cp  ||  g_cares > 0  ||  g_is2024)  return;

	var horse = document.getElementById("horse");
	horse.querySelector("#horse_a").href = "/content/rights/cicada/03-decoy/";
	horse.querySelector("img").src = "xkcd2.png";
	horse.querySelector("img").title = "Correct horse is correct.";
	horse.querySelector("figcaption").innerHTML = "Ducks give bad advice.<br>Books give good advice.";
	horse.scrollIntoView();
	CP('Horse');
} //Horse


//-----------------------------------------------------------------------------
//PeekClick
function PeekClick() {
  document.getElementById("shutter").play();
  setTimeout(function(){ document.getElementById("peephole").src = "peepclick.png"; }, 500);
  setTimeout(function(){ document.getElementById("peephole").src = "peephole.png";  }, 600);
} //PeekClick


//-----------------------------------------------------------------------------
//window.onload - initialize everything
window.addEventListener("load", function() {
	//add listeners for #Cares checkboxes
	var cares = document.querySelectorAll("#Cares input");
	document.getElementById("cares").textContent = cares.length;
	[].forEach.call(cares, function(care) { care.onclick = SetCares; });
});
