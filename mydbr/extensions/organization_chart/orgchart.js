var	G_vmlCanvasManager;	// so non-IE won't freak out

if (! window.console) console = {log: function() {}};	// IE has no console.log

function orgChart( init_params ) {

	"use strict";

///////////////////
// Default values:
///////////////////

var	
	defaults = { 
		lineColor: "#3388DD",      // Color of the connection lines (global for all lines)
		lineWidth: 1,              // Connection line width
		boxWidth: 0,               // Box width (global for all boxes)
		boxHeight: 0,              // Box height (global for all boxes)
		hSpace: 20,                // Horizontal space in between the boxes (global for all boxes)
		vSpace: 20,                // Vertical space in between the boxes (global for all boxes)
		hShift: 30,			       // The number of pixels vertical siblings are shifted (global for all boxes)
		boxLineColor: "#B5D9EA",   // Default box line color
		boxFillColor: "#CFE8EF",   // Default box fill color
		boxPadding: 16,            // Default box padding
		textColor: "#000000",      // Default box text color
		textFont: "arial",         // Default font
		textSize: 12,              // Default text size (pixels, not points)
		textPadding: 0,            // Pixels between lines
		boxHeightMin: 0,           // Minimum height of the node
		boxWidthMin: 0,            // Minimum width of the node
		colorBoxHeight: 10,        // Default coloBox height
		textVAlign: 1,             // Default text alignment
		shadowOffsetX: 3,          // node shadow offsetX
		shadowOffsetY: 3,          // node shadow offsetY
		shadowColor: "#A1A1A1",    // node shadow color
		radius: 5,                 // node corner radius
		roundRectBorderColor: "#003300",
		autobalance: 'auto'         // automatically balance left and right siblings
	},
	nodes = [],
	color_boxes = {},
	theCanvas,
	centerParentOverCompleteTree = 0,	// Experimental, lines may loose connections
	debug = 0,
	maxLoop = 9,
	noalerts = 0,
	canvasClickEvent = null,
	key;
	
	for (key in init_params) {
		defaults[key] = init_params[key];
	}

//////////////////////
// Internal functions:
//////////////////////

var	drawChartPriv,
	orgChartMouseMove,
	orgChartClick,
	vShiftUsibUnderParent,
	vShiftTree,
	hShiftTree,
	hShiftTreeAndRBrothers,
	fillParentix,
	checkLines,
	checkLinesRec,
	checkOverlap,
	countSiblings,
	balance_nodes,
	positionBoxes,
	positionTree,
	reposParents,
	reposParentsRec,
	findRightMost,
	findLeftMost,
	findNodeOnLine,
	drawNode,
	calcNodeDimension,
	drawImageNodes,
	drawConLines,
	getNodeAt,
	getEndOfDownline,
	getNodeAtUnequal,
	makeRoomForDownline,
	underVSib,
	errOut,
	debugOut,
	cleanText,
	dumpNodes,
	overlapBoxInTree,
	getLowestBox,
	getRootNode,
	getUParent,
	nodeUnderParent,
	getAbsPosX,
	getAbsPosY,
	centerOnCanvas,
	leftOnCanvas,
	getColorBoxAt,
	getClickEvent,
	strip_HTML,
	is_bold,
	is_italic;

////////////////////////////////////
// Internal information structures:
////////////////////////////////////

var Node = function(id, parent, contype, txt, bold, url, linecolor, fillcolor, textcolor, imgalign, imgvalign) {
		this.id = id;			// User defined id
		this.parent = parent;		// Parent id, user defined
		this.parentix = -1;		// Parent index in the nodes array, -1 for no parent
		this.contype = contype;		// 'u', 'l', 'r', 'a'
		this.txt = txt;			// Text for the box
		this.bold = bold;		// 1 for bold, 0 if not
		this.url = url;			// url
		this.linecolor = linecolor;
		this.fillcolor = fillcolor;
		
		this.textcolor = textcolor;

		this.textfont = defaults.textFont;
		this.textsize = defaults.textSize;

		this.valign = defaults.textVAlign;
		this.hpos = -1;			// Horizontal starting position in pixels
		this.vpos = -1;			// Vertical starting position in pixels
		this.usib = [];			// 'u' siblings
		this.rsib = [];			// 'r' siblings
		this.lsib = [];			// 'l' siblings
		this.img = '';			// Optional image
		this.imgAlign = imgalign;	// Image alignment 'l', 'c', 'r'
		this.imgVAlign = imgvalign;	// Image vertical alignment 't', 'm', 'b'
		this.imgDrawn;
	};

this.addNode = function(id, parent, ctype, text, bold, url, linecolor, fillcolor, textcolor, img, imgalign) {
	var	imgvalign;

	if (id        === undefined) { id        = ''; }
	if (parent    === undefined) { parent    = ''; }
	if (ctype     === undefined) { ctype     = 'u'; }
	if (bold      === undefined) { bold      = 0; }
	if (text      === undefined) { text      = ''; }
	if (url       === undefined) { url       = ''; }
	if (! linecolor) { linecolor = defaults.boxLineColor; }
	if (! fillcolor) { fillcolor = defaults.boxFillColor; }
	if (! textcolor) { textcolor = defaults.textColor; }
	if (imgalign  === undefined) { imgalign  = 'lm'; }

	if (id === ''){
		id = text;
	}
	if (parent === ''){
		ctype = 'u';
	}
	
	if (id === parent) {
		parent = '';
	}
	
	ctype = ctype.toLowerCase();
	if (ctype !== 'u' && ctype !== 'l' && ctype !== 'r' && ctype !== 'a' && parent !== ''){
		debugOut("Invalid connection type '" + ctype + "' at node '" + id + "'");
		ctype = 'a';
	}
	imgvalign = 'm';
	if (imgalign.substr(1, 1) == 't' || imgalign.substr(1, 1) == 'T') imgvalign = 't';
	if (imgalign.substr(1, 1) == 'b' || imgalign.substr(1, 1) == 'B') imgvalign = 'b';
	if (imgalign.substr(0, 1) == 'c' || imgalign.substr(0, 1) == 'C') imgalign = 'c';
	if (imgalign.substr(0, 1) == 'm' || imgalign.substr(0, 1) == 'M') imgalign = 'c';	// Service!
	if (imgalign.substr(0, 1) == 'r' || imgalign.substr(0, 1) == 'R') imgalign = 'r';
	if (imgalign != 'c' && imgalign != 'r') imgalign = 'l';

	var i;
	for (i = 0; i < nodes.length; i++){
		if (nodes[i].id == id && noalerts !== 1){
			alert("Duplicate node.\nNode " + (1 + nodes.length) + ", id = " + id + ", '" + text + "'\nAlready defined as node " + i + ", '" + nodes[i].txt + "'\n\nThis new node will not be added.\nNo additional messages are given.");
			noalerts = 1;
			return;
		}
	}
	var n = new Node(id, parent, ctype, text, bold, url, linecolor, fillcolor, textcolor, imgalign, imgvalign);
	if (img !== undefined){
		n.img = new Image();
		n.img.src = img;
		n.img.onload = function() {
			drawImageNodes();
		};
	}

	nodes[nodes.length] = n;
};

this.getNodes = function ()
{
	return nodes;
};


//////////////////////
// Public functions:
//////////////////////


this.setDebug = function (value)
{
	debug = value;
};

this.setSize = function (w, h, hspace, vspace, hshift)
{
	if (w      !== undefined && w > 0)	{ defaults.boxWidth  = w; }
	if (h      !== undefined && h > 0)	{ defaults.boxHeight = h; }
	if (hspace !== undefined && hspace > 0)	{ defaults.hSpace    = Math.max(3, hspace); }
	if (vspace !== undefined && vspace > 0)	{ defaults.vSpace    = Math.max(3, vspace); }
	if (hshift !== undefined && hshift > 0)	{ hShift    = Math.max(3, hshift); }
};

this.setFont = function (fname, size, color, valign)
{
	if (fname  !== undefined) { textFont   = fname; }
	if (size   !== undefined && size > 0) { textSize   = size; }
	if (color  !== undefined && color !== '') { textColor  = color; }
	if (valign !== undefined) { defaults.textVAlign = valign; }
	if (defaults.textVAlign === 'c' || defaults.textVAlign === 'center') { defaults.textVAlign = 1; }
};

this.setColor = function (l, f, t, c)
{
	if (l !== undefined && l !== '') { boxLineColor = l; }
	if (f !== undefined && f !== '') { boxFillColor = f; }
	if (t !== undefined && t !== '') { textColor    = t; }
	if (c !== undefined && c !== '') { lineColor    = c; }
};


this.drawChart = function (id, align, fit)
{
	// siblings may be added. Reset all positions first:
	
	var i;
	for (i = 0; i < nodes.length; i++){
		nodes[i].hpos = -1;
		nodes[i].vpos = -1;
		nodes[i].usib = [];
		nodes[i].rsib = [];
		nodes[i].lsib = [];
	}
	drawChartPriv(id, true, align, fit);
};

this.redrawChart = function (id)
{
	drawChartPriv(id, false);
};


this.getNodeByID = function (id)
{
	var i;
	for ( i = 0; i < nodes.length; i += 1) {
		if (nodes[i].id == id) {
			return nodes[i];
		}
	}
	return null;
};

this.getNodeByName = function (name)
{
	var i;
	for ( i = 0; i < nodes.length; i += 1) {
		if (nodes[i].txt.substr(0, name.length) === name) {
			return nodes[i];
		}
	}
	return null;
};

this.getNodeIndByName = function (name)
{
	var i;
	for ( i = 0; i < nodes.length; i += 1) {
		if (nodes[i].txt.substr(0, name.length) === name) {
			return i;
		}
	}
	return null;
};


this.getBoxSize = function ()
{
	var size = new Object();
	size.width = defaults.boxWidth;
	size.height = defaults.boxHeight;

	return size;
};

this.getClickEvent = function()
{
	return canvasClickEvent;
};

this.addColorBox = function( id, weight, score, color, tooltip )
{
	var b = new Object();

	b.weight = weight;
	b.score = score;
	b.color = color;
	b.tooltip = tooltip;
	b.x = 0;
	b.y = 0;
	b.vpos = 0;
	b.hpos = 0;
	
	if (color_boxes[id] == undefined) {
		color_boxes[id] = [];
	}
	color_boxes[id].push( b );
};

this.roundRect = function(ctx, x, y, width, height, radius, color) {
	if (typeof radius === "undefined") {
		radius = 5;
	}
	ctx.beginPath();
	ctx.moveTo(x + radius, y);
	ctx.lineTo(x + width - radius, y);
	ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
	ctx.lineTo(x + width, y + height - radius);
	ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
	ctx.lineTo(x + radius, y + height);
	ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
	ctx.lineTo(x, y + radius);
	ctx.quadraticCurveTo(x, y, x + radius, y);
	ctx.closePath();
	ctx.lineWidth = 1;
	ctx.strokeStyle = defaults.roundRectBorderColor;
	ctx.stroke();
	ctx.fillStyle = color;
	ctx.fill();
};

this.drawColorBox = function()
{
	var s, i, id, node_values, ctx = theCanvas.getContext("2d"), nodex;
	
	for(var ids in color_boxes) {
		node_values = color_boxes[ids];

		s = calc_target_widths( this.getBoxSize().width-6, node_values );
		id = parseInt(ids, 10);
		for (i=0; i < node_values.length; i++) {
      nodex = this.getNodeByID(id);
      if (nodex) {
  			color_boxes[ids][i].hpos = this.getNodeByID(id).hpos+3+s[i][0];
  			color_boxes[ids][i].vpos = this.getNodeByID(id).vpos+defaults.boxHeight - (defaults.colorBoxHeight+3);
  			color_boxes[ids][i].x = s[i][1];
  			color_boxes[ids][i].y =defaults.colorBoxHeight;

  			this.roundRect( ctx, color_boxes[ids][i].hpos, color_boxes[ids][i].vpos, color_boxes[ids][i].x, color_boxes[ids][i].y, 3, node_values[i].color );
      }
		}
		
	}
};


//////////////////////
// Internal functions:
//////////////////////

drawChartPriv = function (id, repos, align, fit)
{
	var	i, ctx, devicePixelRatio, backingStoreRatio, width, height, ratio, node_widths;

	theCanvas = document.getElementById(id);
	if (! theCanvas){
		alert("Canvas id '" + id + "' not found");
		return;
	}
        if (G_vmlCanvasManager !== undefined){ // ie IE
                G_vmlCanvasManager.initElement(theCanvas);
	}
	ctx = theCanvas.getContext("2d");

	// High dpi displays:  
	if ('devicePixelRatio' in window && theCanvas.width!=0) {
                devicePixelRatio = window.devicePixelRatio || 1;
                backingStoreRatio = ctx.webkitBackingStorePixelRatio ||
			ctx.mozBackingStorePixelRatio ||
			ctx.msBackingStorePixelRatio ||
			ctx.oBackingStorePixelRatio ||
			ctx.backingStorePixelRatio || 1;
		ratio = devicePixelRatio / backingStoreRatio;
		width = theCanvas.width;
		height = theCanvas.height;

		theCanvas.width = width * ratio;
		theCanvas.height = height * ratio;

		theCanvas.style.width = width + 'px';
		theCanvas.style.height = height + 'px';

		ctx.scale(ratio, ratio);
		debugOut("RESCALE: " + ratio);
	}

	ctx.lineWidth = 1;
	ctx.fillStyle = defaults.boxFillColor;
	ctx.strokeStyle = defaults.boxLineColor;
	
	node_widths = calcNodeDimension(ctx);
	
	if (defaults.boxWidth==0) defaults.boxWidth = node_widths.width + defaults.boxPadding;
	if (defaults.boxHeight==0) defaults.boxHeight = node_widths.lines * defaults.textSize + defaults.textSize + (node_widths.lines-1)*defaults.textPadding;
	
	if (defaults.boxHeightMin>0 && defaults.boxHeight<defaults.boxHeightMin) {
		defaults.boxHeight = defaults.boxHeightMin;
	}
	if (defaults.boxWidthMin>0 && defaults.boxWidth<defaults.boxWidthMin) {
		defaults.boxWidth = defaults.boxWidthMin;
	}
	if (repos){
		fillParentix();
		countSiblings();
		fillParentix();
		balance_nodes();
		positionBoxes();
		checkLines();
		reposParents();
		checkOverlap();
	}

	if (!fit){
		if (align === 'c' || align === 'center'){
			centerOnCanvas(theCanvas.width);
		}else{
			ctx = leftOnCanvas(theCanvas, ctx);
		}
	}


	if (fit){
		var	maxW = 0;
		var	maxH = 0;

		for (i = 0; i < nodes.length; i++){
			if (nodes[i].hpos > maxW) maxW = nodes[i].hpos;
			if (nodes[i].vpos > maxH) maxH = nodes[i].vpos;
		}

		if (maxW > 0 && maxH > 0 && maxW != theCanvas.width && maxH != theCanvas.height){
			theCanvas.width = maxW + defaults.boxWidth + defaults.shadowOffsetX;
			theCanvas.height = maxH + defaults.boxHeight + defaults.shadowOffsetY;
		}
	}
	// Draw the lines:
	drawConLines(ctx);

	// Draw the boxes:
	for (i = 0; i < nodes.length; i++){
		drawNode(ctx, i);
	}

	// Add click behaviour:
	if (theCanvas.addEventListener){
		theCanvas.removeEventListener("click", orgChartClick, false);	// If any old on this canvas, remove it
		theCanvas.addEventListener("click", orgChartClick, false);
		theCanvas.addEventListener("mousemove", orgChartMouseMove, false);
	} else if (theCanvas.attachEvent){ // IE
		theCanvas.onclick = function () {
			var mtarget = document.getElementById(id);
			orgChartClick(event, mtarget.scrollLeft, mtarget.scrollTop - 20);
		};
		theCanvas.onmousemove = function () {
			var mtarget = document.getElementById(id);
			orgChartMouseMove(event, mtarget.scrollLeft, mtarget.scrollTop - 20);
		};
	}
};

orgChartMouseMove = function(event)
{
	var x, y, i, xx, yy, tt, tto, rect, outside;

	x = xx = event.clientX;
	y = yy = event.clientY;

	x -= getAbsPosX(theCanvas);
	y -= getAbsPosY(theCanvas);

	if (document.documentElement && document.documentElement.scrollLeft){
		x += document.documentElement.scrollLeft;
	}else{
		x += document.body.scrollLeft;
	}
	if (document.documentElement && document.documentElement.scrollTop){
		y += document.documentElement.scrollTop;
	}else{
		y += document.body.scrollTop;
	}

	i = getNodeAt(x, y);
	if (i >= 0 && nodes[i].url.length > 0){
		document.body.style.cursor = 'pointer';
	}else{
		document.body.style.cursor = 'default';
	}
	
	tt = getColorBoxAt(x, y);
	tto = this.previousSibling;
  
	if (tto.className=='organization_target_tt' ) {
		if (tt.id>=0 && tt.tooltip!='') {
			x = tt.hpos;
			y = this.offsetTop+nodes[tt.id].vpos+defaults.boxHeight+7;

			tto.style.top = y+'px';
			tto.style.left = x+'px';
			tto.innerHTML = tt.tooltip;
			tto.style.display = 'block';
			rect = tto.getBoundingClientRect();
			// right
			outside = (rect.right) - (window.innerWidth || document.documentElement.clientWidth);
			if (outside>0) {
				tto.style.left = (x-outside-20)+'px';
			}
			// bottom
			outside = (rect.top+rect.height) - (window.innerHeight || document.documentElement.clientHeight);
			if (outside>0) {
				tto.style.top = ((nodes[i].vpos+getAbsPosY(theCanvas))-rect.height-5)+'px';
			}
		} else {
			tto.style.display = 'none';
		}
	}
	
};

orgChartClick = function(event, offsetx, offsety)
{
	var x, y, i, i1, i2, d;

	if (event.button < 0 || event.button > 1){
		return;	// left button (w3c: 0, IE: 1) only
	}

	canvasClickEvent = event;
	x = event.clientX;
	y = event.clientY;

        x -= getAbsPosX(theCanvas);
        y -= getAbsPosY(theCanvas);

	if (document.documentElement && document.documentElement.scrollLeft){
		x += document.documentElement.scrollLeft;
	}else{
		x += document.body.scrollLeft;
	}
	if (document.documentElement && document.documentElement.scrollTop){
		y += document.documentElement.scrollTop;
	}else{
		y += document.body.scrollTop;
	}

	i = getNodeAt(x, y);
	if (i >= 0){
		if (nodes[i].url.length > 0){
			document.body.style.cursor = 'default';
			if (nodes[i].url.substring(0,10)=='javascript') {
				eval(nodes[i].url);
			} else {
				i1 = nodes[i].url.indexOf('://');
				i2 = nodes[i].url.indexOf('/');
				if (i1 >= 0 && i2 > i1){
					window.open(nodes[i].url);
				} else {
					window.location = nodes[i].url;
				}
			}
		}
	}
};

vShiftUsibUnderParent = function(p, h, ymin)
{
	// Shift all usiblings with a vpos >= ymin down, except this parent.
	// ymin is optional
	if (ymin === undefined) { ymin = 0; }

	var s;

	for (s = 0; s < nodes[p].usib.length; s++){
		vShiftTree(nodes[p].usib[s], h, ymin);
	}
};

vShiftTree = function(p, h, ymin)
{
	// Shift all siblings 'h' down (if they have a position already)
	var s;

	if (nodes[p].vpos >= 0 && nodes[p].vpos >= ymin){
		nodes[p].vpos += h;
	}

	for (s = 0; s < nodes[p].usib.length; s++){
		vShiftTree(nodes[p].usib[s], h, ymin);
	}

	for (s = 0; s < nodes[p].lsib.length; s++){
		vShiftTree(nodes[p].lsib[s], h, ymin);
	}

	for (s = 0; s < nodes[p].rsib.length; s++){
		vShiftTree(nodes[p].rsib[s], h, ymin);
	}
};

hShiftTree = function(p, w)
{
	// Shift all siblings (which have a position already) 'w' pixels
	var s, d;

	debugOut("hShiftTree(" + nodes[p].txt + ", " + w + ")");
	d = debug;
	debug = 0;

	if (nodes[p].hpos >= 0){
		nodes[p].hpos += w;
	}

	for (s = 0; s < nodes[p].usib.length; s++){
		hShiftTree(nodes[p].usib[s], w);
	}

	for (s = 0; s < nodes[p].lsib.length; s++){
		hShiftTree(nodes[p].lsib[s], w);
	}

	for (s = 0; s < nodes[p].rsib.length; s++){
		hShiftTree(nodes[p].rsib[s], w);
	}

	debug = d;
};

hShiftTreeAndRBrothers = function(p, w)
{
	// Shift this tree to the right.
	// If this is an 'u' sib, also shift all brothers which are to the right too.
	// (In which case we shift all other root nodes too).
	var i, q, s, hpos, hpos2, rp, d;

	debugOut("hShiftTreeAndRBrothers(" + nodes[p].txt + ", " + w + ")");
	d = debug;
	debug = 0;

	hpos = nodes[p].hpos;
	rp = getRootNode(p);
	hpos2 = nodes[rp].hpos;

	if (nodes[p].contype === 'u' && nodes[p].parent !== ''){
		q = nodes[p].parentix;
		for (s = nodes[q].usib.length - 1; s >= 0; s--){
			hShiftTree(nodes[q].usib[s], w);
			if (nodes[q].usib[s] === p){
				break;
			}
		}
	}else{
		hShiftTree(p, w);
	}

	if (nodes[p].contype === 'u'){
		for (i = 0; i < nodes.length; i++){
			if (i !== rp && nodes[i].parent === '' && nodes[i].hpos > hpos2){
				hShiftTree(i, w);
			}
		}
	}

	debug = d;
};

fillParentix = function()
{
	// Fill all nodes with the index of the parent.
	var i, j;
	for (i = 0; i < nodes.length; i++){
		if (nodes[i].parent !== ''){
			for (j = 0; j < nodes.length; j++){
				if (nodes[i].parent === nodes[j].id){
					nodes[i].parentix = j;
					break;
				}
			}
			if (nodes[i].parentix === -1){
				debugOut("Node " + nodes[i].id + " has an invalid parent '" + nodes[i].parent + "'");
				nodes[i].parent = '';
				//nodes[i].txt += ' (unknown parent)';
			}
		}
	}
};

checkLines = function()
{
	// Check all vertical lines for crossing boxes. If so, shift to the right.
	var p;

	debugOut("checkLines()");

	for (p = 0; p < nodes.length; p++){
		if (nodes[p].parent === ''){
			checkLinesRec(p);
		}
	}
};

checkLinesRec = function(p)
{
	var s, y, y2, n, m, i, rp, rs, w, branch, tm;

	y = 0;

	// Check lsib, the latest is the lowest point:
	n = nodes[p].lsib.length;
	if (n > 0){
		s = nodes[p].lsib[n-1];
		y = nodes[s].vpos + defaults.boxHeight / 2;
	}

	// Check rsib, the latest is the lowest point:
	n = nodes[p].rsib.length;
	if (n > 0){
		s = nodes[p].rsib[n-1];
		y2 = nodes[s].vpos + defaults.boxHeight / 2;
		y = Math.max(y, y2);
	}

	// If usib, the lowest point is even lower:
	n = nodes[p].usib.length;
	if (n > 0){
		s = nodes[p].usib[0];
		y = nodes[s].vpos - defaults.vSpace / 2;
	}

	if (y > 0){
		for (n = nodes[p].vpos + defaults.boxHeight / 2 + defaults.boxHeight + defaults.vSpace; n <= y; n += defaults.boxHeight + defaults.vSpace){
			m = 0;
			do{
				s = getNodeAt(nodes[p].hpos + defaults.boxWidth / 2 - 5, n);
				if (s >= 0){
					debugOut("Overlap between a downline of '" + nodes[p].txt + "' at point (" + (nodes[p].hpos + defaults.boxWidth / 2) + ", " + n + ") and node '" + nodes[s].txt + "' (" + nodes[s].hpos + ", " + nodes[s].vpos + ")");
					// If the node found is a sib of the box with the downline, shifting the parent doesn't help:
					w =  nodes[s].hpos + defaults.boxWidth + defaults.hSpace - (nodes[p].hpos + defaults.boxWidth / 2);
					rp = s;
					i = 0;
					while (nodes[rp].parent !== '' && rp !== p){
						rp = nodes[rp].parentix;
					}
					if (rp !== p){
						rs = s;
						while (nodes[rs].parent !== '' && nodes[rs].contype !== 'u'){
							rs = nodes[rs].parentix;
						}
						rp = p;
						while (nodes[rp].parent !== '' && nodes[rp].contype !== 'u'){
							rp = nodes[rp].parentix;
						}
						if (nodes[rs].hpos > nodes[p].hpos){
							w =  nodes[p].hpos + defaults.boxWidth / 2 + defaults.hSpace - nodes[s].hpos;
							hShiftTreeAndRBrothers(rs, w);
						}else{
							hShiftTreeAndRBrothers(rp, w);
						}
					}else{
						debugOut("Overlap within the same subtree");

						branch = nodes[s].contype;
						tm = s;
						while (nodes[tm].parentix !== '' && nodes[tm].parentix !== p){
							tm = nodes[tm].parentix;
						}
						branch = nodes[tm].contype;

						debugOut("Make room: branchtype = " + branch + ", tomove: " + nodes[tm].txt);

						rs = getRootNode(s);
						rp = getRootNode(p);
						if (rs === rp){
							if (branch === 'l'){
								w =  nodes[s].hpos + defaults.boxWidth + defaults.hSpace - (nodes[p].hpos + defaults.boxWidth / 2);
								hShiftTreeAndRBrothers(p, w);
								hShiftTree(tm, -w);
							}else{
								w =  (nodes[p].hpos + defaults.boxWidth / 2) - nodes[s].hpos + defaults.hSpace;
								hShiftTreeAndRBrothers(tm, w);
							}
						}else{
							if (nodes[rp].hpos > nodes[rs].hpos){
								hShiftTree(rp, w);
							}else{
								hShiftTree(rs, w);
							}
						}
					}
				}
				m++;
			} while (s >= 0 && m < maxLoop);
		}
	}

	// Check the siblings:
	for (s = 0; s < nodes[p].usib.length; s++){
		checkLinesRec(nodes[p].usib[s]);
	}
	for (s = 0; s < nodes[p].lsib.length; s++){
		checkLinesRec(nodes[p].lsib[s]);
	}
	for (s = 0; s < nodes[p].rsib.length; s++){
		checkLinesRec(nodes[p].rsib[s]);
	}
};

checkOverlap = function ()
{
	var	i, j, retry, m, ui, uj, w;

	debugOut("CheckOverlap()");

	// Boxes direct on top of another box?
	m = 0;
	retry = 1;
	
	while (m < maxLoop && retry){
		retry = 0;
		m++;
		for (i = 0; i < nodes.length; i++){
			for (j = i + 1; j < nodes.length; j++){
				if (nodes[i].hpos === nodes[j].hpos && nodes[i].vpos === nodes[j].vpos){
					debugOut("Complete overlap in node '" + nodes[i].txt + "' and '" + nodes[j].txt);
					ui = getRootNode(i);
					uj = getRootNode(j);
					if (ui !== uj){
						hShiftTreeAndRBrothers(uj, defaults.boxWidth + defaults.hSpace);
					}else{
						ui = getUParent(i);
						uj = getUParent(j);
						if (ui !== uj){
							hShiftTreeAndRBrothers(uj, defaults.boxWidth + defaults.hSpace);
						}else{
							// In the right subtree, find the first 'u' or 'r' parent to shift.
							uj = j;
							while (nodes[uj].parent !== '' && nodes[uj].contype !== 'u' && nodes[uj].contype !== 'r'){
								uj = nodes[uj].parentix;
							}
							if (nodes[uj].parent !== ''){
								hShiftTreeAndRBrothers(uj, defaults.boxWidth + defaults.hSpace);
							}else{
								debugOut("There is nothing I can do about this, sorry");
							}
						}
					}
					retry = 1;
				}
			}
		}
	}

	// Small overlap?
	m = 0;
	retry = 1;
	while (m < maxLoop && retry){
		retry = 0;
		m++;
		for (i = 0; i < nodes.length; i++){
			j = getNodeAtUnequal(nodes[i].hpos - 5, nodes[i].vpos + defaults.boxHeight / 2, i);
			if (j >= 0){
				debugOut("Overlap in node '" + nodes[i].txt + "' and '" + nodes[j].txt);
				ui = getUParent(i);
				uj = getUParent(j);
				if (ui !== uj){
					if (nodes[ui].hpos > nodes[uj].hpos){
						uj = ui;
					}
					if (nodes[i].hpos > nodes[j].hpos){
						w = nodes[j].hpos - nodes[i].hpos + defaults.boxWidth + defaults.hSpace;
					}else{
						w = nodes[i].hpos - nodes[j].hpos + defaults.boxWidth + defaults.hSpace;
					}
					if (nodeUnderParent(i, ui) && nodeUnderParent(j, ui)){
						j = i;
						while (j >= 0 && nodes[j].contype === nodes[i].contype){
							j = nodes[j].parentix;
						}
						if (j >= 0){
							hShiftTreeAndRBrothers(j, w);
						}
					}else{
						while (nodes[ui].parent !== '' && nodes[ui].contype === 'u' && nodes[nodes[ui].parentix].usib.length === 1){
							ui = nodes[ui].parentix;
						}
						hShiftTreeAndRBrothers(ui, w);
					}
					retry = 1;
				}else{
					hShiftTreeAndRBrothers(i, defaults.boxWidth / 2);
					retry = 1;
				}
			}
		}
	}
};

balance_nodes = function()
{
	var left_pos, sid, lsib, rsib, usib, bsib, siblings, lrsib, s, n, sn;
	
	for (n=0; n < nodes.length; n++) {
		siblings = []; // All siblings
		lsib = []; // Left siblings
		rsib = []; // Right siblings
		usib = []; // Under siblings
		bsib = []; // Siblings to be balanced
		
		siblings = nodes[n].lsib.concat(nodes[n].rsib).concat(nodes[n].usib);
		
		for (s=0; s < siblings.length; s++) {
			sid = siblings[s];
			
			switch(nodes[sid].contype) {
				case 'l':
					lsib.push(sid);
				  	break;
				case 'r':
					rsib.push(sid);
				  	break;
				case 'u':
					usib.push(sid);
				  	break;
				case 'a':
					if (nodes[sid].lsib.length+nodes[sid].rsib.length+nodes[sid].usib.length>0) {
						usib.push(sid);
						nodes[sid].contype = 'u';
					} else {
						bsib.push(sid);
					}
				  	break;
			}			
		};
		if (bsib.length>0) {
			if (defaults.autobalance==='r') {
				for (s=0; s < bsib.length; s++) {
					nodes[bsib[s]].contype = 'r';
					rsib.push(bsib[s]);
				}
			}
			if (defaults.autobalance==='l') {
				for (s=0; s < bsib.length; s++) {
					nodes[bsib[s]].contype = 'l';
					lsib.push(bsib[s]);
				}
			}
			if (defaults.autobalance!=='l' && defaults.autobalance!=='r') {
				for (s=0; s < bsib.length; s++) {
					if (lsib.length <= rsib.length) {
						nodes[bsib[s]].contype = 'l';
						lsib.push(bsib[s]);
					} else {
						nodes[bsib[s]].contype = 'r';
						rsib.push(bsib[s]);
					}
				}
			}
		
			nodes[n].usib = usib.sort();
			nodes[n].lsib = lsib.sort();
			nodes[n].rsib = rsib.sort();
		}
	};
};

countSiblings = function()
{
	var i, p, h, v;

	for (i = 0; i < nodes.length; i++){
		p = nodes[i].parentix;
		if (p >= 0){
			if (nodes[i].contype === 'u' || nodes[i].contype === 'a'){
				h = nodes[p].usib.length;
				nodes[p].usib[h] = i;
			}
			if (nodes[i].contype === 'l'){
				v = nodes[p].lsib.length;
				nodes[p].lsib[v] = i;
			}
			if (nodes[i].contype === 'r'){
				v = nodes[p].rsib.length;
				nodes[p].rsib[v] = i;
			}
		}
	}
};

positionBoxes = function()
{
	var i, x;

	debugOut("positionBoxes()");

	// Position all top level boxes:
	// The starting pos is 'x'. After the tree is positioned, center it.
	x = 0;
	for (i = 0; i < nodes.length; i++){
		if (nodes[i].parent === ''){
			//nodes[i].hpos = x + defaults.hSpace;
			//nodes[i].vpos = defaults.vSpace;
			nodes[i].hpos = x + defaults.shadowOffsetX;
			nodes[i].vpos = 0 + defaults.shadowOffsetY;
			positionTree(i, x, x);
			// hpos can be changed during positionTree:
			x = findRightMost(i) + defaults.boxWidth;	// Start for next tree
		}
	}
};

positionTree = function(p)
{
	// Position the complete tree under this parent.
	var h, v, s, o, n, w, q, r, us, uo, x, maxx, minx, x1, x2, y;

	debugOut("positionTree(" + nodes[p].txt + ", " + nodes[p].hpos + ", " + nodes[p].vpos + ")");
	// p has a position already. Position 'l', 'r' and 'u' sibs:

	// Positioning all 'l' sibs:
	for (v = 0; v < nodes[p].lsib.length; v++){
		s = nodes[p].lsib[v];
		// New lsib, so the downline crosses all the way down. Make room first:
		y = getLowestBox(p, "l") + defaults.boxHeight + defaults.vSpace;
		makeRoomForDownline(p, y);

		nodes[s].hpos = nodes[p].hpos - defaults.boxWidth / 2 - defaults.hShift;
		nodes[s].vpos = y;
		if (nodes[s].hpos < 0){
			for (r = 0; r < nodes.length; r++){
				if (nodes[r].parent === ''){
					hShiftTree(r, -nodes[s].hpos);
				}
			}
			nodes[s].hpos = 0;
		}

		// Overlap?
		n = 1;
		do{
			o = getNodeAtUnequal(nodes[s].hpos - 5, nodes[s].vpos + 5, s);
			if (o < 0){
				o = getNodeAtUnequal(nodes[s].hpos + defaults.boxWidth + 5, nodes[s].vpos + 5, s);
			}
			if (o < 0){
				o = findNodeOnLine(nodes[s].vpos, 999999, 'l');
				if (o === s){
					o = -1;
				}
			}
			if (o >= 0){
				debugOut("New lsib '" + nodes[s].txt + "' (" + nodes[s].hpos + ", " + nodes[s].vpos + ") has overlap with existing node '" + nodes[o].txt + "' (" + nodes[o].hpos + ", " + nodes[o].vpos + ")");
				h = nodes[s].hpos - nodes[o].hpos;
				h = Math.abs(h);
				q = nodes[s].parentix;
				w = nodes[o].hpos + defaults.boxWidth + defaults.hSpace - nodes[s].hpos;
				if (nodes[o].contype === 'l') w += defaults.hSpace;
				while (q !== -1 && nodes[q].contype !== 'u') { q = nodes[q].parentix; }
				if (q < 0){
					hShiftTree(p, w);
				}else{
					if (! nodeUnderParent(o, q)){
						hShiftTreeAndRBrothers(q, w);	// ! 2*w, dd 2013-10-21
					}else{
						debugOut("Same parent, do not shift");
					}
				}
			}
			n++;
			if (n > maxLoop){
				o = -1;
			}
		} while (o >= 0);
		positionTree(s);
	}

	// Positioning all rsibs:
	for (v = 0; v < nodes[p].rsib.length; v++){
		s = nodes[p].rsib[v];
		nodes[s].hpos = nodes[p].hpos + defaults.boxWidth / 2 + defaults.hShift;
		nodes[s].vpos = getLowestBox(p, "r") + defaults.boxHeight + defaults.vSpace;
		// Overlap?
		n = 1;
		do{
			o = getNodeAtUnequal(nodes[s].hpos - 5, nodes[s].vpos + 5, s);
			if (o < 0){
				o = getNodeAtUnequal(nodes[s].hpos + defaults.boxWidth + 5, nodes[s].vpos + 5, s);
			}
			if (o < 0){
				o = findNodeOnLine(nodes[s].vpos, 999999, 'l');
				if (o === s){
					o = -1;
				}
			}
			if (o >= 0){
				debugOut("New rsib '" + nodes[s].txt + "' (" + nodes[s].hpos + ", " + nodes[s].vpos + ") has overlap with existing node '" + nodes[o].txt + "' (" + nodes[o].hpos + ", " + nodes[o].vpos + ")");
				h = nodes[s].hpos - nodes[o].hpos;
				h = Math.abs(h);
				q = nodes[s].parentix;
				while (q !== -1 && nodes[q].contype !== 'u') { q = nodes[q].parentix; }
				if (q < 0){
					hShiftTree(p, defaults.boxWidth + defaults.hSpace - h);
				}else{
					us = getUParent(s);
					uo = getUParent(o);
					if (us === uo){
						if (! nodeUnderParent(o, q)){
							hShiftTreeAndRBrothers(q, defaults.boxWidth + defaults.hSpace - h);
						}else{
							// Shift parent if overlap with lsib of our parent
							debugOut("Same parent, do not shift");
						}
					}else{
						// Shift the common parent (if any) to the right, and the uppermost parent of the existing o node back to the left:
						us = getRootNode(s);
						uo = getRootNode(o);
						w = nodes[o].hpos - nodes[s].hpos + defaults.boxWidth + defaults.hSpace;
						if (us === uo){
							debugOut("Common root = " + nodes[us].txt);
							us = s;
							while (nodes[us].parent != '' && ! nodeUnderParent(o, us)){
								us = nodes[us].parentix;
							}
							debugOut("Highest not common u-parent = " + nodes[us].txt);
							hShiftTreeAndRBrothers(us, w);
						}else{
							hShiftTreeAndRBrothers(s, w);
						}
					}
				}
			}
			n++;
			if (n > maxLoop){
				o = -1;
			}
		} while (o >= 0);
		positionTree(s);
	}

	// Make room for the downline (if necessary):
	v = getEndOfDownline(p);
	if (v > 0){
		debugOut("Make room for the downline, from (" + (nodes[p].hpos + defaults.boxWidth / 2) + ", " + (nodes[p].vpos + defaults.boxHeight) + ") to (" + (nodes[p].hpos + defaults.boxWidth / 2) + ", " + v + ")");
		// Check 'l' sibs first
		if (nodes[p].lsib.length > 0){
			maxx = -1;
			for (h = 0; h < nodes[p].lsib.length; h++){
				x = findRightMost(nodes[p].lsib[h], v);
				maxx = Math.max(x, maxx);
			}
			w = maxx + defaults.boxWidth / 2 + defaults.hSpace - nodes[p].hpos;
			if (w > 0){
				debugOut("Make room -> Shift Tree and rsibs over " + w);
				nodes[p].hpos += w;
				for (h = 0; h < nodes[p].rsib.length; h++){
					hShiftTree(nodes[p].rsib[h], w);
				}
			}
		}

		// Check 'r' sibs now
		if (nodes[p].rsib.length > 0){
			minx = 999999;
			for (h = 0; h < nodes[p].rsib.length; h++){
				x = findLeftMost(nodes[p].rsib[h], v);
				minx = Math.min(x, minx);
			}
			w = nodes[p].hpos + defaults.boxWidth / 2 + defaults.hSpace - minx;
			if (w > 0){
				debugOut("Make room -> Shift rsibs over " + w);
				for (h = 0; h < nodes[p].rsib.length; h++){
					hShiftTree(nodes[p].rsib[h], w);
				}
			}
		}
	}

	// 'u' sibs:
	x1 = nodes[p].hpos;
	x2 = nodes[p].hpos;
	v = getLowestBox(p, "lr") + defaults.boxHeight + defaults.vSpace;
	n = nodes[p].usib.length;
	// Maybe we can shift the u-nodes under the subtree to the left.
	if (n >= 2 && x1 > 0){
		// Check all node on this vpos to overlap.
		// Maybe we overlap a downline, this will be caught later on.
		h = findNodeOnLine(v, nodes[p].hpos, 'l');
		if (h < 0){
			x2 = x2 + defaults.boxWidth / 2 - (n * defaults.boxWidth + (n-1) * defaults.hSpace) / 2;
			if (x2 < 0){
				x2 = 0;
			}
			x1 = x2;
		}
		if (h >= 0 && nodes[h].hpos + defaults.boxWidth + defaults.hSpace < x1){
			x1 = nodes[h].hpos + defaults.boxWidth + defaults.hSpace;				// minimum x
			x2 = x2 + defaults.boxWidth / 2 - (n * defaults.boxWidth + (n-1) * defaults.hSpace) / 2;	// wanted
			if (x1 > x2){
				x2 = x1;
			}else{
				x1 = x2;
			}
		}
	}

	for (h = 0; h < nodes[p].usib.length; h++){
		s = nodes[p].usib[h];
		nodes[s].hpos = x2;
		nodes[s].vpos = getLowestBox(p, "lr") + defaults.boxHeight + defaults.vSpace;
		v = underVSib(s);
		// Overlap?
		n = 0;
		do{
			o = getNodeAtUnequal(nodes[s].hpos - 5, nodes[s].vpos + 5, s);
			if (o < 0){
				o = getNodeAtUnequal(nodes[s].hpos + defaults.boxWidth + 5, nodes[s].vpos + 5, s);
			}
			if (o < 0){
				o = findNodeOnLine(nodes[s].vpos, 999999, 'l');
				if (o === s){
					o = -1;
				}
			}
			if (o >= 0){
				debugOut("New usib '" + nodes[s].txt + " at (" + nodes[s].hpos + ", " + nodes[s].vpos + ")' has overlap with existing node '" + nodes[o].txt + "' at (" + nodes[o].hpos + ", " + nodes[o].vpos + ")");
				w = nodes[o].hpos - nodes[s].hpos + defaults.boxWidth + defaults.hSpace;
				// Find the highest node, not in the path of the found 'o' node:
				us = s;
				while (nodes[us].parent != '' && !nodeUnderParent(o, nodes[us].parentix)){
					us = nodes[us].parentix;
				}
				debugOut("Highest found = " + nodes[us].txt);
				hShiftTreeAndRBrothers(us, w);
			}
			n++;
			if (n > maxLoop){
				o = -1;
			}
		} while (o >= 0);
		positionTree(s);
		x2 = nodes[s].hpos + defaults.boxWidth + defaults.hSpace;
	}

	reposParentsRec(p);
};

reposParents = function()
{
	// All parents with usibs are repositioned (start at the lowest level!)
	var i;

	debugOut("reposParents()");

	for (i = 0; i < nodes.length; i++){
		if (nodes[i].parentix === -1){
			reposParentsRec(i);
		}
	}
};

reposParentsRec = function(p)
{
	var w, s, f, h, hpos, r, maxw, minw, d;

	debugOut("reposParentsRec(" + nodes[p].txt + ")");
	d = debug;
	debug = 0;

	hpos = nodes[p].hpos;

	// The sibslings first:
	for (s = 0; s < nodes[p].usib.length; s++){
		reposParentsRec(nodes[p].usib[s]);
	}
	for (s = 0; s < nodes[p].lsib.length; s++){
		reposParentsRec(nodes[p].lsib[s]);
	}
	for (s = 0; s < nodes[p].rsib.length; s++){
		reposParentsRec(nodes[p].rsib[s]);
	}

	// If this is a parent with two or more usibs, reposition it:
	// (Repos over 1 u sib too, just correct it if necessary)
	// Except if this is a sib, without room to move, limit the room to move.
	// Of course a r-sib of this sib can cause an overlap too.
	h = nodes[p].usib.length;
	if (h >= 1){
		debugOut("repos " + nodes[p].txt);
		maxw = -1;
		minw = -1;
		if (nodes[p].contype == 'l'){
			r = nodes[p].parentix;
			maxw = nodes[r].hpos + defaults.boxWidth / 2 - defaults.boxWidth - defaults.hSpace - nodes[p].hpos;
		}
		if (nodes[p].contype == 'r'){
			r = nodes[p].parentix;
			minw = nodes[r].hpos + defaults.boxWidth / 2 - defaults.hSpace - defaults.boxWidth - nodes[p].hpos;
		}
		w = 0;
		if (centerParentOverCompleteTree){
			w = (findRightMost(p) - nodes[p].hpos) / 2;
		}else{
			f = nodes[p].usib[0];
			s = nodes[p].usib[h-1];
			w = nodes[f].hpos + (nodes[s].hpos - nodes[f].hpos) / 2 - nodes[p].hpos;
		}
		if (maxw >= 0 && w > maxw){
			w = maxw;
		}
		if (minw >= 0 && w > minw){
			w = minw;
		}
		s = findNodeOnLine(nodes[p].vpos, nodes[p].hpos, 'r');
		if (s >= 0){
			if (nodes[p].hpos + defaults.boxWidth + defaults.hSpace + w >= nodes[s].hpos){
				w = nodes[s].hpos - defaults.boxWidth - defaults.hSpace - nodes[p].hpos;
			}
		}
		if (nodes[p].usib.length == 1 && nodes[p].hpos + w != nodes[nodes[p].usib[0]].hpos){
			debugOut(nodes[p].txt + " is a parent with 1 usib and not enough room to move, so move complete tree");
			debugOut("Need: " + (nodes[nodes[p].usib[0]].hpos - nodes[p].hpos) + ", room to move: " + w + " (reset)");
			w = nodes[nodes[p].usib[0]].hpos - nodes[p].hpos;
		}
		if (w > 1){
			// Shift this nodes and all 'l' and 'r' sib trees
			nodes[p].hpos += w;
			for (s = 0; s < nodes[p].lsib.length; s++){
				hShiftTree(nodes[p].lsib[s], w);
			}
			for (s = 0; s < nodes[p].rsib.length; s++){
				hShiftTree(nodes[p].rsib[s], w);
			}
		}
	}

	debug = d;
};

findRightMost = function(p, maxv)
{
	// return the highest hpos of the given tree, if maxv is specified, vpos must be less than maxv:
	var maxx, x, i;

	if (maxv === undefined){ maxv = 999999; }

	if (nodes[p].vpos <= maxv){
		maxx = nodes[p].hpos;
	}else{
		maxx = -1;
	}

	// usib to the right?
	for (i = 0; i < nodes[p].usib.length; i++){
		x = findRightMost(nodes[p].usib[i], maxv);
		maxx = Math.max(x, maxx);
	}

	// Walk along the lsibs:
	for (i = 0; i < nodes[p].lsib.length; i++){
		x = findRightMost(nodes[p].lsib[i], maxv);
		maxx = Math.max(x, maxx);
	}

	// Walk along the rsibs:
	for (i = 0; i < nodes[p].rsib.length; i++){
		x = findRightMost(nodes[p].rsib[i], maxv);
		maxx = Math.max(x, maxx);
	}

	return maxx;
};

findLeftMost = function(p, maxv)
{
	// return the lowest hpos of the given tree:
	var minx, x, i;

	if (maxv === undefined){ maxv = 999999; }

	if (nodes[p].vpos <= maxv){
		minx = nodes[p].hpos;
	}else{
		minx = 999999;
	}

	// usib to the left?
	if (nodes[p].usib.length > 0){
		x = findLeftMost(nodes[p].usib[0], maxv);
		minx = Math.min(x, minx);
	}

	// Walk along the lsibs:
	for (i = 0; i < nodes[p].lsib.length; i++){
		x = findLeftMost(nodes[p].lsib[i], maxv);
		minx = Math.min(x, minx);
	}

	// Walk along the rsibs:
	for (i = 0; i < nodes[p].rsib.length; i++){
		x = findLeftMost(nodes[p].rsib[i], maxv);
		minx = Math.min(x, minx);
	}

	return minx;
};

findNodeOnLine = function(v, h, dir)
{
	// Search all nodes on vpos 'v', and return the rightmost node on the left, or the leftmost on the rest,
	// depending on the direction.
	var	i, fnd, x;

	fnd = -1;
	x = (dir === 'l')? -1 : 999999;

	for (i = 0; i < nodes.length; i++){
		if (nodes[i].vpos === v){
			if (dir === 'l' && nodes[i].hpos < h && nodes[i].hpos > x){
				fnd = i;
				x = nodes[i].hpos;
			}
			if (dir === 'r' && nodes[i].hpos > h && nodes[i].hpos < x){
				fnd = i;
				x = nodes[i].hpos;
			}
		}
	}

	return fnd;
};

drawImageNodes = function()
{
	// Images are loaded after drawing finished.
	// After an image has been loaded, this function will be called, which redraws the nodes
	// with images nodes, have a valid image now and are drawn incomplete before.
	var	i, ctx;

	ctx = theCanvas.getContext("2d");

	for (i = 0; i < nodes.length; i++){
		if (nodes[i].img && nodes[i].img.width > 0 && ! nodes[i].imgDrawn){
			drawNode(ctx, i);
		}
	}
};

strip_HTML = function( text )
{
	text = text.replace("<b>", "");
	text = text.replace("</b>", "");
	text = text.replace("<i>", "");
	text = text.replace("</i>", "");

	return text;
};

is_bold = function( text )
{
	return (text.substr(0,3)=='<b>' && text.substr(-4)=='</b>');
};

is_italic = function( text )
{
	return (text.substr(0,3)=='<i>' && text.substr(-4)=='</i>');
};

calcNodeDimension = function(ctx)
{
	var i, lines = [], ii, font, ix, width, max_lines=0, max_width=0, style, has_bold, has_italic, font_style, txt, fsize, r, line_styles, txt_clean;
	
	for (i=0; i < nodes.length; i++) {
		font	= nodes[i].textfont;
		fsize	= nodes[i].textsize;
		
		lines = nodes[i].txt.split("\n");
		max_lines = lines.length > max_lines ? lines.length : max_lines;
		nodes[i].line_styles = [];
		for (ii=0; ii < lines.length; ii++) {
			txt = lines[ii];
			
			style = '';
			has_bold = false;
			has_italic = false;
			
			font = font.toLowerCase();
			ix = font.indexOf("bold ");
			if (ix >= 0){
				font = font.substr(0, ix) + font.substr(ix + 5);
				style = "bold ";
				has_bold = true;
			}
			ix = font.indexOf("italic ");
			if (ix >= 0){
				font = font.substr(0, ix) + font.substr(ix + 5);
				style += "italic ";
				has_italic = true;
			}
			ctx.font = style + fsize + "px " + font;

			font_style = ctx.font;
			if (is_bold(txt) && !has_bold) {
				ctx.font = 'bold '+font_style;
				has_bold = true;
			}
			if (is_italic(txt) && !has_italic) {
				ctx.font = 'italic '+font_style;
				has_italic = true;
			}
			txt_clean = strip_HTML(txt);
			width = ctx.measureText(txt_clean).width;
			if (defaults.boxWidth!=0 && width>defaults.boxWidth) {
				while( txt_clean!='' && ctx.measureText(txt_clean+'…').width > (defaults.boxWidth-defaults.boxPadding) ) {
					txt_clean = txt_clean.substr(0,txt_clean.length-1);
				}
				lines[ii]=txt_clean+'…';
			} else {
				lines[ii] = txt_clean;
			}
			max_width = width>max_width ? width : max_width;
			ctx.font = font_style;
			
			line_styles = new Object();
			line_styles.bold = has_bold;
			line_styles.italic = has_italic;
			nodes[i].line_styles.push(line_styles);
		}
		nodes[i].txt_lines = lines;
	};
	r = new Object();
	r.lines = max_lines;
	r.width = Math.ceil(max_width);
	
	return r;
};


drawNode = function(ctx, i)
{
	var	ix, gradient;
	var	x	= nodes[i].hpos;
	var	y	= nodes[i].vpos;
	var	width	= defaults.boxWidth;
	var	height	= defaults.boxHeight;
	var	txt	= nodes[i].txt;
	var	bold	= nodes[i].bold;
	var	blcolor	= nodes[i].linecolor;
	var	bfcolor	= nodes[i].fillcolor;
	var	tcolor	= nodes[i].textcolor;
	var	font	= nodes[i].textfont;
	var	fsize	= nodes[i].textsize;
	var	valign	= nodes[i].valign;
	var	img	= nodes[i].img;
	var	imgalign = nodes[i].imgAlign;
	var	imgvalign = nodes[i].imgVAlign;

	// Draw shadow with gradient first:
	x += defaults.shadowOffsetX;
	y += defaults.shadowOffsetY;
	ctx.fillStyle = defaults.shadowColor;
	ctx.beginPath();
	ctx.moveTo(x + defaults.radius, y);
	ctx.lineTo(x + width - defaults.radius, y);
	ctx.quadraticCurveTo(x + width, y, x + width, y + defaults.radius);
	ctx.lineTo(x + width, y + height - defaults.radius);
	ctx.quadraticCurveTo(x + width, y + height, x + width - defaults.radius, y + height);
	ctx.lineTo(x + defaults.radius, y + height);
	ctx.quadraticCurveTo(x, y + height, x, y + height - defaults.radius);
	ctx.lineTo(x, y + defaults.radius);
	ctx.quadraticCurveTo(x, y, x + defaults.radius, y);
	ctx.closePath();
	ctx.fill();
	x -= defaults.shadowOffsetX;
	y -= defaults.shadowOffsetY;

	// Draw the box:
	ctx.lineWidth = (bold) ? 2 : 1;
	gradient = ctx.createLinearGradient(x, y, x, y + height);
	gradient.addColorStop(0,  '#FFFFFF');
	gradient.addColorStop(0.7,  bfcolor);
	ctx.fillStyle = gradient;
	ctx.strokeStyle = blcolor;
	ctx.beginPath();
	ctx.moveTo(x + defaults.radius, y);
	ctx.lineTo(x + width - defaults.radius, y);
	ctx.quadraticCurveTo(x + width, y, x + width, y + defaults.radius);
	ctx.lineTo(x + width, y + height - defaults.radius);
	ctx.quadraticCurveTo(x + width, y + height, x + width - defaults.radius, y + height);
	ctx.lineTo(x + defaults.radius, y + height);
	ctx.quadraticCurveTo(x, y + height, x, y + height - defaults.radius);
	ctx.lineTo(x, y + defaults.radius);
	ctx.quadraticCurveTo(x, y, x + defaults.radius, y);
	ctx.closePath();
	ctx.fill();
	ctx.stroke();

	// Draw the image, if any:
	// If the image is available, draw.
	// Mark it incomplete otherwise.
	var xPic, yPic, maxx, maxy;
	if (img){
		// Get all positions and sizes, even if no image loaded yet:
		if (img.width > 0){
			maxx = img.width;
			maxy = img.height;

			// Resize if image too height:
			if (maxy > height - 2 * defaults.radius){
				maxx = img.width * (height - 2 * defaults.radius) / img.height;
				maxy = height - 2 * defaults.radius;
			}

			// Resize if image too width, even after previous resize:
			if (maxx > width - 2 * defaults.radius){
				maxy = img.height * (width - 2 * defaults.radius) / img.width;
				maxx = width - 2 * defaults.radius;
			}
		}else{
			if (width > height){
				maxy = height - 2 * defaults.radius;
			}else{
				maxy = width - 2 * defaults.radius;
			}
			maxx = maxy;
		}

		// Horizontal offset:
		xPic = defaults.radius;
		if (imgalign == 'c') xPic = (width - 2 * defaults.radius - maxx) / 2 + defaults.radius;
		if (imgalign == 'r') xPic = width - maxx - defaults.radius;

		// Vertical offset:
		yPic = defaults.radius;
		if (imgvalign == 'm') yPic = (height - maxy) / 2;
		if (imgvalign == 'b') yPic = height - maxy - 1;

		if (img.width > 0){
			ctx.drawImage(img, x + xPic, y + yPic, maxx, maxy);
			nodes[i].imgDrawn = 1;
		}else{
			// Draw an image-not-found picture
			if (maxy > 0){
				ctx.beginPath();
				ctx.rect(x + xPic, y + yPic, maxx, maxy);
				ctx.fillStyle = '#FFFFFF';
				ctx.fill();
				ctx.lineWidth = 1;
				ctx.strokeStyle = '#000000';
				ctx.stroke();

				ctx.beginPath();
				ctx.moveTo(x + xPic + 1, y + yPic + 1);
				ctx.lineTo(x + xPic + maxx, y + maxy);
				ctx.strokeStyle = '#FF0000';
				ctx.stroke();

				ctx.beginPath();
				ctx.moveTo(x + xPic + maxx, y + yPic + 1);
				ctx.lineTo(x + xPic + 1, y + maxy);
				ctx.strokeStyle = '#FF0000';
				ctx.stroke();
			}
			nodes[i].imgDrawn = 0;
		}

		// Adjust the box size, so the text will be placed next to the image:
		// Find the biggest rectangle for the text:
		if (imgalign == 'l'){
			if (imgvalign == 't'){
				if ((width - maxx) * height > width * (height - maxy)){
					x += (xPic + maxx);
					width -= (xPic + maxx);
				}else{
					y += (yPic + maxy);
					height -= (yPic + maxy);
				}
			}
			if (imgvalign == 'm'){
				x += (xPic + maxx);
				width -= (xPic + maxx);
			}
			if (imgvalign == 'b'){
				if ((width - maxx) * height > width * (height - maxy)){
					x += (xPic + maxx);
					width -= (xPic + maxx);
				}else{
					height -= (yPic + maxy);
				}
			}
		}
		if (imgalign == 'c'){
			if (imgvalign == 't'){
				y += (yPic + maxy);
				height -= (yPic + maxy);
			}
			if (imgvalign == 'm'){
				if (width - maxx > height - maxy){
					x += (xPic + maxx);
					width -= (xPic + maxx);
				}else{
					y += (yPic + maxy);
					height -= (yPic + maxy);
				}
			}
			if (imgvalign == 'b'){
				height = yPic;
			}
		}
		if (imgalign == 'r'){
			if (imgvalign == 't'){
				if ((width - maxx) * height > width * (height - maxy)){
					width = xPic;
				}else{
					y += (yPic + maxy);
					height -= (yPic + maxy);
				}
			}
			if (imgvalign == 'm'){
				width = xPic;
			}
			if (imgvalign == 'b'){
				if ((width - maxx) * height > width * (height - maxy)){
					width = xPic;
				}else{
					height -= (yPic + maxy);
				}
			}
		}
	}


	// The font syntax is: [style] <size> <fontname>. <size> <style> <fontname> does not work! So reformat here:
	var style = '';

	ctx.font = fsize + "px " + font;
	ctx.textBaseline = "top";
	ctx.textAlign = "center";
	ctx.fillStyle = tcolor;

	var font_style = ctx.font;
	var yp = 0, ls, ii;
	if (valign){
		var linepadding = nodes[i].txt_lines.length>1 ? defaults.textPadding/(nodes[i].txt_lines.length-1) : 0;
		yp = Math.floor((height - linepadding - nodes[i].txt_lines.length * fsize) / 2);
	}
	for (ii = 0; ii < nodes[i].txt_lines.length; ii++){
		 ls = nodes[i].line_styles[ii];
		
		if (ls.bold) {
			ctx.font = 'bold '+font_style;
		}
		if (ls.italic) {
			ctx.font = 'italic '+font_style;
		}
		ctx.fillText(nodes[i].txt_lines[ii], x + width / 2, y + yp + defaults.textPadding*(ii+1) - defaults.textPadding);
		ctx.font = font_style;
		yp += parseInt(fsize, 10);
	}

	//ctx.restore();
};

drawConLines = function(ctx)
{
	// Draw all connection lines.
	// We cannot simply draw all lines, over and over again, as the color will change.
	// Therefore we draw all lines separat, and only once.
	var i, f, l, r, v;

	ctx.lineWidth = defaults.lineWidth;
	ctx.strokeStyle = defaults.lineColor;
	ctx.beginPath();
	for (i = 0; i < nodes.length; i++){
		// Top and left lines of siblings
		if (nodes[i].parentix >= 0){
			if (nodes[i].contype === 'u') {
				ctx.moveTo(nodes[i].hpos + defaults.boxWidth / 2, nodes[i].vpos);
				ctx.lineTo(nodes[i].hpos + defaults.boxWidth / 2, nodes[i].vpos - defaults.vSpace / 2);
			}
			if (nodes[i].contype === 'l') {
				ctx.moveTo(nodes[i].hpos + defaults.boxWidth, nodes[i].vpos + defaults.boxHeight / 2);
				ctx.lineTo(nodes[nodes[i].parentix].hpos + defaults.boxWidth / 2, nodes[i].vpos + defaults.boxHeight / 2);
			}
			if (nodes[i].contype === 'r') {
				ctx.moveTo(nodes[i].hpos, nodes[i].vpos + defaults.boxHeight / 2);
				ctx.lineTo(nodes[nodes[i].parentix].hpos + defaults.boxWidth / 2, nodes[i].vpos + defaults.boxHeight / 2);
			}
		}

		// Downline if any siblings:
		v = getEndOfDownline(i);
		if (v >= 0){
			ctx.moveTo(nodes[i].hpos + defaults.boxWidth / 2, nodes[i].vpos + defaults.boxHeight);
			ctx.lineTo(nodes[i].hpos + defaults.boxWidth / 2, v);
		}

		// Horizontal line aboven multiple 'u' sibs:
		if (nodes[i].usib.length > 1){
			f = nodes[i].usib[0];
			l = nodes[i].usib[nodes[i].usib.length-1];

			ctx.moveTo(nodes[f].hpos + defaults.boxWidth / 2, nodes[f].vpos - defaults.vSpace / 2);
			ctx.lineTo(nodes[l].hpos + defaults.boxWidth / 2, nodes[f].vpos - defaults.vSpace / 2);
		}
		// Horizontal line above a single 'u' sib, if not aligned:
		if (nodes[i].usib.length == 1){
			f = nodes[i].usib[0];

			ctx.moveTo(nodes[f].hpos + defaults.boxWidth / 2, nodes[f].vpos - defaults.vSpace / 2);
			ctx.lineTo(nodes[i].hpos + defaults.boxWidth / 2, nodes[f].vpos - defaults.vSpace / 2);
		}
		
	}
	ctx.stroke();
};

getEndOfDownline = function(p)
{
	var	f, l, r, ll, rl, low;

	// if this node has u-sibs, the endpoint can be found from the vpos of the first u-sib:
	if (nodes[p].usib.length > 0){
		f = nodes[p].usib[0];
		return nodes[f].vpos - defaults.vSpace / 2;
	}

	// Find the lowest 'l' or 'r' sib:
	ll = nodes[p].lsib.length;
	rl = nodes[p].rsib.length;
	if (ll > 0 || rl > 0){
		if (ll > 0) {
			l = nodes[p].lsib[ll-1];
		}
		if (rl > 0) {
			r = nodes[p].rsib[rl-1];
		}
		if ((ll > 0 && rl > 0) && (nodes[l].vpos > nodes[r].vpos)) {
			r = l;
		} 
		if (ll > 0 && rl == 0) {
			r = l;
		}
		
		return nodes[r].vpos + defaults.boxHeight / 2;
	}

	return -1;
};

getNodeAt = function(x, y)
{
	var i, x2, y2;

	x2 = x - defaults.boxWidth;
	y2 = y - defaults.boxHeight;

	for (i = 0; i < nodes.length; i++){
		if (x > nodes[i].hpos && x2 < nodes[i].hpos && y > nodes[i].vpos && y2 < nodes[i].vpos){
			return i;
		}
	}
	return -1;
};

getNodeAtUnequal = function(x, y, u)
{
	var i, x2, y2;

	x2 = x - defaults.boxWidth;
	y2 = y - defaults.boxHeight;

	for (i = 0; i < nodes.length; i++){
		if (i !== u && x > nodes[i].hpos && x2 < nodes[i].hpos && y > nodes[i].vpos && y2 < nodes[i].vpos){
			return i;
		}
	}
	return -1;
};

getColorBoxAt = function(x, y)
{
	var i, ni, x2, y2, ids, id;

	for(ids in color_boxes) {
		for (i=0; i < color_boxes[ids].length; i++) {
			x2 = x - color_boxes[ids][i].x;
			y2 = y - color_boxes[ids][i].y;

			if (x > color_boxes[ids][i].hpos && x2 < color_boxes[ids][i].hpos && y > color_boxes[ids][i].vpos && y2 < color_boxes[ids][i].vpos) {
				id = parseInt(ids,10);
				for (ni=0; ni < nodes.length; ni++) {
					if (nodes[ni].id==id) {
						return {id:ni, hpos:color_boxes[ids][i].hpos+getAbsPosX(theCanvas), tooltip:color_boxes[ids][i].tooltip};
					}
				}
			}
		}
	}
	return -1;
};

underVSib = function(n)
{
	// Walk along the parents. If one is a lsib or rsib, return the index.
	while (n >= 0){
		if (nodes[n].contype === 'l'){
			return n;
		}
		if (nodes[n].contype === 'r'){
			return n;
		}
		n = nodes[n].parentix;
	}
	return -1;
};

errOut = function(t)
{
	console.log(t);
};

debugOut = function(t)
{
	if (debug > 0){
		//document.write("<font color='red'>OrgChart.js: <b>" + t + "</b></font><br>");
		console.log(t);
	}
};

cleanText = function(tin)
{
	var i;

	// Remove leading spaces:
	i = 0;
	while (tin.charAt(i) === ' ' || tin.charAt(i) === '\t'){
		i++;
	}
	if (i > 0){
		tin = tin.substr(i);
	}

	// Remove trailing spaces:
	i = tin.length;
	while (i > 0 && (tin.charAt(i-1) === ' ' || tin.charAt(i-1) === '\t')){
		i--;
	}
	if (i < tin.length){
		tin = tin.substr(0, i);
	}

	// Implode double spaces and tabs etc:
	return tin.replace(/[ \t]{2,}/g, " ");
};

dumpNodes = function()
{
	var i;
	for (i = 0; i < nodes.length; i++){
		console.log(i + ": " + nodes[i].parentix + " at(" + nodes[i].hpos + "," + nodes[i].vpos + ") usib = " + nodes[i].usib.length + " lsib = " + nodes[i].lsib.length + " rsib = " + nodes[i].rsib.length + "  " + nodes[i].txt + ", parent = " + nodes[i].parent + ", parentix = " + nodes[i].parentix);
	}
};

overlapBoxInTree = function(p)
{
	// Check all nodes in this tree to overlap another box already placed:
	// Return the index, or -1
	var	s, r, i, x, y;

	if (nodes[p].hpos < 0){
		return -1;
	}

	for (s = 0; s < nodes[p].usib.length; s++){
		r = overlapBoxInTree(nodes[p].usib[s]);
		if (r >= 0){
			return r;
		}
	}

	for (s = 0; s < nodes[p].lsib.length; s++){
		r = overlapBoxInTree(nodes[p].lsib[s]);
		if (r >= 0){
			return r;
		}
	}

	for (s = 0; s < nodes[p].rsib.length; s++){
		r = overlapBoxInTree(nodes[p].rsib[s]);
		if (r >= 0){
			return r;
		}
	}

	for (i = 0; i < nodes.length; i++){
		if (nodes[i].hpos >= 0 && i !== p){
			x = nodes[p].hpos - 5;
			y = nodes[p].vpos + 5;
			if (x > nodes[i].hpos && x < nodes[i].hpos + defaults.boxWidth && y > nodes[i].vpos && y < nodes[i].vpos + defaults.boxHeight){
				return i;
			}
			x = nodes[p].hpos + defaults.boxWidth + 5;
			if (x > nodes[i].hpos && x < nodes[i].hpos + defaults.boxWidth && y > nodes[i].vpos && y < nodes[i].vpos + defaults.boxHeight){
				return i;
			}
		}
	}

	return -1;
};

getLowestBox = function(p, subtree)
{
	var s, y, r;

	if (subtree === undefined){
		subtree = "ulr";
	}

	y = nodes[p].vpos;

	if (subtree.indexOf("u") >= 0){
		for (s = 0; s < nodes[p].usib.length; s++){
			r = getLowestBox(nodes[p].usib[s]);
			y = Math.max(r, y);
		}
	}

	if (subtree.indexOf("l") >= 0){
		for (s = 0; s < nodes[p].lsib.length; s++){
			r = getLowestBox(nodes[p].lsib[s]);
			y = Math.max(r, y);
		}
	}

	if (subtree.indexOf("r") >= 0){
		for (s = 0; s < nodes[p].rsib.length; s++){
			r = getLowestBox(nodes[p].rsib[s]);
			y = Math.max(r, y);
		}
	}

	return y;
};

getRootNode = function(p)
{
	while (nodes[p].parent !== '' && nodes[p].parent!=nodes[p].id){
		p = nodes[p].parentix;
	}
	return p;
};

getUParent = function(n)
{
	// Walk to the top of the tree, and return the first 'u' node found.
	// If none, return the root node.
	while (n >= 0){
		if (nodes[n].contype === 'u' || nodes[n].parent === ''){
			return n;
		}
		if (nodes[n].parentix === n) {
			return -1;
		}
		n = nodes[n].parentix;
	}
	// Not reached
	return -1;
};

nodeUnderParent = function(n, p)
{
	// Return 1 if node n is part of the p tree:
	while (n >= 0){
		if (n === p){
			return 1;
		}
		n = nodes[n].parentix;
	}
	return 0;
};

getAbsPosX = function(obj)
{
	var curleft = 0;

	if (obj.offsetParent){
		do{
			curleft += obj.offsetLeft;
			obj = obj.offsetParent;
		}while (obj);
	}else {
		if(obj.x){
			curleft += obj.x;
		}
	}

	return curleft;
};

getAbsPosY = function(obj)
{
	var curtop = 0;

	if (obj.offsetParent){
		do{
			curtop += obj.offsetTop;
			obj = obj.offsetParent;
		}while (obj);
	}else{
		if(obj.y){
			curtop += obj.y;
		}
	}

	return curtop;
};

makeRoomForDownline = function(p, v)
{
	// Alle l-sib trees may not overlap the downline, up to the point vpos.
	// Shift the parent and all r-sibs to the right
	var	maxx, h, x, w, minx;

	if (v > 0){
		debugOut("makeRoomForDownline " + nodes[p].txt + " at hpos " + nodes[p].hpos + ", up to vpos " +  v);
		// Check 'l' sibs first
		if (nodes[p].lsib.length > 0){
			maxx = -1;
			for (h = 0; h < nodes[p].lsib.length; h++){
				x = findRightMost(nodes[p].lsib[h], v);
				maxx = Math.max(x, maxx);
			}
			w = maxx + defaults.boxWidth / 2 + defaults.hSpace - nodes[p].hpos;
			if (w > 0){
				debugOut("Make room -> Shift Tree and rsibs over " + w);
				nodes[p].hpos += w;
				for (h = 0; h < nodes[p].rsib.length; h++){
					hShiftTree(nodes[p].rsib[h], w);
				}
			}
		}
	}
};

centerOnCanvas = function(width)
{
	var	i, max, min, w;

	// Find the left and rightmost nodes:
	max = -1;
	min = 999999;
	for (i = 0; i < nodes.length; i++){
		if (nodes[i].hpos > max){ max = nodes[i].hpos; }
		if (nodes[i].hpos < min){ min = nodes[i].hpos; }
	}
	max += defaults.boxWidth;

	w = (width / 2) - (max - min) / 2;
	for (i = 0; i < nodes.length; i++){
		nodes[i].hpos += w;
	}
};

leftOnCanvas = function(theCanvas, ctx)
{
	var	i, max_h, min_h, w, max_v, min_v, width, height, ratio, devicePixelRatio, backingStoreRatio;

	// Find the left and rightmost nodes:
	max_v = -1;
	min_v = 999999;
	max_h = -1;
	min_h = 999999;
	
	for (i = 0; i < nodes.length; i++){
		if (nodes[i].hpos > max_h){ max_h = nodes[i].hpos; }
		if (nodes[i].hpos < min_h){ min_h = nodes[i].hpos; }
		
		if (nodes[i].vpos > max_v){ max_v = nodes[i].vpos; }
		if (nodes[i].vpos < min_v){ min_v = nodes[i].vpos; }
	}
	max_v += defaults.boxHeight;
	max_h += defaults.boxWidth;

	w = -1*min_h+1;
	for (i = 0; i < nodes.length; i++){
		nodes[i].hpos += w;
	}

	if (theCanvas.width==0 || theCanvas.height==0) {
		
		width = max_h - min_h +5;
		height = max_v - min_v + 8;

		ratio = 1;

		if ('devicePixelRatio' in window) {
	    	devicePixelRatio = (window.devicePixelRatio ? (window.devicePixelRatio==2 ? 2 : 1) : 1) || 1;
	    	backingStoreRatio = ctx.webkitBackingStorePixelRatio ||
			    ctx.mozBackingStorePixelRatio ||
			     ctx.msBackingStorePixelRatio ||
				ctx.oBackingStorePixelRatio ||
				ctx.backingStorePixelRatio || 1;
			ratio = devicePixelRatio / backingStoreRatio;

			ctx.scale(ratio, ratio);
			debugOut("RESCALE: " + ratio);
		}
		
		theCanvas.style.width = width+"px";
		theCanvas.style.height = height+"px";
    
		theCanvas.width = width * ratio;
		theCanvas.height = height * ratio;
		if (ratio>1) {
			ctx.scale(ratio, ratio);
		}
	}
	return ctx;
};

} // orgChart
