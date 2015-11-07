<?	
#Handle Subdomains i.e de.xivmodels.com
$LangArray = array ("jp" => 0, "en" => 1, "de" => 2, "fr" => 3);

# Language
$LangString = explode(".", $_SERVER[HTTP_HOST])[0];
if($LangString != "jp" && $LangString != "en" && $LangString != "de" && $LangString != "fr")  
{	
	$Language = substr(trim(@$_COOKIE['XIVMODELS:Language']), 0, 1);
	if($Language == NULL) $Language = 1;
}else
	$Language = $LangArray[$LangString];

setcookie('XIVMODELS:Language', $Language, time()+(60*60*24*30*12), '/');
setcookie('XIVMODELS:Language', $Language, time()+(60*60*24*30*12), '/', '.xivmodels.com');
?>