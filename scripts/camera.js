var camera = {
	modelViewer: null,

	viewMat: mat4.create(),
	projectionMat: mat4.create(),

	xRot: 0,
	yRot: 0,
	zRot: 0,

	altKey: false,
	ctrlKey: false,
	shiftKey: false,
	toggledRotation: false,

	xTrans: 0,
	yTrans: -3.0,
	zTrans: -10.0,

	drag: false,
	dragButton: false,
	dragSpeed: [250, 150],
	dragX: 0,
	dragY: 0,

	maxZoom: [-0.75, -80],
	zoomSpeed: 0.5,

	autorotate: {

		active: false,
		steps: 0.01,
		fps: 60,

		toggle: function() {
			if (camera.autorotate.active) {
				camera.autorotate.active = false;
			} else {
				camera.autorotate.active = true;
				camera.autorotate.start();
			}
		},

		start: function() {
			setTimeout(function() {
				camera.yRot = camera.yRot += camera.autorotate.steps;
				camera.updateViewMat();
				if (camera.autorotate.active) {
					camera.autorotate.start();
				}
			}, (1000 / camera.autorotate.fps));
		},
	},

	texture: {

		active: true,

		toggle: function() {
			if (camera.texture.active) {
				camera.texture.active = false;
			} else {
				camera.texture.active = true;
			}
			model_viewer.update_options({texture:camera.texture.active});
		}

	},

	updateProjectionMat: function(canvas) {
		mat4.perspective(this.projectionMat, 45.0, (canvas.width) / canvas.height, 0.1, 100.0);
		if (this.modelViewer) {
			this.modelViewer.set_projection_matrix(this.projectionMat);
		}
	},

	updateViewMat: function () {
		var mv = this.viewMat;
		mat4.identity(mv);
		mat4.translate(mv, mv, [this.xTrans, this.yTrans, this.zTrans]);
		mat4.rotateX(mv, mv, this.xRot);
		mat4.rotateY(mv, mv, this.yRot);
		mat4.rotateZ(mv, mv, this.zRot);
		if (this.modelViewer) {
			this.modelViewer.set_view_matrix(mv);
		}
	},

	init: function(canvas, modelViewer) 
	{
		this.modelViewer = modelViewer;
		this.updateProjectionMat(canvas);
		this.updateViewMat();

		var self = this;
		
		window.onkeydown = function(event) 
		{
			// Function keys
			if (event.keyCode == 18) 		{ self.altKey = true; 	}
			else if (event.keyCode == 17) 	{ self.ctrlKey = true; 	}
			else if (event.keyCode == 16) 	{ self.shiftKey = true; }

			// Pan Keyboard Controls
			if (event.keyCode == 87 || event.keyCode == 38) { canvas.modelMoveAndRotate(event, 'move', 'UP'); 			}
			if (event.keyCode == 83 || event.keyCode == 40) { canvas.modelMoveAndRotate(event, 'move', 'DOWN'); 		}
			if (event.keyCode == 68 || event.keyCode == 39) { canvas.modelMoveAndRotate(event, 'move', 'RIGHT'); 		}
			if (event.keyCode == 65 || event.keyCode == 37) { canvas.modelMoveAndRotate(event, 'move', 'LEFT'); 		}

			// Pan Keyboard Controls
			if (event.keyCode == 73 || event.keyCode == 104 || event.keyCode == 90) { canvas.modelMoveAndRotate(event, 'rotate', 'UP'); 	}
			if (event.keyCode == 75 || event.keyCode == 98  || event.keyCode == 88) { canvas.modelMoveAndRotate(event, 'rotate', 'DOWN'); 	}
			if (event.keyCode == 76 || event.keyCode == 102 || event.keyCode == 81) { canvas.modelMoveAndRotate(event, 'rotate', 'RIGHT');	}
			if (event.keyCode == 74 || event.keyCode == 100 || event.keyCode == 69) { canvas.modelMoveAndRotate(event, 'rotate', 'LEFT'); 	}

			// Zoom keyboard controls
			if (event.keyCode == 99   || event.keyCode == 107) { canvas.modelMoveAndRotate(event, 'zoom', 'IN'); 	}
			if (event.keyCode == 105  || event.keyCode == 109) { canvas.modelMoveAndRotate(event, 'zoom', 'OUT'); 	}

			if (event.keyCode == 36) { canvas.modelMoveAndRotate('reset'); 	}
		};

		window.onkeyup = function(event) 
		{
			self.altKey = false;
			self.ctrlKey = false; 
			self.shiftKey = false;
		};

		canvas.modelMoveAndRotate = function(event, type, direction)
		{
			var selectFocused = $("select").is(":focus");
			console.log(selectFocused);

			if (!selectFocused)
			{
				event.preventDefault();

				if (type == 'move')
				{
					var xMov = self.xTrans;
					var yMov = self.yTrans;

					switch(direction)
					{
						case 'UP': 		yMov = yMov + 0.1; break;
						case 'DOWN': 	yMov = yMov - 0.1; break;
						case 'LEFT': 	xMov = xMov - 0.1; break;
						case 'RIGHT': 	xMov = xMov + 0.1; break;
					}

					self.yTrans = yMov;
					self.xTrans = xMov;
				}
				else if (type == 'rotate')
				{
					var xMov = self.xRot;
					var yMov = self.yRot;

					switch(direction)
					{
						case 'RIGHT': 	yMov = yMov + 0.1; break;
						case 'LEFT': 	yMov = yMov - 0.1; break;
						case 'UP': 		xMov = xMov - 0.1; break;
						case 'DOWN': 	xMov = xMov + 0.1; break;
					}

					self.yRot = yMov;
					self.xRot = xMov;
				}
				else if (type == 'zoom')
				{
					var oldZTrans = self.zTrans;

					switch(direction)
					{
						case 'IN': 	self.zTrans += self.zoomSpeed; break;
						case 'OUT': self.zTrans -= self.zoomSpeed; break;
					}

					if (self.zTrans < self.maxZoom[1] || self.zTrans > self.maxZoom[0]) {
						self.zTrans = oldZTrans;
					}
				}
				else if (type == 'reset')
				{
					self.xRot = 0;
					self.yRot = 0;
					self.zRot = 0;
					self.xTrans = 0;
					self.yTrans = -3.0;
					self.zTrans = -10.0;
				}
			}

			// Update view
			self.updateViewMat();

			return false;
		};

		// Disable alt key on unfocus
		canvas.addEventListener("blur", function(event) {
			self.altKey = false;
			self.ctrlKey = false; 
			self.shiftKey = false;
		}, false);

		canvas.addEventListener("mousewheel", function(event) {
			var oldZTrans = self.zTrans;
			if (event.wheelDelta > 0) {
				self.zTrans += self.zoomSpeed;
			} else {
				self.zTrans -= self.zoomSpeed;
			}
			if (self.zTrans < self.maxZoom[1] || self.zTrans > self.maxZoom[0]) {
				self.zTrans = oldZTrans;
			} else {
				self.updateViewMat();
			}
		}, false);

		canvas.addEventListener("mousedown", function(event) 
		{
			$('select').blur();
			event.preventDefault();
			self.dragX = event.clientX;
			self.dragY = event.clientY;
			self.drag = true;
			self.dragButton = event.button;
			$('section, footer, .alux, .xivdb-info').addClass('zindex2');
		}, false);

		canvas.addEventListener("mouseup", function(event) {
			event.preventDefault();
			self.drag = false;
			$('.zindex2').removeClass('zindex2');
		}, false);

		canvas.addEventListener("mouseout", function(event) {
			event.preventDefault();
			document.body.style.cursor = 'default';
			self.drag = false;
		}, false);

		canvas.addEventListener("mousemove", function(event) {
			if (self.drag)
			{
				document.body.style.cursor = 'move';
				event.preventDefault();
				var x = event.clientX;
				var y = event.clientY;
				var xMov = ((x - self.dragX) / self.dragSpeed[self.dragButton]);
				var yMov = ((y - self.dragY) / self.dragSpeed[self.dragButton]);

				// Rotation
				if (self.dragButton == 0 && !self.ctrlKey && !self.shiftKey)
				{
					// Toggled rotation
					if (self.toggledRotation) {
						self.zRot -= xMov;
						self.xRot += yMov;
					} else {
						self.yRot += xMov;
						self.xRot += yMov;
					}
				}

				// Pan
				if ((self.dragButton == 1 || (self.dragButton == 0 && self.ctrlKey)) && !self.shiftKey)
				{
					//console.log("zoom = " + self.zTrans);
					//console.log("yMov = " + yMov);
					//console.log("xMov = " + xMov);
					self.yTrans -= yMov;
					self.xTrans += xMov;
				}

				// Zoom (crap browsers only)
				if (self.dragButton == 0 && self.shiftKey)
				{
					var oldZTrans = self.zTrans;
					yMov = yMov * 2;
					self.zTrans -= yMov

					if (self.zTrans < self.maxZoom[1] || self.zTrans > self.maxZoom[0]) {
						self.zTrans = oldZTrans;
					} else {
						self.updateViewMat();
					}
				}

				// Update drag X/Y
				self.dragX = event.clientX;
				self.dragY = event.clientY;

				// Update view
				self.updateViewMat();
			}
			else
			{
				document.body.style.cursor = 'default';
			}
		}, false);
	}
}

var model_viewer;
var model = {};

// Example of the progress callback with the snippet of Damien, xivmv.setProgressCallback(progressCallback);
var loading = [];
var stopLoading;
function progressCallback(e, type, name) {

	$('.welcome_window').fadeOut();
	clearTimeout(stopLoading);

	// Loading calculation
	var done = e.position || e.loaded, total = e.totalSize || e.total;
	var percentage = '0%';
	if(done > total)
		percentage = '100%'
	else
		percentage = Math.floor(done/total*100)+"%";

	done = Math.round(done/10)/100;
	total =  Math.round(total/10)/100;
	var suffix = "Kb";
	if(total>2000)
	{
		done = Math.round(done/10)/100;
		total =  Math.round(total/10)/100;
		var suffix = "Mb";
	}

	// Only add if there is an event
	if (done > 0 && total > 0)
	{
		// Get specific file
		var data = name.split("/");
		var file = data[data.length - 1].split(".")[0];

		// If type started
		if ($.inArray(file, loading) == -1) {
			loading.push(file);
			ContentStatus.addLoading(type, file, done);
			console.log(">>> " + file + " | " + name + " | " + type + " | " + total);
		}

		// Display variables
		$('.'+ file +'-done').html(done);
		$('.'+ file +'-total').html(total+suffix);
		$('.'+ file +'-progress').stop(true, true).animate({"width": percentage}, 500);

		// If event ended
		if (e.loaded >= e.total) {
			var index = loading.indexOf(file);
			loading.splice(index, 1);
			ContentStatus.nullClass(file);
			setTimeout(function() {
				ContentStatus.removeLoading(file);
			}, 1000);
		}
	}

	// If loading is complete
	if (loading.length == 0 && $('.loading').is(":visible"))
	{
		stopLoading = setTimeout(function()
		{
			ContentStatus.hideLoading();
		},
		1000);
	}
}

function doWork() {
	var canvas = document.getElementById("webgl_canvas");
	canvas.width = window.innerWidth;
	canvas.height = window.innerHeight;

	xivmv.set_progress_callback(progressCallback);

	model_viewer = xivmv.create_model_viewer(canvas).start();
	if(UrlModel.Hash)
	{
		var data_array = JSON.parse(window.atob(UrlModel.Hash));
		Menu.loadEntities(data_array);
		
		//Set UI Stuff
		for (var i in data_array)
		{
			if (data_array.hasOwnProperty(i) && data_array[i]) 
			{ 
				var data	= data_array[i]
				console.log("SET NAME "+data["name"]+" TYPE:" +data["type"]);
				Menu.set(data["menu"]);
				//console.log("SETTING "+UrlModel.Menu+" FOR "+UrlModel.Name);
				$('.'+data["type"]+'-select option').filter(function () { return $(this).html().toLowerCase() == data["name"].toLowerCase(); }).prop('selected', true);
			}
		}
		
	}
	else
	{
		// Display popup.
		$('.welcome_window').center(); 
		$('.welcome_window').fadeIn();

		/*
		var rand = Math.floor(Math.random()*($('.monster-select option').length+1));		
		var i = 0;
		$(".monster-select option").each(function()
		{
			if(i == rand)
			{
				var data = JSON.parse(window.atob($(this).val()));
				Menu.loadEntities(data);
			}
			i++;
		});
		*/

	}





	camera.init(canvas, model_viewer);

	// To move the camera use setViewMat like the camera object was doing on the previous model_viewer
	// model_viewer.setViewMat(...)

	// If the canvas is resized you might need to change the projectionMatrix
	// model_viewer.setProjectionMat(...)

	// If you need to move a model in the world (shouldn't use this unless you have multiple models)
	// model.setModelMat(...)

	// The end transformation is Projection * View * Model * Point
}


