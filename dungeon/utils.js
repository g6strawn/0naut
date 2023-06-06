//Copyright (c) 2022 Gary Strawn, All rights reserved

//---------------------------------------------------------------------------
//clamp - min <= x <= max
export function clamp(x, min, max) {
	if(min > max) {
		let tmp = min;
		min = max;
		max = tmp;
	}
 	return Math.min(Math.max(x, min), max);
} //clamp


//---------------------------------------------------------------------------
//lerp - linear interpolate
//https://en.wikipedia.org/wiki/Linear_interpolation
export function lerp(t, min, max) {
	return (1 - t) * min + t * max;
	//Note: return min + (t * dist);  Imprecise!
	//  Does not guarantee max when t=1, due to floating-point arithmetic error
} //lerp


//---------------------------------------------------------------------------
//smoothstep - ease-in, ease-out interpolation
//     t < 0:  return 0
// 0 < t < 1:  return 3x^2 - 2x^3
//     t > 1:  return 1
//https://en.wikipedia.org/wiki/Smoothstep
export function smoothstep(t) {
	if(t <= 0)  return 0;
	if(t >= 1)  return 1;
	return t * t * (3 - 2 * t); // 3x^2 - 2x^3
} //smoothstep


//---------------------------------------------------------------------------
//Smooth - same as smoothstep except scales to min, max
// min: starting value       ex:  0 mph at start of on-ramp  (t=0)
// max: target value         ex: 65 mph merging onto freeway (t=1)
// seconds: transition time  ex: 0 to 60 in 4 seconds flat
export class Smooth {
	min  = 0; //t <= 0  returns min
	max  = 0; //t >= 1  returns max
	tMax = 0; //total seconds
	dt   = 0; //elapsed time (see update)

	init(min, max, seconds) {
		this.min = min;
		this.max = max;
		this.tMax = seconds;
		this.dt = 0;
	}

	update(dt) {
		this.dt += dt;
		return this.x(this.dt / (this.tMax || this.dt)); //1 = instant
	}

	x(t) {
		if(t <= 0)  return this.min;
		if(t >= 1)  return this.max;
		t = t * t * (3 - 2 * t); // 3x^2 - 2x^3
		return (1 - t) * this.min + t * this.max;
		//Note: return min + (t * dist);  Imprecise!
		//  Does not guarantee max when t=1, due to floating-point arithmetic error
	}

	get isSmoothing() { return this.dt < this.tMax; }
} //Smooth


//---------------------------------------------------------------------------
//smooth - same as smoothstep except scales to min, max
//   t: current time     ex: 0 = start, 1 = end
// min: starting value   ex: 25 mph at start of on-ramp  (t=0)
// max: target value     ex: 65 mph merging onto freeway (t=1)
export function smooth(t, min, max) {
	if(t <= 0)  return min;
	if(t >= 1)  return max;
	t = t * t * (3 - 2 * t); // 3x^2 - 2x^3
	return (1 - t) * min + t * max;
	//Note: return min + (t * dist);  Imprecise!
	//  Does not guarantee max when t=1, due to floating-point arithmetic error
} //smooth


//--------------------------------------------------------------------------
//rand()        - [0,1)
//rand(max)     - [0,max) max=exclusive
//rand(min,max) - [min,max) min=inclusive, max=exclusive
// min - lower bound, inclusive
// max - upper bound, exclusive
//This is similar to lodash.random;  Why doesn't native Javascript have this?
export function rand(min, max) {
	if(min === undefined  &&  max === undefined) {
		//rand()
		min = 0;
		max = 1;
	} else {
		min = min || 0;
		if(max === undefined) {
			//rand(max)
			max = min;
			min = 0;
		} //else rand(min,max)
	}
	if(min > max) {
		//rand(max,min)
		let tmp = min;
		min = max;
		max = tmp;
	}
	return Math.random() * (max - min) + min;
} //rand


//--------------------------------------------------------------------------
//randInt()        - [0,1] inclusive; ex: true | false
//randInt(max)     - [0,max] inclusive;   ex: randInt(10)   = 11 possibilities
//randInt(min,max) - [min,max] inclusive; ex: randInt(1,10) = 10 possibilities
// min - lower bound (or the next integer greater than min if min isn't an integer)
// max - upper bound (or the next integer lower than max if max isn't an integer)
//Note: using Math.round() makes a non-uniform distribution!
//This is similar to lodash.random;  Why doesn't native Javascript have this?
export function randInt(min, max) {
	if(min === undefined  &&  max === undefined) {
		//randInt()
		min = 0;
		max = 1;
	} else {
		min = min ? Math.ceil(min) : 0;
		if(max === undefined) {
			//randInt(max)
			max = min;
			min = 0;
		} else {
			//randInt(min,max)
			max = Math.floor(max);
		}
	}
	if(min > max) {
		//randInt(max,min)
		let tmp = min;
		min = max;
		max = tmp;
	}
	return Math.floor(Math.random() * (max - min + 1) + min); //+1 = inclusive
} //randInt


//---------------------------------------------------------------------------
//isZero - rue if (x:0, y:0, z:0)
export function isZero(v) { return v.x === 0  &&  v.y === 0  &&  v.z === 0; }


//---------------------------------------------------------------------------
//roundTo - round to specified precision;  non-destructive
//  roundTo(Math.PI, 2) = 3.14
//  roundTo(Math.PI, 4) = 3.1416
//  roundTo(Math.PI, -4) = 0  ***CAUTION!!!
//  roundTo(Math.PI) = 3.141593
//Note: This isn't perfect, floating point rarely is
const ROUND_PRECISION = 6; //EPSILON = 1e-6 = 0.000001;
export function roundTo(x, prec=ROUND_PRECISION) {
	if(x.toArray) //THREE.Vector3, Euler, etc;  if has toArray, also has fromArray, clone
		return x.clone().fromArray(roundTo(x.toArray(), prec));

	if(Array.isArray(x)) {
		const xTmp = [...x]; //don't modify original
		for(let i=0;  i < xTmp.length;  i++)
			xTmp[i] = roundTo(xTmp[i], prec);
		return xTmp;
	} else if(isNaN(x))
		return x;

	const EPSILON = Math.pow(10, prec);
	return Math.round((x + Number.EPSILON) * EPSILON) / EPSILON;
} //roundTo


//---------------------------------------------------------------------------
//isClose - true if floating point x is close to target y
//  isClose(0, 0.01, 2) = false
//  isClose(0, 0.001, 2) = true
//  isClose(0, 0.000001) = false
//  isClose(0, 0.0000001) = true
//Ex:  isClose(9.9e-9, 0)  returns true
export function isClose(x, y, prec=ROUND_PRECISION) {
	if(x.toArray)  return isClose(x.toArray(), y); //THREE.Vector3, Euler, etc
	if(y.toArray)  return isClose(x, y.toArray());

	if(Array.isArray(x)) {
		if(!Array.isArray(y)  ||  x.length != y.length)  return false;
		for(let i=0;  i < x.length;  i++)
			if(!isClose(x[i], y[i]))  return false;
		return true;
	} else if(isNaN(x)  ||  isNaN(y))
		return x == y; //LOOK! Double equal is appropriate here

	const EPSILON = Math.pow(10, -prec);
	return Math.abs(x - y) < EPSILON;
} //isClose


//---------------------------------------------------------------------------
//toStr<float|Vec3> - similar to toString for Vector3, Euler3, Quaternion
// prec: round to this many decimal places
// isFixed: false = shortest-possible float: (1.0, 1.0, 1.0) -> (1, 1, 1)
export function toStr(val, prec=4, isFixed=false) {
	if(!isNaN(val))
		return isFixed  ?  val.toFixed(prec)  :  parseFloat(val.toFixed(prec));

	if('x' in val  &&  'y' in val  &&  'z' in val) {
		const x = toStr(val.x, prec, isFixed);
		const y = toStr(val.y, prec, isFixed);
		const z = toStr(val.z, prec, isFixed);
		return `(${x}, ${y}, ${z})`;
	}

	return val.toString(); //JSON.stringify(val);  stringify is too much
} //toStr


//---------------------------------------------------------------------------
//toTree - return THREE.Object3D in tree view;  i.e. show heirarchy
export function toTree(obj, aLines = [], isLast = true, prefix = '') {
	const nextPrefix = prefix + (isLast ? '  ' : '│ ');
	let sOut = prefix  ?  (prefix + (isLast ? '└─' : '├─'))  :  '';
	sOut += obj.type +':'+ obj.name; //@ +' uuid:'+ obj.uuid;
	if(obj.isObject3D) {
		if(!isClose(obj.position, [0,0,0]))        sOut += ', pos:'+ toStr(obj.position);
		if(!isClose(obj.rotation, [0,0,0,'XYZ']))  sOut += ', rot:'+ toStr(obj.rotation);
		if(!isClose(obj.scale,    [1,1,1]))        sOut += ', scale:'+ toStr(obj.scale);
	}
	if(obj.isMesh) {
		//geometry: geoNum, geoRadius
		sOut += ', geoNum:'+ obj.geometry.getAttribute('position').count;
		obj.geometry.computeBoundingBox();
		sOut += ', geoMin:'+ toStr(obj.geometry.boundingBox.min)
		      + ', geoMax:'+ toStr(obj.geometry.boundingBox.max);

		//add all material(s)
		aLines.push(sOut);
		const aMat = Array.isArray(obj.material) ? obj.material : [obj.material];
		const iLast = aMat.length-1;
		aMat.forEach((mat, i) => toTree(mat, aLines, (i === iLast), nextPrefix));
		//fall through in case Mesh has children
	} else if(obj.isMaterial) {
		//all textures; map, bumpMap, displacementMap, etc.
		for(const [key, val] of Object.entries(obj))
			if(val instanceof Object  &&  val.isTexture)
				sOut += ', '+ key +':'+ val.name;
		aLines.push(sOut);
		return aLines;
	}

	//add all children
	if(!obj.isMesh)  aLines.push(sOut);
	const iLast = obj.children.length - 1;
	obj.children.forEach((child, i) => toTree(child, aLines, (i === iLast), nextPrefix));
	return aLines;
} //toTree

/*
//---------------------------------------------------------------------------
//createStrTex - return THREE.CanvasTexture with specified string
export function createStrTex(str) {
	const ctx = document.createElement('canvas').getContext('2d');
	ctx.canvas.width = 64;
	ctx.canvas.height = 64;
	ctx.font = '60px sans-serif';
	ctx.textAlign = 'center';
	ctx.textBaseline = 'middle';
	ctx.fillStyle = '#FFF';
	ctx.fillText(str, ctx.canvas.width / 2, ctx.canvas.height / 2);
	return new THREE.CanvasTexture(ctx.canvas);
} //createStrTex
const noteTex = createStrTex('♪');
*/