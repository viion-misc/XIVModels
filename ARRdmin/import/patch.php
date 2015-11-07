 <?
	#POST VAR

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		
		if($_POST["PATCH_TAG_ACTION"] == "update")
			$DB->Update("patch", array("Name = '".$_POST["PATCH_TAG_NAME"]."'"), array("AUTO = ".$_POST["PATCH_TAG_ID"]));
		
		if($_POST["PATCH_TAG_ACTION"] == "insert")
			$DB->Insert("patch", array("Name" => $_POST["PATCH_TAG_NAME"]));
			
		if($_POST["PATCH_TAG_ACTION"] == "delete")
			$DB->Remove("patch",  array("AUTO = ".$_POST["PATCH_TAG_ID"]));
	}
	
	#GET Patch tag
	$TAGS = $DB->GetData("patch", array("Name", "AUTO"), true);
		
		//print_r($TAGS);
?>
	
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<form method="post" action="">

	<? foreach($TAGS["data"] AS $TAG): ?>
	<tr>
		<form method="POST" action="">
		<td width="5%" >
			ID: 
		</td>
		<td width="5%" >
			<? echo $TAG["AUTO"]?>
			<input type="hidden" name="PATCH_TAG_ID" value="<? echo $TAG["AUTO"]?>"><br>
		</td>
		<td width="50%" style="padding-top: 13px;">
			<input type="text" name="PATCH_TAG_NAME" value="<? echo $TAG["Name"]?>" style="width:80%"><br>
			<input type="hidden" name="PATCH_TAG_ACTION" value="update"><br>
		</td>
		<td>
			<input type="submit" value="Save"><br>
		</td>
		</form>
	</tr>
	<? endforeach; ?>
	<tr>
	<td colspan="4">
		<br>
		<br>
		<hr>
		<br>
		<br>
	</td>
	</tr>
	<tr>
		<form method="POST" action="">
		<td width="10%" colspan="2">
			ID: 
		</td>
		<td width="50%" style="padding-top: 13px;">
			<input type="text" name="PATCH_TAG_NAME"  style="width:80%"><br>
			<input type="hidden" name="PATCH_TAG_ACTION" value="insert"><br>
		</td>
		<td width="20%" colspan="2">
			<input type="submit" value="Save"><br>
		</td>
		</form>
	</tr>
</table>