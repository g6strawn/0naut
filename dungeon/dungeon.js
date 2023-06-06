//Copyright (c) 2022 Gary Strawn, All rights reserved

import * as THREE from 'three'; //3D library - threejs.org
import Stats              from 'three/addons/libs/stats.module.js';
import { GLTFLoader }     from 'three/addons/loaders/GLTFLoader.js'
import * as SkeletonUtils from 'three/addons/utils/SkeletonUtils.js';
import { FontLoader  }    from 'three/addons/loaders/FontLoader.js';
import { TextGeometry }   from 'three/addons/geometries/TextGeometry.js';

import * as my from './utils.js';
import { msgBox, errBox, log, logShowHide } from './msg.js';
import { convertFBX } from './convertFBX.js';
import { EntMgr, Player, EntLight, EntTorch } from './Ent.js';
//import g_aProtos from '/dungeon/ents.json' assert {type:"json"};;

//Dungeon units are in feet
const CELL_SIZE = 10; //a single cell is 10x10 feet (and 7' tall)
let g_renderer, g_scene, g_camera, g_stats; //see InitRenderer
const g_proto   = {}; //list of pre-loaded models; ex: {wall:Object3D}
const g_dungeon = new THREE.Group(); //Object3D models that aren't Ents
const g_ents    = new EntMgr();
const g_player  = new Player('Thunk');


//---------------------------------------------------------------------------
//fetchGLTF - Fetch .glft from file
//Returns a Promise
//Ex1: fetchGLTF('foo').then(model => g_dungeon.add(model));
//Ex2: const model = await fetchGLTF('foo');
function fetchGLTF(name) {
	return new Promise(onLoad => {
		new GLTFLoader()
		.setPath('/dungeon/assets/grid/')
		.loadAsync(name+'.gltf')
		.then(gltf => {
			//base object loaded (textures still pending)
			console.log(name +'.gltf loaded');
			console.assert(gltf.scenes.length === 1  &&  gltf.scene.children.length === 1); 
//TODO: don't call toTree unless assert fails
//					name +' scene must have 1 child:\n'+ my.toTree(gltf.scene));

			const model = gltf.scene.children[0];
			fixEnt(model, name);
			onLoad(model);
		})
		.catch(err => console.error(`fetchGLTF('${name}') `+ err));
	});
} //fetchGLTF


//---------------------------------------------------------------------------
//addObj - fetch (if necessary), clone(g_aProto), g_dungeon.add
async function addObj(objType) {
	const newObj = new THREE.Object3D();
	g_dungeon.add(newObj);
	if(!g_proto[objType]?.isObject3D)
		g_proto[objType] = await fetchGLTF(objType);
	newObj.add(SkeletonUtils.clone(g_proto[objType]));
	return newObj; //Note: async function returns a promise
} //addObj


//---------------------------------------------------------------------------
//makeEntryTunnel
async function makeEntryTunnel() {
	console.assert(g_dungeon.children.length === 0);
	const loadBar = document.getElementById('loadProgress')
	loadBar.max = 4 + (3*11) + 2;

	//TODO: skybox for outside exit?

	//the lamp outside the entrance?
//	g_ents.add(new EntLight()).position.set(3, 8, 1.5*CELL_SIZE);

	const exit = await addObj('Arch'); //back outside
	exit.position.z = 1*CELL_SIZE;
	loadBar.value++;

	const corner1 = await addObj('WallCorner');
	corner1.position.z = 1*CELL_SIZE;
	loadBar.value++;

	const corner2 = await addObj('WallCorner');
	corner2.position.set(1*CELL_SIZE, 0, 1*CELL_SIZE);
	loadBar.value++;

	const outsideFloor = await addObj('Floor');
	outsideFloor.position.z = 1*CELL_SIZE;
	loadBar.value++;

	//hallway z=[0, -10]
	for(let z=0;  z >= -10;  z--) {
		const left = await addObj('Wall');
		left.position.z = z*CELL_SIZE;
		left.rotateY(Math.PI/2);
		loadBar.value++;
	
		const right = await addObj('Wall');
		right.position.z = z*CELL_SIZE;
		right.rotateY(-Math.PI/2);
		loadBar.value++;

		const floor = await addObj('Floor')
		floor.position.z = z*CELL_SIZE;
		loadBar.value++;
	}

	const entry = await addObj('Door'); //into the dungeon
	entry.position.z = -10*CELL_SIZE;
	loadBar.value++;

	const torch = await addObj('TorchHolderOccupied');
	torch.position.z = -9*CELL_SIZE;
	torch.rotateY(-Math.PI/2);
	loadBar.value++;
	g_ents.add(new EntTorch().ignite(4)).position.set(3.8, 6, -9*CELL_SIZE);

	//exit (WARNING: This is not disposed of properly)
	new FontLoader().load('./assets/helvetiker_regular.typeface.json', font => {
		const mats = [
			new THREE.MeshPhongMaterial({color: 0x880000, flatShading: true}), //front
			new THREE.MeshPhongMaterial({color: 0x880000}) //side
		];
		const geom = new TextGeometry('EXIT', {
			font: font,
			size: 2,
			height: 1,
		});
		const mesh = new THREE.Mesh(geom, mats);
		mesh.position.set(2.5, 3, 20);
		mesh.rotateY(Math.PI);
		g_dungeon.add(mesh);
		g_ents.add(new EntLight(0x880000)).position.set(0.5, 3, 20);
	});


	console.log('makeEntryTunnel done');
} //makeEntryTunnel

/*
//---------------------------------------------------------------------------
//preloadEnts
async function preloadEnts() {
	//progress bar
	const loadBar = document.getElementById('loadProgress')
	const loadTxt = document.getElementById('loadStatus');

	//load manager
	const loadMgr = new THREE.LoadingManager();
	loadMgr.onLoad = () => {
		//everything is finally loaded
		makeEntryTunnel();
		document.getElementById('loading').remove();
	}
	loadMgr.onProgress = (url, itemsLoaded, itemsTotal) => {
		//TODO: Should also have a fun animation: Knight walking, swinging sword
		if(!loadBar.value) {
			//first onProgress is often for a partially loaded file; Ex: .gltf but not .bin
			//  which means progTxt has not been set yet; show that something happened
			loadTxt.innerText = 'Procrastinating...'; //partial files have arrived
		}
		loadBar.value = itemsLoaded;
		loadBar.max   = itemsTotal;
	}

	//preload important files
//TODO: No! This will re-fetch every texture for every Ent
// fetches need to be serial, not parallel
	const aEntPreload = ['Wall', 'Floor', 'Archway', 'ArchwayBars', 'Door', 'WallPillar', 'TorchHolderOccupied', 'Knight'];
	const gltfLoader = new GLTFLoader(loadMgr);
	gltfLoader.setPath('/dungeon/assets/grid/');
	for(const entType of aEntPreload) {
		gltfLoader.loadAsync(entType +'.gltf')
		.then(gltf => {
			//base object loaded (textures maybe still pending)
			loadTxt.innerText = 'Discovered '+ entType;
			console.assert(gltf.scenes.length === 1  &&  gltf.scene.children.length === 1, 
				entType +' scene must have 1 child:\n'+ my.toStr(gltf.scene));

			const ent = gltf.scene.children[0];
			fixEnt(ent, entType);
			g_proto[entType] = ent;
		}).catch(err => console.log('Unable to preload '+ entType +'\n', err));
	}
} //preloadEnts
*/

//---------------------------------------------------------------------------
//fixEnt - Make sure it's all good to go
function fixEnt(ent, entName) {
	//rename
	if(ent.name !== entName) {
		console.log(`Renamed ent: ${ent.name} = ${entName}`);
		ent.name = entName;
	}

	//convert from StandardMaterial (metalRoughness) to Phong
	const usePhong = false;
	if(usePhong)  console.info(`Switching ${entName} to Phong material`);
	ent.traverse(mesh => {
		if(mesh.isMesh  &&  mesh.material.isMeshStandardMaterial) {
			if(usePhong) {
				//convert to Phong
				const m = new THREE.MeshPhongMaterial();
				THREE.MeshBasicMaterial.prototype.copy.call(m, mesh.material);
				mesh.material = m;
			} else
				mesh.material.encoding = THREE.LinearEncoding;
		}
	});
/*
	g_aMobs[mobName].anims = {}; //animations by name
	gltf.animations.forEach(clip => 
		g_aMobs[mobName].anims[clip.name] = clip);

	//instantiate protoEnt
	const clone = SkeletonUtils.clone(g_aProtoEnt[entType]);
	const ent = new THREE.Object3D();
	ent.add(clone);
//	ent['is'+ entType[0].toUpperCase() + entType.slice(1)] = true; //ex: isKnight
//	mob.name = name  ||  (name + mob.id); //numbered type, ex: pig123
//	if(vPos)  mob.position.copy(vPos);
	g_dungeon.add(mob);

	const mixer = new THREE.AnimationMixer(clone);
	const firstClip = Object.values(g_aMobs[mobName].anims)[0];
	const action = mixer.clipAction(firstClip);
	action.play();
	g_aMixers.push(mixer);
*/
} //fixEnt


//---------------------------------------------------------------------------
//startRenderer - create camera, scene, renderer
function startRenderer() {
	//camera;  right-hand coordinates = x:right, y:up, -z:front
	const far = CELL_SIZE * 30; //far culling-plane & end of fog
	g_camera = new THREE.PerspectiveCamera(70, //field-of-view in degrees
		window.innerWidth/window.innerHeight, 0.5, far);

	//scene
	g_scene = new THREE.Scene();
	g_scene.background = new THREE.Color('black');
	g_scene.fog = new THREE.Fog('black', far/2, far); //fade to black
	g_scene.add(new THREE.AmbientLight());

	//floor grid
//	g_scene.add(new THREE.GridHelper(CELL_SIZE, CELL_SIZE, '#448', '#888'));
//	g_scene.add(new THREE.AxesHelper());

	g_scene.add(g_dungeon);
	g_scene.add(g_ents.models);
	g_scene.add(g_player);
	g_player.add(g_camera);

	//track performance stats
	g_stats = new Stats();
	document.body.appendChild(g_stats.dom);
	g_stats.showPanel('hide'); //start hidden, F4 to toggle

	//renderer
	g_renderer = new THREE.WebGLRenderer();
	g_renderer.setPixelRatio(window.devicePixelRatio);
	g_renderer.setSize(window.innerWidth, window.innerHeight);
//@	g_renderer.outputEncoding = THREE.sRGBEncoding;
	document.body.appendChild(g_renderer.domElement);

	//window resize
	window.addEventListener('resize', () => {
		g_camera.aspect = window.innerWidth / window.innerHeight;
		g_camera.updateProjectionMatrix();
		g_renderer.setSize(window.innerWidth, window.innerHeight);
	});

	window.requestAnimationFrame(renderFrame); //get this party started
} //startRenderer


//---------------------------------------------------------------------------
//dispose - call dispose() for THREE:Object3D/Material/Texture... recursive
//WARNING: Assumes assets are not s across other objects
//WARNING: Does NOT remove from parent  i.e. does not call g_scene.remove()
//TODO: Uniforms?
//TODO: Is this really necessary? Or is it better to let Garbage Collection get it?
function dispose(obj) {
	if(!obj)  return;

	//children[], material[]
	if(Array.isArray(obj)) {
		obj.forEach(o => dispose(o));
		return;
	}

	dispose(obj.children);
	dispose(obj.geometry);
	dispose(obj.material);
	if(obj.isMaterial) {
		//check for all possible texture; ex: map, alphaMap, bumpMap...
		for(const tex of Object.values(obj))
			if(tex instanceof THREE.Texture)
				dispose(tex);
	} //material

	if(obj.dispose)  obj.dispose();
//	console.log('Disposed '+ obj.type +':'+ obj.name);
} //dispose


//---------------------------------------------------------------------------
//disposeAll - discard entire dungeon
//does not dispose camera, lights...
function disposeAll() {
	g_dungeon.children.forEach(child => {
		dispose(child);
		g_dungeon.remove(child);
	});
} //disposeAll


//--------------------------------------------------------------------------
//--------------------------------------------------------------------------
//-- Startup code
//--------------------------------------------------------------------------
//--------------------------------------------------------------------------


//---------------------------------------------------------------------------
//convertFBX - Fetch original .fbx file and fix all the broken shit
document.getElementById('entSelect')?.addEventListener('change', ev => {
	const entName = ev.target.value;
	const onLoad = (ent) => {
		disposeAll(); //unload all previous models so they don't overlap
		g_dungeon.add(ent);
	};

	if(ev.target.dataset.ext === 'fbx')
		convertFBX(entName, onLoad);
	else
		fetchGLTF(entName).then(onLoad);
});


//close buttons
document.querySelectorAll('.closeBtn').forEach(btn => {
	btn.addEventListener('click', () => {
		if(typeof btn.parentElement === HTMLDialogElement)
			btn.parentElement.close();
		else
			btn.parentElement.style.display = 'none';
	});
});

//hotkeys
document.addEventListener('keydown', ev => {
	switch(ev.key) {
	case 'F1':
		log('Help screen not yet implemented');
		ev.preventDefault(); /* Don't open Windows help */
		break;

	case 'F2': { //show/hide top info
		const topInfo = document.getElementById('topInfo');
		if(topInfo.open)  topInfo.close();
		else              topInfo.show();
		ev.preventDefault();
		break;
	}

	case 'F3': { //show/hide bottom log/cmd
		logShowHide();
		ev.preventDefault();
		break;
	}

	case 'F4': { //debug output, toggle gui & stats
		g_stats.showPanel(g_stats.mode >= 0  ?  'hide'  :  0);

		//debug output
		// console.log('g_dungeon='+ my.toTree(g_dungeon));
		// console.log('g_renderer='+ JSON.stringify(g_renderer.info, null, 2));
		console.log(`Renderer frame#: ${g_renderer.info.render.frame}
  textures:   ${g_renderer.info.memory.textures}
  geometries: ${g_renderer.info.memory.geometries}
  triangles:  ${g_renderer.info.render.triangles}
  shaders:    ${g_renderer.info.programs.length}
  drawCalls:  ${g_renderer.info.render.calls}`);
		ev.preventDefault();
		break;
	}

	case 'm':
	} //switch
});


startRenderer();
log('Welcome to the Dungeon of Thunk');
log('Loading lots of fun stuff...');
makeEntryTunnel().then(() => {
	document.getElementById('loading').remove();
	log('Done. Watch your step.');
	log('Use <Alt> or <Alt>+L for mouse-look');
});


//--------------------------------------------------------------------------
//renderFrame - update world, render; hopefully 60fps
let g_msPrev=0; //time between render frames
function renderFrame(msCurr) {
	const dt = Math.min((msCurr - g_msPrev) / 1000, 0.05); //0.05 = 50ms, @60fps = 3 frames
	g_msPrev = msCurr;

//	g_aMixers.forEach(mixer => mixer.update(dt));
	g_ents.update(dt);
	g_player.update(dt);
	g_stats?.update();
	g_renderer.render(g_scene, g_camera);
	window.requestAnimationFrame(renderFrame);
} //renderFrame
