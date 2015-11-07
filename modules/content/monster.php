<div class="tab-monster tab-summon tab-pet tab-primal tab-beastman tab-mount addShadow" style="display:none;">

	<div class="content-title addShadow"><img src="images/misc/monsters.png" /> <? echo TranslateWord("Monsters"); ?></div>
	<div style="height:10px;"></div>
    
    <div class="form">
    
    	<div class="label"><? echo TranslateWord("Model", $Language); ?></div>
    	<select style="width:178px;margin-bottom:10px;" class="monster-select">
        	<option><option>
			<? echo $ALL_DropDown["MONSTER"]; ?>	
        </select>
		
		<div class="label"><? echo TranslateWord("Beastman", $Language); ?></div>
    	<select style="width:178px;margin-bottom:10px;" class="beastman-select">
        	<option><option>
			<? echo $HUMAN_DropDown["BEASTMAN"]; ?>	
        </select>
		
		<div class="label"><? echo TranslateWord("Mount", $Language); ?></div>
    	<select style="width:178px;margin-bottom:10px;" class="mount-select">
        	<option><option>
			<? echo $HUMAN_DropDown["MOUNT"]; ?>	
        </select>
		
		<div class="label"><? echo TranslateWord("Primal", $Language); ?></div>
    	<select style="width:178px;margin-bottom:10px;" class="primal-select">
        	<option></option>
			<? echo $ALL_DropDown["PRIMAL"]; ?>	
        </select>
		
		<div class="label"><? echo TranslateWord("Pet", $Language); ?></div>
    	<select style="width:178px;margin-bottom:10px;" class="pet-select">
        	<option></option>
			<? echo $ALL_DropDown["PET"]; ?>	
        </select>
		
		<div class="label"><? echo TranslateWord("Summon", $Language); ?></div>
    	<select style="width:178px;margin-bottom:10px;" class="summon-select">
        	<option></option>
			<? echo $ALL_DropDown["SUMMON"]; ?>	
        </select>
    
    
    	<!--<div class="label"><? echo TranslateWord("Texture", $Language); ?></div>
    	<select style="width:178px;margin-bottom:10px;">
        	<option></option>
        </select>
        
        <div class="seperator"></div>
        
        <div class="label"><? echo TranslateWord("Animation", $Language); ?></div>
    	<select style="width:178px;">
        	<option></option>
        </select>-->
    
    </div>

</div>