<?
	require "credentials.php";
	require "includes/functions.php";
	require "includes/translate_csv.php";
	require "includes/analytics.php";

    # url detection
    wwwRedirect();

	# Connection to XIVPADS
	require "classes/pdo.php";
	$DB = new Database(KEY);
	
	#Get Language
	require "includes/language.php";
	
	#Dropdown data (needs languge)
	require "includes/dropdowns.php";	
	
	#Load item from url
	require "includes/url.php";
	

	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
	<META NAME="author"      CONTENT="Premium Virtue, Nemi Chan, Antoine Hom">
	<META NAME="publisher"   CONTENT="Premium Virtue, Nemi Chan, Antoine Hom">
	<META NAME="copyright"   CONTENT="Premium Virtue, Nemi Chan, Antoine Hom">
	<META NAME="description" CONTENT="XIV Models is a Final Fantasy XIV: A Realm Reborn Model Viewer. It allows you to view the 3D models for Monsters and Players. You can customize and share costumes, import your character and see the details of the in-game models. It is fully web based, you don't even need the game installed! It has many customization options and we're very excited to show it to the FFXIV Community!">
	<META NAME="keywords"    CONTENT="FFXIV, XIV, XIVPads, A Realm Reborn, ARR, FFXIV:ARR, Models, Modelviewer, Final, Fantasy, XIV, Final Fantasy, Community, Wardrobe, Compare, Viewer">
	<META NAME="page-topic"  CONTENT="Final Fantasy XIV A Realm Reborn Modelviewer">
	<META NAME="page-type"   CONTENT="Game Information">
	<META NAME="audience"    CONTENT="Final Fantasy, Video Game">
	<META NAME="robots"      CONTENT="index,follow">
	
    <title>Final Fantasy XIV: A Realm Reborn Modelviewer</title>
    <link rel="shortcut icon" href="favicon.ico" />
    <style>
    @import 'css/forms.css';
    @import 'css/messages.css';
    @import 'css/main.css';
    </style>
    <link rel="stylesheet" type="text/css" href="css/forms.css" />
    <link rel="stylesheet" type="text/css" href="css/messages.css" />
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <link rel="stylesheet" type="text/css" href="scripts/minicolors/minicolors.css" />
    
    <script type="text/javascript" src="scripts/gl-matrix.js"></script>
    <script type="text/javascript" src="scripts/inflate.min.js"></script>
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="scripts/xivmv_min.js"></script>
    <script type="text/javascript" src="scripts/gen_min.js"></script>
    <script type="text/javascript" src="scripts/cam_min.js"></script>
    <script type="text/javascript" src="scripts/minicolors/minicolors.js"></script>
	<script type="text/javascript" src="scripts/jquery/jquery.cookies.js"></script>

    
    
</head>
<body>
	<!-- Menus -->
	<section>
        <? require "includes/headerjs.php"; ?>
    	<? include "modules/menu/menu.php"; ?>
        
        <div class="content">
			<div class="tab">
			
            <?
			include "modules/content/home.php";
			include "modules/content/monster.php";
			include "modules/content/player.php";
			include "modules/content/all.php";
			include "modules/content/settings.php";
			?>
            
			</div>
		</div>
        <br style="clear:both;" />
        
        <!-- Loading -->
        <? include "modules/menu/loading.php"; ?>
        
    </section>
    	

    <!-- Logos -->
    <img src="images/logos/xivmodels.png" class="sitelogo" />
    
    <!-- XIVDB Display -->
    <div class="xivdb-info">
    </div>
    
    <!-- right column -->
    <div class="adsense">
    	<? include "includes/adsense.php"; ?>
    </div>
    
    <!-- Footer -->
    <? include "includes/footer.php"; ?>
    
    <!-- Status -->
	<img class="mascot" src="mascot-models.png" />
    <a href="http://xivwiki.com"><img src="images/logos/xivwiki.png" class="alux xivwiki" /></a>
    <a href="http://xivpads.com"><img src="images/logos/xivpads.png" class="alux xivpads" /></a>
    <a href="http://xivdb.com"><img src="images/logos/xivdb.png" class="alux xivdb" /></a>
    <img src="images/misc/alux.png" class="alux" />
    <div class="status addShadow"></div>
	
    <!-- Canvas -->
	<canvas id="webgl_canvas" width="2048" height="2048"></canvas>
			
	<script>
		$(document).ready(function()
		{
			$('.tooltip').uitooltip();
		});
	</script>	

    <!-- Welcome window -->
    <? include "includes/welcome.php"; ?>	
	
	<!-- Donation window -->
	<? include "includes/donation.php"; ?>


</body>

</html>
