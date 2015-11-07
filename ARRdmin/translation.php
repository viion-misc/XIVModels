<h1>Translations</h1>

<form method="post" enctype="multipart/form-data">

	<input name="file" type="file" />
    <input name="" type="submit" value="Read" />

</form>


<?

include "functions.php";

if ($_FILES["file"]["error"] > 0)
{
	echo "Error: " . $_FILES["file"]["error"] . "<br>";
}
else if ($_FILES["file"])
{
	//print_r($_FILES["file"]);
	

	// Save File
	$TempName = $_FILES["file"]["tmp_name"];
	$Filename = $_FILES["file"]["name"];
	move_uploaded_file($TempName, $Filename);
	
	// Open File
	$Contents = file_get_contents($Filename);
	//print_r($Contents);
	
	// Split to lines
	$Contents = explode("\n", $Contents);
	
	//print_r($Contents);
	
	// Loop
	$Translations = array();
	$s = 5;
	$i = 0;
	foreach($Contents as $C)
	{
		if ($i > $s) 
		{
			
			$csv = str_getcsv($C, ',', '"');
			$EN = $csv[1];
			$DE = $csv[2];
			$FR = $csv[3];
			$JP = $csv[4];
			
			if ($DE) { $Translations['2'][trim(addslashes($EN))] = "'". addslashes(trim($DE)) ."',"; Show("DE: ". $EN ." => ". trim($DE)); }
			if ($FR) { $Translations['3'][trim(addslashes($EN))] = "'". addslashes(trim($FR)) ."',"; Show("FR: ". $EN ." => ". trim($FR)); }
			if ($JP) { $Translations['0'][trim(addslashes($EN))] = "'". addslashes(trim($JP)) ."',"; Show("JP: ". $EN ." => ". trim($JP)); }
		}
		$i++;
	}
	
	$Context = print_r($Translations, true);
	$Context = str_ireplace("[", '"', $Context);
	$Context = str_ireplace("]", '"', $Context);
	$Context = str_ireplace("Array", "array", $Context);
	//$Context = str_ireplace('"2"', ',"2"', $Context);
	$Context = str_ireplace('"3"', ',"3"', $Context);
	$Context = str_ireplace('"0"', ',"0"', $Context);
	$Context = '$TranslationArray = '. $Context;
	
	$p1 = '<? function TranslateWord($Word, $Language) { ';
	$p2 = '; $NewWord = $TranslationArray[$Language][addslashes($Word)]; if (empty($NewWord)) { return $Word; } else { return $NewWord; } } ?>';
	
	unlink("../includes/translate_csv.php");
	file_put_contents("../includes/translate_csv.php", $p1. $Context .$p2);
	chmod("../includes/translate_csv.php", 0775);
	
	Show("--- js ---");
	
	// JS Loop
	$Translations = array();
	$s = 5;
	$i = 0;
	foreach($Contents as $C)
	{
		if ($i > $s) 
		{
			$csv = str_getcsv($C, ',', '"');
			$EN = $csv[1];
			$DE = $csv[2];
			$FR = $csv[3];
			$JP = $csv[4];
			$js = $csv[5];
			
			if ($js)
			{
				Show("JP: ". $EN ." => ". trim($JP));
				$Translations[] = "arr['". trim(addslashes($EN)) ."'] = new Array('". addslashes(trim($JP)) ."', '". $EN ."', '". addslashes(trim($DE)) ."', '". addslashes(trim($FR)) ."');"; 
			}
			
		}
		$i++;
	}
	
	$Context = NULL;
	foreach($Translations as $T)
	{
		$Context .= $T ."\n";
	}
	
	$p1 = "Translate = { TranslateWord: function(s) { var arr = {}; \n ";	
	$p2 = "\n if(s in arr) { var l = Cookies.get('Language'); var nw = arr[s][l]; if (!nw) { nw = s; } return nw; } else { return s; } } };";
	$JSCode = str_ireplace("\n", " ", $p1. $Context .$p2);
	
	unlink("../scripts/translate_csv.js");
	file_put_contents("../scripts/translate_csv.js", $JSCode);
	chmod("../scripts/translate_csv.js", 0775);
	
	echo "<h2>Complete</h2>";
}

?>