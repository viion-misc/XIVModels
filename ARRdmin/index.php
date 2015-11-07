<?
	
//	error_reporting(E_ALL);
	# Database
	include "../classes/pdo.php";
	include "../credentials.php";
	
	
	# Database connection
	$DB = new Database(KEY);
	

	if (trim($_POST['pw']) == 'x1vm0d3l5')
	{
		setcookie('xivmodels:lol', '5f31769db16ce3556b416de8d4fb2fff', time()+(60*60*24*30*12), '/');
		setcookie('xivmodels:lol', '5f31769db16ce3556b416de8d4fb2fff', time()+(60*60*24*30*12), '/', '.xivdb.com');
		header('location: ./');
	}

	# If session empty
	$Cookie = substr(trim(@$_COOKIE['xivmodels:lol']), 0, 64);
	
	// damien
	if ($Cookie != '5f31769db16ce3556b416de8d4fb2fff') 
	{  
		?>
        <form method="post">
        <input name="pw" type="text" />
        <input name="" value="login" type="submit" />
        </form>
        <?
		die;
	}
	
	# User Level
	$SessionLevel = "Admin";
	
	
	function parse_me($str) 
	{
		//print_r($str);
		//print_r("<br>");
		$ReturnString = flatString($str);
		//print_r("<br>");
		//print_r($ReturnString);
		//print_r("<br>");
		//print_r("<br>");
		return addslashes($ReturnString);
	}
	
	function flatString($arr)
	{
		$ReturnString = "";
		
		$TryArray = (array)$arr;
		
		//print_r($TryArray);
		//print_r("<br>");
		
		if($TryArray["value"])
		{
			$TryArray["value"] = (array)$TryArray["value"];
			foreach($TryArray["value"] AS $Piece)
			{
				$Type = (array)$Piece;
				if($Type["type"])
				{
					if($Type["type"]=="linefeed")
						$ReturnString .= "\n";
				}else
					$ReturnString .= $Piece;
			}
			
			return $ReturnString;
		
		}
		else
			return $arr;
	
	}
	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<html >
    
    <!-- 	XIVPads is a place to track your character progress over time, 
            it creates events on your adventures, lets you know where you are, 
            what you can do, its everything you need 								-->
			
	<head>
	
	<title>xivdb - Admin</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	

    <link rel="stylesheet" type="text/css" href="styles.css" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	
	
	</head>

	<body style="padding:50px;">
	
        <div style="width:120px;float:left;">
            <img src="../images/logos/xivmodels.png" />
    
            <nav style="margin-top:30px;">
                <a href="?Admin=Home">Home</a>
				<a href="?Admin=Translation">Translations</a>
                <a href="?Admin=Patches">Patches</a>
            </nav>
            
            <nav style="margin-top:30px;">
                <a href="?Admin=IMP_models">Models</a>
                <a href="?Admin=IMP_models_all">Monsters></a>
                <a href="?Admin=IMP_humans">Models Humans</a>
            </nav>
        </div>
        
        
        <!-- Switch -->
        <div style="float:left; width:800px; margin-left:30px; margin-top:66px;">
        <?
            $Page = trim($_GET["Admin"]);
            switch($Page)
            {
                default: include "home.php"; break;
                case 'Home': include "home.php"; break;
				case 'Translation': include "translation.php"; break;
                case 'Patches': include "import/patch.php"; break;
				
                case 'IMP_models': include "import/imp_models.php"; break;
                case 'IMP_models_all': include "import/imp_models_all.php"; break;
                case 'IMP_humans': include "import/imp_models_human.php"; break;
                
            }
        ?>
		</div>
	
	</body>
	
</html>

<?
	$DB->Disconnect();
?>