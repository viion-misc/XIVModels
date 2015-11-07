<div id="fb-root"></div>
<script>
$(document).ready(function()
{

var loadedSocials = 0;

	!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.onreadystatechange = socialButtonLoaded;js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
	(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); 
	  js.onreadystatechange = socialButtonLoaded;
	  js.id = id;
	  js.async = true;
	  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	var po = document.createElement('script'); 
	po.onreadystatechange = socialButtonLoaded
	po.type = 'text/javascript'; po.async = true;
	po.src = 'https://apis.google.com/js/plusone.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);


	console.log("ready");
	$('#socialbuttons').fadeIn(150);
	 //$('#socialbuttons').animate({left: '200px'});
});
/*
function socialButtonLoaded(){
	loadedSocials++
	if(loadedSocials >= 3)
		$('#socialbuttons').fadeIn(150);
}*/
function socialButtonLoaded(){
	var state = this.readyState;
	console.log(state);
	if (state === "complete") {
		console.log("COMPLETE");
	}
}
</script>
        
<div id="socialbuttons" style="display:none !important;margin-bottom:10px;">
    
    <!-- G plus like -->
    <div class="g-plusone" data-annotation="bubble" data-size="medium" data-height="20" data-href="https://plus.google.com/102403947438134266769" data-rel="publisher" style="display:inline-block;margin-left: -25px;;"></div>
    
    
    <!-- Twitter Like -->
    <span style="display:inline-block; width:0px;"></span>
    <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://xivdb.com" data-via="xivdb" data-related="xivdb" style="display:inline-block;bottom: -30px;">Tweet</a>

    
    <!-- Facebook Like -->
    <span style="display:inline-block; width:0px;"></span>
    <div class="fb-like" data-href="https://www.facebook.com/xivdb" data-send="false" data-layout="button_count" data-width="90" data-show-faces="false" data-font="lucida grande" data-colorscheme="dark"></div>
    


</div>