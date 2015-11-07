<?

/*   Slot Tranlsation                                                                 
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

$LAN = array("JP", "EN", "DE", "FR");		
$HUMAN_DropDown = array();
$DropDown = array();
$ALL_DropDown = array();

#Get models by name i.e gear

$SelectLists = $DB->SQL("SELECT ID, Icon, Model, Material, Slot, Name_".$LAN[$Language]." AS NAME, Type AS TYPE FROM models ORDER BY Name_".$LAN[$Language]." ASC");
	//print_r($SelectLists);
foreach($SelectLists as $Select)
{
	$Hash = array();
	if($Select["Model"])
	{
		$Hash[] =	 array(	  
		"id" 			=> $Select["ID"]
		, "model" 		=> $Select["Model"]
		, "icon" 		=> Iconize($Select["Icon"])
		, "type" 		=> $Select["TYPE"]
		, "material" 	=> $Select["Material"]
		, "name"		=> $Select["NAME"]
		, "slot"		=> $Select["Slot"]
		);
		switch($Select["TYPE"])
		{
			CASE "NPC":
				$DropDown["NPC"] .= "<option value='".base64_encode(json_encode($Hash))."'>".ucwords($Select["NAME"])."</option>";
			BREAK;
			CASE "ITEM":
				if($Select["Slot"])
					$DropDown[$Select["Slot"]] .= "<option value='".base64_encode(json_encode($Hash))."'>".ucwords($Select["NAME"])."</option>";	
				else
					$DropDown["ITEM"] .= "<option value='".base64_encode(json_encode($Hash))."'>".ucwords($Select["NAME"])."</option>";
			BREAK;
		}
	}
}


#Get models for those we only show unique models (like mobs)
$All_SelectLists = $DB->SQL("SELECT ID, Icon, Model, Material, Slot, Name_".$LAN[$Language]." AS NAME, Type AS TYPE FROM models_all ORDER BY Name_".$LAN[$Language]." ASC");
	//print_r($SelectLists);
foreach($All_SelectLists as $Select)
{
	$Hash = array();
	if($Select["Model"])
	{
		$Hash[] = array(	  
		"id" 			=> $Select["ID"]
		, "model" 		=> $Select["Model"]
		, "icon" 		=> $Select["Icon"]
		, "type" 		=> $Select["TYPE"]
		, "material"	=> $Select["Material"]
		, "name"		=> $Select["NAME"]
		, "slot"		=> "body"
		);
		
		
		switch($Select["TYPE"])
		{
			CASE "MONSTER":
				$ALL_DropDown["MONSTER"] .= "<option value='".base64_encode(json_encode($Hash))."'>".ucwords($Select["NAME"])."</option>";
			BREAK;
			CASE "SUMMON":
				$ALL_DropDown["SUMMON"] .= "<option value='".base64_encode(json_encode($Hash))."'>".ucwords($Select["NAME"])."</option>";
			BREAK;
			CASE "PET":
				$ALL_DropDown["PET"] .= "<option value='".base64_encode(json_encode($Hash))."'>".ucwords($Select["NAME"])."</option>";
			BREAK;
			CASE "PRIMAL":
				$ALL_DropDown["PRIMAL"] .= "<option value='".base64_encode(json_encode($Hash))."'>".ucwords($Select["NAME"])."</option>";
			BREAK;
			CASE "MOUNT":
				$HUMAN_DropDown["MOUNT"] .= "<option value='".base64_encode(json_encode($Hash))."'>".ucwords($Select["NAME"])."</option>";
			BREAK;
		}
	}
}



#Get models for those we only show unique models (like mobs)
$All_SelectLists = $DB->SQL("SELECT ID, Icon, Body, Hands, Legs, Head, Material, Slot, Name_".$LAN[$Language]." AS NAME, Type AS TYPE FROM models_human ORDER BY Name_".$LAN[$Language]." ASC");
	//print_r($SelectLists);
foreach($All_SelectLists as $Select)
{
	$Hash = array();
	if($Select["Body"])
	{
		$Parts = array("Body", "Hands", "Legs", "Head");
		foreach($Parts AS $Part)
		{
				
			$Hash[] = array(
			"id"		=> $Select["ID"]
			,"model"	=> $Select[$Part]
			,"icon" 	=> $Select["Icon"]
			,"type"		=> $Select["TYPE"]
			,"material"	=> $Select["Material"]
			,"name" 	=> $Select["NAME"]
			,"slot"		=> $Part
			);
		}				
		switch($Select["TYPE"])
		{
			CASE "BEASTMAN":
				$HUMAN_DropDown["BEASTMAN"] .= "<option value='".base64_encode(json_encode($Hash))."'>".ucwords($Select["NAME"])."</option>";
			BREAK;
			CASE "MOUNT":
				$HUMAN_DropDown["MOUNT"] .= "<option value='".base64_encode(json_encode($Hash))."'>".ucwords($Select["NAME"])."</option>";
			BREAK;
			
		}
	}
}

	
?>