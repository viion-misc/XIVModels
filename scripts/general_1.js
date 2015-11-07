$(document).ready(function() {

	// Tooltip
	$(".tooltip").uitooltip();

	// Minicolors
	$('.background').minicolors({
		change: function(Hex, opacity) {
			$('html').css({
				'background' : 'none',
				'background-color' : Hex
			});
		}
	});

	$('select').keydown(function() {
		$(this).change();
	});


	$(".player-select").change(function(){
		Menu.changeSelect($(this), 'item');
	});
	$(".monster-select").change(function(){
		Menu.changeSelect($(this), 'monster');
	});
	$(".primal-select").change(function(){
		Menu.changeSelect($(this), 'primal');
	});
	$(".summon-select").change(function(){
		Menu.changeSelect($(this), 'summon');
	});
	$(".pet-select").change(function(){
		Menu.changeSelect($(this), 'pet');
	});
	$(".beastman-select").change(function(){
		Menu.changeSelect($(this), 'beastman');
	});
	$(".mount-select").change(function(){
		Menu.changeSelect($(this), 'mount');
	});
	

	//race-switch
	$(".race-switch").change(function(){
		for(var slot in model)
		{
			
			if(model[slot]["model"] && model.hasOwnProperty(slot))
			{
				//console.log(":::::::::::::::::::::::::::"+slot+":::::::::::::::::::::::::::::::::::::OLD:::"+model[slot]["model_path"]+":::::"+Menu.adaptToRace(model[slot]["model_path"])+":::::");
				model_viewer.detach(model[slot]["model"]);
				model[slot]["model"] = model_viewer.attach(Menu.adaptToRace(model[slot]["model_path"]));
				
				if(model[slot]["material"])
				{
					//console.log("LOADING MAT "+model[slot]["material"]);
					model[slot]["model"].set_material_version(Menu.adaptToRace(model[slot]["material"]));
				}
			}
		}
	});
	
	
	// Start
	doWork();
	
	initDropDowns();

});
var onresizeTimer;
window.onresize = function(event)
{
	$('.welcome_window').center(); 
	$('.donation_window').center(); 
	ContentStatus.setStatus(null, true);
	window.clearTimeout(onresizeTimer);
	onresizeTimer = window.setTimeout(function()
	{
		var canvas = document.getElementById("webgl_canvas");
		canvas.width = window.outerWidth;
		canvas.height = window.innerHeight;
		camera.updateProjectionMat(canvas);
		ContentStatus.setStatus();
	},
	300);
}

//---------------------------------------------------------------------------------------------

// Status functions
var ContentStatus = {

	// Set the status for the bottom right corner
	setStatus: function(text, loading)
	{
		if (text || loading)
		{
			if (loading) {
				$('.status').html('<img src="images/loading2.gif" />').fadeIn(300);
			} else {
				$('.status').html('<div class="status-text">' + text + '</div>').fadeIn(300);
			}
		}
		else
		{
			$('.status').fadeOut(200);
		}
	},

	// Show the loading box
	showLoading: function() {
		$('.loading').html('').fadeIn(200);
	},

	// Show the loading box
	hideLoading: function() {
		$('.loading').fadeOut(200, function() { $(this).html(''); });
	},

	// Show the loading box
	addLoading: function(type, name, done) {
		if (type && name && done > 1)
		{
			if (!$('.loading').is(":visible")) { ContentStatus.showLoading(); }
			$('.loading').append('<div class="loading-' + name + '" style="padding:5px;"> \
					<div style="margin-bottom:8px;"> \
					<span class="loading-title">' + ucwords(type) + '</span> \
					<span class="loading-info" style="float:right;"><span class="' + name + '-done"></span><span style="opacity:0.5;">/</span><span class="' + name + '-total"></span></span> \
					</div> \
					<div class="progress_bar" style="width: 207px;"><div class="' + name + '-progress progress_bar_fill"></div></div> \
				</div>');
		}
	},

	nullClass: function(name)
	{
		$('.loading').removeClass('loading-' + name);
		$('.loading').removeClass(name + '-done');
		$('.loading').removeClass(name + '-total');
		$('.loading').removeClass(name + '-progress');
	},

	setLoadingText: function(text) {
		$('.loading').html('<div style="padding:10px;">'+ text +'</div>');
	},

	removeLoading: function(name) {
		$('.loading-' + name).slideUp(200);
	}
}

// Menu functions
var Menu = {

	active: 'home',
	searchTimeout: {},
	loadedType: {},
	grouping_entities: ["beastman", "mount"],

	set: function(tab)
	{
		$('.menu-' + Menu.active).removeClass('active');
		$('.tab-' + Menu.active).hide();
		$('.menu-' + tab).addClass('active');
		$('.tab-' + tab).show();
		Menu.active = tab;
	}

	,changeSelect: function(me, type)
	{

		clearTimeout(Menu.searchTimeout);
		Menu.searchTimeout = setTimeout(function()
		{
			//Get details
			var name = $(me).find(":selected").text();
			var data = $(me).val();
			
			if(data != 'remove')
			{
				data = JSON.parse(window.atob($(me).val()));
				
				//Reset everything when switching type (check first array element)
				if(Menu.loadedType != data[0]["type"] || $.inArray(data[0]["type"].toLowerCase(), Menu.grouping_entities)!=-1)
					Menu.clearScene();
					
				Menu.loadEntities(data);
				
			}
			else
			{
				// Remove item
				var slot = $(me).data('type');
				Menu.clearScene(slot);
				Menu.buildUrl();
			}

		},300);

	}
	,buildUrl: function(data)
	{
		// build url, if more than 1 id build hash from group
		var ids = [];
		var item = {};
		for(var mod in model)
		{
			if (model.hasOwnProperty(mod) && model[mod]) 
			{ 
				ids.push([model[mod]["id"], model[mod]["type"]]);
				item = model[mod];
			}
		}
		console.log("IDS");
		console.log(ids);
		
		if(ids.length>1 && $.inArray(item["type"].toLowerCase(), Menu.grouping_entities)==-1)
		{
			//Group hash it	
			var hash = "group/"+JSON.stringify(window.btoa(ids));
			setTitle("Final Fantasy XIV : A Realm Reborn (FFXIV ARR) Model Viewer");
			url.set(hash.replace(/"/g,''), false);
		
		}else if((ids.length == 1 && item) || $.inArray(item["type"].toLowerCase(), Menu.grouping_entities)!=-1)
		{
			//Single item 
			setTitle(item["name"] + " - Final Fantasy XIV : A Realm Reborn (FFXIV ARR) Model Viewer");
			url.set(item["type"].toLowerCase()+"/"+item["id"]+"/"+replaceAll(item["name"], " ", "-"), false);
		}else
		{
			setTitle("Final Fantasy XIV : A Realm Reborn (FFXIV ARR) Model Viewer");
			url.set('', false);
		}
		//Add history for current item
		//History.add("models_history", data["id"], data["name"], data["icon"], data["type"]);
			
	}
	,clearScene: function(slot)
	{
		for(var mod in model)
		{
			if(mod == slot || slot == undefined)
			{
				if(model[mod]["model"])
					model_viewer.detach(model[mod]["model"]);
					
				delete model[mod];
			}
		}
	
	}
	,loadEntities: function(data_array)
	{
		console.log("DATTAARRAY");
		console.log(data_array);
		//Attach models
		for (var i in data_array)
		{
			if (data_array.hasOwnProperty(i) && data_array[i]) 
			{ 
				var data	= data_array[i]
				var slot	= data["slot"];
				
				Menu.clearScene(slot);
				model[slot] = {"model_path": data["model"], "material" : data["material"], "slot" : slot, "type" : data["type"], "name" : data["name"], "icon" : data["icon"], "id" : data["id"]};
				Menu.loadModel(slot);
				
				Menu.buildUrl();
				
			}
		}
	}
	,loadModel: function(model_slot)
	{

		Menu.loadedType = model[model_slot]["type"].toUpperCase();
		
		console.log("LOADING MODEL :"+model[model_slot]["name"]+" | SLOT : "+model[model_slot]["slot"]+ " | TYPE : "+model[model_slot]["type"]+ " | PATH : "+model[model_slot]["model_path"] + " | MATERIAL : "+model[model_slot]["material"]);
		
		// Attach model
		model[model_slot]["model"] = model_viewer.attach(Menu.adaptToRace(model[model_slot]["model_path"]));
		
		//Attach material if defined else it takes defauklt 
		if(model[model_slot]["material"])
		{
			console.log("LOADING MAT "+model[model_slot]["material"]);
			model[model_slot]["model"].set_material_version(model[model_slot]["material"]);
		}
		
		if($('.xivdb-info').children().length >= 8) // purge last if 8 or more
			$('.xivdb-info a:last-child').remove();

			
		// Do not show history for all ports if its a beastmen for example (only for body itself)
		var add_history = true;
		if($.inArray(model[model_slot]["type"].toLowerCase(), Menu.grouping_entities)!=-1 && model[model_slot]["slot"].toLowerCase() != 'body' )
			add_history = false;
			
			
		if(add_history)
		{
			$('.xivdb-info').prepend('<a href="?'+model[model_slot]["type"]+'/'+model[model_slot]["id"]+'/'+replaceAll(model[model_slot]["name"], " ", "-") + '"><img src="http://xivmodels.com/' + model[model_slot]["icon"].toLowerCase() + '" class="hud-page-icon tooltip" data-tooltip="' + model[model_slot]["name"] + '" style="float:none;" /></a>');
			$('.tooltip').uitooltip();
			
			History.add("models_history", model[model_slot]["id"], model[model_slot]["name"], model[model_slot]["icon"], model[model_slot]["type"]);
		}
		

	}

	,adaptToRace: function(model_path)
	{
		console.log("SWITCHING PATH FROM : c0101 TO : "+$('.race-switch').val()+ " NEW URL : " +replaceAll(model_path, 'c0101', $('.race-switch').val()));
		if($('.race-switch').val())
			return replaceAll(model_path, 'c0101', $('.race-switch').val());
		else 
			return model_path;
	}
}

var Display = {

	setBG: function(file)
	{
		$('html').css({
			'background' : 'url(' + file + ')',
			'background-position' : 'center center',
			'background-size' : '100% 100%',
		});
	}

}

var url =
{
	get: function()
	{
		return document.URL;
	},

	set: function(Location, Minor)
	{
		if (Minor) {
			window.history.replaceState("", "", "?" + Location);
		} else {
			window.history.pushState("", "", "?" + Location);
		}
	}
};


//---------------------------------------------------------------------------------------------
// Minified Stuff
//---------------------------------------------------------------------------------------------

function Open(Page) { window.location = Page;};
function OpenTab(Page) { var win = window.open(Page, '_blank'); win.focus(); };
function ucwords(str) { return (str + '').replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function ($1) { return $1.toUpperCase(); }); }
function setTitle(Value) { document.title = Value; };
function replaceAll(txt, replace, with_this) { if (txt && replace && with_this) { return txt.replace(new RegExp(replace, 'g'),with_this); } else { return txt; } };
function getOccurence(e,t){for(var n=0,r=e.length;n<r;n++){if(e[n][0]===t){return n}}return-1};

// History
var History = {size:6, add:function(b, c, d, e, f) {
  var a = Cookies.get(b);

  if(a) {
    var a = JSON.parse(a), g = getOccurence(a, c);
    -1 < g && a.splice(g, 1);
    a.push([c, d, e, f]);
    a.reverse();
    a = a.splice(0, History.size);
    a.reverse();
    Cookies.set(b, JSON.stringify(a))
  }else {
    Cookies.set(b, JSON.stringify([[c, d, e, f]]))
  }
}, get:function(b) {
  return(b = Cookies.get(b)) ? JSON.parse(b) : !1
}};

// Cookies
var Cookies={expires:{"default":365},set:function(e,t){$.cookie(e,t,{expires:Cookies.expires.default})},get:function(e){return $.cookie(e)},getAll:function(){return $.cookie()},remove:function(e){$.removeCookie(e)},setCookie:function(e,t){Cookies.set(e,t)},getCookie:function(e){return Cookies.get(e)},deleteCookie:function(e){Cookies.remove(e)}};

// UI Tooltip
(function(e){e.fn.uitooltip=function(){return this.each(function(){var t=e(this).data("tooltip");var n=e(this).data("tooltip-style");var r="tooltip-style";if(n){r=r+"-"+n}if(t!=undefined){e(this).hover(function(t){e("#"+r).remove();var n=e(this).data("tooltip");var i=t.pageX+15;var s=t.pageY+15;e("body").append("<div id='"+r+"' style='position: absolute; z-index: 9999; display: none;'>"+n+"</div>");var o=e("#"+r).width();e("#"+r).width(o);e("#"+r).css("left",i).css("top",s).show()},function(){e("#"+r).remove()});e(this).mousemove(function(t){var n=t.pageX+15;var i=t.pageY+15;var s=e("#"+r).outerWidth(true)+10;var o=e("#"+r).outerHeight(true);if(n+s>e(window).scrollLeft()+e(window).width())n=t.pageX-s;if(e(window).height()+e(window).scrollTop()<i+o)i=t.pageY-o;e("#"+r).css("left",n).css("top",i).show()})}})}})(jQuery);

// Set language icon
function setLangIcon(){var e=new Array("jp","en","de","fr");var t=new Array("日本語","English","Deutsch","Fran&ccedil;ais");var n=e[Cookies.getCookie("XIVMODELS:Language")];var r=t[Cookies.getCookie("XIVMODELS:Language")];$("#selected_language_text").html(r);$(".selected_language").attr("src","images/language/"+n+".png");$(".selected_language").animate({opacity:1},250)};

// Dropdown menus
function initDropDowns() {
  $(".add-dropdown-zone").hover(function() {
    var a = $(this).data("menu");
    $("#" + a).stop(!0, !0).show()
  }, function() {
    var a = $(this).data("menu");
    $("#" + a).stop(!0, !0).hide()
  })
}
;



function hideDropDown(){$(".hud-dropdown").hide();$(".hud-dropdown-button-hover").removeClass("hud-dropdown-button-hover")};

//centering
jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + 
                                                $(window).scrollTop()) + "px");
    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + 
                                                $(window).scrollLeft()) + "px");
    return this;
}

