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
	/*										Parse model files											  */
	/******************************************************************************************************/
	/******************************************************************************************************/
	//error_reporting(E_ALL);
	
	
	$SQL = " INSERT INTO models_all (ID, Model, Material, Name_EN, Name_DE, Name_FR, Name_JP, Type, Icon) VALUES ";
	$SQL_Values = array();
	$i = 0;
	
	$di = new RecursiveDirectoryIterator("../chara/monster");
	foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
		if ($file->getFilename() != "." && $file->getFilename() != ".." && $file->getFilename() != ".DS_STORE") {
			//only interested in real files
			
			$fileArray = explode('.', $file->getFilename());
			
			if($fileArray[1] == 'mtrl')
			{
				print_r($file->getPath()."<br>");
				$PathArray = explode('/',$file->getPath());
				
				// only interested in materials
				//print $file->getFilename();
				
				$m = substr($PathArray[3], -4);
				$b = substr($PathArray[6], -4);
				$material = (int)substr($PathArray[8],-4);
				
				if($m < 7000)
					$Type = 'MONSTER';
				else if($m >= 7000 && $m < 8000)
					$Type = 'SUMMON';
				else if($m > 8000  && $m < 9000)
					$Type = 'PET';
				else if($m >= 9000)
					$Type = 'MONSTER';
					
				$Model = "chara/monster/m".$m."/obj/body/b".$b."/model/m".$m."b".$b.".mdl";
				
				$ID = "m".$m."b".$b."_".$material;

				Show("ID :" .$ID." | TYPE: ".$Type. " FUNC | ".translate_name($ID, "TYPE"));
				$SQL_Values[] =  "('".$ID."', '".$Model."', '".$material."', '".translate_name($ID, "en")."', '".translate_name($ID, "de")."', '".translate_name($ID, "fr")."', '".translate_name($ID, "jp")."', '".($ID==translate_name($ID, "TYPE")?$Type:translate_name($ID, "TYPE"))."', '".$ICON."')";
				
			}
		
		}
	}
	
	
	$SQL .= implode (",", $SQL_Values). " ON DUPLICATE KEY UPDATE 
															  Model=VALUES(Model)
															, Type=VALUES(Type)
															, Material=VALUES(Material)
															, Icon=VALUES(Icon)
															, Name_DE=VALUES(Name_DE)
															, Name_FR=VALUES(Name_FR)
															, Name_FR=VALUES(Name_FR)
															, Name_JP=VALUES(Name_JP)";
	
	//print_r($SQL);
	
	$DB->IUSQL($SQL);
	
	
	
	echo "<h2>Complete</h2>";
	
	
?>