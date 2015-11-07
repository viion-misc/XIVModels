<?/*
	For DropDown reference:
	738 Main Hand                                                          
	739 Off Hand                                                           
	740 Head                                                               
	741 Body                                                               
	742 Hands                                                              
	743 Waist                                                              
	744 Legs                                                               
	745 Feet                                                               
	746 Ears                                                               
	747 Neck                                                               
	748 Wrists                                                             
	749 Right Ring                                                         
	750 Left Ring                                                          
	751 Soul Crystal                                                       
	752 Throwing Weapon
	753 Undershirt
	754 Undergarment
	
*/
?>
<div class="tab-player addShadow" style="display:none;">

	<div class="content-title addShadow"><img src="images/misc/armor.png" /> <? echo TranslateWord("Weapons and Armor", $Language); ?></div>
	<div style="height:10px;"></div>
    
    <div class="form">
    
    	<span class="label"><? echo TranslateWord("Character", $Language); ?></span>
    	<select class="race-switch" style="width:178px;">
        	<option value="c0101"><? echo TranslateWord('Hyur M', $Language); ?></option>
            <option value="c0201"><? echo TranslateWord('Hyur F', $Language); ?></option>
            <!--<option value="c0301"><? echo TranslateWord('Human3', $Language); ?></option>
            <option value="c0401"><? echo TranslateWord('Human4', $Language); ?></option>-->
            <option value="c0501"><? echo TranslateWord('Elzen M', $Language); ?></option>
            <option value="c0601"><? echo TranslateWord('Elzen F', $Language); ?></option>
            <option value="c0701"><? echo TranslateWord("Miqo'te M", $Language); ?></option>
            <option value="c0801"><? echo TranslateWord("Miqo'te F", $Language); ?></option>
            <option value="c0901"><? echo TranslateWord('Roegadyn M', $Language); ?></option>
            <option value="c1001"><? echo TranslateWord('Roegadyn F', $Language); ?></option>
            <option value="c1101"><? echo TranslateWord('Lalafel M', $Language); ?></option>
            <option value="c1201"><? echo TranslateWord('Lalafel F', $Language); ?></option>
        </select>
    
    	<div class="seperator"></div>
    
    	<img src="images/misc/slot-main.png" class="labelimage"  />
    	<select style="width:145px;margin-bottom:10px;" class="player-select item-select" data-type="main hand">			
        	<option value="remove"><? echo TranslateWord("Weapons", $Language); ?></option>
			<? echo $DropDown["main hand"]; ?>
			<? echo $DropDown["off hand"]; ?>
        </select>
    
    	<img src="images/misc/slot-head.png" class="labelimage"  />
    	<select style="width:145px;margin-bottom:10px;" class="player-select item-select"  data-type="head">
        	<option value="remove"><? echo TranslateWord("Head", $Language); ?></option>
			<? echo $DropDown["head"]; ?>
        </select>
        
        <img src="images/misc/slot-body.png" class="labelimage"  />
    	<select style="width:145px;margin-bottom:10px;" class="player-select item-select"  data-type="body">
        	<option value="remove"><? echo TranslateWord("Body", $Language); ?></option>
			<? echo $DropDown["body"]; ?>												
        </select>                                                                      
                                                                                       
        <img src="images/misc/slot-hands.png" class="labelimage"  />                   
    	<select style="width:145px;margin-bottom:10px;" class="player-select item-select"  data-type="hands">                               
        	<option value="remove"><? echo TranslateWord("Hands", $Language); ?></option>                                                     
			<? echo $DropDown["hands"]; ?>                                                 
        </select>                                                                      
                                                                                       
        <img src="images/misc/slot-legs.png" class="labelimage"  />                    
    	<select style="width:145px;margin-bottom:10px;" class="player-select item-select"  data-type="legs">                               
        	<option value="remove"><? echo TranslateWord("Legs", $Language); ?></option>                                                      
			<? echo $DropDown["legs"]; ?>                                                 
        </select>                                                                      
                                                                                       
        <img src="images/misc/slot-feet.png" class="labelimage"  />                 
    	<select style="width:145px;margin-bottom:10px;" class="player-select item-select" data-type="feet" >                            
        	<option value="remove"><? echo TranslateWord("Feet", $Language); ?></option>                                                   
			<? echo $DropDown["feet"]; ?>
        </select>
        
        <img src="images/misc/slot-ring.png" class="labelimage"  />
    	<select style="width:145px;margin-bottom:10px;" class="player-select item-select" data-type="ring">
        	<option value="remove"><? echo TranslateWord("Ring", $Language); ?></option>
			<? echo $DropDown["ring"]; ?>
        </select>
        
        <img src="images/misc/slot-ear.png" class="labelimage"  />
    	<select style="width:145px;margin-bottom:10px;" class="player-select item-select" data-type="ear">
        	<option value="remove"><? echo TranslateWord("Ear", $Language); ?></option>
			<? echo $DropDown["ear"]; ?>
        </select>
        
        <img src="images/misc/slot-wrists.png" class="labelimage"  />
    	<select style="width:145px;margin-bottom:10px;" class="player-select item-select" data-type="wrists">
        	<option value="remove"><? echo TranslateWord("Wrists", $Language); ?></option>
			<? echo $DropDown["wrists"]; ?>
        </select>
        
        <img src="images/misc/slot-neck.png" class="labelimage"  />
    	<select style="width:145px;margin-bottom:10px;" class="player-select item-select" data-type="neck">
        	<option value="remove"><? echo TranslateWord("Neck", $Language); ?></option>
			<? echo $DropDown["neck"]; ?>
        </select>

       <!-- <div class="seperator"></div>
        
        <span class="label">Animation</span>
    	<select style="width:178px;">
        	<option><? echo TranslateWord("Running"); ?></option>
        </select>-->
    
    </div>

</div>