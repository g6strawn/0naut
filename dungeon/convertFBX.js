//Copyright (c) 2022 Gary Strawn, All rights reserved
/* convertFBX - Fixes original FBX files from modular dungeon
I paid $10 for this:  https://fertile-soil-productions.itch.io/modular-dungeon

NOTE: Also changed /three/examples/jsm/loaders/FBXLoader.js
FBXTreeParser.parseTexture()  added after line 369
  369: texture.name = textureNode.attrName;
+ 370: //@ This line added to fix stupid Unity files with improper names
+ 371: if(textureNode.attrName == 'base_color_texture')  texture.name = textureNode.Media; //x
*/
import * as THREE from 'three'; //3D library - threejs.org
import { FBXLoader } from 'three/addons/loaders/FBXLoader.js';
import { GLTFExporter }  from 'three/addons/exporters/GLTFExporter.js';

import * as my from '/dungeon/utils.js';

export { convertFBX };


//g_aTexNames - map old texture filenames to new names
const g_aTexNames = {
BrickWalldiff2k:     { newName: "Brick",       mapNorm: "BrickN" },
FlagNarrowForked:    { newName: "FlagNarrow",  mapNorm: "FlagNarrowN", mapDisp: "FlagNarrowDisp" },
FlagNarrowPointed:   { newName: "FlagNarrow",  mapNorm: "FlagNarrowN", mapDisp: "FlagNarrowDisp" },
FlagWideForked:      { newName: "FlagWide",    mapNorm: "FlagWideN",   mapDisp: "FlagWideDisp" },
FlagWidePointed:     { newName: "FlagWide",    mapNorm: "FlagWideN",   mapDisp: "FlagWideDisp" },
FlagWrappedTopLeft:  { newName: "FlagTopLeft"  },
FlagWrappedTopRight: { newName: "FlagTopRight" },
FloorRockConcrete:   { newName: "Floor",       mapNorm: "FloorN" },
FloorTiles08Color2k: { newName: "FloorTile",   mapNorm: "FloorTileN" },
Metalcollight:       { newName: "MetalIronLt"  }, //mapNorm: "MetalN" },
Metalcolinner:       { newName: "MetalIronMd"  }, //mapNorm: "MetalN" },
Metalcoldark:        { newName: "MetalIronDk"  }, //mapNorm: "MetalN" },
Metalcolgold:        { newName: "MetalGold"    }, //mapNorm: "MetalN" },
Metalcolredgold:     { newName: "MetalGoldRed" }, //mapNorm: "MetalN" },
StoneGrayDark:       { newName: "StoneDk",     mapNorm: "FloorN" },
StoneGrayInner:      { newName: "StoneMd",     mapNorm: "FloorN" },
StoneGrayLight:      { newName: "StoneLt",     mapNorm: "FloorN" },
StoneGrayPillarDark: { newName: "StonePillarDk" },
StoneGrayPillarLight:{ newName: "StonePillarLt" },
Stone2KColor:        { newName: "ConcreteLt",  mapNorm: "ConcreteN" },
Stone2KColorLight:   { newName: "RockLt",      mapNorm: "FloorN" },
Stone2KColorInner:   { newName: "RockMd",      mapNorm: "FloorN" },
Stone2KColorDark:    { newName: "RockDk",      mapNorm: "FloorN" },
WallTopStone2KColor: { newName: "ConcreteMd",  mapNorm: "ConcreteN" },
WallBotStone2KColor: { newName: "ConcreteDk",  mapNorm: "ConcreteN" },
WoodBoardDk:         { newName: "BoardsDk",    dup: "WoodBoardsColorDark" },
WoodBoardsColorDark: { newName: "BoardsDk",    mapNorm: "BoardsN" },
WoodBoardsColorLight:{ newName: "BoardsLt",    mapNorm: "BoardsN" },
WoodBoardsColorMed:  { newName: "BoardsMd",    mapNorm: "BoardsN" },
WoodSolidcol:        { newName: "PlywoodDk",   dup: "WoodSolidcolDark" },
WoodSolidcolDark:    { newName: "PlywoodDk",   mapNorm: "PlywoodN" },
WoodSolidcolLight:   { newName: "PlywoodLt",   mapNorm: "PlywoodN" },
WoodSolidcolMed:     { newName: "PlywoodMd",   mapNorm: "PlywoodN" },
WoodSolidcolRed:     { newName: "PlywoodRed",  mapNorm: "PlywoodN" },
};


//---------------------------------------------------------------------------
//roundTRS - round Translation/Rotation/Scale and log if not zero
function roundTRS(node, ent) {
	//position
	if(!my.isZero(node.position)) {
		node.position.copy(my.roundTo(node.position)); //round if appropriate, no 9.9e-99
		if(!my.isZero(node.position))
			console.log(node.name +' has position: '+ my.toStr(node.position));
	}

	//rotation
	if(!my.isZero(node.rotation)) {
		node.rotation.copy(my.roundTo(node.rotation)); //round if appropriate
		if(!my.isZero(node.rotation))
			console.log(node.name +' has rotation: '+ my.toStr(node.rotation));
	}

	//scale
	const vONE = new THREE.Vector3(1,1,1);
	if(!node.scale.equals(vONE)) {
		node.scale.copy(my.roundTo(node.scale)); //round if appropriate
		if(!node.scale.equals(vONE))
			console.log(node.name +' has scale: '+ my.toStr(node.scale));
	}

	ent.updateMatrix(); //make matrix pretty too
} //roundTRS


//---------------------------------------------------------------------------
//fixMaterial - fix names
//TODO: convert Phong to StandardMesh (ie. add pbr metallicFactor & roughnessFactor)
function fixMaterial(mat, ent) {
	console.assert(Object.keys(mat.userData).length === 0);
	mat.userData = {};

	//color (specular? emissive?)
	//  hidden=0xece7e2, base=0x8a8174, top=0x797061
//	if(mat.name === 'hidden'  &&  color.getHex() === 0xece7e2)
//		mat.color = THREE.Color('grey'); //@

	//texture maps: change name, add mapNormal, mapDisplacement
	if(!mat.map) {
		//texture is missing
		if(mat.name !== 'Hidden')  console.warn(mat.name +' has no texture');
	} else {
		//fix texture/material name
		if(mat.name === 'Hidden')  console.warn('Hidden has texture: '+ mat.map.name);
		if(!mat.map.name) {
			console.warn(mat.name +' texture had no name');
			mat.map.name = mat.name ?? mat.parent.name; //use material's name
		} else if(mat.map.name.includes('_')) { //if old name (if has underscores)
			//switch texture to new name from lookup table: g_aTexNames
			if(mat.name.startsWith('Flag'))  mat.map.name = mat.name; //fix flags
			let key = mat.map.name
				.replace(/\.[^/.]+$/, '') //remove .ext
				.replace(/[^0-9a-z]/gi, ''); //remove all non-alphanum
			if(!g_aTexNames[key])
				console.warn(mat.name +' uses unknown texture: '+ mat.map.name);
			else {
				//add ent's name to list of ents that used this texture
				if(!g_aTexNames[key].aUsed)  g_aTexNames[key].aUsed = [ent.name];
				else                         g_aTexNames[key].aUsed.push(ent.name);

				//get new name
				mat.name = g_aTexNames[key].newName;
				if(g_aTexNames[key].isDup)  mat.name = g_aTexNames[newName].newName;
				mat.map.name = mat.name;

				//add normal map
				const texLoader = new THREE.TextureLoader(g_loadMgr);
				if(g_aTexNames[key].mapNorm) {
					const mapName = g_aTexNames[key].mapNorm;
					texLoader.load(`assets/modular/textures/${mapName}.jpg`, tex => {
						//NOTE: This is asynchronous. It might take awhile to load the texture.
						mat.normalMap = tex;
						mat.normalMap.name = mapName;
						mat.needsUpdate = true;
					}, undefined, err => console.error(err));
				}

/* //@ Why you no work?
				//add displacement map (flags)
				if(g_aTexNames[key].mapDisp) {
					const mapName = g_aTexNames[key].mapDisp;
					texLoader.load(`assets/modular/textures/${mapName}.jpg`, tex => {
						//NOTE: This is asynchronous. It might take awhile to load the texture.
						mat.displacementMap = tex;
						mat.displacementMap.name = mapName;
						mat.needsUpdate = true;
					}, undefined, err => console.error(err));
				}
*/
			}
		} //convert names
	} //texture map
} //fixMaterial


//---------------------------------------------------------------------------
//FixMesh - fix names
function FixMesh(mesh, ent) {
	console.assert(mesh.children.length === 0);

	//geometry
	console.assert(Object.keys(mesh.geometry.userData).length === 0);
	mesh.geometry.userData = {};
	if(!mesh.geometry.name)  mesh.geometry.name = mesh.name; //use mesh's name

	//material
	if(mesh.material.isMaterial)
		fixMaterial(mesh.material, ent);
	else { //else array of materials
		//Ents with multiple materials:
		//  PropBarrelOpen.lidLip
		//  PropFurnitureChair.backUpper, backLower, seat, legCrossbars
		//  NOT PropFurnitureStool, PropFurnitureTable
		//  PropTreasureChestPlain.trunkPanels0-4; wood or iron (not lid panels)
		//  PropTreasureChestRare.trunkPanels0-6 & lidPanels0-6; MetalGold, PlywoodDk, PlywoodRed
		let matList = mesh.name +' has multiple materials: ';
		mesh.material.forEach(mat => {
			matList += mat.name +', ';
			fixMaterial(mat, ent);
		});
		console.log(matList);
	}
} //FixMesh


//---------------------------------------------------------------------------
//fixNode - check name, remove userData, round pos/rot/scale, rename materials
//  node - THREE.Object3D being fixed
//  ent  - top-level parent entity
function fixNode(node, ent) {
	console.assert(node.name, 'Node has no name!  node.parent='+ node.parent.name);
	node.name = node.name.replaceAll('_', '');

	//make sure there is nothing surprising in the userData
	const aKeys = Object.keys(node.userData);
	console.assert(aKeys.length === 1  &&  aKeys[0] === 'transformData');
	console.assert(node.userData.transformData.eulerOrder === 'ZYX'); //see FixMatrix #EulerOrder
	node.userData = {}; //remove Unity data

	roundTRS(node, ent); //round pos/rot/scale and log if not zero
	if(node.isMesh)  FixMesh(node, ent);
} //fixNode


//---------------------------------------------------------------------------
//fixYupAnd100x - module entities probably have rotation = y-up and scale=100
// if so, bake children
function fixYupAnd100x(ent) {
	let matPretty; //pretty matrix avoids rounding errors; Ex: no 9.99e-99
	//rotation
	if(!my.isClose(ent.rotation, [0,0,0, 'XYZ'])) {
		if(!my.isClose(ent.rotation, [-Math.PI/2, 0, 0, 'XYZ']))
			console.warn(ent.name +' has UNBAKED rotation: '+ my.toStr(ent.rotation));
		else {
			console.log(ent.name +' baked to y-up');
			matPretty = new Matrix4().set(...[ //y-up
				1, 0, 0, 0,
				0, 0, 1, 0,
				0,-1, 0, 0,
				0, 0, 0, 1
			]);
			ent.rotation.set(0,0,0, 'XYZ'); //all gone after baking
		}
	}

	//scale
	if(!my.isClose(ent.scale, [0,0,0])) {
		if(!my.isClose(ent.scale, [100, 100, 100]))
			console.warn(ent.name +' has UNBAKED scale: '+ my.toStr(ent.scale));
		else {
			console.log(ent.name +' baked to scale x10');
			if(!matPretty)  matPretty = new THREE.Matrix4();
			ent.scale.setScalar(10); //scale 10x instead of 100x
			matPretty.scale(ent.scale);
			ent.scale.setScalar(1); //all gone after baking
		}
	}

	//bake parent matrix into all child nodes / geometry
	if(!matPretty)  return; //no baking today
	ent.traverse(node => {
		if(!my.isClose(node.position, [0,0,0]))
			node.position.applyMatrix4(matPretty);
		if(!my.isClose(node.rotation, [0,0,0,'XYZ'])) {
			//swap axis from z-up to y-up
			let oldY = node.rotation.y;
			node.rotation.y = node.rotation.z;
			node.rotation.z = oldY;
		}
		if(node.geometry) {
			//bake geometry: apply root ent's transformation matrix
			node.geometry.applyMatrix4(matPretty);
		}
	});
	ent.matrix = new Matrix4(); //all gone after baking
	ent.updateMatrix(); //put position back (position was not baked)
} //fixYupAnd100x


//---------------------------------------------------------------------------
//fixModularFBX - fix modular dungeon .FBX file
function fixModularFBX(ent, entName) {
	if(ent.name !== entName) {
		console.info(`Renamed ent from ${ent.name} to ${entName}`);
		ent.name = entName;
	}

	//remove extra top-level node
	console.assert(ent.isGroup);
	if(ent.children.length === 1  &&  ent.children[0].isGroup) {
		const child = ent.children[0];
		console.warn('Combined extra top-level node: '+ child.name);
		console.assert(child.children.length > 1);
		console.assert(my.isClose(child.rotation, [0,0,0,'XYZ']));
		console.assert(my.isClose(child.scale, [1,1,1]));
		if(!my.isClose(child.position, [0,0,0]))
			ent.position.add(child.position); //bring child position up one level
		ent.remove(child); //detach extra node
		while(child.children.length > 0) //move all grandchildren up one level
			ent.add(child.children[0]); //add() also removes child from current parent
	}

	//special exceptions
	switch(ent.name.replaceAll('_', '')) {
	case 'PropSwitchFloor': //lift up to floor, object center is lever axis
		//TODO: should lift to floor then bake (so child lever y=0.5)
		ent.position.z += 0.05; //note: this is before y-up + 100x
		break;

	case 'PropBrazierMetalSmall':
		//original is double rotated (y-up then back down, geometry was already baked)
		console.log(ent.name +' does NOT rotate y-up');
		ent.rotation.x = 0;
		ent.children.forEach(mesh => mesh.rotation.x = 0);
		break;

	case 'PropHazardFloorHoles':
	case 'PropHazardFloorSpikes':
		//scale is missing
		ent.scale.set(100, 100, 100);
		break;

	case 'StructureFloorGrateCircleBars':
	case 'StructureFloorGrateCircleGrid':
	case 'StructureFloorGrateSquareBars':
	case 'StructureFloorGrateSquareGrid':
		//ignore warnings: floor drains were below grade but will be fixed
		const name = ent.name.replace('Structure_Floor_Grate', 'Drain');
		ent.getObjectByName(name).traverse(obj => obj.position.z = 0);
		break;

	case 'PropTorchHolderOccupied':
		ent.getObjectByName('object224002').name = 'holderUpper';
		ent.getObjectByName('object250002').name = 'holderLower';
		ent.getObjectByName('object295001').name = 'torchTop';
		ent.getObjectByName('object218001').name = 'torchHandle';
		break;
	}//switch

	fixYupAnd100x(ent); //bake y-up and 100x scale into child nodes & geometry
	ent.traverse(node => fixNode(node, ent)); //check all children
} //fixModularFBX


//---------------------------------------------------------------------------
//LoadingManager - called when all textures are loaded
let g_ent; //entity being fixed
let g_loadMgr = new THREE.LoadingManager(() => {
		//texture loading complete (including new normal maps), now it's safe to export
		exportGLTF(g_ent);
		g_ent = undefined;
});


//---------------------------------------------------------------------------
//exportGLTF - save .gltf; uses ent.name as filename
// isBinary - true = save binary (.gltf + .bin); false = only JSON (.gltf)
function exportGLTF(ent, isBinary=true) {
	const opt = { //output options
		binary: isBinary,  //.glb or .gltf
		trs: true, //pos/Euler/scale or matrix
		onlyVisible: false, //false = include hidden nodes
		truncateDrawRange: false, //false = include everything, even outside draw range
	};

	new GLTFExporter().parse(ent, gltf => {
		console.assert(ent.name, 'exportGLTF ent has no name');
		const entName = ent.name ?? 'scene';
		if(gltf instanceof ArrayBuffer) { //if binary
			SaveFile(entName +'.glb', new Blob([gltf], {type:'application/octet-stream'}));
		} else { //else JSON
			const sPretty = JSON.stringify(gltf, null, 2); //indent 2 spaces
			SaveFile(entName +'.gltf', new Blob([sPretty], {type:'text/plain'}));
		}

		//SaveFile - download file to browser's download folder	
		function SaveFile(filename, blob) {
			const a = document.createElement('a');
			a.download = filename;
			a.href = window.URL.createObjectURL(blob);
			a.click();
		} //SaveFile
	}, err => console.log('GLTF parse error: '+ err), opt);
} //exportGLTF


//---------------------------------------------------------------------------
//convertFBX - Load original .fbx file and fix all the broken shit
function convertFBX(entName, onLoadComplete) {
	console.log(`--- convertFBX(${entName}) ---`);
	new FBXLoader(g_loadMgr).load(`assets/modular/fbx/${entName}.fbx`, fbx => {
		//base object loaded, fix it
		if(g_ent) {
			console.error(`Unable to load ${fbx.name}, still waiting for ${g_ent.name} to finish`);
			return;
		}
		g_ent = fbx; //used by LoadingManager after loading all textures
		console.log('Before fix:\n'+ toTree(fbx));
		fixModularFBX(fbx, entName); //modular dungeon pack
		console.log('After fix:\n'+ toTree(fbx));
		onLoadComplete(g_ent);
	});
} //convertFBX
