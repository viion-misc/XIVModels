<?

$Url = PageURL();

$Url = explode('/', explode('?', $Url)[1]);

//Show($Url);
if($Url[0] == 'group')
	$UnHash = explode(',', base64_decode($Url[1]));
else
	$UnHash = array($Url[1], $Url[0]);

$Hash = array();
//Show($UnHash);

for($i=0;$i<=count($UnHash);$i+=2)
{
	$TYPE = strtolower($UnHash[$i+1]);
	$ID = $UnHash[$i];
	
	if(!$TYPE || !$ID)
		CONTINUE;

	//Show($TYPE);
	switch($TYPE)
	{
		CASE 'item':
			$RES = $DB->SQL("SELECT ID, Icon, Model, Material, Slot, Name_".$LAN[$Language]." AS NAME, Type AS TYPE FROM models WHERE ID = '".$ID."' AND Type='".strtoupper($TYPE)."'")[0];
			//Show($RES);
			if($RES)
				$Hash[] = array(
				"id"		=> $RES["ID"]
				,"model"	=> $RES["Model"]
				,"icon" 	=> Iconize($RES["Icon"])
				,"type"		=> $RES["TYPE"]
				,"material"	=> $RES["Material"]
				,"menu"		=> 'player'
				,"type"		=> 'item'
				,"name" 	=> $RES["NAME"]
				,"slot" 	=> $RES["Slot"]
				);
		break;
		CASE 'monster':
		CASE 'pet':
		CASE 'primal':
		CASE 'summon':
			$RES = $DB->SQL("SELECT ID, Icon, Model, Material, Slot, Name_".$LAN[$Language]." AS NAME, Type AS TYPE FROM models_all WHERE ID = '".$ID."' AND Type='".strtoupper($TYPE)."'")[0];
			
			if($RES)	
				$Hash[] = array(
				"id"		=> $RES["ID"]
				,"model"	=> $RES["Model"]
				,"icon" 	=> $RES["Icon"]
				,"type"		=> $RES["TYPE"]
				,"material"	=> $RES["Material"]
				,"menu"		=> strtolower($TYPE)
				,"type"		=> strtolower($TYPE)
				,"name" 	=> $RES["NAME"]
				,"slot"		=> "body"
				);
		break;
		CASE 'beastman':
			$RES = $DB->SQL("SELECT ID, Icon, Body, Hands, Legs, Head, Material, Slot, Name_".$LAN[$Language]." AS NAME, Type AS TYPE FROM models_human WHERE ID = '".$ID."' AND Type='".strtoupper($TYPE)."'")[0];
		
			if($RES)
			{	
				$Parts = array("Body", "Hands", "Legs", "Head");
				foreach($Parts AS $Part)
				{
						
					$Hash[] = array(
					"id"		=> $RES["ID"]
					,"model"	=> $RES[$Part]
					,"icon" 	=> $RES["Icon"]
					,"type"		=> $RES["TYPE"]
					,"material"	=> $RES["Material"]
					,"menu"		=> strtolower($TYPE)
					,"type"		=> strtolower($TYPE)
					,"name" 	=> $RES["NAME"]
					,"slot"		=> $Part
					);
				}
			}
		break;
		CASE 'mount':
			$RES = $DB->SQL("SELECT ID, Icon, Model, Material, Slot, Name_".$LAN[$Language]." AS NAME, Type AS TYPE FROM models_all WHERE ID = '".$ID."' AND Type='".strtoupper($TYPE)."'")[0];
			
			if($RES)	
				$Hash[] = array(
				"id"		=> $RES["ID"]
				,"model"	=> $RES["Model"]
				,"icon" 	=> $RES["Icon"]
				,"type"		=> $RES["TYPE"]
				,"material"	=> $RES["Material"]
				,"menu"		=> strtolower($TYPE)
				,"type"		=> strtolower($TYPE)
				,"name" 	=> $RES["NAME"]
				,"slot"		=> "body"
				);
				
			$RES = $DB->SQL("SELECT ID, Icon, Body, Hands, Legs, Head, Material, Slot, Name_".$LAN[$Language]." AS NAME, Type AS TYPE FROM models_human WHERE ID = '".$ID."' AND Type='".strtoupper($TYPE)."'")[0];
		
			if($RES)
			{	
				$Parts = array("Body", "Hands", "Legs", "Head");
				foreach($Parts AS $Part)
				{
						
					$Hash[] = array(
					"id"		=> $RES["ID"]
					,"model"	=> $RES[$Part]
					,"icon" 	=> $RES["Icon"]
					,"type"		=> $RES["TYPE"]
					,"material"	=> $RES["Material"]
					,"menu"		=> strtolower($TYPE)
					,"type"		=> strtolower($TYPE)
					,"name" 	=> $RES["NAME"]
					,"slot"		=> $Part
					);
				}
			}
		
		break;
	}
}
//Show($Hash);
?>

<script>
	var UrlModel = {
	
		<?
		if(!empty($Hash))
		{
		?>
			Hash: "<? echo base64_encode(json_encode($Hash));?>"
			
		<?
		}
?>
	}
</script>
