//Copyright (c) 2022 Gary Strawn, All rights reserved
import * as THREE from 'three';
import Controls from "./Controls.js";
import { msgBox } from './msg.js';
import * as my from "./utils.js"

const GRAVITY = -32.17405; //gravity = -9.8 m/s/s = -32 ft/s/s


//---------------------------------------------------------------------------
//Ent - game entity
export class Ent extends THREE.Object3D {
	vel = new THREE.Vector3(); //current velocity in ft/sec (not single-frame speed)
	isFalling = false; //true if being accelerated by gravity (i.e. in the air)

	aComponents = [];

	constructor(name) {
		super();
		this.isEnt = true;
		this.type  = 'Ent';
		if(name)  this.name = name;
	}

	//---------------------------------------------------------------------------
	//update - update position & gravity (vel.y)
	update(dt) {
		this.aComponents.forEach(cmp => cmp.update(dt));

		//gravity:  v₁ = v₀ + gt + ½gt²
		if(!this.parent?.isEnt) { //only fall if not attached to another ent
			this.vel.y += GRAVITY * dt //v₁ = v₀ + gt
			      + 0.5 * GRAVITY * dt * dt; // + ½gt²
		}
		this.position.addScaledVector(this.vel, dt); //p₁ = p₀ + vt

		//collide with floor
		if(this.position.y < 0) { //don't fall through floor
			this.isFalling = false;
			this.position.y = 0;
			if(this.vel.y < 0)  this.vel.y = 0;
		} else
			this.isFalling = true;

		//x-bounds
		const xMax = 3.8;
		if(this.position.x < -xMax) {
			this.position.x = -xMax;
			if(this.vel.x < 0)  this.vel.x = 0;
		}
		else if(this.position.x > xMax) {
			this.position.x = xMax;
			if(this.vel.x > 0)  this.vel.x = 0;
		}

		//z-bounds
		if(this.position.z < -103) {
			this.position.z = -102.5;
			if(this.vel.z < 0)  this.vel.z = 0;
			if(this.isPlayer  &&  (this.rotation.y - Math.PI/2) < Math.PI) { //if facing door
				msgBox('The door is locked.', 'Unlock', 5)
				.then(()=>msgBox("You don't have the key.", '&#x1F612', 5));
			}
		}
		else if(this.position.z > 15) {
			this.position.z = 15; //teleport back some
			if(this.vel.z > 0)  this.vel.z = 0;
			if(this.isPlayer  &&  (this.rotation.y - Math.PI/2) < Math.PI) //if facing exit
				window.location = '/';
		}

/*
		//collisions
		//TODO: slow framerate might allow Ents to pass through objects without colliding
		if(this.collider) {
			const intersect = this.collider.intersect(); //return {normal: depth:} | false
			if(!intersect)
				this.isFalling = true;
			else {
				this.isFalling = 
			}
		}
*/
	} //Ent.update


	//---------------------------------------------------------------------------
	//base class overrides
//	load() {}
//	save() {}

	//clone() //inherited: new this.constructor().copy
	copy(src) {
		super.copy(src);
		this.vel.copy(src.vel);
		console.assert(0, 'TODO: clone or share components?');
		return this;
	}

	add(object) {
		if(object.isCamera) {
			console.assert(this.camera === undefined); //multiple cameras not supported
			this.camera = object;
			this.camera.position.copy(this.position);
			this.camera.position.y += this.config.yEyesStand ?? 0;
		}
		return super.add(object);
	}

	remove(object) {
		if(object.isCamera)  delete this.camera;
		return super.remove(object);
	}

/* components
	//---------------------------------------------------------------------------
	//components
	addComponent(ComponentClass, ...args) {
		const cmp = new ComponentClass(this, ...args);
		this.aComponents.push(cmp);
		return cmp;
	}

	delComponent(cmp) {
		const i = this.aComponents.indexOf(cmp);
		if(i >= 0)  this.aComponents.splice(i, 1);
	}

	getComponent(ComponentClass) {
		return this.aComponents.find(cmp => cmp instanceof ComponentClass);
	}
*/
} //Ent


//---------------------------------------------------------------------------
//EntLight - Lamp or some sort of light
export class EntLight extends Ent {
	#pointLight; //THREE.pointLight
	#bulbMesh; //the part that glows
	get light() { return this.#pointLight }

	//---------------------------------------------------------------------------
	//ctor - create a new light
	constructor(color=0xFFCC00, intensity=1, radius=0) {
		super('light');
		this.isLight = true;
		this.#pointLight = new THREE.PointLight(color, intensity, 20);
		this.add(this.#pointLight);
		if(radius) {
			this.#bulbMesh = new THREE.Mesh(
				new THREE.SphereGeometry(radius, 4, 2),
				new THREE.MeshBasicMaterial({toneMapped:false}));
			this.#bulbMesh.material.color.set(color * 1.2);
			this.add(this.#bulbMesh);
		}
	}

	//---------------------------------------------------------------------------
	//dispose - remove light
	dispose() {
		if(!this.#pointLight)  return; //already extinguished
		this.remove(this.#pointLight);
		this.#pointLight.dispose();
		this.#pointLight = null;
		this.#bulbMesh.geometry.dispose();
		this.#bulbMesh.material.dispose();
	}

	//---------------------------------------------------------------------------
	//dim - dimmer switch;  on/off/flicker
	//ignite / extinguish - turn light on / off
	#dimSmooth; //transition smoothly between intensities
	dim(intensity, seconds) {
		if(intensity == this.light.intensity  ||  seconds == 0)  return; //done
		if(!this.#dimSmooth)  this.#dimSmooth = new my.Smooth();
		this.#dimSmooth.init(this.light.intensity, intensity, seconds);
		return this;
	}
	ignite(seconds)     { return this.dim(1, seconds); } //full brightness
	extinguish(seconds) { return this.dim(0, seconds); } //full off

	//---------------------------------------------------------------------------
	//update - dimmer/flicker
	update(secDelta) {
		if(!this.light)  return;
		if(this.#dimSmooth?.isSmoothing) {
			//adjust brightness
			this.light.intensity = this.#dimSmooth.update(secDelta);
			if(this.#bulbMesh) {
				this.#bulbMesh.material.color = this.#pointLight.color.clone()
					.multiplyScalar(this.#pointLight.intensity);
			}
		} else if(this.isTorch) {
			//flicker like a torch
			this.dim((1 - (my.rand() * my.rand()) * 0.8) + 0.2, //exp (0.2, 1]
				my.rand(1/60, 10/60)); //1-10 frames
		}
	}
} //EntLight

export class EntTorch extends EntLight {
	constructor() {
		super(0xDD9922, 0, 0.5); //start unlit
		this.name = 'torch';
		this.isTorch = true;
	}
}


//---------------------------------------------------------------------------
//Mob - Mobile entity (can walk, run, jump...)
export class Mob extends Ent {
	vPush = new THREE.Vector3(); //movement induced by player or AI (in Ent coordinates)

	constructor(name) {
		super(name);
		this.isMob = true;
		this.type = 'Mob';
	}

	//---------------------------------------------------------------------------
	//Mob.update - apply vPush to vel
	#xSmooth = new my.Smooth();
	#zSmooth = new my.Smooth();
	update(secDelta) {
		//rotate vPush coordinates (vPush.x = right, vel.x = east)
		const sinY = Math.sin(this.rotation.y);
		const cosY = Math.cos(this.rotation.y);
		const xPush =  cosY * this.vPush.x  +  sinY * this.vPush.z;
		const zPush = -sinY * this.vPush.x  +  cosY * this.vPush.z;

		//v₁ = v₀ + at;  smoothly apply vPush (i.e. simulate momentum)
		//vPush is target velocity, not acceleration or current velocity
		if(xPush != this.vel.x  ||  zPush != this.vel.z) { //if vel is changing
			if(xPush != this.#xSmooth.max)
				this.#xSmooth.init(this.vel.x, xPush, this.config.velDelay);
			if(zPush != this.#zSmooth.max)
				this.#zSmooth.init(this.vel.z, zPush, this.config.velDelay);
			this.vel.x = this.#xSmooth.update(secDelta);
			this.vel.z = this.#zSmooth.update(secDelta);
		}

		if(!this.isFalling  &&  this.vPush.y) //jump
			this.vel.y = this.vPush.y; //apply entire impulse in a single frame

		super.update(secDelta); //apply vel & gravity
	} //Mob.update
} //Mob


//---------------------------------------------------------------------------
//Player - player character
export class Player extends Mob {
	config = {
		//movement speeds; units are feet/second
		velWalk     : 8,    // 5 ft/s = 3.5mph = brisk walk;  8 ft/s = 5.5mph = jog
		velRun      : 20,   //20 ft/s = avg, 30 ft/s = elite, 40 ft/s = Usain Bolt's record
		velWalkBack : 5,    //walking backwards
		velRunBack  : 10,   //running backwards (while carrying a sharp sword?)
		velCreep    : 2,    //shh, I'm hunting wabbits
		velCreepRun : 4,    //the wascally wabbit is wunning
		//Note: velJumpRun if running forward or from crouch;  otherwise velJump
		//Note: to convert max height to velJump: v₀ = sqrt(2g * maxHeight)
		velJump     : 10,   //10 ft/s = 18" high = standing non-crouch
		velJumpRun  : 18.8, //18.8 ft/s = 5'5" high = NBA record

		velDelay    : 0.5,  //velocity change delay, in seconds; stop to run = 0.5s = 40 ft/s/s

		//stand / crouch
		yEyesStand  : 5,    //player's eyes are 5' height (yup, he's short for a storm trooper)
		yEyesCrouch : 3,    //eye height when crouching
		crouchDelay : 0.25, //seconds to crouch/stand
	}

	constructor(name) {
		super(name),
		this.isPlayer = true;
		this.type = 'Player';
		this.controls = new Controls(this); //player controls: keyboard & mouse
	}

	//---------------------------------------------------------------------------
	//update - crouch/stand, get vPush from user controls
	#crouchSmooth = new my.Smooth();
	#infoWasZero = false; //true if vel was zero previous frame too (hidden when not changing)
	update(dt) {
		//update stand/crouch height
		const yEyes = this.controls.keys.KeyC ? this.config.yEyesCrouch : this.config.yEyesStand;
		const yCurr = this.camera?.position.y ?? yEyes;
		if(yEyes != yCurr  &&  !this.isFalling) { //if changed, not when jumping (it looks dumb)
			if(yEyes != this.#crouchSmooth.max) //if new target
				this.#crouchSmooth.init(yCurr, yEyes, this.config.crouchDelay);
			this.camera.position.y = this.#crouchSmooth.update(dt);
		}

		this.vPush = this.controls.update(dt); //get user input
		super.update(dt); //update physics & collisions

		//show current pos & vel
		const info = document.getElementById('infoPlayer');
		if(info  &&  info.style.display != 'none'
		  &&  !(my.isZero(this.vel)  &&  this.#infoWasZero)) {
			let txt = 'pos: '+ my.toStr(this.position, 1, true); //true = fixed so doesn't flicker
			if(!my.isZero(this.vel))
				 txt += '\nvel: '+ my.toStr(this.vel, 1, true);
			info.textContent = txt;
			this.#infoWasZero = my.isZero(this.vel);
		}
	} //Player.update
} //Player


//---------------------------------------------------------------------------
//EntMgr - entity manager
export class EntMgr {
	models = new THREE.Group(); //rendered Ents
	#aEnts = [];    //master list of all Ents
	#addQueue = []; //new Ents added during update
	#delQueue = new Set(); //old Ents removed during update (Set = delete in order)

	constructor() {
		this.models = new THREE.Group();
		this.models.name = 'Ents';
	}

	get numEnts()  { return this.#aEnts.length + this.#addQueue.length; }

	add(ent)  { this.#addQueue.push(ent);  this.models.add(ent);     return ent; }
	del(ent)  { this.#delQueue.push(ent);  this.models.remove(ent);  return ent; }

	update(dt) {
		this.#addAddQueue();
		this.#delDelQueue();
		for(const ent of this.#aEnts) {
			if(this.#delQueue.has(ent))  return; //it was deleted during this forEach
			ent.update(dt);
		}
		this.#delDelQueue();
	}

	#addAddQueue() { //add ents from the addQueue
		//add queued ents
		if(this.#addQueue.length) {
			this.#aEnts.push(...this.#addQueue);
			this.#addQueue.length = 0;
		}
	}

	#delDelQueue() { //delete ents from the delQueue
		if(this.#delQueue.size) {
			this.#aEnts = this.#aEnts.filter(ent => !this.#delQueue.has(ent));
			this.#delQueue.clear();
		}
	}
} //EntMgr

/* //x
//---------------------------------------------------------------------------
//Component - entity component; ex: latch, jetPack, glowPaint...
//  True Entity/Component/System goes crazy with this (similar to MVC).
//  We prefer Object Oriented Design; inheritance like  Player::MOB::Ent::Object3D
class Component {
	ent; //must always belong to an Ent
	constructor(ent) { this.ent = ent; } //must belong to an Ent
	update() {}
}
*/