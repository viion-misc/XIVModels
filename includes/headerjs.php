<script>
var HistoryManipulation = { 

	refresh: function()
	{
		var viewHistory = History.get("models_history");

		if (viewHistory)
		{
			$('.xivdb-info').html('');
			viewHistory.reverse();
			
			for(i = 0; i < viewHistory.length; i++)
			{	
				var data = viewHistory[i];
				console.log(data);
				$('.xivdb-info').append('<a href="?' + data[3].toLowerCase() + '/' + data[0] + '/' + replaceAll(data[1], ' ', '-') + '"><img src="http://xivdb.com/' + data[2].toLowerCase() + '" class="hud-page-icon tooltip" data-tooltip="' + data[1] + '" style="float:none;" /></a>');
			}
		}
	}
}

$(document).ready(function()
{
	
	HistoryManipulation.refresh();
	$('.xivdb-info').show();
	
});
</script>

<style>
.dropmenu
{
	border: solid 1px #000;
	border-bottom: none;
	margin: -20px 75px 0 43px;
	box-shadow: 0 0 3px #555;
	background-color: #222;
	border-top-right-radius: 3px;
	border-top-left-radius: 3px;
}
</style>

<div class="dropmenu">            
        
	<!-- Language --> 
	<span id="sm-language" class="add-dropdown-zone header-nav" data-menu="nav-language" style="display:inline-block;">
		<span class="hud-search-subnav-button-text" style="cursor:pointer;color:#aaa;"><img src="images/language/en_black.png" class="selected_language" style="opacity: 1;margin-bottom: -1px;"> <span id="selected_language_text">English</span></span>
		<div id="nav-language" class="hud-dropdown" style="width: 100px; margin: 0px 0px 0px 10px; display: none;">
			<a class="hud-dropdown-button" href="http://xivmodels.com"><img src="images/language/en.png" class="hud-dropdown-icon2">English</a>
			<a class="hud-dropdown-button" href="http://de.xivmodels.com"><img src="images/language/de.png" class="hud-dropdown-icon2">Deutsch</a>
			<a class="hud-dropdown-button" href="http://fr.xivmodels.com"><img src="images/language/fr.png" class="hud-dropdown-icon2">Fran&ccedil;ais</a>
			<a class="hud-dropdown-button" href="http://jp.xivmodels.com"><img src="images/language/jp.png" class="hud-dropdown-icon2">日本語</a>
		</div>    			
	</span> 
	<script>setLangIcon();</script>
	
	<span class="add-dropdown-zone header-nav tooltip" onclick="$('.donation_window').center(); $('.donation_window').fadeIn();" style="display:inline-block;" data-tooltip="<? echo TranslateWord("Donate to XIVModels.com!", $Language); ?>">
		<span class="hud-search-subnav-button-text" style="cursor:pointer;color:#aaa;">
			<img src="images/misc/heart.png" style="opacity: 1;margin-bottom: -2px;height: 13px;"> <span ><? echo TranslateWord("Donate", $Language); ?>
		</span>
	</span>

</div>

<span style="clear:both;"></span>