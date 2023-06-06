//Copyright (c) 2022 Gary Strawn, All rights reserved
/* Controls - handle user input; keyboard & mouse

MouseLook: use the pointer (left-drag or lockMouse) to look around
left-drag - press and hold left-button (button #0) to look around
lockMouse - when activated, mouse looks around with no cursor, infinite scroll

mousemove left/right rotates both ent's y-axis (and attached camera(s) if present)
mousemove up/down rotates first camera's x-axis but not ent's
Note: if not camera is attached, mouse up/down does nothing

LockNotes:
lockMouse (pointerLock) can only be started from a user gesture (keydown, mousedown).
If exited programmatically, pointerLock can be re-entered immediately.
If user pressed ESC to exit, requires ~1 sec before re-enter.
https://w3c.github.io/pointerlock/#dom-element-requestpointerlock


Keyboard tracker examples:
controls.keys.ArrowLeft : true if <ArrowLeft> is currently pressed (val = seconds pressed)
controls.keys.KeyA      : true if <a> or <A> is currently pressed  (val = seconds pressed)
controls.keys.Space < 0 : true if <Space> was just pressed         (val = -1)

Caution: Some hotkeys, such as Ctrl+W (close window), cannot be changed!!!

The following keys are used (see update):
	ArrowUp,    KeyW,
	ArrowDown,  KeyS,
	ArrowLeft,  KeyA,
	ArrowRight, KeyD,
	ShiftLeft,  //run
	Space,  //jump
	KeyC,   //crouch
	//special key: Alt+L = toggle lockMouse (ESC unlocks, required by browser)
*/
import { Vector3 } from "three";
import { errBox } from "./msg.js";


export default class Controls {
	keys = {}; //keys currently pressed: {<key>:<-1 if just pressed | seconds pressed>}

	#ent; //Ent being controlled
	speeds  = {   //movement speeds; default = 0, configured by setEnt()
		velWalk     : 0, // 5 ft/s = 3.5mph = brisk walk;  8 ft/s = 5.5mph = jog
		velRun      : 0, //20 ft/s = avg, 30 ft/s = elite, 40 ft/s = Usain Bolt's record
		velWalkBack : 0, // 4 ft/s = walking backwards
		velRunBack  : 0, //10 ft/s = running backwards (while carrying a sharp sword?)
		velCreep    : 0, // 2 ft/s = shh, I'm hunting wabbits
		velCreepRun : 0, // 4 ft/s = the wascally wabbit is wunning
		velJump     : 0, //10 ft/s = 18" high = standing non-crouch
		velJumpRun  : 0, //18.8 ft/s = 5'5" high = NBA record
	};

	//user configuration (same even if Ent is reassiged)
	keyLookSpeed   = Math.PI/4; //radians per second; keyboard only
	mouseDragSpeed = 0.00125; //0.00125 ~matches visual drag speed
	mouseLockSpeed = 0.0025;  //speed when isMouseLock

	//---------------------------------------------------------------------------
	//init
	constructor(ent) {
		this.setEnt(ent);
		this.startListen(); //Note: does not call lockMouse; see LockNotes above
	} //init


	//---------------------------------------------------------------------------
	//setEnt - switch Ent being controlled
	setEnt(ent) {
		console.assert(ent?.isEnt);
		this.#ent = ent;

		//make a copy of ent.config
		console.assert(ent.config); //if no speeds configured, Ent isn't controllable
		for(const key of Object.keys(this.speeds))  //init only those we need
			this.speeds[key] = ent?.config[key] ?? 0;
	} //setEnt


	//---------------------------------------------------------------------------
	//attach event listeners
	startListen() {
		document.addEventListener('keydown', this.#keyDown);
		document.addEventListener('keyup', this.#keyUp);
		document.addEventListener('mousemove', this.#mouseLook);
		document.addEventListener('pointerdown', this.#pointerDown);
		document.addEventListener('pointerup', this.#pointerUp);
		document.addEventListener('contextmenu', this.#contextMenu);
		document.addEventListener('pointerlockchange', this.#pointerLockChange);
		document.addEventListener('pointerlockerror', this.#pointerLockError);
	}

	//release event listeners
	stopListen() {
		document.removeEventListener('keydown', this.#keyDown);
		document.removeEventListener('keyup', this.#keyUp);
		document.removeEventListener('mousemove', this.#mouseLook);
		document.removeEventListener('pointerdown', this.#pointerDown);
		document.removeEventListener('pointerup', this.#pointerUp);
		document.removeEventListener('contextmenu', this.#contextMenu);
		document.removeEventListener('pointerlockchange', this.#pointerLockChange);
		document.removeEventListener('pointerlockerror', this.#pointerLockError);
	}
	dispose() { stopListen(); }


	//---------------------------------------------------------------------------
	//mouseLock = pointerLock = no visible cursor, no bounds;  see Lock Notes above
	get isMouseLock() { return document.pointerLockElement === document.body; }
	lockMouse(force)  {
		if(force === true  ||  (force === undefined  &&  !this.isMouseLock))
			document.body.requestPointerLock(); //see Lock Notes above
		else
			document.exitPointerLock();
	}


	//---------------------------------------------------------------------------
	//update - return push-velocity (ft/s) from user input (wasd...)
	//Receives: dt = seconds elapsed since previous update
	//Returns: vPush is target velocity, not acceleration or current velocity
	update(dt) {
		let vPush = new Vector3(); //+x:right, -x:left, +y:jump, -y:fall, +z:back, -z:forward
		if(this.keys.ArrowUp     ||  this.keys.KeyW)  vPush.z--;
		if(this.keys.ArrowDown   ||  this.keys.KeyS)  vPush.z++;
		if(this.keys.ArrowLeft   ||  this.keys.KeyA)  vPush.x--;
		if(this.keys.ArrowRight  ||  this.keys.KeyD)  vPush.x++;

		let isWalk = !this.keys.ShiftLeft;
		let isRun  =  this.keys.ShiftLeft;
		if(vPush.z == 0  &&  vPush.x == 0)
			isWalk = isRun = false;

		//linear velocity (walk, run, crouch, backward)
		let speed = isWalk ? this.speeds.velWalk : isRun ? this.speeds.velRun : 0;
		if(vPush.z > 0) //if moving backwards
			speed = isWalk ? this.speeds.velWalkBack : this.speeds.velRunBack;
		if(this.keys.KeyC) //if crouched
			speed = isWalk ? this.speeds.velCreep : this.speeds.velCreepRun;

		if(vPush.z  &&  vPush.x)  speed *= Math.SQRT1_2; //normalize diagonal
		vPush.z *= speed;
		vPush.x *= speed * 0.5; //sideways is slower

		if(this.keys.Space) { //jump constantly (as opposed to this.newKeys.Space)
			//velJumpRun if running forward or crouched;  otherwise velJump
			const isRunJump = (isRun  &&  vPush.z < 0)  ||  this.keys.KeyC;
			vPush.y = isRunJump  ?  this.speeds.velJumpRun  :  this.speeds.velJump;
		}

		//update keypress times (new keys are no longer -1)
		for(const [key, val] of Object.entries(this.keys))
			this.keys[key] = ((val == -1)  ?  dt  :  (val + dt));

		return vPush;
	} //update


	//---------------------------------------------------------------------------
	//private eventListener callbacks (with bind)
	#altLock = false; //true=mouse locked with Alt+L; false=unlocked or right-clock lock
	#keyDown = function(ev) {
		//handle Alt+L mouse lock
		if(ev.altKey && ev.key == 'l') {
			this.lockMouse(); //toggle
			this.#altLock = this.isMouseLock;
			ev.preventDefault();
			return;
		}

		//Note: Some hotkeys, such as Ctrl+W (close window), cannot be prevented!!!
		if(ev.ctrlKey && ev.key == 's') { //prevent Ctrl+S
			ev.preventDefault();
			return;
		}

		//track keys
		this.keys[ev.code] = -1;
	}.bind(this);


	#keyUp = function(ev) {
		delete this.keys[ev.code];
		ev.preventDefault();
	}.bind(this);


	//---------------------------------------------------------------------------
	//mouseLook - use mouse to look around (yaw = ent, pitch = camera)
	#isMouseDrag = false; //true when left-button is down
	#isMouseLook = false; //true when right-button is down
	#mouseLook = function(ev) {
		if(!this.isMouseLock  &&  !this.#isMouseDrag  &&  !this.#isMouseLook)  return;

		//Note: rotate YXZ: y-axis first to keep y-up (camera pitch yes, camera roll no)
		//#isMouseDrag/isMouseLock controls are reversed (one drags, the other points)

		//mouse.movementX = y-axis rotation = turn (yaw) ent left/right
		const deltaY = this.#isMouseDrag
			?   ev.movementX * this.mouseDragSpeed
			:  -ev.movementX * this.mouseLockSpeed;
		if(this.#ent.isMob) //Note: can still turn even if in the air (ent.isFalling)
			this.#ent.rotation.y += deltaY; //rotate ent (camera, if attached, will follow)
		else if(this.#ent.camera)
			this.#ent.camera.rotation.y += deltaY; //not a mob, only turn camera

		if(this.#ent.camera) {
			//mouse.movementY = x-axis rotation = tilt (pitch) camera up/down (but not ent)
			const deltaX = this.#isMouseDrag
				?   ev.movementY * this.mouseDragSpeed
				:  -ev.movementY * this.mouseLockSpeed;
			let rx = this.#ent.camera.rotation.x + deltaX;
			rx = Math.min(Math.PI/2, Math.max(-Math.PI/2, rx));
			this.#ent.camera.rotation.x = rx;
		}
	}.bind(this); //needed for eventListener


	#pointerDown = function(ev) {
		switch(ev.button) {
		case 0: this.#isMouseDrag = true;  break; //left button
		case 1: break; //middle button
		case 2: //right button
			if(!this.#altLock) {
				this.lockMouse(true);
				this.#isMouseLook = true;
			}
			break;
		}
	}.bind(this);

	#pointerUp = function(ev) {
		switch(ev.button) {
		case 0: this.#isMouseDrag = false;  break; //left button
		case 1: break; //middle button
		case 2: //right button
			if(!this.#altLock) {
				this.lockMouse(false);
				this.#isMouseLook = false;
			}
			break;
		}
	}.bind(this);

	#contextMenu = function(ev) {
		ev.preventDefault(); //don't show default right-click menu
	}

	//see LockNotes above
	#mouseLockTime = 0;
	#pointerLockChange = function(ev) {
		this.#mouseLockTime = Date.now();
	}.bind(this);

	#pointerLockError = function(ev) {
		if(Date.now() - this.#mouseLockTime < 1000) {
			//error is *probably* because of relocking too fast after pressing ESC to unlock
			errBox("Can't enter/exit mouse lock so fast.\nWait a second.", 3000);
		} else
			errBox("Press Alt+L to lock/unlock mouse look.<br>"+
			       "Right-click for temporary mouse look.<br>"+
			       "Or left-click and drag.", 'OK');
	}.bind(this);
}; //PlayerControls
