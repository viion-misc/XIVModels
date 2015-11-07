<div class="tab-settings addShadows form" style="display:none;">

	<div class="content-title addShadow"><img src="images/misc/settings.png" /> <? echo TranslateWord("Settings", $Language); ?></div>
	<div style="height:10px;"></div>
    
    <div style="margin:0 0 5px -2px;"><input onclick="camera.autorotate.toggle();" type="button" value="<? echo TranslateWord("Toggle: Rotate Model", $Language); ?>" /></div>
    
    <div style="margin:0 0 10px 0;">
    	<div class="label" style="margin:0 0 5px 1px;"><? echo TranslateWord("Background Themes and Color", $Language); ?></div>
    	<div><input class="minicolors background" data-default-value="#fc0" type="text" value="#333" style="font-size:12px;" /></div>
    </div>
    
    <div style="margin:0 0 0 2px;">
    <?
	// Get images
	$Files = scandir('images/bgs/');
	
	// Array values
	$Files = array_values(array_diff($Files,array('.')));
	$Files = array_values(array_diff($Files,array('..')));
	$Files = array_values(array_diff($Files,array('thumbs')));
	foreach($Files as $File)
	{
		echo '<img src="images/bgs/thumbs/'. $File .'" onclick="Display.setBG(\'images/bgs/'. $File .'\');" width="32" height="32" style="margin:0 2px 2px 0;cursor:pointer;" />';	
	}
	
	?>
    </div>

</div>