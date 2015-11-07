<h1>Post A Message</h1>

<?

if ($_GET['n'])
{
	if ($_POST['news-title'])
	{
		$DB->Update("news", 
			array(
				"Title 		= '". addslashes(trim($_POST['news-title'])) ."'",
				"Details 	= '". addslashes(trim($_POST['news-message'])) ."'",
				"Time 		= ". time()
			),
			array(
				"AUTO = ". trim($_GET['n'])
			)
		);	
	}
	$News = $DB->SQL("SELECT * FROM news WHERE AUTO = ". trim($_GET['n']))[0];
	
}
else if ($_POST['news-title'])
{
	$DB->Insert("news", array(
		"Title" 	=> addslashes(trim($_POST['news-title'])),
		"Details" 	=> addslashes(trim($_POST['news-message'])),
		"Time" 		=> time(),
		"CICUID" 	=> $Session->Character()
	));	
	echo '<div class="success">Posted message</div>';
}

?>

<link rel="stylesheet" href="../plugins/redactor/css/redactor.css" />
<script src="../plugins/redactor/redactor.js"></script>
<script type="text/javascript">
$(document).ready(function() 
{ 
    // Radactor  (global settings)
    $('#redactor').redactor({
        path: "../plugins/redactor",
        minheight: 300,
        autoresize: true,
        cleanup: false,
        convertLinks: true,
        
    });
});
</script>
    
    
<form method="post">

	<div style="padding:5px;">Title</div>
	<input name="news-title" type="text" value="<? echo $News['Title']; ?>" style="width:500px;" />
    
    <div style="padding:5px;">Message</div>
	<textarea id="redactor" name="news-message"><? echo $News['Details']; ?></textarea>
    <style>.redactor_editor { height:200px; }</style>
    
    
    <div style="margin-top:20px;" align="right"><input type="submit" value="Post Comment" /></div>

</form>