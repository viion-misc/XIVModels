<? require_once("functions.php"); ?>
<form method="post" enctype="multipart/form-data">

	<input name="file" type="file" />
    <input name="" type="submit" value="Read" />

</form>

<h2>Models</h2>
<h2>Translation</h2>
<?

	set_error_handler(E_ALL);
	/******************************************************************************************************/
	/******************************************************************************************************/
	/*										Build translation array										  */
	/******************************************************************************************************/
	/******************************************************************************************************/
if(!$_FILES["file"])
die();
	
if ($_FILES["file"]["error"] > 0)
{
	echo "Error: " . $_FILES["file"]["error"] . "<br>";
	
}
else if ($_FILES["file"])
{
	
	// Save File
	$TempName = $_FILES["file"]["tmp_name"];
	$Filename = $_FILES["file"]["name"];
	move_uploaded_file($TempName, $Filename);
	// Open File
	$Contents = file_get_contents($Filename);

	// Split to lines
	$Contents = explode("\n", $Contents);

	// Loop
	$Translations = array();
	$s = 6;
	$i = 0;
	foreach($Contents as $C)
	{
		if ($i > $s) 
		{
			$csv = str_getcsv($C, ',', '"');
			$ID 	= $csv[0];
			$EN 	= $csv[1];
			$DE 	= $csv[2];
			$FR 	= $csv[3];
			$JP 	= $csv[4];
			$TYPE 	= $csv[5];
			
			if ($EN){$Translations[$ID]["en"] = addslashes(trim($EN)); /*Show("EN: ". $EN ." => ". trim($EN));*/}
			if ($DE){$Translations[$ID]["de"] = addslashes(trim($DE)); /*Show("DE: ". $DE ." => ". trim($DE));*/}
			if ($FR){$Translations[$ID]["fr"] = addslashes(trim($FR)); /*Show("FR: ". $FR ." => ". trim($FR));*/}
			if ($JP){$Translations[$ID]["jp"] = addslashes(trim($JP)); /*Show("JP: ". $JP ." => ". trim($JP));*/}
			if ($TYPE){$Translations[$ID]["TYPE"] = strtoupper($TYPE); 			   /*Show("TYPE: ". $TYPE ." => ". trim($TYPE));*/}
		}
		$i++;
	}

//print_r($Translations);
	
	echo "<h2>Complete</h2>";
}
	$ICON = "images/misc/mob.png";


	function translate_name($ID, $LAN)
	{
		global $Translations;
		//print_r($Translations[$ID][$LAN]);
		return $Translations[$ID][$LAN]?$Translations[$ID][$LAN]:$ID;
	}


	?><h2>Import</h2><?
	/******************************************************************************************************/
	/******************************************************************************************************/
	/*										Parse Demihuman model files											  */
	/******************************************************************************************************/
	/******************************************************************************************************/
	//error_reporting(E_ALL);
	
	
	
	$SQL = " INSERT INTO models_human (ID, Body, Hands, Legs, Head, Material, Name_EN, Name_DE, Name_FR, Name_JP, Type, Icon) VALUES ";
	$SQL_Values = array();
	$i = 0;
	
	$Collection = array();
	
	$di = new RecursiveDirectoryIterator("../chara/demihuman");
	foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
		if ($file->getFilename() != "." && $file->getFilename() != ".." && $file->getFilename() != ".DS_STORE") {
			//only interested in real files
			
			$fileArray = explode('.', $file->getFilename());
			
			if($fileArray[1] == 'mdl')
			{
				 
				//print_r($file->getPath()."<br>");
				$PathArray = explode('/',$file->getPath());
				//../chara/demihuman/d1004/obj/equipment/e0002/model
				// only interested in materials
				//print $file->getFilename();
				
				$d = substr($PathArray[3], -4);
				$e = substr($PathArray[6], -4);
				
				if($d<1000) //ignore mounts for now
					continue;


				Switch(substr($fileArray[0],-3))
				{
					CASE 'top':
						$Model = "chara/demihuman/d".$d."/obj/equipment/e".$e."/model/d".$d."e".$e."_top.mdl";
						$Collection[$d]["TOP"][$e] = $Model;
					break;
					CASE 'met':
						$Model = "chara/demihuman/d".$d."/obj/equipment/e".$e."/model/d".$d."e".$e."_met.mdl";
						$Collection[$d]["MET"][$e] = $Model;
					break;
					CASE 'sho':
						$Model = "chara/demihuman/d".$d."/obj/equipment/e".$e."/model/d".$d."e".$e."_sho.mdl";
						$Collection[$d]["SHO"][$e] = $Model;
					break;
					CASE 'glv':
						$Model = "chara/demihuman/d".$d."/obj/equipment/e".$e."/model/d".$d."e".$e."_glv.mdl";
						$Collection[$d]["GLV"][$e] = $Model;
					break;
				}
		
				//$SQL_Values[] =  "('".$ID."', '".$Model."', '".$material."', '".translate_name($ID, "en")."', '".translate_name($ID, "de")."', '".translate_name($ID, "fr")."', '".translate_name($ID, "jp")."', '".($ID==translate_name($ID, "TYPE")?$Type:translate_name($ID, "TYPE"))."', '".$ICON."')";
				
			}
		
		}
	}
	$i=0;
	foreach($Collection AS $D => $Col)
	{
		$CartesianCol = cartesian($Col);
		Show($CartesianCol);
		foreach($CartesianCol As $FinalCol)
		{
			$material = 1;
			
			IF(!$FinalCol["TOP"]) // needs body attached
				continue;
				
			$ID = "";
			$ID .= explode('/',$FinalCol["TOP"])[5];
			$ID .= explode('/',$FinalCol["MET"])[5];
			$ID .= explode('/',$FinalCol["SHO"])[5];
			$ID .= explode('/',$FinalCol["GLV"])[5];
			
			$ID = strtoupper("D".$D.$ID);
			
			$Type="BEASTMAN";
			
			$SQL_Values[] =  "('".$ID."', '".$FinalCol["TOP"]."', '".$FinalCol["SHO"]."', '".$FinalCol["GLV"]."', '".$FinalCol["MET"]."', '".$material."', '".translate_name($ID, "en")."', '".translate_name($ID, "de")."', '".translate_name($ID, "fr")."', '".translate_name($ID, "jp")."', '".($ID==translate_name($ID, "TYPE")?$Type:translate_name($ID, "TYPE"))."', '".$ICON."')";
			$i++;
		}
	
	}
	
	
	/***********************************************/
	/* 			ADD CUSTOM (MOUNTS ETC)				*/
	/***********************************************/
	
	//Magitek
	$material = 1;
	$Type="MOUNT";
	$ID = "D0002E0001E0001E0001E0001";
	$SQL_Values[] =  "('".$ID."'
						, 'chara/demihuman/d0002/obj/equipment/e0001/model/d0002e0001_top.mdl'
						, 'chara/demihuman/d0002/obj/equipment/e0001/model/d0002e0001_sho.mdl'
						, 'chara/demihuman/d0002/obj/equipment/e0001/model/d0002e0001_dwn.mdl'
						, 'chara/demihuman/d0002/obj/equipment/e0001/model/d0002e0001_met.mdl'
						, '".$material."'
						, '".translate_name($ID, "en")."'
						, '".translate_name($ID, "de")."'
						, '".translate_name($ID, "fr")."'
						, '".translate_name($ID, "jp")."'
						, '".($ID==translate_name($ID, "TYPE")?$Type:translate_name($ID, "TYPE"))."'
						, '".$ICON."')";
	
	/***********************************************/
	/* 			Chocobos						   */
	/***********************************************/
	$material = 1;
	$Type="MOUNT";
	for($i=0; $i<19; $i++)
	{
		if($i ==17 || $i ==18)
			continue;
	
		$e = str_pad($i, 4, 0, STR_PAD_LEFT);
		
		$ID = "D0001E".$e."E".$e."E".$e."E".$e;
		$DWN = 'chara/demihuman/d0001/obj/equipment/e0001/model/d0001e0001_dwn.mdl';
		if($i==9)
			$DWN = 'chara/demihuman/d0001/obj/equipment/e0009/model/d0001e0009_dwn.mdl';
	
		$SQL_Values[] =  "('".$ID."'
						, 'chara/demihuman/d0001/obj/equipment/e".$e."/model/d0001e".$e."_top.mdl'
						, 'chara/demihuman/d0001/obj/equipment/e".$e."/model/d0001e".$e."_sho.mdl'
						, '".$DWN."'
						, 'chara/demihuman/d0001/obj/equipment/e".$e."/model/d0001e".$e."_met.mdl'
						, '".$material."'
						, '".translate_name($ID, "en")."'
						, '".translate_name($ID, "de")."'
						, '".translate_name($ID, "fr")."'
						, '".translate_name($ID, "jp")."'
						, '".($ID==translate_name($ID, "TYPE")?$Type:translate_name($ID, "TYPE"))."'
						, '".$ICON."')";
	}
	
	
	/***********************************************/
	/* 			Naked monsters					   */
	/***********************************************/
	$material = 1;
	$Type="BEASTMAN";

	$BMs = array(
				array("d1007", "e0001")/*Sahagin 1*/
				,array("d1007", "e0002")/*Sahagin 2*/
				,array("d1005", "e0001")/*Sylph 1*/
				,array("d1005", "e0002")/*Sylph 1*/
				
			);
	foreach($BMs AS $BM)
	{
		$ID = strtoupper($BM[0]).strtoupper($BM[1]);
		
		$SQL_Values[] =  "('".$ID."'
					, 'chara/demihuman/".$BM[0]."/obj/equipment/".$BM[1]."/model/".$BM[0]."".$BM[1]."_top.mdl'
					, ''
					, ''
					, ''
					, '".$material."'
					, '".translate_name($ID, "en")."'
					, '".translate_name($ID, "de")."'
					, '".translate_name($ID, "fr")."'
					, '".translate_name($ID, "jp")."'
					, '".($ID==translate_name($ID, "TYPE")?$Type:translate_name($ID, "TYPE"))."'
					, '".$ICON."')";		
	
	}
	
	Show($SQL_Values);
	$SQL .= implode (",", $SQL_Values). " ON DUPLICATE KEY UPDATE 
															 ID=VALUES(ID)
															, Type=VALUES(Type)
															, Body=VALUES(Body)
															, Hands=VALUES(Hands)
															, Icon=VALUES(Icon)
															, Legs=VALUES(Legs)
															, Head=VALUES(Head)
															, Material=VALUES(Material)
															, Name_EN=VALUES(Name_EN)
															, Name_DE=VALUES(Name_DE)
															, Name_FR=VALUES(Name_FR)
															, Name_JP=VALUES(Name_JP)";
	
	//print_r($SQL);
	
	$DB->IUSQL($SQL);
	
	
	
	echo "<h2>Complete</h2>";
	
	
?>