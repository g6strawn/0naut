import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';

let g_camera, g_scene, g_renderer, g_controls; //see InitRenderer
let g_geoSats  = new THREE.BufferGeometry();
let g_geoUsers = new THREE.BufferGeometry();
let g_matSats  = new THREE.PointsMaterial({color:0xFF00FF}); //cyan
let g_matUsers = new THREE.PointsMaterial({color:0x00FFFF}); //pink
let g_pntSats = null, g_pntUsers = null; //point buffers
//x let g_xMouse = 0, g_yMouse = 0;

InitRenderer();
LoadWorld();
RenderFrame();


//---------------------------------------------------------------------------
//InitRenderer - create camera, scene, renderer, controls, stats
function InitRenderer() {
	//camera
	//right-hand coordinates, x:right, y:up, -z:front
	g_camera = new THREE.PerspectiveCamera(70, window.innerWidth / window.innerHeight, 100, 30000);
	g_camera.position.set(1000, 2000, 10000);

	//scene
	g_scene = new THREE.Scene();
	g_scene.background = new THREE.Color('black');

	g_scene.add(new THREE.PolarGridHelper(5000, 4, 5));

	//renderer
	g_renderer = new THREE.WebGLRenderer();
	g_renderer.setPixelRatio(window.devicePixelRatio);
	g_renderer.setSize(window.innerWidth, window.innerHeight);
	document.body.appendChild(g_renderer.domElement);

	//window resize
	window.addEventListener('resize', () => {
		g_camera.aspect = window.innerWidth / window.innerHeight;
		g_camera.updateProjectionMatrix();
		g_renderer.setSize(window.innerWidth, window.innerHeight);
	});

	//orbit controller
	g_controls = new OrbitControls(g_camera, g_renderer.domElement);
	g_controls.minDistance = 7000;
	g_controls.maxDistance = 20000;

/*
	//user controls
	document.body.style.touchAction = 'none';
	document.body.addEventListener('pointermove', (ev) => {
		if(ev.isPrimary === false)  return;
		g_xMouse = ev.clientX - (window.innerWidth / 2);
		g_yMouse = ev.clientY - (window.innerHeight / 2);
 	});
*/

	//handle radio buttons
	document.querySelectorAll('input[name=showList]').forEach(elem => {
		elem.addEventListener('change', LoadWorld)
	});
} //InitRenderer


//---------------------------------------------------------------------------
//LoadWorld - load satellites & user data
function LoadWorld() {
	const list = document.querySelector('input[name=showList]:checked').value; //3-6

	//load satellites
	fetch(`./index.php?list=${list}&type=sat`).then(resp=>resp.json()).then(aSats=>{
		console.log("aSats size="+ aSats.length);

		//load vertices (swap coordinates: z-up -> y-up)
		const verts = [];
		aSats.forEach(s => { verts.push(parseFloat(s.x), parseFloat(s.z), parseFloat(-s.y)); });
		g_geoSats.dispose();
		g_geoSats.setAttribute('position', new THREE.Float32BufferAttribute(verts, 3));

		if(g_pntSats)  g_scene.remove(g_pntSats);
		g_pntSats = new THREE.Points(g_geoSats, g_matSats);
		g_scene.add(g_pntSats);
	});

	//load users
	fetch(`./index.php?list=${list}&type=user`).then(resp=>resp.json()).then(aUsers=>{
		console.log("aUsers size="+ aUsers.length);

		//load vertices (swap coordinates: z-up -> y-up)
		const verts = [];
		aUsers.forEach(u => { verts.push(parseFloat(u.x), parseFloat(u.z), parseFloat(-u.y)); });
		g_geoUsers.dispose();
		g_geoUsers.setAttribute('position', new THREE.Float32BufferAttribute(verts, 3));

		if(g_pntUsers)  g_scene.remove(g_pntUsers);
		g_pntUsers = new THREE.Points(g_geoUsers, g_matUsers);
		g_scene.add(g_pntUsers);
	});

/*
	const verts = [];
	for(let i = 0; i < 1000; i ++) {
		const x = THREE.MathUtils.randFloatSpread(2000);
		const y = THREE.MathUtils.randFloatSpread(2000);
		const z = THREE.MathUtils.randFloatSpread(2000);
		verts.push(x, y, z);
	}

	const geometry = new THREE.BufferGeometry();
	geometry.setAttribute('position', new THREE.Float32BufferAttribute(verts, 3));

	const particles = new THREE.Points(geometry, new THREE.PointsMaterial({color:0xFFFFFF}));
	g_scene.add(particles);
*/
} //LoadWorld


//--------------------------------------------------------------------------
//RenderFrame - update world, render; hopefully 60fps
function RenderFrame(msCurr) {
/*
	g_camera.position.x += ( g_xMouse - g_camera.position.x) * 0.05;
	g_camera.position.y += (-g_yMouse - g_camera.position.y) * 0.05;
	g_camera.lookAt(g_scene.position);
*/
	//render
	g_controls.update();
//x	g_stats?.update();
	g_renderer.render(g_scene, g_camera);
	window.requestAnimationFrame(RenderFrame);
} //RenderFrame
