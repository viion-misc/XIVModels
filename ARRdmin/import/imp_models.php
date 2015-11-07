<? require_once("functions.php"); ?>

<h2>Models</h2>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="50%" valign="top" style="padding-right:20px;">
		<h3>Mobs</h3>
		<strong>Progress</strong>
		<div class="num_progress1"></div>
		<table width="100%" border="0" cellspacing="0" cellpadding="3" style="margin-top:10px;">
		<tr>
			<td width="100%"><div class="progress_bar"><div class="progress_bar_1"></div></div></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td width="50%" valign="top" style="padding-right:20px;">
		<h3>NPC</h3>
		<strong>Progress</strong>
		<div class="num_progress2"></div>
		<table width="100%" border="0" cellspacing="0" cellpadding="3" style="margin-top:10px;">
		<tr>
			<td width="100%"><div class="progress_bar"><div class="progress_bar_2"></div></div></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td width="50%" valign="top" style="padding-right:20px;">
		<h3>Items</h3>
		<strong>Progress</strong>
		<div class="num_progress3"></div>
		<table width="100%" border="0" cellspacing="0" cellpadding="3" style="margin-top:10px;">
		<tr>
			<td width="100%"><div class="progress_bar"><div class="progress_bar_2"></div></div></td>
		</tr>
		</table>
	</td>
</tr>
</table>

<?
	set_error_handler(E_ALL);
	// Setup
	$Begin = time();
	$RefreshRate = 0.1;
	flush();
	$Rows = 0;
	
	// Folders
	#------------------------------------------------------------------------------------------------------------------	   
	// Parse Item Names
	$File = 'json/bnpc_names.json';
	$Lines = file_get_contents($File);		// Get Contents
	$Lines = json_decode($Lines, true);			// Explode Contents to array
	
	
	// Update Status
	$Total_Items = count($Lines);
	
	$Start = microtime_float();
	$Current = $Start;
	flush();
	
	// Parse csv line
	$i = 0;
	
	//print_r($Lines);
	
	
	/******************************************************************************************************/
	/******************************************************************************************************/
	/*										Mobs														  */
	/******************************************************************************************************/
	/******************************************************************************************************/
	
	/*$SQL = " INSERT INTO models (ID, Model, Name_EN, Name_DE, Name_FR, Name_JP, Type, Tag) VALUES ";
	$SQL_Values = array();
	
	foreach($Lines as $ID => $Line)
	{
		$Name = $Line["name"];
		
		if(!empty($Name["en"]))
		{	
			$Name_EN = parse_me($Name["en"]);
			$Name_DE = parse_me($Name["de"]);
			$Name_FR = parse_me($Name["fr"]);
			$Name_JA = parse_me($Name["ja"]);

			$m = NULL;
			$b = NULL;
			$Model = 0;
			$m = $Line["infos"]["value"]["model"]["value"]["m_value"];
			$b = $Line["infos"]["value"]["model"]["value"]["b_value"];
			
			if($m && $b)
			{
				$m = str_pad($m, 4, '0', STR_PAD_LEFT);
				$b = str_pad($b, 4, '0', STR_PAD_LEFT);
				
				
				$Model = "chara/monster/m".$m."/obj/body/b".$b."/model/m".$m."b".$b.".mdl";
			
				
				$SQL_Values[] =  "(".$ID.", '".$Model."', '".$Name_EN."', '".$Name_DE."', '".$Name_FR."', '".$Name_JA."', 'MOB', '".$PATCH_TAG."')";
			}
		
		}
	
		$i++;
		// Update Display
		$Difference = (microtime_float() - $Current);
		
		if ($Difference > $RefreshRate)
		{
			$Progress = (round($i / $Total_Items, 2) * 100);
			echo '<script>
				$(".num_progress1").html("<strong>'. $i .'</strong> / '. $Total_Items .'");
				$(".progress_bar_1").animate({"width":"'. $Progress .'%"}, 150);
			</script>';
			flush();
			$Current = microtime_float();
		}
	}
	
	$Progress = (round($i / $Total_Items, 2) * 100);
	echo '<script>
		$(".num_progress1").html("<strong>'. $i .'</strong> / '. $Total_Items .'");
		$(".progress_bar_1").animate({"width":"'. $Progress .'%", "background-color":"#3FA800"}, 150);
	</script>';
	flush();
	
	$SQL .= implode (",", $SQL_Values). " ON DUPLICATE KEY UPDATE 
															  Model=VALUES(Model)
															, Name_EN=VALUES(Name_EN)
															, Name_DE=VALUES(Name_DE)
															, Name_FR=VALUES(Name_FR)
															, Name_JP=VALUES(Name_JP)";
	
	//print_r($SQL);
	
	$DB->IUSQL($SQL);
	*/
	/******************************************************************************************************/
	/******************************************************************************************************/
	/*										NPC															  */
	/******************************************************************************************************/
	/******************************************************************************************************/
	/*$File = 'json/enpc_residents.json';
	$Lines = file_get_contents($File);		// Get Contents
	$Lines = json_decode($Lines, true);			// Explode Contents to array
	
	
	// Update Status
	$Total_Items = count($Lines);
	
	$Start = microtime_float();
	$Current = $Start;
	flush();
	
	// Parse csv line
	$i = 0;
	$SQL = " INSERT INTO models (ID, Model, Name_EN, Name_DE, Name_FR, Name_JP, Type, Tag) VALUES ";
	$SQL_Values = array();
	
	foreach($Lines as $ID => $Line)
	{
		$Name = $Line["name"];
		$Model = $Line["infos"]["value"]["model"];
		
		if(!empty($Name["en"]) && !empty($Model))
		{	
			$Name_EN = parse_me($Name["en"]);
			$Name_DE = parse_me($Name["de"]);
			$Name_FR = parse_me($Name["fr"]);
			$Name_JA = parse_me($Name["ja"]);
			
			$Model = dechex($Model);
			$Model = str_pad($Model, 12, '0', STR_PAD_LEFT);
			$w = substr($Model, -4);
			$b = substr($Model, -8, 4);
			$WeaponFlag = substr($Model, 0, 4);
			
			$w = hexdec($w);
			$w = str_pad($w, 4, '0', STR_PAD_LEFT);
			$b = hexdec($b);
			$b = str_pad($b, 4, '0', STR_PAD_LEFT);
			$WeaponFlag = hexdec($WeaponFlag);
			
			//Show($Name["en"]." ".$Model." b:".$b." w:".$w." wf: ".$WeaponFlag);

			$Model = "chara/accessory/a".$w."/model/c0101a".$w."_".$Suffix.".mdl";
			
			
			$SQL_Values[] =  "(".$ID.", '".$Model."', '".$Name_EN."', '".$Name_DE."', '".$Name_FR."', '".$Name_JA."', 'NPC', '".$PATCH_TAG."')";
		
		}
	
		$i++;
		// Update Display
		$Difference = (microtime_float() - $Current);
		
		if ($Difference > $RefreshRate)
		{
			$Progress = (round($i / $Total_Items, 2) * 100);
			echo '<script>
				$(".num_progress2").html("<strong>'. $i .'</strong> / '. $Total_Items .'");
				$(".progress_bar_2").animate({"width":"'. $Progress .'%"}, 150);
			</script>';
			flush();
			$Current = microtime_float();
		}
	}
	
	$Progress = (round($i / $Total_Items, 2) * 100);
	echo '<script>
		$(".num_progress2").html("<strong>'. $i .'</strong> / '. $Total_Items .'");
		$(".progress_bar_2").animate({"width":"'. $Progress .'%", "background-color":"#3FA800"}, 150);
	</script>';
	flush();
	
	$SQL .= implode (",", $SQL_Values). " ON DUPLICATE KEY UPDATE 
															Type=VALUES(Type)
															, Name_EN=VALUES(Name_EN)
															, Name_DE=VALUES(Name_DE)
															, Name_FR=VALUES(Name_FR)
															, Name_JP=VALUES(Name_JP)";
	
	//print_r($SQL);
	
	$DB->IUSQL($SQL);	
	*/

	#Translation for slots
	$Translation = array(
			/*"Pugilist's Arm" 					*/		1	=> "Main Hand" /*738*/												
			,/*"Gladiator's Arm"				*/		2	=> "Main Hand" /*738*/                              
			,/*"Marauder's Arm" 				*/		3	=> "Main Hand" /*738*/                               
			,/*"Archer's Arm" 					*/		4	=> "Main Hand" /*738*/                                 
			,/*"Lancer's Arm" 					*/		5	=> "Main Hand" /*738*/                                 
			,/*"Thaumaturge's Arm" 				*/		6	=> "Main Hand" /*738*/                            
			,/*"Two-Handed Thaumaturge's Arm" 	*/		7	=> "Main Hand" /*738*/                     
			,/*"Conjurer's Arm" 				*/		8	=> "Main Hand" /*738*/                               
			,/*"Two-Handed Conjurer's Arm" 		*/		9	=> "Main Hand" /*738*/                     
			,/*"Arcanist's Grimoire" 		   */       10  => "Main Hand" /*738*/	                   
			,/*"Shield"		                   */      	11	=> "Off Hand" /*739*/	                   
			,/*"Carpenter's Primary Tool"      */  		12	=> "Main Hand" /*738*/                     
			,/*"Blacksmith's Primary Tool"     */  		14	=> "Main Hand" /*738*/                     
			,/*"Armorer's Primary Tool"		   */       16	=> "Main Hand" /*738*/                     
			,/*"Goldsmith's Primary Tool" 		*/      18  => "Main Hand" /*738*/                     
			,/*"Leatherworker's Primary Tool" 	*/	    20	=> "Main Hand" /*738*/                     
			,/*"Weaver's Primary Tool" 		   */       22	=> "Main Hand" /*738*/                     
			,/*"Alchemist's Primary Tool" 		*/      24  => "Main Hand" /*738*/                     
			,/*"Culinarian's Primary Tool" 		*/		26	=> "Main Hand" /*738*/	                   
			,/*"Miner's Primary Tool" 		   */       28	=> "Main Hand" /*738*/			           
			,/*"Botanist's Primary Tool" 		*/      30  => "Main Hand" /*738*/		               
			,/*"Fisher's Primary Tool" 		   */       32	=> "Main Hand" /*738*/			           
			,/*"Carpenter's Secondary Tool" 	*/	    13  => "Off Hand" /*739*/                     
			,/*"Blacksmith's Secondary Tool" 	*/		15 	=> "Off Hand" /*739*/                     
			,/*"Armorer's Secondary Tool" 		*/      17  => "Off Hand" /*739*/                     
			,/*"Goldsmith's Secondary Tool" 	*/	    19  => "Off Hand" /*739*/                     
			,/*"Leatherworker's Secondary Tool"*/		21  => "Off Hand" /*739*/                     
			,/*"Weaver's Secondary Tool" 		*/      23  => "Off Hand" /*739*/	                   
			,/*"Alchemist's Secondary Tool" 	*/	    25  => "Off Hand" /*739*/                     
			,/*"Culinarian's Secondary Tool" 	*/	    27  => "Off Hand" /*739*/                     
			,/*"Miner's Secondary Tool" 		*/  	29	=> "Off Hand" /*739*/                     
			,/*"Botanist's Secondary Tool" 		*/      31	=> "Off Hand" /*739*/                     
			,/*"Fisher's Secondary Tool" 		*/      33 	=> "Off Hand" /*739*/                     
			,/*"Throwing Weapon"               */   	100	=> "Off Hand" /*752*/                     
			,/*"Head" 		                   */     	34	=> "Head"			/*740 */                    
			,/*"Body" 		                   */       35 	=> "Body" 		  	/*741 */                    
			,/*"Hands" 		                   */      	37	=> "Hands" 		  	/*742*/                   
			,/*"Waist"		                   */      	39	=> "Waist"		 	/*743 */                    
			,/*"Legs" 		                   */       36 	=> "Legs" 		  	/*744 */                    
			,/*"Feet" 		                   */       38	=> "Feet" 		  	/*745 */                    
			,/*"Undershirt" 		           */       101 => "Undershirt" 	/*753 */                    
			,/*"Undergarment" 		           */       102 => "Undergarment" 	/*754 */                    
			,/*"Earrings"		               */      	41  => "Ear"			/*746 */                    
			,/*"Necklace" 		               */      	40	=> "Neck" 			/*747 */                    
			,/*"Bracelets"		               */      	42  => "Wrists"		/*748 */                    
			,/*"Ring"		                   */      	43  => "Ring"		  	/*749 */                    
	);                                  
	
	/******************************************************************************************************/
	/******************************************************************************************************/
	/*										Items														  */
	/******************************************************************************************************/
	/******************************************************************************************************/
	$File = 'json/items.json';
	$Lines = file_get_contents($File);		// Get Contents
	$Lines = json_decode($Lines, true);			// Explode Contents to array
	
	
	// Update Status
	$Total_Items = count($Lines);
	
	$Start = microtime_float();
	$Current = $Start;
	flush();
	
	
	
	
	// Parse csv line
	$i = 0;
	$SQL = " INSERT INTO models (ID, Icon, Model, Material, Slot, Name_EN, Name_DE, Name_FR, Name_JP, Type, Tag) VALUES ";
	$SQL_Values = array();
	
	foreach($Lines as $ID => $Line)
	{
		

		$Icon = $Line["icon"];
		$Name = $Line["name"];
		$Model = $Line["model"];
		$ItemCat = $Line["item_ui_category"]["id"];
		
		
		if(!empty($Name["en"]) && !empty($Model))
		{	
		
			/* 	weap: 
					chara/weapon/w0201/obj/body/b0013/model/w0201b0013.mdl
				gear:
					chara/equipment/e0201/model/e0201b0013.mdl
				acce:
					chara/accessory/a0001/model/c0201a0001_nek.mdl
														_rir
														_ear
														_wrs
			
			
			$hexify = pack("H*", implode('', $Model));*/
			
			$Model = dechex($Model);
			$Model = str_pad($Model, 12, '0', STR_PAD_LEFT);
			$w = substr($Model, -4);
			$b = substr($Model, -8, 4);
			$WeaponFlag = substr($Model, 0, 4);
			
			$w = hexdec($w);
			$w = str_pad($w, 4, '0', STR_PAD_LEFT);
			$b = hexdec($b);
			$b = str_pad($b, 4, '0', STR_PAD_LEFT);
			$WeaponFlag = hexdec($WeaponFlag);
			
			Show($Name["en"]." ".$Model." b:".$b." w:".$w." wf: ".$WeaponFlag);
			
			
			
			$Material = $b;

			//Hardcoded for now
				$Material = 1;
			
			if(in_array($ItemCat, array(40, 41, 42 ,43)))
			{						
				// is accessory
				switch($ItemCat)
				{
					CASE 40:											
						$Suffix = 'nek';                                
					BREAK;                                              
					CASE 41:     
						$Suffix = 'ear'; 								
					BREAK;                                              
					CASE 42:
						$Suffix = 'wrs'; 
					BREAK;
					CASE 43:
						$Suffix = 'rir'; 
					BREAK;
				}
				$Model = "chara/accessory/a".$w."/model/c0101a".$w."_".$Suffix.".mdl";
			}
			elseif($WeaponFlag>0) // is weapon
			{
				$Model = "chara/weapon/w".$w."/obj/body/b".$b."/model/w".$w."b".$b.".mdl";
				$WeaponFlag = str_pad($WeaponFlag, 4, '0', STR_PAD_LEFT);
				$Material = $WeaponFlag;
			}
			else // else equipment
			{
				switch($ItemCat)
				{
					CASE 38:											
						$Suffix = 'sho';            				                    
					BREAK;                                      	        
					CASE 36:                                    	
						$Suffix = 'dwn'; 									
					BREAK;                                      	        
					CASE 35:                                    	
						$Suffix = 'top'; 
					BREAK;
					CASE 34:
						$Suffix = 'met'; 
					BREAK;
					CASE 37:
						$Suffix = 'glv'; 
					BREAK;
				}
				
				$Model = "chara/equipment/e".$w."/model/c0101e".$w."_".$Suffix.".mdl";
				$Material = $b;
			}
		
			$Name_EN = parse_me($Name["en"]);
			$Name_DE = parse_me($Name["de"]);
			$Name_FR = parse_me($Name["fr"]);
			$Name_JA = parse_me($Name["ja"]);
			
			
			Show(stripslashes(strtolower($Translation[$ItemCat])));
			
			$SQL_Values[] =  "(".$ID.", '".$Icon."','".$Model."', '".$Material."', '".stripslashes(strtolower($Translation[$ItemCat]))."' , '".$Name_EN."', '".$Name_DE."', '".$Name_FR."', '".$Name_JA."', 'ITEM', '".$PATCH_TAG."')";
		
		}
	
		$i++;
		// Update Display
		$Difference = (microtime_float() - $Current);
		
		if ($Difference > $RefreshRate)
		{
			$Progress = (round($i / $Total_Items, 2) * 100);
			echo '<script>
				$(".num_progress3").html("<strong>'. $i .'</strong> / '. $Total_Items .'");
				$(".progress_bar_3").animate({"width":"'. $Progress .'%"}, 150);
			</script>';
			flush();
			$Current = microtime_float();
		}
	}
	
	$Progress = (round($i / $Total_Items, 2) * 100);
	echo '<script>
		$(".num_progress3").html("<strong>'. $i .'</strong> / '. $Total_Items .'");
		$(".progress_bar_3").animate({"width":"'. $Progress .'%", "background-color":"#3FA800"}, 150);
	</script>';
	flush();
	
	$SQL .= implode (",", $SQL_Values). " ON DUPLICATE KEY UPDATE 
															  Type=VALUES(Type)
															, Icon=VALUES(Icon)
															, Slot=VALUES(Slot)
															, Model=VALUES(Model)
															, Material=VALUES(Material)
															, Name_EN=VALUES(Name_EN)
															, Name_DE=VALUES(Name_DE)
															, Name_FR=VALUES(Name_FR)
															, Name_JP=VALUES(Name_JP)";


	$DB->IUSQL($SQL);
	
	
	
	
	echo "<h2>Complete</h2>";
	
	
?>