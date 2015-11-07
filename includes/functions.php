<?
function wwwRedirect()
{
	if (explode(".", parse_url(getURL())['host'])[0] == 'www')
	{
		header("location: ". str_ireplace("www.", "", getURL()));
	}
}



function xyToPixel($x, $y)
{
	$x = $x * 50;
	$y = $y * 50;

	return array("x" => $x, "y", $y);

}

// Returns an array of classes.
function GetClassList()
{
        $classes = array("Archer", "Gladiator",  "Lancer", "Marauder", "Pugilist",
						  "Thaumaturge", "Conjurer", "Arcanist",                                                       						//      War
						 "Thaumaturge", "Conjurer",                                                                         	 			//      Magic
						 "Alchemist", "Armorer", "Blacksmith", "Carpenter", "Culinarian",  "Goldsmith", "Leatherworker", "Weaver",       	//      Hand
						 "Botanist", "Fisher", "Miner");                                                                                 	//      Land
                                         
        return $classes;
}
 
function GetClassListByDicipline()
{
        $classes = array("Dicipline of War" => array("Archer", "Gladiator",  "Lancer", "Marauder", "Pugilist"),                                                       	 //      War
						 "Dicipline of Magic" => array("Thaumaturge", "Conjurer", "Arcanist"),                                                                        	//      Magic
						 "Dicipline of Hand" => array("Alchemist", "Armorer", "Blacksmith", "Carpenter", "Culinarian",  "Goldsmith", "Leatherworker", "Weaver"),			 //      Hand
						 "Dicipline of Land" => array("Botanist", "Fisher", "Miner"));                                                                                	//      Land
                                        
        return $classes;
}


function GetClassIDList()
{
		$ClassArray = array();
		$ClassArray["Adventurer"] 	= "0";
		$ClassArray["Gladiator"] 		= "1";
		$ClassArray["Pugilist"] 		= "2";
		$ClassArray["Marauder"] 		= "3";
		$ClassArray["Lancer"] 		= "4";
		$ClassArray["Archer"] 		= "5";
		$ClassArray["Conjurer"] 		= "6";
		$ClassArray["Thaumaturge"] 	= "7";
		
		$ClassArray["Carpenter"] 		= "8";
		$ClassArray["Blacksmith"] 	= "9";
		$ClassArray["Armorer"] 		= "10";
		$ClassArray["Goldsmith"] 		= "11";
		$ClassArray["Leatherworker"] 	= "12";
		$ClassArray["Weaver"] 		= "13";
		$ClassArray["Alchemist"] 		= "14";
		$ClassArray["Culinarian"] 	= "15";
		$ClassArray["Miner"] 			= "16";
		$ClassArray["Botanist"] 		= "17";
		$ClassArray["Fisher"] 		= "18";

		$ClassArray["Paladin"] 		= "19";
		$ClassArray["Monk"] 			= "20";
		$ClassArray["Warrior"] 		= "21";
		$ClassArray["Dragoon"] 		= "22";
		$ClassArray["Bard"] 			= "23";
		$ClassArray["White Mage"] 	= "24";
		$ClassArray["Black Mage"] 	= "25";
		$ClassArray["Arcanist"] 		= "26";
		$ClassArray["Summoner"] 		= "27";
		$ClassArray["Scholar"] 		= "99"; /* REPLACE THIS AS SOON AS CLASS IS OUT */
                                         
        return $ClassArray;
}

function JobToClassByID($ID)
{										
		$ClassArray["19"] 		= "1";		/*["Paladin"] 		*/
		$ClassArray["20"] 		=  "2";       /*["Monk"] 		    */
		$ClassArray["21"] 		=  "3";       /*["Warrior"] 	    */
		$ClassArray["22"] 		=  "4";       /*["Dragoon"] 	    */
		$ClassArray["23"] 		=  "5";       /*["Bard"] 		    */
		$ClassArray["24"] 		=  "6";       /*["White Mage"]    */
		$ClassArray["25"] 		=  "7";       /*["Black Mage"]    */
		$ClassArray["27"] 		= "26";        /*["Summoner"] 	    */
		$ClassArray["99"] 		= "26";        /*["Scholar"] 	    */
                                         
        return $ClassArray[$ID]?$ClassArray[$ID]:NULL;
}

function ClassToJobByID($ID)
{										
		$ClassArray["1"] 		= "19";		/*["Paladin"] 		*/
		$ClassArray["2"] 		= "20";       /*["Monk"] 		    */
		$ClassArray["3"] 		= "21";       /*["Warrior"] 	    */
		$ClassArray["4"] 		= "22";       /*["Dragoon"] 	    */
		$ClassArray["5"] 		= "23";       /*["Bard"] 		    */
		$ClassArray["6"] 		= "24";       /*["White Mage"]    */
		$ClassArray["7"] 		= "25";       /*["Black Mage"]    */
		$ClassArray["26"] 		= "27";        /*["Summoner"] 	    */
		$ClassArray["26"] 		= "99";        /*["Scholar"] 	    */
                                         
        return $ClassArray[$ID]?$ClassArray[$ID]:NULL;
}


# Creates a valid link
function MakeLink($Page, $Arguments)
{
	$Link = "?". strtolower($Page);
	$i = 0;
	foreach($Arguments as $A)
	{
		$Arguments[$i] = str_ireplace(" ", "-", $A);
		$i++;	
	}
	if ($Arguments) { $Link .= '/'. implode("/", $Arguments); }
	return $Link;	
}

# Hash something valid for url
function UrlHash($String)
{
	return base64_encode(json_encode($String, true));
}

# Create correct icon display
function Iconize($Icon, $Name = NULL)
{
	// Format icon
	$Icon = str_pad($Icon, 5, "0", STR_PAD_LEFT);
	$IconDisplay = 'images/icons/0'. $Icon[0] . $Icon[1] .'000/0'. $Icon .'.png';	
	
	// Make sure icon exists
	if (file_exists($IconDisplay))
		$IconDisplay = $IconDisplay;
	else if (file_exists('../'.$IconDisplay))
		$IconDisplay = '../'.$IconDisplay;
	else if (file_exists('../../'.$IconDisplay))
		$IconDisplay = '../../'.$IconDisplay;
	else
		$IconDisplay = 'images/misc/noicon.png';
		
	if ($Name) 
	{
		if (stripos($Name, "+1") !== false) 
		{ 
			$IconDisplay = explode("/", $IconDisplay);
			$IconDisplay[count($IconDisplay) -1] = 'hq/'. $IconDisplay[count($IconDisplay) -1];
			$IconDisplay = implode("/", $IconDisplay);
		}	
	}
	
	// Return	
	return $IconDisplay;
}

# Get the hunting log icon
function HuntingLogIcon($Class, $Language)
{
	// Remove numbers
	$Class = (trim(str_replace(range(0,9), NULL, $Class)));
	
	// Conditional checks
	if ($Class == 'order' || $Class == 'bruderschaft' || $Class == 'ordre' || stripos($Class, '双蛇党') !== false)
	{
		$Icon = "images/20ffxiv/nations/gridania.png";
	}
	else if ($Class == 'maelstrom' || $Class == 'mahlstrom' || stripos($Class, '黒渦団') !== false)
	{
		$Icon = "images/20ffxiv/nations/limsalominsa.png";
	}
	else if($Class == 'immortal' || $Class == 'legion' || $Class == 'immortels' || stripos($Class, '不滅隊') !== false)
	{
		$Icon = "images/20ffxiv/nations/uldah.png";
	}
	else
	{
		$Icon = "images/20ffxiv/classes/". (TranslateClassToEnglish($Class, $Language)) .".png";
	}

	// Return
	return $Icon;
}

# Moves the selected key to the top of the array
function moveToTop($Array, $Key)
{
	// Get the element moving to top
	$Get = $Array[$Key];	
	
	// Only proceed if the element exists
	if ($Get)
	{
		// Null its array value 
		$Array[$Key] = NULL;
		
		// Filter out nulls
		$Array = array_filter($Array);
		
		// Append value to end
		$Array[$Key] = $Get;
		
		// Get end value, move pop it, move it to top.
		end($Array);
		$last_key     = key($Array);
		$last_value   = array_pop($Array);
		$Array         = array_merge(array($last_key => $last_value), $Array);
	}
	
	// Return array
	return $Array;
}

# Moves the selected key to the bottom of the array
function moveToBottom($Array, $Key)
{
	// Get the element moving to top
	$Get = $Array[$Key];
	
	// Only proceed if the element exists
	if ($Get)	
	{
		// Null its array value 
		$Array[$Key] = NULL;
		
		// Filter out nulls
		$Array = array_filter($Array);
		
		// Append value to end
		$Array[$Key] = $Get;
	}
	
	// Return array
	return $Array;
}
# Function to detectu url queries 
function DetectQuery()
{
	// Gets the URL
	$URL = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	
	// parse the url
	$URL_Array = parse_url($URL);

	return $URL_Array[query];
}
	
# Parse a file to remove whitespace and comments, returns an array
function ParseFile($File) {
	
	// Get file contents
	$Data = file_get_contents($File);
	
	// Ignore lines starting with: (blank lines removed automatically)
	$Data = explode("\n", $Data);
	
	// Loop
	$Array = array();
	foreach($Data as $Line) {
		
		// Trim to keep it clean
		$Line = trim($Line);
		
		// If blank
		if (empty($Line)) { continue; }
		
		// If contains #
		if ($Line[0] == '#') { continue; }
		
		// If contains //
		if (($Line[0] . $Line[1]) == '//') { continue; }
		
		$Array[] = $Line;
		
	}
	
	// Return parsed data
	return $Array;
}

	
# Method to get current url (for call back use)
function PageURL()
{
	$pageURL = 'http';
	if (Read($_SERVER, "HTTPS", '') == "on")
		$pageURL .= "s";
		
	$pageURL .= "://";
	
	if ($_SERVER["SERVER_PORT"] != "80") 
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	else
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	return $pageURL;
}

# Method to translate time into formal
function TranslateTime($time, $long = false, $opt = NULL)
{
	$TimeNow = time();
	$TimeThen = $time;
	
	$Difference = $TimeNow - $TimeThen;
	
	# Compare Differences in time for the different outputs 
	#-----------------------------------------------------------------------------------------				Instant
	if ($Difference == 0)
		return '<span style="color:#5FA400;font-weight:bold;">Now!</span>';
	#-----------------------------------------------------------------------------------------				Seconds
	else if ($Difference < 60)
		return '<span style="color:#090;font-weight:bold;">'. $Difference .' seconds ago.</span>';
	#-----------------------------------------------------------------------------------------				Minutes
	else if ($Difference < 3600)
	{
		$Minutes = round($Difference / 60);
		return '<span style="color:#33A4FF;font-weight:bold;">'. $Minutes .' minutes ago.</span>';
	}
	#-----------------------------------------------------------------------------------------				Hours (below 3)
	else if ($Difference < 10800)
	{
		$Hours = floor($Difference / 3600);
		$Seconds = $Difference % 3600;
		$Minutes = floor($Seconds / 60);
		return '<span style="font-weight:bold;">'. $Hours ."hrs ". $Minutes ."mins ago.</span>";
	}
	#-----------------------------------------------------------------------------------------				Hours (above 3)
	else if ($Difference < 86400)
	{
		$Hours = floor($Difference / 3600);
		$Seconds = $Difference % 3600;
		$Minutes = round($Seconds / 60);
		return $Hours ."hrs ". $Minutes ."mins ago.";
	}
	#-----------------------------------------------------------------------------------------				Yesterday
	else if ($Difference < (86400*2))
		return "<em>Yesterday</em>";
	#-----------------------------------------------------------------------------------------				General date/time
	else
	{
		if ($long)
			return date('M d Y, g:i a', $TimeThen);
		else
			return date('M d Y', $TimeThen);
	}
	#-----------------------------------------------------------------------------------------	
}
function ConvertDuration($Time)
{
	// Clean up duration into specific formats
	$Precision 			= 8;
	$TimeInSeconds		= ($Time);
	
	$Years_To_Go 		= round($TimeInSeconds / (3600 * 24 * 365), $Precision);
	$Days_Left_Over		= $TimeInSeconds % (3600 * 24 * 365);
	//		^------------------------v
	$Months_To_Go 		= round($Days_Left_Over / (3600 * 24 * 30), $Precision);
	$Months_Left_Over	= $TimeInSeconds % (3600 * 24 * 30);
	//		^------------------------v
	$Days_To_Go 		= round($Months_Left_Over / (3600 * 24), $Precision);
	$Hours_Left_Over	= $TimeInSeconds % (3600 * 24);
	//		^------------------------v
	$Hours_To_Go 		= round($Hours_Left_Over / 3600, $Precision);
	$Secons_Left_Over 	= $TimeInSeconds % 3600;
	//		^------------------------v
	$Minutes_To_Go 		= round($Secons_Left_Over / 60, $Precision);
	$Left_Over_Seconds 	= $Secons_Left_Over % 60;

	// Create Arrays as we only need pre decimal number.
	$Years 		= explode(".", $Years_To_Go);
	$Months 	= explode(".", $Months_To_Go);
	$Days 		= explode(".", $Days_To_Go);
	$Hours 		= explode(".", $Hours_To_Go);
	$Minutes 	= explode(".", $Minutes_To_Go);
	$Seconds 	= $Left_Over_Seconds;
	
	$Array = array("TotalInSeconds" 	=> $TimeInSeconds,
				   "TimeInYears" 		=> $Years,
				   "TimeInMonths" 		=> $Months,
				   "TimeInDays" 		=> $Days,
				   "TimeInHours" 		=> $Hours,
				   "TimeInMinutes" 		=> $Minutes,
				   "TimeInSeconds" 		=> $Seconds);
				   
	return $Array;
}

# Method to translate to unix
function DateToUnix($Date)
{
	$Data = explode("/", $Date);
	if (count($Data) == 3)
		return mktime(0, 0, 0, $Data[0], $Data[1], $Data[2]);
	else
		return false;
}

# Prints out variables with pre format and using print_r()
function Show($Variable)
{
	echo '<pre>';
	print_r($Variable);
	echo '</pre>';
}

# Sort an array via a key
function sksort(&$array, $subkey, $sort_ascending) 
{
	if (count($array))
		$temp_array[key($array)] = array_shift($array);
	foreach($array as $key => $val){
		$offset = 0;
		$found = false;
		foreach($temp_array as $tmp_key => $tmp_val)
		{
			if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey]))
			{
				$temp_array = array_merge(    (array)array_slice($temp_array,0,$offset),
											array($key => $val),
											array_slice($temp_array,$offset)
										  );
				$found = true;
			}
			$offset++;
		}
		if(!$found) $temp_array = array_merge($temp_array, array($key => $val));
	}
	if ($sort_ascending)
		$array = array_reverse($temp_array);
	else 
		$array = $temp_array;
}

# Microtime
function microtime_float() 
{ 
	list ($msec, $sec) = explode(' ', microtime()); 
	$microtime = (float)$msec + (float)$sec; 
	return $microtime; 
}
function Timer() { return microtime_float(); }
	
# Validate read data
function Read(&$data, $property=NULL, $default='')
{
	if (is_array($data))
	{
		if (key_exists($property, $data))
		{
			return $data[$property];
		}
		else
		{
			return $default;
		}
	}
	return $default;
}

# Ordinal based on number
function ordinal($ordnum) 
{ 
	$ordinalsuffixes = array("th","st","nd","rd"); 
  	for($i=1;$i<=3;$i++) 
	{ 
		if(($ordnum % 10 == $i) && ($ordnum % 100 != 10+$i )) 
		return $ordinalsuffixes[$i]; 
	} 
	return $ordinalsuffixes[0] ;    
}
function getClassArray()
{
	return array(
			'2' => "Monk"
			,'3' => "Paladin"
			,'4' => "Warrior"
			,'7' => "Bard"
			,'8' => "Dragoon"
			,'22' => "Black Mage"
			,'23' => "White mage"
			,'29' => "Carpenter"
			,'30' => "Blacksmith"
			,'31' => "Armorer"
			,'32' => "Goldsmith"
			,'33' => "Leatherworker"
			,'34' => "Weaver"
			,'35' => "Alchemist"
			,'36' => "Culinarian"
			,'39' => "Miner"
			,'40' => "Botanist"
			,'41' => "Fisher"
			);
}
function RemoveHTTP($url='') 
{
	return preg_replace("/^https?:\/\/(.+)$/i","\\1", $url);
}
function ShortenString($input)
{
	if (strlen($input) > 60)
			return "Link";
		else 
			return $input;
}
function DetectLinksSimple($string)
{
	$string = str_ireplace("\n", " \n ", $string);
	$Words = explode(" ", $string);
	//Show($Words);
	
	$i = 0;
	foreach($Words as $Word)
	{
		if (stripos($Word, "[img]") !== false || stripos($Word, "[url=") !== false)
		{
			$Words[$i] = $Word;
		}
		else
		{
			if (stripos($Word, "http://") !== false || stripos($Word, "https://") !== false)
			{
				$Link = "<a style='text-decoration:underline;color:#4AA500;vertical-align: top;' target='_blank' href='". trim(str_ireplace("\n", "", $Word)) . "'>" . ShortenString($Word)  . "</a> ";
				$Words[$i] = $Link;
			}
		}
		$i++;
	}
	return implode(" ", $Words);
}
function getURL() {
	return (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
}
function getQuery() {
	$Res = parse_url(GetURL());
	return $Res['query'];
}
function plural($num)
{
	if ($num != 1)
		return "s";
	else
		return NULL;	
}
function pluralize($word)
{
	if ($word[strlen($word)-1] != 's') {
		return $word.'s';
	}
	
	return $word;
}
#-----------------------------------------------------------------------------------
# Language Functions
function getDefaultLanguage() 
{
   if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
      return parseDefaultLanguage($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
   else
      return parseDefaultLanguage(NULL);
}
function parseDefaultLanguage($http_accept, $deflang = "en") 
{
   if(isset($http_accept) && strlen($http_accept) > 1)
   {
      # Split possible languages into array
      $x = explode(",",$http_accept);
      foreach ($x as $val) 
	  {
         #check for q-value and create associative array. No q-value means 1 by rule
         if(preg_match("/(.*);q=([0-1]{0,1}\.\d{0,4})/i",$val,$matches))
            $lang[$matches[1]] = (float)$matches[2];
         else
            $lang[$val] = 1.0;
      }
      #return default language (highest q-value)
      $qval = 0.0;
      foreach ($lang as $key => $value)
	  {
         if ($value > $qval)
		 {
            $qval = (float)$value;
            $deflang = $key;
         }
      }
   }
   return strtolower($deflang);
}
#-------------------------------------------------------------------------
# Gets ip
function GetIP()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
	{
		$ip=$_SERVER['HTTP_CLIENT_IP'];
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
	{
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else
	{
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}
#-------------------------------------------------------------------------

function GetBool($String)
{
	if ($String == 'false')
		return false;	

	if ($String == 'true')
		return true;
		
	if ($String == '0')
		return false;	

	if ($String == '1')
		return true;	
		
	if (empty($String))
		return false;	
}
#-------------------------------------------------------------------------
# Resizes an image
function  ResizeImage($file,
					  $width              = 0, 
					  $height             = 0, 
					  $proportional       = false, 
					  $output             = 'file', 
					  $delete_original    = true, 
					  $use_linux_commands = false,
					  $quality = 100 ) {

	if ( $height <= 0 && $width <= 0 ) return false;
	
	# Setting defaults and meta
	$info                         = getimagesize($file);
	$image                        = '';
	$final_width                  = 0;
	$final_height                 = 0;
	list($width_old, $height_old) = $info;
	
	# Calculating proportionality
	if ($proportional) {
	if      ($width  == 0)  $factor = $height/$height_old;
	elseif  ($height == 0)  $factor = $width/$width_old;
	else                    $factor = min( $width / $width_old, $height / $height_old );
	
	$final_width  = round( $width_old * $factor );
	$final_height = round( $height_old * $factor );
	}
	else {
	$final_width = ( $width <= 0 ) ? $width_old : $width;
	$final_height = ( $height <= 0 ) ? $height_old : $height;
	}
	
	# Loading image to memory according to type
	switch ( $info[2] ) {
	case IMAGETYPE_GIF:   $image = imagecreatefromgif($file);   break;
	case IMAGETYPE_JPEG:  $image = imagecreatefromjpeg($file);  break;
	case IMAGETYPE_PNG:   $image = imagecreatefrompng($file);   break;
	default: return false;
	}
	
	
	# This is the resizing/resampling/transparency-preserving magic
	$image_resized = imagecreatetruecolor( $final_width, $final_height );
	if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
	$transparency = imagecolortransparent($image);
	
	if ($transparency >= 0) {
	$transparent_color  = imagecolorsforindex($image, $trnprt_indx);
	$transparency       = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
	imagefill($image_resized, 0, 0, $transparency);
	imagecolortransparent($image_resized, $transparency);
	}
	elseif ($info[2] == IMAGETYPE_PNG) {
	imagealphablending($image_resized, false);
	$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
	imagefill($image_resized, 0, 0, $color);
	imagesavealpha($image_resized, true);
	}
	}
	imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);
	
	# Taking care of original, if needed
	if ( $delete_original ) {
	if ( $use_linux_commands ) exec('rm '.$file);
	else @unlink($file);
	}
	
	# Preparing a method of providing result
	switch ( strtolower($output) ) {
	case 'browser':
	$mime = image_type_to_mime_type($info[2]);
	header("Content-type: $mime");
	$output = NULL;
	break;
	case 'file':
	$output = $file;
	break;
	case 'return':
	return $image_resized;
	break;
	default:
	break;
	}
	
	# Writing image according to type to the output destination
	switch ( $info[2] ) {
	case IMAGETYPE_GIF:   imagegif($image_resized, $output, $quality);    break;
	case IMAGETYPE_JPEG:  imagejpeg($image_resized, $output, $quality);   break;
	case IMAGETYPE_PNG:   imagepng($image_resized, $output);    break;
	default: return false;
	}
	
	return true;
}

# Gets the file type of an image
function GetFiletype($string)
{
	$split = explode(".", $string);
	$last_element = count($split)-1;
	return strtolower($split[$last_element]);	
}

# Extra @Names
function GetAtNames($String)
{
	$String = explode(" ", $String);
	$Array = array();
	foreach($String as $Word)
	{
		if ($Word[0] == "@" && strlen($Word) > 4) { $Array[] = $Word; }
	}
	return $Array;
}

# Random Strings
function Random32() { return md5(RandomString(32)); }
function Random64() { return md5(RandomString(32)) . md5(RandomString(32)); }
function Random128() { return md5(RandomString(32)) . md5(RandomString(32)) . md5(RandomString(32)) . md5(RandomString(32)); }

# Generate a random string based ona length.
function RandomString($length)
{
	$randstr = "";
	for($i=0; $i<$length; $i++)
	{
		$randnum = mt_rand(0,61);
		if($randnum < 10)
		{
			$randstr .= chr($randnum+48);
		}
		else if($randnum < 36)
		{
			$randstr .= chr($randnum+55);
		}
		else
		{
			$randstr .= chr($randnum+61);
		}
	}
	return $randstr;
}

####################################################################################################
#START - SKILL DESCRIPTION PARSING
####################################################################################################

function SplitDesc($String)
{
	$String = explode("  ", $String);
	return $String;
}


function parse_desc($SkillDesc, $JobClass, $Level, $Res)
{
	if (is_array($SkillDesc)) 
	{
		
		for($i=0;$i<count($SkillDesc);$i++)
		{
			$Rest = $SkillDesc[$i];
			//Show($Rest);
			
			if (is_array($Rest)) 
			{
				$compareTo = null;
				
				if($Rest["condition"]["left_operand"] == "level")
					$compareTo = $Level;
				if($Rest["condition"]["left_operand"] == "class_job")
					$compareTo = $JobClass;
				
				$result = false;
				switch($Rest["condition"]["operator"])
				{
					case ">=": 
						if($compareTo >= $Rest["condition"]["right_operand"])
							$result = true;
					break;
					case "==": 
						if($compareTo == $Rest["condition"]["right_operand"])
							$result = true;
					break;
					case "<=": 
						if($compareTo <= $Rest["condition"]["right_operand"])
							$result = true;
					break;
					case "<": 
						if($compareTo < $Rest["condition"]["right_operand"])
							$result = true;
					break;
					case ">": 
						if($compareTo > $Rest["condition"]["right_operand"])
							$result = true;
					break;
					case "!=": 
						if($compareTo != $Rest["condition"]["right_operand"])
							$result = true;
					break;
				}
				if($result)
					$Res .= parse_desc($Rest["yes"], $JobClass, $Level, "");
				else
					$Res .= parse_desc($Rest["no"], $JobClass, $Level, "");
				
			}
			else
			{
				$Res .= $Rest;
			}
		}
		return $Res;
		
	}else
	{
		return $Res . $SkillDesc;
	}
};

function parse_desc_levels($SkillDesc, $Res)
{
	if (is_array($SkillDesc)) 
	{
		
		for($i=0;$i<count($SkillDesc);$i++)
		{
			$Rest = $SkillDesc[$i];
			
			if (is_array($Rest)) 
			{
				
				if($Rest["condition"]["left_operand"] == "level" && !in_array($Rest["condition"]["right_operand"],$Res))
					$Res[] = $Rest["condition"]["right_operand"];

				$Res = array_merge(parse_desc_levels($Rest["true"],  array()), $Res);
				$Res = array_merge(parse_desc_levels($Rest["false"],  array()), $Res);
			}
		}
		return $Res;
		
	}else
	{
		return $Res;
	}
};

####################################################################################################
#END - SKILL DESCRIPTION PARSING
####################################################################################################
	
?>
